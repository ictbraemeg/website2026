<?php
/**
 * reachToUs.php
 * Handles the contact form POST from contacts.php.
 * - Validates CAPTCHA and inputs
 * - Sends email via PHPMailer (SMTP)
 * - For AJAX (fetch): returns plain text status strings
 */

declare(strict_types=1);

session_start();

require_once __DIR__ . "/config/shikisho.php";
require_once __DIR__ . "/config/mail.php";
require_once __DIR__ . "/vendor/autoload.php"; // Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set("Africa/Nairobi");

/* ── Only accept POST ─────────────────────────────────────────── */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contacts.php");
    exit();
}

/* ── Detect whether request is AJAX fetch ─────────────────────── */
$is_ajax =
    !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) ||
    isset($_SERVER["HTTP_FETCH_MODE"]) ||
    strpos($_SERVER["HTTP_ACCEPT"] ?? "", "application/json") !== false;

/* ── Validate CAPTCHA ─────────────────────────────────────────── */
$submitted_captcha = strtoupper(trim($_POST["captcha"] ?? ""));
$session_captcha = strtoupper($_SESSION["digit"] ?? "");

if (empty($session_captcha) || $submitted_captcha !== $session_captcha) {
    unset($_SESSION["digit"]); // force refresh on next load
    if ($is_ajax) {
        http_response_code(422);
        echo "wrong_captcha";
    } else {
        header("Location: contacts.php?err=captcha");
    }
    exit();
}

unset($_SESSION["digit"]); // consumed — prevent replay

/* ── Collect & sanitise ───────────────────────────────────────── */
$name = ucwords(trim(strip_tags($_POST["name"] ?? "")));
$email = trim(strip_tags($_POST["email"] ?? ""));
$phone = trim(strip_tags($_POST["phone"] ?? ""));
$subject = trim(strip_tags($_POST["subject"] ?? ""));
$message = trim(strip_tags($_POST["message"] ?? ""));
$date = date("d-m-Y H:i");

/* ── Validate required fields ─────────────────────────────────── */
if (!$name || !$email || !$phone || !$subject) {
    if ($is_ajax) {
        http_response_code(400);
        echo "missing_fields";
    } else {
        header("Location: contacts.php?err=fields");
    }
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($is_ajax) {
        http_response_code(400);
        echo "invalid_email";
    } else {
        header("Location: contacts.php?err=email");
    }
    exit();
}

/* ── Injection guard ──────────────────────────────────────────── */
if (preg_match('/(\n|\r|\t|%0A|%0D|%08|%09)/i', $email . $name)) {
    http_response_code(400);
    echo "invalid_input";
    exit();
}

/* ── Company info (for context if needed) ─────────────────────── */
$co_qry = $dbc->prepare(
    "SELECT * FROM tbl_company WHERE published='1' LIMIT 1",
);
$co_qry->execute();
$rcs = $co_qry->fetch(PDO::FETCH_ASSOC);

/* ── Build email body ─────────────────────────────────────────── */
$email_body =
    '
<table border="1" cellpadding="8" cellspacing="0" width="600"
       style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;">
  <tr style="background:#0d4a2e;color:#ffffff;">
    <td colspan="2" align="center" style="padding:16px;">
      <strong>Contact Form — Braemeg SACCO Website</strong>
    </td>
  </tr>
  <tr><td width="30%"><strong>Name</strong></td><td>' .
    htmlspecialchars($name) .
    '</td></tr>
  <tr style="background:#f5f5f5;"><td><strong>Email</strong></td><td>' .
    htmlspecialchars($email) .
    '</td></tr>
  <tr><td><strong>Phone</strong></td><td>' .
    htmlspecialchars($phone) .
    '</td></tr>
  <tr style="background:#f5f5f5;"><td><strong>Subject</strong></td><td>' .
    htmlspecialchars($subject) .
    '</td></tr>
  <tr><td><strong>Message</strong></td><td>' .
    nl2br(htmlspecialchars($message)) .
    '</td></tr>
  <tr style="background:#f5f5f5;"><td><strong>Date</strong></td><td>' .
    $date .
    '</td></tr>
</table>';

/* ── Send via PHPMailer ───────────────────────────────────────── */
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = MAIL_AUTH;
    $mail->Port = MAIL_PORT;

    if (MAIL_AUTH) {
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        // Use login by default for production; adjust if needed
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS
    }

    // From / reply-to / recipients
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addReplyTo($email, $name);

    // Primary recipient
    $mail->addAddress("info.braemegsacco@gmail.com", "Braemeg SACCO");

    // BCCs
    $mail->addBCC("braemegsacco@yahoo.com");
    $mail->addBCC("rgitundu@gmail.com");

    // Content
    $mail->Subject = "Website Contact: " . $subject;
    $mail->isHTML(true);
    $mail->Body = $email_body;
    $mail->AltBody =
        "Contact from: {$name} <{$email}>\n" .
        "Phone: {$phone}\n" .
        "Subject: {$subject}\n\n" .
        "{$message}\n\n" .
        "Date: {$date}";

    $mail->send();

    if ($is_ajax) {
        http_response_code(200);
        echo "ok";
    } else {
        header("Location: thankYou1.php");
    }
} catch (Exception $e) {
    http_response_code(500);
    if ($is_ajax) {
        echo "mail_error";
    } else {
        header("Location: contacts.php?err=mail");
    }
}
