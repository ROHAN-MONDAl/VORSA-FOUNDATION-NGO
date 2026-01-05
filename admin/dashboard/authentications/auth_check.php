<?php
// 1. Set Session Settings (8 Hours)
ini_set('session.gc_maxlifetime', 28800); 
ini_set('session.cookie_lifetime', 28800); 

session_start();

// 2. Database Connection Include
// We use dirname(__DIR__, 3) to go up 3 folders:
// authentications -> dashboard -> admin -> public_html (Root)
$server_path = dirname(__DIR__, 3) . '/server.php';

if (file_exists($server_path)) {
    include $server_path;
} else {
    // Debugging: Print where we looked so you can fix it if it's wrong
    die("Error: Could not find server.php. Looking at: " . $server_path);
}

// Ensure $conn exists after include
if (!isset($conn)) {
    die("Error: Database connection variable (\$conn) is missing from server.php.");
}

// ---------------------------------------------------------
// 3. Session & Cookie Logic
// ---------------------------------------------------------

// Function to restore session from remember_token cookie
function restoreAdminSessionFromCookie($conn) {
    if (isset($_COOKIE['admin_remember'])) {
        $token = $_COOKIE['admin_remember'];

        // Prepare statement to prevent SQL Injection
        $stmt = $conn->prepare("SELECT id FROM admins WHERE remember_token = ?");
        if ($stmt) {
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
            $stmt->close();
        }
    }
    return false;
}

// If session is not set, try restoring it
if (!isset($_SESSION['admin'])) {
    if (!restoreAdminSessionFromCookie($conn)) {
        // Redirect to login page (adjust path if needed)
        header("Location: ../../../admin/index.php"); 
        exit;
    }
}

// Check inactivity timeout (1 hour = 3600 sec)
if (!isset($_SESSION['remember_me'])) {
    $inactivityLimit = 3600;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactivityLimit) {
        session_unset();
        session_destroy();
        
        // Safer redirect using JavaScript
        echo "<script>
            alert('Logged out due to 1 hour of inactivity.'); 
            window.location.href='../../../admin/index.php';
        </script>";
        exit;
    }

    // Update last activity
    $_SESSION['last_activity'] = time();
}

// 4. Export the variable for use in Dashboard
$session_admin_id = $_SESSION['admin'] ?? '';

if (!$session_admin_id) {
    echo "<script>
        alert('Session expired. Please login again.'); 
        window.location.href='../../../admin/index.php';
    </script>";
    exit;
}
?>