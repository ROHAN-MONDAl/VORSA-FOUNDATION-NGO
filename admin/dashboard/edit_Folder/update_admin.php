<?php
include('../authentications/auth_check.php'); // DB connection + session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$user_id || !$email) {
        $_SESSION['admin_message'] = "User ID and Email are required.";
        $_SESSION['admin_message_type'] = "danger";
        header("Location: ../settings.php");
        exit;
    }

    // Check duplicates excluding current admin's own record
    $checkSql = "SELECT * FROM admins WHERE (user_id = ? OR email = ?) AND id != ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ssi", $user_id, $email, $session_admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // We have at least one admin with the same user_id or email
        while ($dup = $result->fetch_assoc()) {
            if ($dup['user_id'] === $user_id) {
                $_SESSION['admin_message'] = "User ID already exists.";
                $_SESSION['admin_message_type'] = "danger";
                header("Location: ../settings.php");
                exit;
            }
            if ($dup['email'] === $email) {
                $_SESSION['admin_message'] = "Email already exists.";
                $_SESSION['admin_message_type'] = "danger";
                header("Location: ../settings.php");
                exit;
            }
        }
    }

    // Get current password hash to keep if password not changed
    $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->bind_param("i", $session_admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current = $result->fetch_assoc();
    $current_password_hash = $current['password'] ?? '';

    if ($password === '') {
        // Keep existing password hash if password field is empty
        $password_to_store = $current_password_hash;
    } else {
        // Validate password complexity
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[\W_]/', $password)) {
            $_SESSION['admin_message'] = "Password must contain at least one uppercase letter, one lowercase letter, and one special character.";
            $_SESSION['admin_message_type'] = "danger";
            header("Location: ../settings.php");
            exit;
        }
        // Hash new password
        $password_to_store = password_hash($password, PASSWORD_DEFAULT);
    }

    // Now update the admin record
    $stmt = $conn->prepare("UPDATE admins SET user_id = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $user_id, $email, $password_to_store, $session_admin_id);

    if ($stmt->execute()) {
        $_SESSION['admin_message'] = "Admin updated successfully.";
        $_SESSION['admin_message_type'] = "success";
    } else {
        $_SESSION['admin_message'] = "Update failed. Please try again.";
        $_SESSION['admin_message_type'] = "danger";
    }

    header("Location: ../settings.php");
    exit;
}
?>
