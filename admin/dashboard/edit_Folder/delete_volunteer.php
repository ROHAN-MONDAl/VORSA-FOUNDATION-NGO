<?php
session_start();
include('../../../server.php'); // Adjust the path as needed

if (isset($_GET['registration_id'])) {
    $registration_id = $_GET['registration_id'];

    $query = "DELETE FROM volunteers WHERE registration_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $registration_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: ../volunteers.php");
exit();
?>

