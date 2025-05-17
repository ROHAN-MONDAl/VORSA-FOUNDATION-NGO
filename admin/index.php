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
    <!-- Custom css -->
    <link rel="stylesheet" href="css/style.css" />


    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
        <div class="container">

            <!-- Hamburger button -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileMenu"
                aria-controls="mobileMenu"
                aria-label="Toggle navigation"
                style="outline:none; box-shadow:none !important; border:none !important; background:transparent;"
                onfocus="this.style.outline='none'; this.style.boxShadow='none';"
                onblur="this.style.outline=''; this.style.boxShadow='';">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo + Brand -->
            <a class="navbar-brand d-flex align-items-center mx-auto" href="#">
                <img src="images/logo.png" alt="Logo" class="nav-logo-img"/>
                NGO Admin
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
        <div
            class="offcanvas offcanvas-start text-bg-success d-lg-none"
            tabindex="-1"
            id="mobileMenu"
            aria-labelledby="mobileMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
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
        </div>

    </nav>

    <!-- Overlay should be outside login container -->
    <div class="overlay"></div>

    <div class="login-container">

        <form id="loginForm" action="validate-login.php" method="POST">
            <h2>Admin Login</h2>

            <label>User ID</label>
            <input type="text" name="user_id" id="user_id" />
            <span class="error" id="user_id_error"></span>

            <label>Password</label>
            <input type="password" name="password" id="password" />
            <span class="error" id="password_error"></span>

            <div class="remember-forgot">
                <label><input type="checkbox" name="remember" /> Remember Me</label>
                <a href="#" id="forgotLink">Forgot ID/Password?</a>
            </div>

            <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY_HERE"></div>
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer d-flex align-items-center justify-content-center text-white mt-4" style="height: 50px;">
        <p class="mb-0">© 2025 Vorsha Foundation. All rights reserved.</p>
    </footer>




    <!-- Forgot Password Modal -->
    <div id="forgotModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <form id="forgotForm" action="includes/send-otp.php" method="POST">
                <h2>Forgot Password</h2>
                <label>Enter your Email</label>
                <input type="email" id="forgot_email" name="email" required />
                <span class="error" id="forgot_email_error"></span>
                <button type="submit">Send OTP</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>