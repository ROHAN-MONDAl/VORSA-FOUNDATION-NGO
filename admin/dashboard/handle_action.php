<?php
require '../../server.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

function generatePDFCertificate($name, $address, $volunteerId, $date, $savePath)
{
    require('fpdf/fpdf.php');
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    $backgroundPath = __DIR__ . '/certificate/certificate_bg.jpg';
    if (!file_exists($backgroundPath)) {
        echo 'Certificate background image not found.';
        exit;
    }

    $pdf->Image($backgroundPath, 0, 0, 297);
    $pdf->SetFont('Arial', '', 16);
    $pdf->SetTextColor(0, 0, 0);

    $lines = [
        "This is to certify that $name,",
        "residing at $address,",
        "has been duly registered as a Volunteer with Voice of Rural Social Awareness (VORSA)",
        "as of $date.",
        "Volunteer ID: $volunteerId"
    ];

    $y = 120;
    foreach ($lines as $line) {
        $pdf->SetXY(10, $y);
        $pdf->Cell(0, 10, $line, 0, 1, 'C');
        $y += 12;
    }

    $pdf->Output('F', $savePath);
}

function sendCertificateEmail($to, $name, $filePath)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'codecomettechnology@gmail.com';
        $mail->Password = 'nqqt ncdb ixbl uobl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('codecomettechnology@gmail.com', 'VORSA FOUNDATION');
        $mail->addAddress($to, $name);
        $mail->addAttachment($filePath);

        $mail->isHTML(true);
        $mail->Subject = 'Your Volunteer Certificate';
        $mail->Body = "Dear $name,<br><br>Thank you for registering with VORSA. Your certificate is attached.";
        $mail->send();
    } catch (Exception $e) {
        echo 'Email failed: ' . $mail->ErrorInfo;
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'], $_POST['action'])) {
        echo 'Missing data.';
        exit;
    }

    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $res = mysqli_query($conn, "SELECT * FROM registrations WHERE id = $id");
        if (!$res || mysqli_num_rows($res) === 0) {
            echo 'Registration not found.';
            exit;
        }

        $row = mysqli_fetch_assoc($res);

        $name = $row['name'];
        $address = "{$row['village']}, {$row['block']}, {$row['district']}, {$row['state']} - {$row['pin']}";
        $email = $row['email'];
        $volunteerId = $row['registration_id'];
        $created_at = date("d M Y", strtotime($row['created_at']));

        $certDir = 'certificates/';
        if (!file_exists($certDir)) mkdir($certDir, 0777, true);
        $certPath = $certDir . $volunteerId . '.pdf';

        generatePDFCertificate($name, $address, $volunteerId, $created_at, $certPath);

        $stmt = $conn->prepare("INSERT INTO volunteers (registration_id, name, dob, mobile, email, state, district, village, block, pin, blood_group, created_at, certificate_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssssss",
            $row['registration_id'],
            $row['name'],
            $row['dob'],
            $row['mobile'],
            $row['email'],
            $row['state'],
            $row['district'],
            $row['village'],
            $row['block'],
            $row['pin'],
            $row['blood_group'],
            $row['created_at'],
            $certPath
        );
        $stmt->execute();
        $stmt->close();

        sendCertificateEmail($email, $name, $certPath);
        mysqli_query($conn, "DELETE FROM registrations WHERE id = $id");

        echo 'approved';
        exit;

    } elseif ($action === 'reject') {
        mysqli_query($conn, "DELETE FROM registrations WHERE id = $id");
        echo 'rejected';
        exit;

    } else {
        echo 'Invalid action.';
        exit;
    }
} else {
    echo 'Invalid request method.';
    exit;
}
