<?php
session_start();

$conn = new mysqli("localhost", "root", "", "vorsha_ngo");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

function restoreAdminSessionFromCookie($conn) {
    if (isset($_COOKIE['admin_remember'])) {
        $token = $_COOKIE['admin_remember'];

        $stmt = $conn->prepare("SELECT id FROM admins WHERE remember_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $admin_id = null;
            $stmt->bind_result($admin_id);
            $stmt->fetch();
            $_SESSION['admin'] = $admin_id;
            return true;
        }
    }
    return false;
}

if (!isset($_SESSION['admin'])) {
    if (!restoreAdminSessionFromCookie($conn)) {
        header("Location: ../index.php");
        exit;
    }
}

// Add this session validation after session restore
$session_admin_id = $_SESSION['admin'] ?? '';

if (!$session_admin_id) {
    echo "<script>alert('Session expired. Please login again.'); window.location.href='../index.php';</script>";
    exit;
}
?>
