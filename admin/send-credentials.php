<?php
session_start();
include 'includes/db.php';

$enteredOtp = $_POST['otp'];

if ($_SESSION['otp'] == $enteredOtp) {
  $email = $_SESSION['email'];
  $result = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email'");
  $row = mysqli_fetch_assoc($result);
  $user_id = $row['user_id'];
  $password = $row['password']; // Ideally hashed – send reset link instead in production

  $subject = "Your Login Credentials";
  $msg = "User ID: $user_id\nPassword: $password";
  $headers = "From: codecomettechnology@gmail.com";
  mail($email, $subject, $msg, $headers);

  echo "Credentials sent to your email.";
} else {
  echo "Invalid OTP!";
}
?>
