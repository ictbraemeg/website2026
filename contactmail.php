<?php
/**
 * contactmail.php
 * Handles the contact form POST. Called directly or via fetch() from main.js.
 * Returns plain text response: "ok" on success, error message on failure.
 */

require_once "config/shikisho.php";
require_once "config/mail.php";

date_default_timezone_set("Africa/Nairobi");

/* ── Only accept POST ─────────────────────────────────────── */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed");
}

/* ── Collect and sanitise inputs ──────────────────────────── */
$name = ucwords(trim(strip_tags($_POST["mname"] ?? "")));
$email = trim(strip_tags($_POST["email"] ?? ""));
$phone = trim(strip_tags($_POST["phone"] ?? ""));
$subject = trim(strip_tags($_POST["subject"] ?? ""));
$message = trim(strip_tags($_POST["message"] ?? ""));
$date = date("d-m-Y H:i");

/* ── Validate required fields ─────────────────────────────── */
if (empty($name) || empty($email) || empty($subject)) {
    http_response_code(400);
    exit("Please fill in all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Invalid email address.");
}

/* ── Basic injection guard ────────────────────────────────── */
$injection_pattern = '/(\n|\r|\t|%0A|%0D|%08|%09)/i';
if (
    preg_match($injection_pattern, $email) ||
    preg_match($injection_pattern, $name)
) {
    http_response_code(400);
    exit("Invalid input detected.");
}

/* ── Get company info for email "from" ────────────────────── */
$company_qry = $dbc->prepare(
    "SELECT * FROM tbl_company WHERE published='1' LIMIT 1",
);
$company_qry->execute();
$company = $company_qry->fetch(PDO::FETCH_ASSOC);

/* ── Build HTML email body ────────────────────────────────── */
$email_body =
    '
<table border="1" cellpadding="8" cellspacing="0" width="600"
       style="border-collapse:collapse; font-family:Arial,sans-serif; font-size:14px;">
  <tr style="background:#0d4a2e; color:#ffffff;">
    <td colspan="2" align="center" style="padding:16px;">
      <strong>New Contact Form Submission — Braemeg SACCO</strong>
    </td>
  </tr>
  <tr>
    <td width="30%" style="padding:10px;"><strong>Name</strong></td>
    <td style="padding:10px;">' .
    htmlspecialchars($name) .
    '</td>
  </tr>
  <tr style="background:#f5f5f5;">
    <td style="padding:10px;"><strong>Email</strong></td>
    <td style="padding:10px;">' .
    htmlspecialchars($email) .
    '</td>
  </tr>
  <tr>
    <td style="padding:10px;"><strong>Phone</strong></td>
    <td style="padding:10px;">' .
    htmlspecialchars($phone) .
    '</td>
  </tr>
  <tr style="background:#f5f5f5;">
    <td style="padding:10px;"><strong>Subject</strong></td>
    <td style="padding:10px;">' .
    htmlspecialchars($subject) .
    '</td>
  </tr>
  <tr>
    <td style="padding:10px;"><strong>Message</strong></td>
    <td style="padding:10px;">' .
    nl2br(htmlspecialchars($message)) .
    '</td>
  </tr>
  <tr style="background:#f5f5f5;">
    <td style="padding:10px;"><strong>Date</strong></td>
    <td style="padding:10px;">' .
    $date .
    '</td>
  </tr>
</table>';

/* ── Send via PHPMailer ────────────────────────────────────── */
include_once "phpmailer/class.phpmailer.php";

$mail = new PHPMailer(true);

try {
    $mail->IsSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = MAIL_AUTH;
    $mail->Port = MAIL_PORT;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;

    $mail->SetFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->AddReplyTo($email, $name);
    $mail->AddAddress("info.braemegsacco@gmail.com", "Braemeg SACCO");
    $mail->AddBCC("braemegsacco@yahoo.com");
    $mail->AddBCC("rgitundu@gmail.com");

    $mail->Subject = "Online Contact: " . $subject;
    $mail->IsHTML(true);
    $mail->Body = $email_body;
    $mail->AltBody = "New contact from {$name} ({$email})\n\nSubject: {$subject}\nPhone: {$phone}\n\nMessage:\n{$message}\n\nDate: {$date}";

    $mail->Send();

    /* If the request was an AJAX fetch, return JSON-friendly ok */
    if (
        !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) ||
        strpos($_SERVER["HTTP_ACCEPT"] ?? "", "application/json") !== false
    ) {
        header("Content-Type: application/json");
        echo json_encode(["status" => "ok"]);
    } else {
        /* Traditional form POST — redirect to thank-you page */
        header("Location: thankYou1.php");
    }
} catch (phpmailerException $e) {
    http_response_code(500);
    exit("Mail error: " . $e->errorMessage());
} catch (Exception $e) {
    http_response_code(500);
    exit("Error: " . $e->getMessage());
}
