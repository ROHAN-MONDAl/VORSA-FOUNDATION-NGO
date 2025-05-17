<?php
session_start();
include '../server.php'; // Your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id']);
    $password = trim($_POST['password']);
    $captcha = $_POST['g-recaptcha-response'];
    $remember = isset($_POST['remember']); // checkbox

    // CAPTCHA not completed
    if (!$captcha) {
        echo "<script>alert('Please complete the CAPTCHA.'); window.location.href='index.php';</script>";
        exit;
    }

    // Google reCAPTCHA verification
    $secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"; // Test key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        echo "<script>alert('Captcha verification failed.'); window.location.href='index.php';</script>";
        exit;
    }

    // Check user credentials
    $stmt = $conn->prepare("SELECT * FROM admins WHERE user_id = ?");
    if (!$stmt) {
        echo "<script>alert('Database error.'); window.location.href='index.php';</script>";
        exit;
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['admin'] = $user_id;

            // Set cookie if remember me is checked (30 days)
            if ($remember) {
                setcookie('admin_remember', $user_id, time() + (86400 * 30), "/"); // 86400 = 1 day
            } else {
                // If unchecked, clear cookie
                if (isset($_COOKIE['admin_remember'])) {
                    setcookie('admin_remember', '', time() - 3600, "/");
                }
            }

            echo "<script>window.location.href='dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href='index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('User ID not found.'); window.location.href='index.php';</script>";
        exit;
    }
}

// Auto-login if cookie exists
if (isset($_COOKIE['admin_remember']) && !isset($_SESSION['admin'])) {
    $_SESSION['admin'] = $_COOKIE['admin_remember'];
    header("Location: dashboard.php");
    exit;
}
?>
