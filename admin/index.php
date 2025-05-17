<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Vorsha Foundation - Auth System</title>
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="container">

    <!-- LOGIN FORM -->
    <form id="login-form">
      <h2>Login</h2>
      <label>User ID</label>
      <input type="text" name="user_id" id="login-user-id" />
      <span class="error" id="login-user-id-error"></span>

      <label>Password</label>
      <input type="password" name="password" id="login-password" />
      <span class="error" id="login-password-error"></span>

      <label>
        <input type="checkbox" name="remember_me" id="login-remember" />
        Remember Me
      </label>

      <div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY"></div>
      <span class="error" id="login-recaptcha-error"></span>

      <button type="submit">Login</button>
      <div class="form-message" id="login-message"></div>

      <p><a href="#" id="show-forgot">Forgot ID / Password?</a></p>
    </form>

    <!-- FORGOT FORM -->
    <form id="forgot-form" style="display:none;">
      <h2>Forgot ID / Password</h2>
      <label>User ID</label>
      <input type="text" name="user_id" id="forgot-user-id" />
      <span class="error" id="forgot-user-id-error"></span>

      <label>Email</label>
      <input type="email" name="email" id="forgot-email" />
      <span class="error" id="forgot-email-error"></span>

      <button type="submit">Send OTP</button>
      <div class="form-message" id="forgot-message"></div>

      <p><a href="#" id="back-to-login-from-forgot">Back to Login</a></p>
    </form>

    <!-- OTP VERIFICATION FORM -->
    <form id="otp-form" style="display:none;">
      <h2>Verify OTP</h2>
      <label>OTP Code</label>
      <input type="text" name="otp" id="otp-code" maxlength="6" />
      <span class="error" id="otp-error"></span>

      <button type="submit">Verify OTP</button>
      <div class="form-message" id="otp-message"></div>
    </form>

    <!-- RESET PASSWORD FORM -->
    <form id="reset-form" style="display:none;">
      <h2>Reset Password</h2>
      <label>New Password</label>
      <input type="password" name="password" id="reset-password" />
      <span class="error" id="reset-password-error"></span>

      <button type="submit">Reset Password</button>
      <div class="form-message" id="reset-message"></div>
    </form>

  </div>

  <script src="js/script.js"></script>
</body>

</html>