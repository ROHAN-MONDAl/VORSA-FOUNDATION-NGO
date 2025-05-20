<?php
// Load database connection from server.php
require '../../server.php';

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

/**
 * Generate PDF certificate with volunteer info.
 * Uses FPDF library to create a certificate PDF with background and text.
 *
 * @param string $name Volunteer name
 * @param string $address Volunteer address
 * @param string $volunteerId Unique volunteer ID
 * @param string $date Registration date formatted
 * @param string $savePath Path to save the generated PDF
 */

//  Create a function to generate the PDF certificate
function generatePDFCertificate($name, $address, $volunteerId, $date, $savePath, $alignment = 'center', $topPadding = 80)
{
    require('fpdf/fpdf.php');

    // Create landscape A4 PDF
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Path to certificate background
    $backgroundPath = __DIR__ . '/certificate/certificate_bg.jpg';

    // Show error if background image is missing
    if (!file_exists($backgroundPath)) {
        echo 'Certificate background image not found.';
        exit;
    }

    // Add full-width background image
    $pdf->Image($backgroundPath, 0, 0, 297);

    // Set default text color to black
    $pdf->SetTextColor(0, 0, 0);

    // Map simple alignment to FPDF format
    $alignMap = [
        'left' => 'L',
        'center' => 'C',
        'right' => 'R'
    ];
    $fpdfAlign = isset($alignMap[$alignment]) ? $alignMap[$alignment] : 'C'; // default to center

    // --- Certificate Lines (customize as needed) ---
    $lines = [
        ["This is to certify that", 'Times', '', 16],
        [$name, 'Times', 'BI', 24], // Bold Italic name
        ["residing at $address,", 'Times', '', 16],
        ["has been duly registered as a Volunteer", 'Times', '', 16],
        ["with Voice of Rural Social Awareness (VORSA)", 'Times', 'B', 16], // Bold
        ["as of $date.", 'Times', '', 16],
        ["Volunteer ID: $volunteerId", 'Times', 'B', 14] // Bold
    ];

    // Start vertical position (top padding)
    $currentY = $topPadding;

    // Loop through lines and render text
    foreach ($lines as $line) {
        list($text, $fontFamily, $fontStyle, $fontSize) = $line;
        $pdf->SetFont($fontFamily, $fontStyle, $fontSize);
        $pdf->SetXY(10, $currentY);                      // X = 10mm left padding, Y = current line height
        $pdf->Cell(0, 10, $text, 0, 1, $fpdfAlign);       // Full-width cell, align by user setting
        $currentY += 10;                                  // Line spacing
    }

    // Save the PDF to disk
    $pdf->Output('F', $savePath);
}



/**
 * Send volunteer certificate by email using PHPMailer.
 *
 * @param string $to Recipient email address
 * @param string $name Recipient name
 * @param string $filePath Path to the PDF certificate file
 */
function sendCertificateEmail($to, $name, $filePath)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';           // SMTP server
        $mail->SMTPAuth = true;                    // Enable SMTP authentication
        $mail->Username = 'codecomettechnology@gmail.com';  // SMTP username
        $mail->Password = 'nqqt ncdb ixbl uobl';            // SMTP password (use app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587;

        $mail->setFrom('codecomettechnology@gmail.com', 'VORSA FOUNDATION');
        $mail->addAddress($to, $name);
        $mail->addAttachment($filePath);          // Attach generated PDF certificate

        $mail->isHTML(true);
        $mail->Subject = 'Your Volunteer Certificate';
        $mail->Body = "Dear $name,<br><br>We appreciate your registration with the Voice of Rural Social Awareness (VORSA). 
        Your certificate of recognition is attached for your records";

        $mail->send();  // Send the email
    } catch (Exception $e) {
        // If email sending fails, return error message and stop script
        echo 'Email failed: ' . $mail->ErrorInfo;
        exit;
    }
}

// Main logic: handle POST requests only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required POST parameters
    if (!isset($_POST['id'], $_POST['action'])) {
        echo 'Missing data.';
        exit;
    }

    $id = intval($_POST['id']);
    $action = $_POST['action'];

    // Handle approval
    if ($action === 'approve') {
        // Fetch registration data for the given ID
        $res = mysqli_query($conn, "SELECT * FROM registrations WHERE id = $id");
        if (!$res || mysqli_num_rows($res) === 0) {
            echo 'Registration not found.';
            exit;
        }

        $row = mysqli_fetch_assoc($res);

        // Prepare data for certificate and email
        $name = $row['name'];
        $address = "{$row['village']}, {$row['block']}, {$row['district']}, {$row['state']} - {$row['pin']}";
        $email = $row['email'];
        $volunteerId = $row['registration_id'];
        $created_at = date("d M Y", strtotime($row['created_at']));

        // Create directory for certificates if not exists
        $certDir = 'certificates/';
        if (!file_exists($certDir)) mkdir($certDir, 0777, true);
        $certPath = $certDir . $volunteerId . '.pdf';

        // Generate PDF certificate file
        generatePDFCertificate($name, $address, $volunteerId, $created_at, $certPath);

        // Check for duplicate volunteer registration to avoid duplicate entries
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM volunteers WHERE registration_id = ?");
        $stmtCheck->bind_param("s", $volunteerId);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            // Duplicate found - notify admin and do NOT insert duplicate record
            echo 'duplicate';
            exit;
        }

        // Insert volunteer data into volunteers table
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

        // Send the volunteer certificate email
        sendCertificateEmail($email, $name, $certPath);

        // Remove registration entry since it is approved
        mysqli_query($conn, "DELETE FROM registrations WHERE id = $id");

        // Indicate successful approval
        echo 'approved';
        exit;
    } elseif ($action === 'reject') {
        // Delete registration entry on rejection
        mysqli_query($conn, "DELETE FROM registrations WHERE id = $id");
        echo 'rejected';
        exit;
    } else {
        echo 'Invalid action.';
        exit;
    }
} else {
    // Reject non-POST requests
    echo 'Invalid request method.';
    exit;
}
