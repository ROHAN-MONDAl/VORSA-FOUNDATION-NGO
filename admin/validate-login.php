<?php
session_start();
include '../server.php'; // Your DB connection

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lock_time'])) {
    $_SESSION['lock_time'] = 0;
}

$lockDuration = 180; // 3 minutes
$currentTime = time();

// Check if user is locked out
if ($_SESSION['login_attempts'] >= 5) {
    $elapsed = $currentTime - $_SESSION['lock_time'];
    
    if ($elapsed < $lockDuration) {
        $remaining = $lockDuration - $elapsed;
        $_SESSION['notification'] = "Too many failed attempts. Try again in " . ceil($remaining) . " seconds.";
        header("Location: index.php");
        exit;
    } else {
        // Reset lockout
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lock_time'] = 0;
        unset($_SESSION['notification']);
    }
}

// Only process login if not locked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id']);
    $password = trim($_POST['password']);
    $captcha = $_POST['g-recaptcha-response'];
    $remember = isset($_POST['remember']);

    // CAPTCHA check
    if (!$captcha) {
        $_SESSION['notification'] = "Please complete the CAPTCHA.";
        header("Location: index.php");
        exit;
    }

    // Verify Google reCAPTCHA
    $secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"; // Google test key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        $_SESSION['notification'] = "Captcha verification failed.";
        header("Location: index.php");
        exit;
    }

    // Validate credentials
    $stmt = $conn->prepare("SELECT * FROM admins WHERE user_id = ?");
    if (!$stmt) {
        $_SESSION['notification'] = "Database error.";
        header("Location: index.php");
        exit;
    }

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            // Check again if user is still locked
            if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['lock_time']) < $lockDuration) {
                $remaining = $lockDuration - (time() - $_SESSION['lock_time']);
                $_SESSION['notification'] = "You are still locked. Try again in " . ceil($remaining) . " seconds.";
                header("Location: index.php");
                exit;
            }

            // Success login
            $_SESSION['admin'] = $user_id;
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lock_time'] = 0;
            unset($_SESSION['notification']);

            // Remember me
            if ($remember) {
                setcookie('admin_remember', $user_id, time() + (86400 * 30), "/");
            } else {
                setcookie('admin_remember', '', time() - 3600, "/");
            }

            header("Location: dashboard.php");
            exit;
        } else {
            // Wrong password
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['lock_time'] = time();
                $_SESSION['notification'] = "Too many failed attempts. Please wait 3 minutes before trying again.";
            } else {
                $_SESSION['notification'] = "Incorrect password. Attempts left: " . (5 - $_SESSION['login_attempts']);
            }
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['notification'] = "User ID not found.";
        header("Location: index.php");
        exit;
    }
}

// Auto-login from cookie
if (isset($_COOKIE['admin_remember']) && !isset($_SESSION['admin'])) {
    $_SESSION['admin'] = $_COOKIE['admin_remember'];
    header("Location: dashboard.php");
    exit;
}
?>
