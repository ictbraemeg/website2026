<?php
/**
 * mycaptcha.php
 *
 * Generates a simple numeric CAPTCHA image and stores the code
 * in $_SESSION['digit'] for reachToUs.php to validate.
 */

declare(strict_types=1);

/* ── Start session before any output ─────────────────────────── */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* ── Generate code and store in session ──────────────────────── */
$code = (string) rand(10000, 99999);
$_SESSION["digit"] = $code;

/* ── Ensure GD is available ──────────────────────────────────── */
if (!function_exists("imagecreate")) {
    header("Content-Type: text/plain; charset=UTF-8");
    echo "ERROR: PHP GD extension is not enabled on this server.";
    exit();
}

/* ── Create image ───────────────────────────────────────────── */
$width = 120;
$height = 40;

$image = imagecreate($width, $height);
if ($image === false) {
    header("Content-Type: text/plain; charset=UTF-8");
    echo "ERROR: Could not create CAPTCHA image.";
    exit();
}

/* Colors */
$bgColor = imagecolorallocate($image, 240, 240, 240); // light grey
$textColor = imagecolorallocate($image, 50, 50, 50); // dark grey
$noiseColor = imagecolorallocate($image, 180, 180, 180);

/* Simple noise */
for ($i = 0; $i < 4; $i++) {
    imageline(
        $image,
        rand(0, $width),
        rand(0, $height),
        rand(0, $width),
        rand(0, $height),
        $noiseColor,
    );
}

/* ── Draw the code ───────────────────────────────────────────── */
$fontSize = 5; // built-in font
$textWidth = imagefontwidth($fontSize) * strlen($code);
$textHeight = imagefontheight($fontSize);
$x = (int) (($width - $textWidth) / 2);
$y = (int) (($height - $textHeight) / 2);

imagestring($image, $fontSize, $x, $y, $code, $textColor);

/* ── Output as PNG ───────────────────────────────────────────── */
header("Content-Type: image/png");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

imagepng($image);
imagedestroy($image);
