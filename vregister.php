<?php
session_start();
// ✅ Connect DB
require 'server.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function showPopup($msg)
{
    echo "<script>alert('$msg'); window.history.back();</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $mobile = $_POST['mob'];
    $email = $_POST['mail'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $village = $_POST['village'];
    $block = $_POST['block'];
    $pin = $_POST['pin'];
    $blood = $_POST['blood'];

    // ✅ reCAPTCHA verification
    $secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"; // test key
    $responseKey = $_POST['g-recaptcha-response'] ?? '';
    $userIP = $_SERVER['REMOTE_ADDR'];

    if (empty($responseKey)) {
        showPopup("Please complete the CAPTCHA.");
    }

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $responseJson = file_get_contents($url);
    $response = json_decode($responseJson);

    if (!$response->success) {
        showPopup("Captcha verification failed!");
    }

    // 2. Duplicate check
    $check = $conn->query("SELECT * FROM  registrations WHERE email='$email' OR mobile='$mobile'");
    if ($check->num_rows > 0) {
        showPopup("Email or mobile already registered.");
    }

    // 3. Generate registration ID
    $latest = $conn->query("SELECT registration_id FROM  registrations ORDER BY id DESC LIMIT 1");
    if ($latest->num_rows > 0) {
        $row = $latest->fetch_assoc();
        $num = intval(substr($row['registration_id'], 1)) + 1;
    } else {
        $num = 1;
    }
    $registration_id = "V" . str_pad($num, 6, "0", STR_PAD_LEFT);

    // 4. Generate and store OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['form_data'] = compact('registration_id', 'name', 'dob', 'mobile', 'email', 'state', 'district', 'village', 'block', 'pin', 'blood');

    // 5. Send OTP email
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'codecomettechnology@gmail.com';
    $mail->Password = 'uons ghbx ieri vchm'; // Use your app password
    $mail->SMTPSecure = 'tls';
    $mail->setFrom('codecomettechnology@gmail.com', 'Vorsha Foundation');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP for Volunteer Registration';
    $mail->Body = "Your OTP is: $otp";

    if (!$mail->send()) {
        showPopup("Failed to send OTP email.");
    } else {
        header("Location: vindex.php?otp=1");
        exit();
    }
}
