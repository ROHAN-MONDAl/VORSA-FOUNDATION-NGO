<?php
session_start();
include '../server.php'; // your DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer files (adjust path accordingly)
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$email = $_POST['email'];
$otp = rand(100000, 999999);

$_SESSION['otp'] = $otp;
$_SESSION['email'] = $email;

// Escape email to prevent SQL injection
$email_safe = mysqli_real_escape_string($conn, $email);

$query = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email_safe'");
if (mysqli_num_rows($query) == 1) {

  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'codecomettechnology@gmail.com';      // Your Gmail address
    $mail->Password   = 'uons ghbx ieri vchm';                  // Your Gmail app password or real password if less secure apps enabled
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('codecomettechnology@gmail.com', 'Vorsha Foundation');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Use this OTP to reset your credentials: $otp";

    $mail->send();
    header("Location: verify-otp.php");
    exit;
  } catch (Exception $e) {
    // Use JS alert for mailer error
    $error = addslashes($mail->ErrorInfo); // escape quotes for JS
    echo "<script>alert('Mailer Error: $error'); window.history.back();</script>";
    exit;
  }
} else {
  // Use JS alert for email not found
  echo "<script>alert('Email not found!'); window.history.back();</script>";
  exit;
}
