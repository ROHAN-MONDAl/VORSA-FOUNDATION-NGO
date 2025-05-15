<?php
session_start();
header('Content-Type: application/json');

$code = $_POST['code'] ?? '';

if ($code == $_SESSION['verification_code']) {
    unset($_SESSION['verification_code']); // remove code after success
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
