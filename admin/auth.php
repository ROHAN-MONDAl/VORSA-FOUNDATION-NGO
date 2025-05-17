<?php
session_start();
header('Content-Type: application/json');
require_once 'server.php';
require_once 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$action = $_GET['action'] ?? '';

function jsonResponse($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

function sanitize($str) {
    return htmlspecialchars(trim($str));
}

// Google reCAPTCHA secret key
$recaptcha_secret = 'YOUR_RECAPTCHA_SECRET_KEY';

function verifyRecaptcha($token, $secret) {
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secret,
        'response' => $token
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === false) return false;
    $res = json_decode($result, true);
    return $res['success'] ?? false;
}

if ($action === 'login') {
    $user_id = sanitize($_POST['user_id'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] == 1;
    $recaptcha_token = $_POST['recaptcha'] ?? '';

    if (!verifyRecaptcha($recaptcha_token, $recaptcha_secret)) {
        jsonResponse(false, 'Captcha verification failed.');
    }

    if (empty($user_id) || empty($password)) {
        jsonResponse(false, 'User ID and Password required.');
    }

    // Validate password complexity
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        jsonResponse(false, 'Password complexity requirement not met.');
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE user_id = ?");
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        jsonResponse(false, 'Invalid User ID or Password.');
    }
    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        jsonResponse(false, 'Invalid User ID or Password.');
    }

    $_SESSION['user_id'] = $user_id;
    if ($remember_me) {
        setcookie('user_id', $user_id, time() + (86400 * 30), "/");
    } else {
        setcookie('user_id', '', time() - 3600, "/");
    }

    jsonResponse(true, 'Login successful.');
}

if ($action === 'send_otp') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $user_id = sanitize($_POST['user_id'] ?? '');

    if (!$email) {
        jsonResponse(false, 'Valid email is required.');
    }
    if (empty($user_id)) {
        jsonResponse(false, 'User ID is required.');
    }

    // Verify user exists and email matches user_id
    $stmt = $conn->prepare("SELECT id FROM users WHERE user_id = ? AND email = ?");
    $stmt->bind_param('ss', $user_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        jsonResponse(false, 'User ID and Email do not match.');
    }
    $user = $result->fetch_assoc();

    // Generate OTP and expiry (10 mins)
    $otp = rand(100000, 999999);
    $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expires = ? WHERE id = ?");
    $stmt->bind_param('ssi', $otp, $expires, $user['id']);
    $stmt->execute();

    // Send OTP email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';  // your SMTP username
        $mail->Password = 'your-email-password';  // your SMTP password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Site');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "<p>Your OTP code is: <b>$otp</b>. It expires in 10 minutes.</p>";

        $mail->send();
        $_SESSION['otp_user_id'] = $user_id;
        jsonResponse(true, 'OTP sent to your email.');
    } catch (Exception $e) {
        jsonResponse(false, "Mailer Error: {$mail->ErrorInfo}");
    }
}

if ($action === 'verify_otp') {
    $otp = sanitize($_POST['otp'] ?? '');
    if (empty($_SESSION['otp_user_id'])) {
        jsonResponse(false, 'Session expired, please request OTP again.');
    }
    $user_id = $_SESSION['otp_user_id'];

    $stmt = $conn->prepare("SELECT otp, otp_expires FROM users WHERE user_id = ?");
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $user['otp'] !== $otp) {
        jsonResponse(false, 'Invalid OTP.');
    }
    if (strtotime($user['otp_expires']) < time()) {
        jsonResponse(false, 'OTP expired.');
    }

    $_SESSION['otp_verified'] = true;
    jsonResponse(true, 'OTP verified.');
}

if ($action === 'reset_password') {
    if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
        jsonResponse(false, 'OTP verification required.');
    }

    $password = $_POST['password'] ?? '';
    $user_id = $_SESSION['otp_user_id'] ?? '';

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        jsonResponse(false, 'Password does not meet complexity requirements.');
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_expires = NULL WHERE user_id = ?");
    $stmt->bind_param('ss', $hashed_password, $user_id);
    $stmt->execute();

    // Clear session vars
    unset($_SESSION['otp_verified'], $_SESSION['otp_user_id']);

    jsonResponse(true, 'Password reset successful.');
}

jsonResponse(false, 'Invalid request.');
