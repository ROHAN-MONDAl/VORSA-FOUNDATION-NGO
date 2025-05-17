<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$otp = rand(100000, 999999);

$_SESSION['otp'] = $otp;
$_SESSION['email'] = $email;

// Check if email exists in DB
$query = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email'");
if (mysqli_num_rows($query) == 1) {
  $subject = "Your OTP Code";
  $msg = "Use this OTP to reset your credentials: $otp";
  $headers = "From: codecomettechnology@gmail.com";
  mail($email, $subject, $msg, $headers);
  header("Location: ../verify-otp.php");
} else {
  echo "Email not found!";
}
?>
