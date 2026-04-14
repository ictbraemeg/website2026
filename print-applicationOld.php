<?php
/**
 * print-application.php
 * Generates a printable, filled membership application form.
 * Accessed via ?ref=YYYYMMDD_XXXXXXXX
 * Renders the exact PDF layout with all data populated.
 */

$ref = preg_replace("/[^A-Z0-9_]/", "", strtoupper($_GET["ref"] ?? ""));
if (!$ref) {
    header("Location: apply.php");
    exit();
}

$data_file = __DIR__ . "/uploads/membership/" . $ref . "/application.json";
if (!file_exists($data_file)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><p>Application not found. Please <a href="apply.php">apply again</a>.</p></body></html>';
    exit();
}

$json = file_get_contents($data_file);
$d = json_decode($json, true); // associative array [web:151]
if (!$d) {
    http_response_code(500);
    exit("Could not read application data.");
}

require_once __DIR__ . "/config/crypto.php"; // provides dec()

$m = $d["member"] ?? [];
$em = $d["employment"] ?? [];
$b = $d["bank"] ?? [];
$k = $d["kin"] ?? [];
$bn = $d["beneficiaries"] ?? [];
$r = $d["remittances"] ?? [];
$c = $d["consent"] ?? [];
$files = $d["files"] ?? [];

/**
 * Safely escape a value from an array.
 */
function v(array $arr, string $key): string
{
    return htmlspecialchars($arr[$key] ?? "", ENT_QUOTES);
}

/**
 * Decrypt helper that is null/empty-safe for template use.
 */
function vd(array $arr, string $key): string
{
    if (!array_key_exists($key, $arr) || $arr[$key] === "") {
        return "";
    }
    return htmlspecialchars(dec($arr[$key]), ENT_QUOTES);
}

/* Decrypt member fields that were stored encrypted in application.json */
if (isset($m["idno"])) {
    $m["idno"] = dec($m["idno"]);
}
if (isset($m["mobile"])) {
    $m["mobile"] = dec($m["mobile"]);
}
if (isset($m["email"])) {
    $m["email"] = dec($m["email"]);
}
if (isset($m["kra_pin"])) {
    $m["kra_pin"] = dec($m["kra_pin"]);
}

/* Decrypt kin mobile if encrypted */
if (isset($k["kin_mobile"]) && $k["kin_mobile"] !== "") {
    $k["kin_mobile"] = dec($k["kin_mobile"]);
}

/* Decrypt beneficiaries ID and mobile if encrypted */
foreach ($bn as $i => $ben) {
    if (isset($ben["idno"]) && $ben["idno"] !== "") {
        $bn[$i]["idno"] = dec($ben["idno"]);
    }
    if (isset($ben["mobile"]) && $ben["mobile"] !== "") {
        $bn[$i]["mobile"] = dec($ben["mobile"]);
    }
}

/* Photo path */
$photo_file = $files["photo"] ?? "";
$photo_path = "/uploads/membership/" . $ref . "/" . $photo_file;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Membership Application — <?php echo htmlspecialchars(
    $d["ref"] ?? "",
); ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'DM Sans', Arial, sans-serif;
    font-size: 11px;
    color: #1a1a1a;
    background: #f0f0f0;
    padding: 20px;
  }

  .page {
    background: #fff;
    max-width: 210mm;
    margin: 0 auto 20px;
    padding: 20mm 18mm;
    box-shadow: 0 4px 24px rgba(0,0,0,0.12);
    position: relative;
  }

  /* Header */
  .pg-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3d6b3d;
  }
  .pg-header__logo img { height: 60px; }
  .pg-header__logo-fallback {
    font-weight: 700; font-size: 13px;
    color: #3d6b3d; line-height: 1.4;
  }
  .pg-header__address {
    text-align: right; font-size: 10px;
    color: #444; line-height: 1.7;
  }

  .pg-watermark {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-35deg);
    font-size: 60px; font-weight: 900;
    color: rgba(61,107,61,0.04);
    white-space: nowrap; pointer-events: none;
    z-index: 0; user-select: none;
  }

  .pg-content { position: relative; z-index: 1; }

  .pg-main-title {
    text-align: center;
    font-size: 15px; font-weight: 700;
    text-decoration: underline;
    margin: 10px 0 6px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
  }

  .pg-tagline {
    text-align: center;
    font-size: 10px; color: #3d6b3d;
    font-style: italic; margin-bottom: 14px;
  }

  /* Sections */
  .pf-section { margin-bottom: 12px; }

  .pf-section__head {
    background: #3d6b3d;
    color: #fff;
    font-weight: 700; font-size: 10.5px;
    padding: 5px 8px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0;
  }

  .pf-section__body {
    border: 1px solid #3d6b3d;
    border-top: none;
    padding: 8px;
  }

  .pf-row {
    display: grid;
    gap: 0;
    border-bottom: 1px solid #d0d0d0;
    margin-bottom: 4px;
    padding-bottom: 4px;
  }
  .pf-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
  .pf-row--2 { grid-template-columns: 1fr 1fr; }
  .pf-row--3 { grid-template-columns: 1fr 1fr 1fr; }
  .pf-row--full { grid-template-columns: 1fr; }

  .pf-field { padding: 2px 4px; }
  .pf-field__label {
    font-size: 9px; color: #555;
    text-transform: uppercase; letter-spacing: 0.04em;
    margin-bottom: 2px;
  }
  .pf-field__value {
    font-size: 11px; font-weight: 600;
    color: #111;
    min-height: 14px;
    border-bottom: 1px solid #aaa;
    padding-bottom: 1px;
  }
  .pf-field__value:empty::after { content: ' '; }

  /* Beneficiary table */
  .pf-table { width: 100%; border-collapse: collapse; font-size: 10px; }
  .pf-table th {
    background: #e8f5e8; color: #3d6b3d;
    padding: 4px 5px; text-align: left;
    font-size: 9px; text-transform: uppercase;
    border: 1px solid #a0c8a0;
  }
  .pf-table td {
    padding: 4px 5px;
    border: 1px solid #ccc;
    color: #111; font-weight: 500;
  }
  .pf-table tr:nth-child(even) td { background: #f9fdf9; }

  /* Consent table */
  .pf-consent-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 6px; }
  .pf-consent-table th {
    background: #e8f5e8; color: #3d6b3d;
    padding: 4px 8px; border: 1px solid #a0c8a0;
    font-size: 9px;
  }
  .pf-consent-table td { padding: 4px 8px; border: 1px solid #ccc; }
  .pf-consent-table td.yes { color: #166534; font-weight: 700; }
  .pf-consent-table td.no  { color: #991b1b; font-weight: 700; }

  /* Payment methods */
  .pf-checkboxes { display: flex; flex-wrap: wrap; gap: 12px; margin: 4px 0; }
  .pf-cb {
    display: flex; align-items: center; gap: 4px;
    font-size: 10.5px;
  }
  .pf-cb__box {
    width: 12px; height: 12px;
    border: 1.5px solid #3d6b3d;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 9px; color: #3d6b3d; font-weight: 900;
  }

  /* Declaration */
  .pf-declaration {
    border: 1.5px solid #3d6b3d;
    border-radius: 4px;
    padding: 10px;
    font-style: italic;
    font-size: 11px;
    background: #f9fdf9;
    margin-bottom: 12px;
  }
  .pf-signature-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 8px;
  }
  .pf-signature-line {
    border-bottom: 1px solid #333;
    height: 24px;
  }
  .pf-signature-label { font-size: 9px; color: #555; margin-top: 2px; }

  /* Official use */
  .pf-official {
    border: 2px solid #3d6b3d;
    margin-top: 12px;
  }
  .pf-official__head {
    background: #3d6b3d; color: #fff;
    padding: 5px 8px; font-weight: 700;
    font-size: 10px; text-transform: uppercase;
  }
  .pf-official__body { padding: 8px; }

  /* Photo box */
  .pf-photo-box {
    width: 90px; height: 110px;
    border: 1.5px solid #3d6b3d;
    display: flex; align-items: center; justify-content: center;
    float: right; margin: 0 0 8px 12px;
    overflow: hidden; background: #f0f5f0;
  }
  .pf-photo-box img { width: 100%; height: 100%; object-fit: cover; }
  .pf-photo-box__placeholder { font-size: 9px; color: #888; text-align: center; padding: 8px; }

  /* Print toolbar */
  .print-toolbar {
    max-width: 210mm;
    margin: 0 auto 14px;
    display: flex; gap: 10px; align-items: center;
    flex-wrap: wrap;
  }
  .print-toolbar__btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 100px;
    font-family: inherit; font-size: 13px; font-weight: 600;
    cursor: pointer; border: none; text-decoration: none;
  }
  .print-toolbar__btn--primary { background: #1a7a4a; color: #fff; }
  .print-toolbar__btn--secondary { background: #fff; color: #1a7a4a; border: 1.5px solid #1a7a4a; }
  .print-toolbar__ref { margin-left: auto; font-size: 12px; color: #666; }

  /* Notes */
  .pf-note { font-size: 9.5px; color: #555; font-style: italic; margin-top: 5px; }

  .pf-savings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }

  @media print {
    body { background: #fff; padding: 0; }
    .print-toolbar { display: none; }
    .page { box-shadow: none; margin: 0; padding: 15mm 14mm; max-width: 100%; }
    .page + .page { page-break-before: always; }
  }
</style>
</head>
<body>

<!-- Print toolbar (hidden when printing) -->
<div class="print-toolbar">
    <a href="javascript:window.print()" class="print-toolbar__btn print-toolbar__btn--primary">
        🖨 Print / Save as PDF
    </a>
    <a href="apply.php" class="print-toolbar__btn print-toolbar__btn--secondary">
        ← Back to Apply
    </a>
    <span class="print-toolbar__ref">Ref: <?php echo htmlspecialchars(
        $d["ref"],
    ); ?> &nbsp;|&nbsp; <?php echo htmlspecialchars(
     $d["dateadded"] ?? "",
 ); ?></span>
</div>

<!-- ══ PAGE 1 ══════════════════════════════════════════════════ -->
<div class="page">
<div class="pg-watermark">BRAEMEG SACCO</div>
<div class="pg-content">

<div class="pg-header">
    <div class="pg-header__logo">
        <img src="images/logo.png" alt="Braemeg SACCO" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <div class="pg-header__logo-fallback" style="display:none">BRAEMEG SACCO<br>Society Limited</div>
    </div>
    <div class="pg-header__address">
        Polla House Building, 4th Floor,<br>
        Along Gitanga Road, Kawangware.<br>
        P.O Box 45112 – 00100, Nairobi.<br>
        +254 724 053 548<br>
        info@braemegsacco.co.ke &nbsp;|&nbsp; www.braemegsacco.co.ke
    </div>
</div>

<h2 class="pg-main-title">Membership Application Form</h2>
<p class="pg-tagline">Akiba Yangu, Maisha Yangu</p>

<?php if ($photo_file): ?>
<div class="pf-photo-box">
    <img src="<?php echo htmlspecialchars(
        $photo_path,
    ); ?>" alt="Passport photo">
</div>
<?php else: ?>
<div class="pf-photo-box">
    <div class="pf-photo-box__placeholder">Passport<br>Photo</div>
</div>
<?php endif; ?>

<!-- MEMBER DETAILS -->
<div class="pf-section">
    <div class="pf-section__head">Member Details</div>
    <div class="pf-section__body">
        <div class="pf-row pf-row--full">
            <div class="pf-field">
                <div class="pf-field__label">Full Name (As Per ID/Alien Card)</div>
                <div class="pf-field__value"><?php echo v(
                    $m,
                    "fullname",
                ); ?></div>
            </div>
        </div>
        <div class="pf-row pf-row--3">
            <div class="pf-field"><div class="pf-field__label">ID / Alien Card No.</div><div class="pf-field__value"><?php echo v(
                $m,
                "idno",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Date of Birth</div><div class="pf-field__value"><?php echo v(
                $m,
                "dob",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Gender</div><div class="pf-field__value"><?php echo v(
                $m,
                "gender",
            ); ?></div></div>
        </div>
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Mobile No.</div><div class="pf-field__value"><?php echo v(
                $m,
                "mobile",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Email</div><div class="pf-field__value"><?php echo v(
                $m,
                "email",
            ); ?></div></div>
        </div>
        <div class="pf-row pf-row--3">
            <div class="pf-field"><div class="pf-field__label">KRA PIN</div><div class="pf-field__value"><?php echo v(
                $m,
                "kra_pin",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Marital Status</div><div class="pf-field__value"><?php echo v(
                $m,
                "marital_status",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Residence</div><div class="pf-field__value"><?php echo v(
                $m,
                "residence",
            ); ?></div></div>
        </div>
        <div class="pf-row pf-row--3">
            <div class="pf-field"><div class="pf-field__label">Postal Address</div><div class="pf-field__value"><?php echo v(
                $m,
                "postal_address",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Postal Code</div><div class="pf-field__value"><?php echo v(
                $m,
                "postal_code",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Town</div><div class="pf-field__value"><?php echo v(
                $m,
                "town",
            ); ?></div></div>
        </div>
    </div>
</div>

<!-- EMPLOYMENT DETAILS -->
<div class="pf-section">
    <div class="pf-section__head">Employment Details</div>
    <div class="pf-section__body">
        <div class="pf-row pf-row--full"><div class="pf-field"><div class="pf-field__label">Name of Employer</div><div class="pf-field__value"><?php echo v(
            $em,
            "employer",
        ); ?></div></div></div>
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Employment No.</div><div class="pf-field__value"><?php echo v(
                $em,
                "emp_no",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Designation</div><div class="pf-field__value"><?php echo v(
                $em,
                "designation",
            ); ?></div></div>
        </div>
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Employment Terms</div><div class="pf-field__value"><?php echo v(
                $em,
                "emp_terms",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Campus (if applicable)</div><div class="pf-field__value"><?php echo v(
                $em,
                "campus",
            ); ?></div></div>
        </div>
    </div>
</div>

<!-- BANK DETAILS -->
<div class="pf-section">
    <div class="pf-section__head">Bank Details</div>
    <div class="pf-section__body">
        <div class="pf-row pf-row--full"><div class="pf-field"><div class="pf-field__label">Name of Bank</div><div class="pf-field__value"><?php echo v(
            $b,
            "bank_name",
        ); ?></div></div></div>
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Account No.</div><div class="pf-field__value"><?php echo v(
                $b,
                "bank_account",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Branch</div><div class="pf-field__value"><?php echo v(
                $b,
                "bank_branch",
            ); ?></div></div>
        </div>
    </div>
</div>

</div><!-- /pg-content -->
</div><!-- /page 1 -->


<!-- ══ PAGE 2 ══════════════════════════════════════════════════ -->
<div class="page">
<div class="pg-watermark">BRAEMEG SACCO</div>
<div class="pg-content">

<div class="pg-header">
    <div class="pg-header__logo">
        <img src="images/logo.png" alt="Braemeg SACCO" onerror="this.style.display='none'">
    </div>
    <div class="pg-header__address">Polla House Building, 4th Floor, Gitanga Road &nbsp;|&nbsp; +254 724 053 548</div>
</div>

<!-- NEXT OF KIN -->
<div class="pf-section">
    <div class="pf-section__head">Next of Kin <small style="font-weight:400;text-transform:none;">(Person to be contacted in case of emergency)</small></div>
    <div class="pf-section__body">
        <div class="pf-row pf-row--full"><div class="pf-field"><div class="pf-field__label">Full Name</div><div class="pf-field__value"><?php echo v(
            $k,
            "kin_name",
        ); ?></div></div></div>
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Relationship</div><div class="pf-field__value"><?php echo v(
                $k,
                "kin_relationship",
            ); ?></div></div>
            <div class="pf-field"><div class="pf-field__label">Mobile No.</div><div class="pf-field__value"><?php echo v(
                $k,
                "kin_mobile",
            ); ?></div></div>
        </div>
    </div>
</div>

<!-- BENEFICIARY -->
<div class="pf-section">
    <div class="pf-section__head">Beneficiary <small style="font-weight:400;text-transform:none;">(Person(s) to receive funds in the event of loss of life)</small></div>
    <div class="pf-section__body">
        <table class="pf-table">
            <thead>
                <tr>
                    <th>Name</th><th>Relationship</th><th>% Allocation</th><th>ID No.</th><th>Mobile No.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $shown = 0;
                foreach ($bn as $ben) {
                    echo '<tr>
                        <td>' .
                        htmlspecialchars($ben["name"] ?? "", ENT_QUOTES) .
                        '</td>
                        <td>' .
                        htmlspecialchars(
                            $ben["relationship"] ?? "",
                            ENT_QUOTES,
                        ) .
                        '</td>
                        <td>' .
                        htmlspecialchars($ben["allocation"] ?? "", ENT_QUOTES) .
                        '%</td>
                        <td>' .
                        htmlspecialchars($ben["idno"] ?? "", ENT_QUOTES) .
                        '</td>
                        <td>' .
                        htmlspecialchars($ben["mobile"] ?? "", ENT_QUOTES) .
                        '</td>
                    </tr>';
                    $shown++;
                }
                while ($shown < 4) {
                    echo "<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>";
                    $shown++;
                }
                ?>
            </tbody>
        </table>
        <p class="pf-note"><strong>Note:</strong> For minors, ensure you indicate a guardian(s) with their mobile and ID/Alien card numbers.</p>
    </div>
</div>

</div></div><!-- /page 2 -->


<!-- ══ PAGE 3 ══════════════════════════════════════════════════ -->
<div class="page">
<div class="pg-watermark">BRAEMEG SACCO</div>
<div class="pg-content">

<div class="pg-header">
    <div class="pg-header__logo"><img src="images/logo.png" alt="" onerror="this.style.display='none'"></div>
    <div class="pg-header__address">Polla House Building, 4th Floor, Gitanga Road &nbsp;|&nbsp; +254 724 053 548</div>
</div>

<!-- REMITTANCES -->
<div class="pf-section">
    <div class="pf-section__head">Remittances to the Society</div>
    <div class="pf-section__body">
        <p style="font-weight:600;margin-bottom:8px;font-size:10.5px;">This is to confirm that my monthly contribution shall be done through:</p>
        <div class="pf-checkboxes">
            <?php
            $methods = $r["payment_methods"] ?? [];
            $all_methods = [
                "Employee Check-off",
                "Standing Order",
                "Mobile Money Deposit",
                "Direct Bank Deposit",
            ];
            foreach ($all_methods as $pm):
                $checked = in_array($pm, $methods, true); ?>
            <div class="pf-cb">
                <div class="pf-cb__box"><?php echo $checked ? "✓" : ""; ?></div>
                <span><?php echo htmlspecialchars($pm, ENT_QUOTES); ?></span>
            </div>
            <?php
            endforeach;
            ?>
        </div>
        <?php if (!empty($r["payroll_no"])): ?>
        <div class="pf-row pf-row--2" style="margin-top:6px;">
            <div class="pf-field"><div class="pf-field__label">Payroll No. (Braeburn Staff)</div><div class="pf-field__value"><?php echo v(
                $r,
                "payroll_no",
            ); ?></div></div>
        </div>
        <?php endif; ?>

        <div style="margin-top:10px;">
            <p style="font-weight:700;font-size:10.5px;text-decoration:underline;margin-bottom:4px;">Capital Shares (KES 10,000 Total)</p>
            <div class="pf-row pf-row--2">
                <div class="pf-field">
                    <div class="pf-field__label">Monthly Contribution (KES)</div>
                    <div class="pf-field__value"><?php echo v(
                        $r,
                        "capital_shares",
                    ); ?></div>
                </div>
            </div>
            <p class="pf-note">Note: This monthly contribution will stop once it sums up to KES 10,000.</p>
        </div>

        <div style="margin-top:10px;">
            <p style="font-weight:700;font-size:10.5px;text-decoration:underline;margin-bottom:4px;">Deposits &amp; Savings Products</p>
            <div class="pf-row pf-row--2">
                <div class="pf-field"><div class="pf-field__label">Total Monthly Savings (KES)</div><div class="pf-field__value"><?php echo v(
                    $r,
                    "savings_total",
                ); ?></div></div>
            </div>
            <div class="pf-savings-grid">
                <div class="pf-field"><div class="pf-field__label">• Deposits</div><div class="pf-field__value"><?php echo v(
                    $r,
                    "dep_deposits",
                ); ?></div></div>
                <div class="pf-field"><div class="pf-field__label">• Christmas Savings</div><div class="pf-field__value"><?php echo v(
                    $r,
                    "dep_christmas",
                ); ?></div></div>
                <div class="pf-field"><div class="pf-field__label">• Holiday Savings</div><div class="pf-field__value"><?php echo v(
                    $r,
                    "dep_holiday",
                ); ?></div></div>
                <div class="pf-field"><div class="pf-field__label">• TOTO Savings</div><div class="pf-field__value"><?php echo v(
                    $r,
                    "dep_toto",
                ); ?></div></div>
            </div>
        </div>

        <div style="margin-top:8px;border-top:1.5px solid #3d6b3d;padding-top:6px;">
            <div class="pf-row pf-row--2">
                <div class="pf-field">
                    <div class="pf-field__label">Total Monthly Contributions (Deposits + Capital Shares)</div>
                    <div class="pf-field__value" style="font-size:13px;font-weight:700;"><?php echo v(
                        $r,
                        "total_monthly",
                    ); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DECLARATION -->
<div class="pf-section">
    <div class="pf-section__head">Declaration</div>
    <div class="pf-section__body">
        <div class="pf-declaration">
            In making this membership application, I do hereby agree to conform to the society's By-laws and any amendments thereof.
        </div>
        <div class="pf-signature-row">
            <div>
                <div class="pf-signature-line"></div>
                <div class="pf-signature-label">Signature</div>
            </div>
            <div>
                <div class="pf-signature-line"></div>
                <div class="pf-signature-label">Date</div>
            </div>
        </div>
    </div>
</div>

</div></div><!-- /page 3 -->


<!-- ══ PAGE 4 ══════════════════════════════════════════════════ -->
<div class="page">
<div class="pg-watermark">BRAEMEG SACCO</div>
<div class="pg-content">

<div class="pg-header">
    <div class="pg-header__logo"><img src="images/logo.png" alt="" onerror="this.style.display='none'"></div>
    <div class="pg-header__address">Polla House Building, 4th Floor, Gitanga Road &nbsp;|&nbsp; +254 724 053 548</div>
</div>

<!-- PHOTO & VIDEO CONSENT -->
<div class="pf-section">
    <div class="pf-section__head">Photo &amp; Video Consent</div>
    <div class="pf-section__body">
        <p style="font-size:10.5px;margin-bottom:8px;">Braemeg NWDT Sacco may take my still or moving images during events, general meetings or functions, and use them appropriately and with due diligence in its platforms listed below.</p>
        <table class="pf-consent-table">
            <thead>
                <tr><th>Platform</th><th>I Consent (Sign)</th><th>I Do Not Consent (Sign)</th></tr>
            </thead>
            <tbody>
                <?php
                $consent_items = [
                    ["label" => "Website", "val" => $c["website"] ?? ""],
                    [
                        "label" => "Social Media",
                        "val" => $c["social_media"] ?? "",
                    ],
                    [
                        "label" => "Brochures, Pamphlets & Handbooks",
                        "val" => $c["brochures"] ?? "",
                    ],
                ];
                foreach ($consent_items as $ci): ?>
                <tr>
                    <td><?php echo htmlspecialchars(
                        $ci["label"],
                        ENT_QUOTES,
                    ); ?></td>
                    <td class="<?php echo $ci["val"] === "yes"
                        ? "yes"
                        : ""; ?>"><?php echo $ci["val"] === "yes"
    ? "✓ Consent"
    : ""; ?></td>
                    <td class="<?php echo $ci["val"] === "no"
                        ? "no"
                        : ""; ?>"><?php echo $ci["val"] === "no"
    ? "✗ Do Not Consent"
    : ""; ?></td>
                </tr>
                <?php endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- IMPORTANT NOTES -->
<div class="pf-section">
    <div class="pf-section__head">Important Notes</div>
    <div class="pf-section__body">
        <ol style="padding-left:18px;font-size:10.5px;line-height:1.7;">
            <li>Entrance fee is <strong>KES 1,000</strong></li>
            <li>Attach a copy of your ID/Passport, KRA Pin, and recent passport size photo.</li>
            <li>Once your application has been verified, you shall receive a confirmation message with your membership no.</li>
            <li>To make payments through M-Pesa paybill, use: <strong>Paybill No. 400200</strong>, <strong>Account No: 01120000540400</strong>. Forward the M-Pesa Message to <strong>0724053548</strong>.</li>
            <li>Talk to us through <strong>0724053548</strong>, or email: <strong>info@braemegsacco.co.ke</strong></li>
            <li>Get more information on our products &amp; services at <strong>www.braemegsacco.co.ke</strong></li>
        </ol>
    </div>
</div>

<!-- FOR OFFICIAL USE ONLY -->
<div class="pf-official">
    <div class="pf-official__head">For Official Use Only</div>
    <div class="pf-official__body">
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Verified and Approved by</div><div class="pf-field__value">&nbsp;</div></div>
            <div class="pf-field"><div class="pf-field__label">Signature</div><div class="pf-field__value">&nbsp;</div></div>
        </div>
        <div class="pf-row pf-row--2">
            <div class="pf-field"><div class="pf-field__label">Date of Admission</div><div class="pf-field__value">&nbsp;</div></div>
            <div class="pf-field"><div class="pf-field__label">Membership No. Issued</div><div class="pf-field__value">&nbsp;</div></div>
        </div>
    </div>
</div>

<p class="pg-tagline" style="margin-top:14px;">Akiba Yangu, Maisha Yangu</p>

</div></div><!-- /page 4 -->

</body>
</html>
