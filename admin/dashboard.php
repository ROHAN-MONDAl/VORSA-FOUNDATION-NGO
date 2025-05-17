<?php
session_start();

// Check if admin session exists
if (!isset($_SESSION['admin'])) {
    // If no session but "remember me" cookie exists, create session from cookie
    if (isset($_COOKIE['admin_remember'])) {
        $_SESSION['admin'] = $_COOKIE['admin_remember'];
    } else {
        // Neither session nor cookie present, redirect to login
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        h1 { color: #333; }
        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background: #e04444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h1>
        <p>This is your dashboard.</p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
