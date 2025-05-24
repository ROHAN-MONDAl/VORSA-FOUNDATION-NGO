<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Admin Login - NGO</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Remix Icon CDN -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <!-- Custom css -->
  <link rel="stylesheet" href="css/style.css" />

</head>

<body>
  <!-- Loader -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <div class="container-fluid min-vh-100 d-flex flex-column p-0">
    <nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
      <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Hamburger button -->
        <!-- <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu"
                    aria-controls="mobileMenu"
                    aria-label="Toggle navigation"
                    style="outline:none; box-shadow:none !important; border:none !important; background:transparent;"
                    onfocus="this.style.outline='none'; this.style.boxShadow='none';"
                    onblur="this.style.outline=''; this.style.boxShadow='';">
                    <i class="ri-menu-2-line" style="font-size: 1.5rem; color: #fff;"></i>
                </button> -->


        <!-- Logo + Brand -->
        <a class="navbar-brand d-flex align-items-center mx-auto" href="#">
          <img src="images/logo.png" alt="Logo" class="nav-logo-img" style="height:40px; width:auto;" />
          <h4 class="mb-0">Vorsha Foundation</h4>
        </a>

        <!-- Desktop Menu -->
        <!-- <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
                    <ul class="navbar-nav mx-auto" style="gap: 2rem;">
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link text-center" href="#">Dashboard</a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link text-center" href="#">Members</a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link text-center" href="#">Events</a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link text-center" href="#">Settings</a>
                        </li>
                    </ul>
                </div> -->

      </div>

      <!-- Mobile Offcanvas Menu -->
      <!-- <div
                class="offcanvas offcanvas-start d-lg-none" style="background-color: #008000;"
                tabindex="-1"
                id="mobileMenu"
                aria-labelledby="mobileMenuLabel">
                <div class="offcanvas-header">
                    <img src="images/logo.png" alt="Logo" class="nav-logo-img me-2" style="height:40px; width:auto;" />
                    <h4 class="mb-0 text-white">Vorsha Foundation</h4>
                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#">Members</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#">Events</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#">Settings</a></li>
                    </ul>
                </div>
            </div> -->

    </nav>

    <!-- Overlay should be outside login container -->
    <div class="overlay"></div>

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


    <!-- Footer -->
    <footer class="footer d-flex align-items-center justify-content-center text-white mt-auto" style="height: 50px;">
      <p class="mb-0" style="color: #fff;">
        © <span id="currentYear"></span> Vorsha Foundation. Design and developed by
        <a href="http://" style="color: #fff; text-decoration: none;">Web2infinity</a> All rights reserved.
      </p>
      <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
      </script>
    </footer>

    <!-- Forgot Password Modal -->
    <div id="forgotModal" class="modal">
      <div class="modal-content">
        <span class="close-btn">&times;</span>
        <form id="forgotForm" action="send-otp.php" method="POST">
          <h2>Forgot Uesrname</h2>
          <label>Enter your Email</label>
          <input type="email" id="forgot_email" name="email" required class="form-control" />
          <span class="error" id="forgot_email_error"></span>
          <button type="submit" class="btn btn-success w-100 mt-2">Send OTP</button>
        </form>
      </div>
    </div>
  </div>


  <!-- Google reCAPTCHA API script for spam protection -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <!-- Bootstrap 5 JavaScript bundle for UI components -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery library for DOM manipulation and AJAX -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Custom JavaScript for site-specific functionality -->
  <script src="js/script.js"></script>
</body>

</html>