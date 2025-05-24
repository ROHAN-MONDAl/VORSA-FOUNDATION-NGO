<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // from Composer

function sendCertificateEmail($to, $name, $certificatePath) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // change this
        $mail->SMTPAuth = true;
        $mail->Username = 'codecomettechnology@gmail.com';
        $mail->Password = 'uons ghbx ieri vchm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('codecomettechnology@gmail.com', 'VORSA FOUNDATION');
        $mail->addAddress($to, $name);
        $mail->addAttachment($certificatePath);

        $mail->isHTML(true);
        $mail->Subject = 'Your Volunteer Certificate';
        $mail->Body = "Dear $name,<br><br>Thank you for registering. Please find your certificate attached.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
    }
}
