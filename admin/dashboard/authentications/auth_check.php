<?php
// Session lifetime: 10 seconds
ini_set('session.gc_maxlifetime', 28800 );// 8 hours × 60 minutes × 60 seconds

ini_set('session.cookie_lifetime', 28800 );// 8 hours × 60 minutes × 60 seconds

session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "vorsha_ngo");
if ($conn->connect_error) {
    showPopup("Database connection failed.");
}



// Function to restore session from remember_token cookie
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
            $_SESSION['remember_me'] = true;
            $_SESSION['last_activity'] = time();
            return true;
        }
    }
    return false;
}

// If session is not set, try restoring it
if (!isset($_SESSION['admin'])) {
    if (!restoreAdminSessionFromCookie($conn)) {
        header("Location: ../index.php");
        exit;
    }
}

// Check inactivity timeout (1 hour = 3600 sec)
if (!isset($_SESSION['remember_me'])) {
    $inactivityLimit = 3600;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactivityLimit) {
        session_unset();
        session_destroy();
        echo "<script>alert('Logged out due to 1 hour of inactivity.'); window.location.href='../index.php';</script>";
        exit;
    }

    // Update last activity
    $_SESSION['last_activity'] = time();
}

// Final session validation
$session_admin_id = $_SESSION['admin'] ?? '';
if (!$session_admin_id) {
    echo "<script>alert('Session expired. Please login again.'); window.location.href='../index.php';</script>";
    exit;
}
?>
