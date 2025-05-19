<?php
function generateCertificate($name, $address, $volunteerId, $date, $savePath) {
    $template = 'WhatsApp Image 2025-05-16 at 16.15.46_eff60b21.jpg'; // certificate background
    $image = imagecreatefromjpeg($template);

    $text = "This is to certify that $name, residing at $address,\n"
          . "has been duly registered as a Volunteer with Voice of Rural Social Awareness (VORSA)\n"
          . "as of $date.\n\nVolunteer ID: $volunteerId";

    $black = imagecolorallocate($image, 0, 0, 0);
    $font = __DIR__ . '/arial.ttf'; // ensure this font is available
    $fontSize = 18;

    // Word wrap manually
    $lines = explode("\n", wordwrap($text, 90));
    $y = 450; // vertical positioning
    foreach ($lines as $line) {
        $box = imagettfbbox($fontSize, 0, $font, $line);
        $x = (imagesx($image) - ($box[2] - $box[0])) / 2;
        imagettftext($image, $fontSize, 0, $x, $y, $black, $font, $line);
        $y += 30;
    }

    imagejpeg($image, $savePath, 100);
    imagedestroy($image);
}
