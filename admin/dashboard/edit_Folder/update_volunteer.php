<?php

include('../authentications/auth_check.php'); // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'registration_id' => $_POST['registration_id'],
        'name' => $_POST['name'],
        'dob' => $_POST['dob'],
        'mobile' => $_POST['mobile'],
        'email' => $_POST['email'],
        'state' => $_POST['state'],
        'district' => $_POST['district'],
        'village' => $_POST['village'],
        'block' => $_POST['block'],
        'pin' => $_POST['pin'],
        'blood' => $_POST['blood'],
    ];

    // Step 1: Check if email or mobile already exists for another user
    $checkQuery = "SELECT registration_id FROM volunteers 
                   WHERE (email = ? OR mobile = ?) AND registration_id != ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("sss", $data['email'], $data['mobile'], $data['registration_id']);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $_SESSION['error_message'] = "Email or Mobile number already exists for another user.";
        header("Location: edit_Volunteers.php?registration_id=" . $data['registration_id']);
        exit();
    }

    // Step 2: Perform the update
    $updateQuery = "UPDATE volunteers SET 
                    name = ?, dob = ?, mobile = ?, email = ?, state = ?, district = ?, 
                    village = ?, block = ?, pin = ?, blood_group = ? 
                    WHERE registration_id = ?";

    $stmt = $conn->prepare($updateQuery);
$stmt->bind_param(
    "sssssssssss",  // 11 strings
    $data['name'],
    $data['dob'],
    $data['mobile'],
    $data['email'],
    $data['state'],
    $data['district'],
    $data['village'],
    $data['block'],
    $data['pin'],
    $data['blood'],
    $data['registration_id']  // string here
);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data is saved.";
    } else {
        $_SESSION['error_message'] = "Failed to save data.";
    }

    header("Location: edit_Volunteers.php?registration_id=" . $data['registration_id']);
    exit();
}
