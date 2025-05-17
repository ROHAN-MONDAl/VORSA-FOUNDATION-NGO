<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons (Optional) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="modal show d-block" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 border-0 shadow-lg rounded-4">
        <div class="modal-header border-0 justify-content-center bg-success text-white rounded-top-4">
          <div class="text-center">
            <i class="bi bi-shield-lock-fill fs-1"></i>
            <h5 class="modal-title mt-2 fw-bold">OTP Verification</h5>
          </div>
        </div>
        <div class="modal-body">
          <form action="send-credentials.php" method="POST">
            <div class="mb-3">
              <label for="otp" class="form-label fw-semibold">Enter the 6-digit code sent to your email</label>
              <input type="text" name="otp" id="otp" class="form-control form-control-lg rounded-pill text-center" placeholder="######" maxlength="6" required>
            </div>
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-success btn-lg rounded-pill">Verify OTP</button>
            </div>
          </form>
        </div>
        <div class="text-center pb-3">
          <small class="text-muted">Didn't receive the code? <a href="index.php">Try again</a></small>
        </div>
      </div>
    </div>
  </div>

</body>

</html>