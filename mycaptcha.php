<?php
/**
 * mycaptcha.php
 * Generates a CAPTCHA image and stores the answer in the session.
 * Requires GD extension and a font file at fonts/times_new_yorker.ttf
 */
session_start();

/* ── Generate random string ─────────────────────────────────── */
function captcha_random_string(int $length = 5): string {
    $chars = array_merge(range('2', '9'), range('A', 'H'), range('J', 'N'), range('P', 'Z'));
    shuffle($chars);
    return strtoupper(substr(implode('', $chars), 0, $length));
}

$code = captcha_random_string(5);
$_SESSION['digit'] = $code;

/* ── Image settings ─────────────────────────────────────────── */
$width     = 120;
$height    = 38;
$font_size = 20;
$font_file = __DIR__ . '/fonts/times_new_yorker.ttf';

$image = imagecreatetruecolor($width, $height);

/* Background */
$bg    = imagecolorallocate($image, 220, 240, 255);
$line  = imagecolorallocate($image, 150, 190, 220);
$text  = imagecolorallocate($image, 20, 20, 20);
$noise = imagecolorallocate($image, 180, 210, 230);

imagefill($image, 0, 0, $bg);

/* Noise lines */
for ($i = 0; $i < 6; $i++) {
    imagesetthickness($image, rand(1, 2));
    imageline($image, rand(0, $width), 0, rand(0, $width), $height, $line);
}

/* Noise dots */
for ($i = 0; $i < 40; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $noise);
}

/* Text — use imagefttext if font exists, fall back to imagestring */
if (file_exists($font_file)) {
    imagefttext($image, $font_size, rand(-4, 4), 8, 28, $text, $font_file, $code);
} else {
    imagestring($image, 5, 10, 10, $code, $text);
}

/* Output */
header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
imagepng($image);
imagedestroy($image);
