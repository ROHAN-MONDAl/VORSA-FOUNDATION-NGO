<?php session_start(); ?>
<form action="send-credentials.php" method="POST">
  <h2>Enter OTP</h2>
  <input type="text" name="otp" required />
  <button type="submit">Verify OTP</button>
</form>
