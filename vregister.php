<?php
session_start(); // Start the session to store OTP and form data

// ✅ Connect to the database
require 'server.php';

// ✅ Include PHPMailer for sending emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// ✅ Helper function to show an alert and go back
function showPopup($msg)
{
    echo "<script>alert('$msg'); window.history.back();</script>";
    exit;
}

// ✅ Check if the form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🔹 Get form data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $mobile = $_POST['mob'];
    $email = $_POST['mail'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $village = $_POST['village'];
    $block = $_POST['block'];
    $pin = $_POST['pin'];
    $blood = $_POST['blood'];

    // ✅ Google reCAPTCHA check (test key for local/dev use)
    $secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";
    $responseKey = $_POST['g-recaptcha-response'] ?? '';
    $userIP = $_SERVER['REMOTE_ADDR'];

    if (empty($responseKey)) {
        showPopup("Please complete the CAPTCHA.");
    }

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $responseJson = file_get_contents($url);
    $response = json_decode($responseJson);

    if (!$response->success) {
        showPopup("Captcha verification failed!");
    }

    // ✅ Check if the email or mobile is already registered
    $check = $conn->query("SELECT * FROM registrations WHERE email='$email' OR mobile='$mobile'");
    if ($check->num_rows > 0) {
        showPopup("Email or mobile already registered.");
    }

    // ✅ Generate a unique volunteer ID like V000001
    // Step 1: Get the latest registration_id from both tables
    $res1 = $conn->query("SELECT registration_id FROM registrations ORDER BY id DESC LIMIT 1");
    $res2 = $conn->query("SELECT registration_id FROM volunteers ORDER BY id DESC LIMIT 1");

    $num1 = 0;
    $num2 = 0;

    if ($res1 && $res1->num_rows > 0) {
        $row1 = $res1->fetch_assoc();
        $num1 = intval(substr($row1['registration_id'], 1));
    }

    if ($res2 && $res2->num_rows > 0) {
        $row2 = $res2->fetch_assoc();
        $num2 = intval(substr($row2['registration_id'], 1));
    }

    // Step 2: Get the highest number and generate the next ID
    $nextId = max($num1, $num2) + 1;
    $registration_id = "V" . str_pad($nextId, 6, "0", STR_PAD_LEFT); // e.g. V000001

    // ✅ Generate a 6-digit OTP
    $otp = rand(100000, 999999);

    // ✅ Store OTP and form data in session
    $_SESSION['otp'] = $otp;
    $_SESSION['form_data'] = compact(
        'registration_id', 'name', 'dob', 'mobile', 'email',
        'state', 'district', 'village', 'block', 'pin', 'blood'
    );

    // ✅ Send OTP via email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'vorsafoundation@gmail.com'; // Replace with your email
        $mail->Password = 'wrtn vszy jdmg zcgy'; // Replace with your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('vorsafoundation@gmail.com', 'Vorsa Foundation');
        $mail->addAddress($email); // Send to the user's email

        $mail->Subject = 'Your OTP for Volunteer Registration';
        $mail->Body = "Dear $name,\n\nYour OTP for completing the registration is: $otp\n\nThank you for joining VORSA Foundation!";

        $mail->send();

        // ✅ Redirect to OTP verification page
        header("Location: vindex.php?otp=1");
        exit();
    } catch (Exception $e) {
        showPopup("Failed to send OTP email: " . $mail->ErrorInfo);
    }
} else {
    showPopup("Invalid request.");
}
