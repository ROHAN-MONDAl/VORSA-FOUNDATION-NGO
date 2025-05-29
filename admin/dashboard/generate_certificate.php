<?php
// Include your database connection here
include('authentications/auth_check.php');

// Require FPDF library
require('fpdf/fpdf.php');

// The function to generate certificate PDF
function generatePDFCertificate($name, $address, $volunteerId, $date, $savePath, $alignment = 'center', $topPadding = 70)
{
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Add Poppins fonts - ensure these files are in fpdf/font/
    $pdf->AddFont('Poppins', '', 'Poppins-Regular.php');
    $pdf->AddFont('Poppins', 'B', 'Poppins-Bold.php');
    $pdf->AddFont('Poppins', 'I', 'Poppins-Italic.php');
    $pdf->AddFont('Poppins', 'BI', 'Poppins-BoldItalic.php');

    $backgroundPath = __DIR__ . '/certificate/vorsafoundation.png';
    if (!file_exists($backgroundPath)) {
        die('Certificate background image not found.');
    }

    // Draw the background image (A4 landscape: 297 x 210 mm)
    $pdf->Image($backgroundPath, 0, 0, 297, 210);

    $pdf->SetTextColor(0, 0, 0);

    $alignMap = [
        'left' => 'L',
        'center' => 'C',
        'right' => 'R'
    ];
    $fpdfAlign = $alignMap[$alignment] ?? 'C';

    // Text lines to print on certificate
    $lines = [
        ["This is to proudly certify that", 'Poppins', '', 16],
        [$name, 'Poppins', 'BI', 24], // Bold Italic name
        // Uncomment to add address if you want
        // ["residing at $address,", 'Poppins', '', 16],
        ["has registered as a Volunteer with", 'Poppins', '', 16],
        ["VOICE OF RURAL SUPPORT AND ACADEMIC FOUNDATION", 'Poppins', 'B', 16],
        ["as of $date.", 'Poppins', '', 16],
        ["Volunteer ID: $volunteerId", 'Poppins', 'B', 14]
    ];

    $currentY = $topPadding;
    foreach ($lines as $line) {
        list($text, $fontFamily, $fontStyle, $fontSize) = $line;
        $pdf->SetFont($fontFamily, $fontStyle, $fontSize);
        $pdf->SetXY(10, $currentY);
        $pdf->Cell(0, 10, $text, 0, 1, $fpdfAlign);
        $currentY += 10;
    }

    // Save PDF to the given path
    $pdf->Output('F', $savePath);
}

// Main execution starts here
if (!isset($_GET['registration_id'])) {
    die('Registration ID is required');
}

$regId = mysqli_real_escape_string($conn, $_GET['registration_id']);

// Fetch volunteer info from DB
$sql = "SELECT * FROM volunteers WHERE registration_id = '$regId'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) !== 1) {
    die('Volunteer not found');
}

$volunteer = mysqli_fetch_assoc($result);

// Prepare save path
$savePath = __DIR__ . "/certificates/{$volunteer['registration_id']}.pdf";

// Generate the PDF certificate
generatePDFCertificate(
    $volunteer['name'],
    $volunteer['village'] . ', ' . $volunteer['district'],  // example address
    $volunteer['registration_id'],
    date('F j, Y'),  // current date or you can use $volunteer['dob']
    $savePath,
    'center',
    70
);

// Output the PDF file for download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($savePath) . '"');
readfile($savePath);
exit;
