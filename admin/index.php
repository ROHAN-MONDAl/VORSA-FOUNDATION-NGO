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

        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="login-container w-100" style="max-width: 400px;">
                <form id="loginForm" action="validate-login.php" method="POST">
                    <h2>Admin Login</h2>
                    
                    <?php if (isset($_SESSION['notification'])): ?>
                        <div id="notification" style="background: #d4edda;color: #155724;padding:10px;border-radius:5px;margin-bottom:15px;">
                            <?php echo $_SESSION['notification']; ?>
                        </div>
                        <script>
                            setTimeout(() => {
                                const note = document.getElementById("notification");
                                if (note) note.style.display = "none";
                            }, 5000);
                        </script>
                    <?php endif; ?>

                    <label>User ID</label>
                    <input type="text" name="user_id" id="user_id" class="form-control" />
                    <span class="error" id="user_id_error"></span>

                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" />
                    <span class="error" id="password_error"></span>

                    <div class="d-flex align-items-center justify-content-between mt-2 mb-3 border-bottom pb-2">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" style="border:1px solid #000;" onclick="this.classList.toggle('checked-green', this.checked);">

                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <a href="#" id="forgotLink" class="text-decoration-none">Forgot Username?</a>
                    </div>

                    <div style="display: flex; justify-content: center; margin-bottom: 10px;">
                        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" style="display: inline-block; transform: scale(0.85);"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Login</button>
                </form>
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