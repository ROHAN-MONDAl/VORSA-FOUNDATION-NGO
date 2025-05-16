<?php
session_start();
require 'server.php';

if (!isset($_GET['code'], $_SESSION['otp'], $_SESSION['form_data'])) {
    echo "<script>alert('Invalid OTP process.');window.location='index.php';</script>";
    exit;
}

if (isset($_GET['code'])) {
    if ($_GET['code'] != $_SESSION['otp']) {
        echo "<script>
            alert('Incorrect OTP. Try again.');
            window.history.back();
        </script>";
        exit;
    }
}

$data = $_SESSION['form_data'];

$stmt = $conn->prepare("INSERT INTO volunteers (registration_id, name, dob, mobile, email, state, district, village, block, pin, blood_group) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $data['registration_id'], $data['name'], $data['dob'], $data['mobile'], $data['email'], $data['state'], $data['district'], $data['village'], $data['block'], $data['pin'], $data['blood']);
$stmt->execute();
$stmt->close();

unset($_SESSION['otp']);
unset($_SESSION['form_data']);

header("Location: index.php?msg=success&id=" . urlencode($data['registration_id']));
exit();
