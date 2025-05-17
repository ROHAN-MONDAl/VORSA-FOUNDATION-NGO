<?php
session_start();
include 'includes/db.php';

$user_id = $_POST['user_id'];
$password = $_POST['password'];
$captcha = $_POST['g-recaptcha-response'];

if (!$captcha) {
  echo "Please complete the CAPTCHA.";
  exit;
}

// Google reCAPTCHA verification
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=YOUR_SECRET_KEY&response=" . $captcha);
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
  echo "Captcha verification failed.";
  exit;
}

// Get user from DB
$res = mysqli_query($conn, "SELECT * FROM admins WHERE user_id='$user_id'");
if (mysqli_num_rows($res) == 1) {
  $row = mysqli_fetch_assoc($res);
  $hashedPassword = $row['password'];

  // Verify password hash
  if (password_verify($password, $hashedPassword)) {
    $_SESSION['admin'] = $user_id;
    echo "Login successful!";
    // header("Location: dashboard.php");
  } else {
    echo "Incorrect password.";
  }
} else {
  echo "User ID not found.";
}
?>
