<?php
session_start();

// Clear session data
$_SESSION = [];
session_destroy();

// Clear remember me cookie if exists
if (isset($_COOKIE['admin_remember'])) {
    setcookie('admin_remember', '', time() - 3600, "/");
}

// Redirect to login page
header("Location: ../index.php");
exit;
?>
