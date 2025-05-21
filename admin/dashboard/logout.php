<?php
session_start();
include('../../server.php');

if (isset($_SESSION['admin'])) {
    $stmt = $conn->prepare("UPDATE admins SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin']);
    $stmt->execute();
}

session_unset();
session_destroy();
setcookie("admin_remember", "", time() - 3600, "/", "", true, true);

header("Location: ../index.php");
exit;
