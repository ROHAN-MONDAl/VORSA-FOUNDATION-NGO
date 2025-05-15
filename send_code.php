<?php
session_start();
header('Content-Type: application/json');

// CAPTCHA check
$secret = 'YOUR_SECRET_KEY_HERE';
$captcha = $_POST['g-recaptcha-response'];

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
$response = json_decode($verify);

if (!$response->success) {
    echo json_encode(['success' => false, 'message' => 'Captcha failed.']);
    exit;
}

// Email validation
$email = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Invalid email.']);
    exit;
}

// Generate 6-digit code
$code = rand(100000, 999999);
$_SESSION['verification_code'] = $code;
$_SESSION['email'] = $email;

// Send Email
$subject = "Your Verification Code";
$message = "Your 6-digit code is: $code";
$headers = "From: noreply@yourdomain.com";

if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send email.']);
}
