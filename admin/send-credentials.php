<?php
session_start();
include '../server.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$enteredOtp = $_POST['otp'];

if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
  $email = $_SESSION['email'];
  $safeEmail = mysqli_real_escape_string($conn, $email);

  // Check if email exists
  $result = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$safeEmail'");
  if (mysqli_num_rows($result) !== 1) {
    echo "<script>
                alert('Email not found!');
                window.history.back();
              </script>";
    exit;
  }

  $row = mysqli_fetch_assoc($result);
  $user_id = $row['user_id'];

  $subject = "Vorsha Foundation Admin Credentials";

  $msg = "Dear Admin,

  We have received a request to retrieve your login credentials for the Vorsha Foundation Admin Portal.

  Your User ID: $user_id

  For security reasons, we do not send passwords via email. If you require a password reset or further assistance, please contact IT support.

  If you did not request this information, please notify us immediately.

  Best regards,
  Web2infinity IT Support
  www.web2infinity.com";

  $mail = new PHPMailer(true);

  try {
    // SMTP setup
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'codecomettechnology@gmail.com';      // Your Gmail
    $mail->Password   = 'uons ghbx ieri vchm';                // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Email setup
    $mail->setFrom('codecomettechnology@gmail.com', 'Vorsha Foundation');
    $mail->addAddress($email);

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body    = $msg;

    $mail->send();

    echo "<script>
                alert('Credential has been sent.');
                window.location.href = 'index.php';
              </script>";
    exit;
  } catch (Exception $e) {
    $error = addslashes($mail->ErrorInfo);
    echo "<script>
                alert('Mailer Error: $error');
                window.location.href = 'index.php';
              </script>";
    exit;
  }
} else {
  echo "<script>
            alert('Invalid OTP!');
            window.location.href = 'verify-otp.php';
          </script>";
  exit;
}
