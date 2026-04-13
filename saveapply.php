<?php
/**
 * saveapply.php
 * Handles the full membership application POST.
 * Returns JSON: { status, ref, email, print_url } on success.
 */

/* ── Output buffer: catch any stray PHP notices before JSON ──── */
ob_start();

/* Suppress display of errors — log them instead */
ini_set("display_errors", "0");
ini_set("log_errors", "1");
error_reporting(E_ALL);

date_default_timezone_set("Africa/Nairobi");

/* Set JSON header now; ob_clean() before every echo ensures clean output */
header("Content-Type: application/json");

function json_exit(array $payload, int $code = 200): void
{
    ob_clean(); /* discard any stray output accumulated so far */
    http_response_code($code);
    echo json_encode($payload);
    exit();
}

/*
 * Shutdown handler — catches fatal errors that would otherwise produce
 * an empty or HTML response, causing the JS fetch to show "network error".
 */
register_shutdown_function(function () {
    $err = error_get_last();
    if (
        $err &&
        in_array(
            $err["type"],
            [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR],
            true,
        )
    ) {
        ob_clean();
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" =>
                "A server error occurred. Please try again or contact us on +254 724 053 548.",
        ]);
    }
});

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    json_exit(["status" => "error", "message" => "Method not allowed"], 405);
}

require_once "config/shikisho.php";
require_once "config/mail.php";
include_once "phpmailer/class.phpmailer.php";

/* ── Helper: sanitise a text field ──────────────────────────── */
function sf(string $key, bool $upper = false): string
{
    $v = trim(strip_tags($_POST[$key] ?? ""));
    return $upper ? strtoupper($v) : $v;
}

/* ── Collect all fields ──────────────────────────────────────── */
$fullname = sf("fullname");
$idno = sf("idno");
$dob = sf("dob");
$gender = sf("gender");
$mobile = sf("mobile");
$email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
$kra_pin = sf("kra_pin", true);
$marital_status = sf("marital_status");
$residence = sf("residence");
$postal_address = sf("postal_address");
$postal_code = sf("postal_code");
$town = sf("town");

$employer = sf("employer");
$emp_no = sf("emp_no");
$designation = sf("designation");
$emp_terms = sf("emp_terms");
$campus = sf("campus");

$bank_name = sf("bank_name");
$bank_account = sf("bank_account");
$bank_branch = sf("bank_branch");

$kin_name = sf("kin_name");
$kin_relationship = sf("kin_relationship");
$kin_mobile = sf("kin_mobile");

$payment_methods = isset($_POST["payment_method"])
    ? (array) $_POST["payment_method"]
    : [];
$payroll_no = sf("payroll_no");
$capital_shares = sf("capital_shares");
$savings_total = sf("savings_total");
$dep_deposits = sf("dep_deposits");
$dep_christmas = sf("dep_christmas");
$dep_holiday = sf("dep_holiday");
$dep_toto = sf("dep_toto");
$total_monthly = sf("total_monthly");

$consent_0 = sf("consent_0");
$consent_1 = sf("consent_1");
$consent_2 = sf("consent_2");

$newsletter = sf("newsletter_opt_in");
$declaration = sf("declaration");
$dateadded = date("d-m-Y H:i:s");

/* Beneficiaries — names sent as ben_name[], ben_relationship[] etc. */
$beneficiaries = [];
$ben_names = (array) ($_POST["ben_name"] ?? []);
$ben_rels = (array) ($_POST["ben_relationship"] ?? []);
$ben_allocs = (array) ($_POST["ben_allocation"] ?? []);
$ben_idnos = (array) ($_POST["ben_idno"] ?? []);
$ben_mobiles = (array) ($_POST["ben_mobile"] ?? []);
foreach ($ben_names as $i => $bn) {
    if (trim($bn) !== "") {
        $beneficiaries[] = [
            "name" => trim(strip_tags($bn)),
            "relationship" => trim(strip_tags($ben_rels[$i] ?? "")),
            "allocation" => (int) ($ben_allocs[$i] ?? 0),
            "idno" => trim(strip_tags($ben_idnos[$i] ?? "")),
            "mobile" => trim(strip_tags($ben_mobiles[$i] ?? "")),
        ];
    }
}

/* ── Validate required fields ────────────────────────────────── */
$errors = [];
if (!$fullname) {
    $errors[] = "Full name is required.";
}
if (!$idno) {
    $errors[] = "ID/Alien Card No. is required.";
}
if (!$dob) {
    $errors[] = "Date of Birth is required.";
}
if (!$gender) {
    $errors[] = "Gender is required.";
}
if (!$mobile) {
    $errors[] = "Mobile number is required.";
}
if (!$email) {
    $errors[] = "Email address is required.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
if (!$kra_pin) {
    $errors[] = "KRA PIN is required.";
}
if (!$employer) {
    $errors[] = "Employer name is required.";
}
if (!$emp_terms) {
    $errors[] = "Employment terms are required.";
}
if (!$bank_name) {
    $errors[] = "Bank name is required.";
}
if (!$bank_account) {
    $errors[] = "Bank account number is required.";
}
if (!$bank_branch) {
    $errors[] = "Bank branch is required.";
}
if (!$kin_name) {
    $errors[] = "Next of kin name is required.";
}
if (!$kin_relationship) {
    $errors[] = "Next of kin relationship is required.";
}
if (!$kin_mobile) {
    $errors[] = "Next of kin mobile is required.";
}
if (!$declaration) {
    $errors[] = "You must agree to the declaration.";
}
if (!$newsletter) {
    $errors[] = "Please confirm your email communications preference.";
}

if (!empty($errors)) {
    json_exit(["status" => "error", "message" => implode(" ", $errors)], 400);
}

/* Injection guard */
if (preg_match('/(\n|\r|\t|%0A|%0D|%08|%09)/i', $email)) {
    json_exit(
        ["status" => "error", "message" => "Invalid input detected."],
        400,
    );
}

/* ── Duplicate check ─────────────────────────────────────────── */
$chk = $dbc->prepare("SELECT PID FROM tbl_membership WHERE email = :e LIMIT 1");
$chk->execute([":e" => $email]);
if ($chk->rowCount() > 0) {
    json_exit(["status" => "duplicate"]);
}

/* ── Handle file uploads ─────────────────────────────────────── */
$upload_dir = __DIR__ . "/uploads/membership/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$ref = date("Ymd") . "_" . strtoupper(substr(md5($email . time()), 0, 8));
$app_dir = $upload_dir . $ref . "/";
mkdir($app_dir, 0755, true);

$allowed_pdf = ["application/pdf"];
$allowed_img = ["image/jpeg", "image/png"];
$max_pdf_bytes = 5 * 1024 * 1024;
$max_img_bytes = 2 * 1024 * 1024;

function save_upload(
    array $file,
    string $dest_dir,
    string $dest_name,
    array $allowed_mimes,
    int $max_bytes,
): array {
    if (!isset($file["tmp_name"]) || $file["error"] !== UPLOAD_ERR_OK) {
        return [
            "ok" => false,
            "path" => "",
            "error" => "Upload error or missing file.",
        ];
    }
    if ($file["size"] > $max_bytes) {
        return ["ok" => false, "path" => "", "error" => "File too large."];
    }
    $mime = mime_content_type($file["tmp_name"]);
    if (!in_array($mime, $allowed_mimes, true)) {
        return [
            "ok" => false,
            "path" => "",
            "error" => "Invalid file type (" . $mime . ").",
        ];
    }
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $filename = $dest_name . "." . strtolower($ext);
    $dest = $dest_dir . $filename;
    if (!move_uploaded_file($file["tmp_name"], $dest)) {
        return ["ok" => false, "path" => "", "error" => "Could not save file."];
    }
    return [
        "ok" => true,
        "path" => $dest,
        "filename" => $filename,
        "error" => "",
    ];
}

$upload_id = save_upload(
    $_FILES["doc_id"] ?? [],
    $app_dir,
    "national_id",
    $allowed_pdf,
    $max_pdf_bytes,
);
$upload_kra = save_upload(
    $_FILES["doc_kra"] ?? [],
    $app_dir,
    "kra_pin",
    $allowed_pdf,
    $max_pdf_bytes,
);
$upload_photo = save_upload(
    $_FILES["doc_photo"] ?? [],
    $app_dir,
    "passport_photo",
    $allowed_img,
    $max_img_bytes,
);

$upload_errors = [];
if (!$upload_id["ok"]) {
    $upload_errors[] = "National ID: " . $upload_id["error"];
}
if (!$upload_kra["ok"]) {
    $upload_errors[] = "KRA PIN: " . $upload_kra["error"];
}
if (!$upload_photo["ok"]) {
    $upload_errors[] = "Photo: " . $upload_photo["error"];
}

if (!empty($upload_errors)) {
    json_exit(
        ["status" => "error", "message" => implode(" | ", $upload_errors)],
        400,
    );
}

/* ── Persist full application as JSON ────────────────────────── */
$app_data = [
    "ref" => $ref,
    "dateadded" => $dateadded,
    "member" => compact(
        "fullname",
        "idno",
        "dob",
        "gender",
        "mobile",
        "email",
        "kra_pin",
        "marital_status",
        "residence",
        "postal_address",
        "postal_code",
        "town",
    ),
    "employment" => compact(
        "employer",
        "emp_no",
        "designation",
        "emp_terms",
        "campus",
    ),
    "bank" => compact("bank_name", "bank_account", "bank_branch"),
    "kin" => compact("kin_name", "kin_relationship", "kin_mobile"),
    "beneficiaries" => $beneficiaries,
    "remittances" => [
        "payment_methods" => $payment_methods,
        "payroll_no" => $payroll_no,
        "capital_shares" => $capital_shares,
        "savings_total" => $savings_total,
        "dep_deposits" => $dep_deposits,
        "dep_christmas" => $dep_christmas,
        "dep_holiday" => $dep_holiday,
        "dep_toto" => $dep_toto,
        "total_monthly" => $total_monthly,
    ],
    "consent" => [
        "website" => $consent_0,
        "social_media" => $consent_1,
        "brochures" => $consent_2,
    ],
    "newsletter" => $newsletter,
    "files" => [
        "id" => $upload_id["filename"] ?? "",
        "kra" => $upload_kra["filename"] ?? "",
        "photo" => $upload_photo["filename"] ?? "",
    ],
];
file_put_contents(
    $app_dir . "application.json",
    json_encode($app_data, JSON_PRETTY_PRINT),
);

/* ── Save basic record to tbl_membership ─────────────────────── */
/* Split fullname into surname + othername for existing schema */
$name_parts = explode(" ", $fullname, 2);
$db_surname = strtoupper($name_parts[0] ?? $fullname);
$db_othername = strtoupper($name_parts[1] ?? "");

$stmt = $dbc->prepare(
    "INSERT INTO tbl_membership
        (surname, othername, IDno, email, mobile, postalAdd, residence, career, employer, dateadded)
     VALUES
        (:surname,:oname,:idno,:email,:mobile,:postal,:resi,:career,:employer,:added)",
);
$stmt->execute([
    ":surname" => $db_surname,
    ":oname" => $db_othername,
    ":idno" => $idno,
    ":email" => $email,
    ":mobile" => $mobile,
    ":postal" => $postal_address,
    ":resi" => $residence,
    ":career" => $emp_terms,
    ":employer" => $employer,
    ":added" => $dateadded,
]);

/* ── Build print URL ─────────────────────────────────────────── */
$protocol =
    !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$print_url =
    $protocol . "://" . $host . "/print-application.php?ref=" . urlencode($ref);

/* ── Email builder ───────────────────────────────────────────── */
function send_mail(
    string $to,
    string $to_name,
    string $subject,
    string $body,
    array $attachments = [],
): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->IsSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = MAIL_AUTH;
        $mail->Port = MAIL_PORT;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SetFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->AddAddress($to, $to_name);
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        foreach ($attachments as $att) {
            if (file_exists($att["path"])) {
                $mail->AddAttachment($att["path"], $att["name"]);
            }
        }
        $mail->Send();
        return true;
    } catch (Exception $e) {
        error_log("Mail error to " . $to . ": " . $e->getMessage());
        return false;
    }
}

/* ── Email 1: Confirmation to applicant ──────────────────────── */
$confirm_body =
    '
<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
  <div style="background:#0d4a2e;padding:24px;text-align:center;">
    <h1 style="color:#22c55e;margin:0;font-size:20px;">BRAEMEG SACCO</h1>
    <p style="color:rgba(255,255,255,0.7);margin:6px 0 0;font-size:13px;">Membership Application Received</p>
  </div>
  <div style="padding:28px;background:#f8faf8;border:1px solid #d1e8d8;">
    <p style="font-size:16px;color:#1c2e1e;">Dear <strong>' .
    htmlspecialchars($fullname) .
    '</strong>,</p>
    <p>Thank you for applying to join Braemeg SACCO. Your application has been received and will be reviewed by our Secretariat within <strong>5 working days</strong>.</p>

    <div style="background:#fff;border:1px solid #d1e8d8;border-radius:8px;padding:16px;margin:20px 0;">
      <p style="margin:0 0 12px;font-weight:bold;color:#0d4a2e;">Application Reference</p>
      <p style="font-size:22px;font-weight:bold;color:#1a7a4a;letter-spacing:2px;margin:0;">' .
    $ref .
    '</p>
    </div>

    <p><strong>Your Application Summary:</strong></p>
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
      <tr style="background:#dcfce7;"><td style="padding:8px;font-weight:bold;">Full Name</td><td style="padding:8px;">' .
    htmlspecialchars($fullname) .
    '</td></tr>
      <tr><td style="padding:8px;font-weight:bold;">ID/Alien Card No.</td><td style="padding:8px;">' .
    htmlspecialchars($idno) .
    '</td></tr>
      <tr style="background:#dcfce7;"><td style="padding:8px;font-weight:bold;">Mobile</td><td style="padding:8px;">' .
    htmlspecialchars($mobile) .
    '</td></tr>
      <tr><td style="padding:8px;font-weight:bold;">Email</td><td style="padding:8px;">' .
    htmlspecialchars($email) .
    '</td></tr>
      <tr style="background:#dcfce7;"><td style="padding:8px;font-weight:bold;">Employer</td><td style="padding:8px;">' .
    htmlspecialchars($employer) .
    '</td></tr>
      <tr><td style="padding:8px;font-weight:bold;">Date Applied</td><td style="padding:8px;">' .
    $dateadded .
    '</td></tr>
    </table>

    <div style="margin:24px 0;text-align:center;">
      <a href="' .
    $print_url .
    '" style="background:#1a7a4a;color:#fff;padding:12px 28px;border-radius:100px;text-decoration:none;font-weight:bold;display:inline-block;">
        Download Your Filled Application Form
      </a>
    </div>

    <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:14px;margin:20px 0;">
      <p style="margin:0 0 8px;font-weight:bold;color:#92400e;">Next Steps</p>
      <ol style="margin:0;padding-left:20px;color:#92400e;font-size:13px;">
        <li>Pay the entrance fee of <strong>KES 1,000</strong> via M-Pesa Paybill 400200, Account 01120000540400</li>
        <li>Forward the M-Pesa SMS to <strong>0724053548</strong></li>
        <li>Print, sign and submit your downloaded application form to a Braemeg representative</li>
        <li>Look out for a second email to confirm your newsletter subscription</li>
      </ol>
    </div>

    <p style="font-size:13px;color:#5a7060;">If you have any questions, contact us at <a href="mailto:info@braemegsacco.co.ke">info@braemegsacco.co.ke</a> or call +254 724 053 548.</p>
  </div>
  <div style="background:#0d4a2e;padding:14px;text-align:center;">
    <p style="color:rgba(255,255,255,0.5);font-size:12px;margin:0;">Braemeg SACCO Society Limited &nbsp;|&nbsp; Polla House, Gitanga Road, Nairobi</p>
  </div>
</div>';

send_mail(
    $email,
    $fullname,
    "Braemeg SACCO — Membership Application Received [" . $ref . "]",
    $confirm_body,
);

/* ── Email 2: Newsletter confirmation to applicant ───────────── */
$newsletter_body =
    '
<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
  <div style="background:#0d4a2e;padding:24px;text-align:center;">
    <h1 style="color:#22c55e;margin:0;font-size:20px;">BRAEMEG SACCO</h1>
    <p style="color:rgba(255,255,255,0.7);margin:6px 0 0;font-size:13px;">Email Communications — Subscription Confirmation</p>
  </div>
  <div style="padding:28px;background:#f8faf8;border:1px solid #d1e8d8;">
    <p style="font-size:16px;color:#1c2e1e;">Dear <strong>' .
    htmlspecialchars($fullname) .
    '</strong>,</p>
    <p>You have indicated that you wish to receive email communications, newsletters and important notices from Braemeg SACCO.</p>
    <p>You will receive updates on:</p>
    <ul style="color:#1c2e1e;font-size:14px;">
      <li>New products and services</li>
      <li>AGM notices and meeting minutes</li>
      <li>Dividend announcements</li>
      <li>Financial tips and member benefits</li>
      <li>SACCO news and community updates</li>
    </ul>
    <p style="background:#dcfce7;border:1px solid #22c55e;border-radius:8px;padding:14px;">
      Your email address <strong>' .
    htmlspecialchars($email) .
    '</strong> has been added to our mailing list.
    </p>
    <p style="font-size:13px;color:#5a7060;">You can unsubscribe at any time by contacting us at <a href="mailto:info@braemegsacco.co.ke">info@braemegsacco.co.ke</a>.</p>
  </div>
  <div style="background:#0d4a2e;padding:14px;text-align:center;">
    <p style="color:rgba(255,255,255,0.5);font-size:12px;margin:0;">Braemeg SACCO Society Limited &nbsp;|&nbsp; Akiba Yangu, Maisha Yangu</p>
  </div>
</div>';

send_mail(
    $email,
    $fullname,
    'Braemeg SACCO — You\'re on our mailing list',
    $newsletter_body,
);

/* ── Email 3: Full application to SACCO secretariat ─────────── */
$ben_rows = "";
foreach ($beneficiaries as $b) {
    $ben_rows .=
        '<tr>
      <td style="padding:6px;border:1px solid #ddd;">' .
        htmlspecialchars($b["name"]) .
        '</td>
      <td style="padding:6px;border:1px solid #ddd;">' .
        htmlspecialchars($b["relationship"]) .
        '</td>
      <td style="padding:6px;border:1px solid #ddd;">' .
        $b["allocation"] .
        '%</td>
      <td style="padding:6px;border:1px solid #ddd;">' .
        htmlspecialchars($b["idno"]) .
        '</td>
      <td style="padding:6px;border:1px solid #ddd;">' .
        htmlspecialchars($b["mobile"]) .
        '</td>
    </tr>';
}

$sacco_body =
    '
<div style="font-family:Arial,sans-serif;font-size:13px;">
  <h2 style="color:#0d4a2e;">New Membership Application — ' .
    $ref .
    '</h2>
  <p>Date: ' .
    $dateadded .
    '</p>

  <h3>Member Details</h3>
  <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
    <tr><td><b>Full Name</b></td><td>' .
    htmlspecialchars($fullname) .
    "</td><td><b>ID/Alien Card No.</b></td><td>" .
    htmlspecialchars($idno) .
    '</td></tr>
    <tr><td><b>Date of Birth</b></td><td>' .
    htmlspecialchars($dob) .
    "</td><td><b>Gender</b></td><td>" .
    htmlspecialchars($gender) .
    '</td></tr>
    <tr><td><b>Mobile</b></td><td>' .
    htmlspecialchars($mobile) .
    "</td><td><b>Email</b></td><td>" .
    htmlspecialchars($email) .
    '</td></tr>
    <tr><td><b>KRA PIN</b></td><td>' .
    htmlspecialchars($kra_pin) .
    "</td><td><b>Marital Status</b></td><td>" .
    htmlspecialchars($marital_status) .
    '</td></tr>
    <tr><td><b>Residence</b></td><td>' .
    htmlspecialchars($residence) .
    "</td><td><b>Postal Address</b></td><td>" .
    htmlspecialchars($postal_address) .
    ", " .
    htmlspecialchars($postal_code) .
    ", " .
    htmlspecialchars($town) .
    '</td></tr>
  </table>

  <h3>Employment Details</h3>
  <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
    <tr><td><b>Employer</b></td><td>' .
    htmlspecialchars($employer) .
    "</td><td><b>Employment No.</b></td><td>" .
    htmlspecialchars($emp_no) .
    '</td></tr>
    <tr><td><b>Designation</b></td><td>' .
    htmlspecialchars($designation) .
    "</td><td><b>Terms</b></td><td>" .
    htmlspecialchars($emp_terms) .
    '</td></tr>
    <tr><td><b>Campus</b></td><td colspan="3">' .
    htmlspecialchars($campus) .
    '</td></tr>
  </table>

  <h3>Bank Details</h3>
  <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
    <tr><td><b>Bank</b></td><td>' .
    htmlspecialchars($bank_name) .
    "</td><td><b>Account No.</b></td><td>" .
    htmlspecialchars($bank_account) .
    "</td><td><b>Branch</b></td><td>" .
    htmlspecialchars($bank_branch) .
    '</td></tr>
  </table>

  <h3>Next of Kin</h3>
  <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
    <tr><td><b>Name</b></td><td>' .
    htmlspecialchars($kin_name) .
    "</td><td><b>Relationship</b></td><td>" .
    htmlspecialchars($kin_relationship) .
    "</td><td><b>Mobile</b></td><td>" .
    htmlspecialchars($kin_mobile) .
    '</td></tr>
  </table>

  ' .
    (!empty($ben_rows)
        ? '<h3>Beneficiaries</h3>
  <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
    <tr style="background:#dcfce7;"><th>Name</th><th>Relationship</th><th>Allocation</th><th>ID No.</th><th>Mobile</th></tr>
    ' .
            $ben_rows .
            '
  </table>'
        : "") .
    '

  <h3>Remittances</h3>
  <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
    <tr><td><b>Payment Methods</b></td><td>' .
    htmlspecialchars(implode(", ", $payment_methods)) .
    '</td></tr>
    <tr><td><b>Capital Shares (monthly)</b></td><td>KES ' .
    htmlspecialchars($capital_shares) .
    '</td></tr>
    <tr><td><b>Deposits</b></td><td>KES ' .
    htmlspecialchars($dep_deposits) .
    '</td></tr>
    <tr><td><b>Christmas Savings</b></td><td>KES ' .
    htmlspecialchars($dep_christmas) .
    '</td></tr>
    <tr><td><b>Holiday Savings</b></td><td>KES ' .
    htmlspecialchars($dep_holiday) .
    '</td></tr>
    <tr><td><b>TOTO Savings</b></td><td>KES ' .
    htmlspecialchars($dep_toto) .
    '</td></tr>
    <tr><td><b>Total Monthly Contributions</b></td><td><strong>KES ' .
    htmlspecialchars($total_monthly) .
    '</strong></td></tr>
  </table>

  <h3>Documents Attached</h3>
  <ul>
    <li>National ID: ' .
    ($upload_id["filename"] ?? "N/A") .
    '</li>
    <li>KRA PIN: ' .
    ($upload_kra["filename"] ?? "N/A") .
    '</li>
    <li>Passport Photo: ' .
    ($upload_photo["filename"] ?? "N/A") .
    '</li>
  </ul>

  <p><a href="' .
    $print_url .
    '">View Filled Application Form Online</a></p>
</div>';

$attachments = [];
if ($upload_id["ok"]) {
    $attachments[] = [
        "path" => $upload_id["path"],
        "name" => "National_ID_" . $ref . ".pdf",
    ];
}
if ($upload_kra["ok"]) {
    $attachments[] = [
        "path" => $upload_kra["path"],
        "name" => "KRA_PIN_" . $ref . ".pdf",
    ];
}
if ($upload_photo["ok"]) {
    $attachments[] = [
        "path" => $upload_photo["path"],
        "name" =>
            "Passport_Photo_" .
            $ref .
            "." .
            pathinfo($upload_photo["filename"], PATHINFO_EXTENSION),
    ];
}

send_mail(
    "info.braemegsacco@gmail.com",
    "Braemeg SACCO",
    "New Membership Application — " . $ref . " — " . $fullname,
    $sacco_body,
    $attachments,
);

/* BCC copies to other admins */
send_mail(
    "braemegsacco@yahoo.com",
    "Braemeg SACCO (BCC)",
    "New Membership Application — " . $ref,
    $sacco_body,
);

/* ── Return success ──────────────────────────────────────────── */
json_exit([
    "status" => "ok",
    "ref" => $ref,
    "email" => $email,
    "print_url" => $print_url,
]);
