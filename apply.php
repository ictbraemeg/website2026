<?php
/**
 * apply.php — Braemeg SACCO Online Membership Application
 * Mirrors the official 4-page PDF form exactly.
 */

// CSRF Token
session_start();
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

require_once "config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base = "";
$nav_active = "";
$page_title = "Apply for Membership — " . htmlspecialchars($rcs["name"]);

$page_heading = "Membership Application";
$page_sub =
    "Complete this form online. Your filled application will be generated automatically on submission.";
$breadcrumbs = [
    ["label" => "Home", "href" => "index.php"],
    ["label" => "Apply for Membership"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "includes/head.php"; ?>
    <link rel="stylesheet" href="css/apply-form.css">
</head>
<body>

<?php include "includes/topbar.php"; ?>
<?php include "includes/navbar.php"; ?>
<?php include "includes/page-header.php"; ?>

<section class="apply-section">
<div class="container">
<div class="apply-layout">

<!-- ── INFO SIDEBAR ─────────────────────────────────────────── -->
<aside class="apply-sidebar">

    <div class="apply-sidebar__block">
        <span class="section-tag">Eligibility</span>
        <h2 class="apply-sidebar__title">Who Can Join?</h2>
        <ul class="apply-sidebar__list">
            <li>Employees of all international schools in the Braemeg network</li>
            <li>Spouses &amp; children of existing Braemeg SACCO members</li>
            <li>Employees of Braemeg Sacco Limited</li>
        </ul>
    </div>

    <div class="apply-sidebar__block apply-sidebar__block--green">
        <h3 class="apply-sidebar__sub">Required Documents</h3>
        <ul class="apply-sidebar__checklist">
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>National ID or Passport (PDF)</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>KRA PIN Certificate (PDF)</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>Passport-size Photo</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>Completed &amp; signed application form</li>
        </ul>
    </div>

    <div class="apply-sidebar__block apply-sidebar__block--mpesa">
        <h3 class="apply-sidebar__sub">M-Pesa Payment</h3>
        <p>Entrance fee: <strong>KES 1,000</strong></p>
        <div class="mpesa-detail"><span>Paybill</span><strong>400200</strong></div>
        <div class="mpesa-detail"><span>Account No.</span><strong>01120000540400</strong></div>
        <div class="mpesa-detail"><span>Forward M-Pesa SMS to</span><strong>0724053548</strong></div>
    </div>

    <!-- Progress tracker -->
    <div class="apply-sidebar__block">
        <h3 class="apply-sidebar__sub">Form Progress</h3>
        <ul class="apply-progress" id="apply-progress">
            <li class="apply-progress__item" data-section="member">Member Details</li>
            <li class="apply-progress__item" data-section="employment">Employment Details</li>
            <li class="apply-progress__item" data-section="bank">Bank Details</li>
            <li class="apply-progress__item" data-section="kin">Next of Kin</li>
            <li class="apply-progress__item" data-section="beneficiary">Beneficiary</li>
            <li class="apply-progress__item" data-section="remittances">Remittances</li>
            <li class="apply-progress__item" data-section="consent">Consent &amp; Declaration</li>
            <li class="apply-progress__item" data-section="documents">Documents</li>
        </ul>
    </div>

</aside>

<!-- ── MAIN FORM ─────────────────────────────────────────────── -->
<main id="main-content" class="apply-main">

<div class="apply-form-card">
    <div class="apply-form-card__header">
        <div class="apply-form-card__logo-row">
            <img src="images/logo.png" alt="Braemeg SACCO" class="apply-form-card__logo" onerror="this.style.display='none'">
        </div>
        <h2 class="apply-form-card__title">MEMBERSHIP APPLICATION FORM</h2>
    </div>

    <!-- Feedback alerts -->
    <div id="apply-error"    class="apply-alert apply-alert--error"    hidden></div>
    <div id="apply-duplicate" class="apply-alert apply-alert--warning" hidden>
        <strong>Email already registered.</strong> If you are already a member, please use the <a href="https://portal.braemegsacco.co.ke:8085" target="_blank" rel="noopener">member portal</a>.
    </div>

    <form id="apply-form" action="saveapply.php" method="POST" enctype="multipart/form-data" novalidate>

    <input type="hidden"
           name="csrf_token"
           value="<?php echo htmlspecialchars(
               $_SESSION["csrf_token"] ?? "",
               ENT_QUOTES,
           ); ?>">

    <!-- ══════════════════════════════════════════════════════
         SECTION 1: MEMBER DETAILS
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-member">
        <legend class="af-section__legend">Member Details</legend>

        <div class="af-row af-row--full">
            <div class="af-group">
                <label class="af-label" for="fullname">Full Name (As Per ID/Alien Card) *</label>
                <input type="text" id="fullname" name="fullname" class="af-input" required autocomplete="name" placeholder="As it appears on your ID">
            </div>
        </div>

        <div class="af-row af-row--3">
            <div class="af-group">
                <label class="af-label" for="idno">ID / Alien Card No. *</label>
                <input type="text" id="idno" name="idno" class="af-input" required>
            </div>
            <div class="af-group">
                <label class="af-label" for="dob">Date of Birth *</label>
                <input type="date" id="dob" name="dob" class="af-input" required>
            </div>
            <div class="af-group">
                <label class="af-label" for="gender">Gender *</label>
                <select id="gender" name="gender" class="af-input" required>
                    <option value="">Select…</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Prefer not to say</option>
                </select>
            </div>
        </div>

        <div class="af-row af-row--2">
            <div class="af-group">
                <label class="af-label" for="mobile">Mobile No. *</label>
                <input type="tel" id="mobile" name="mobile" class="af-input" required autocomplete="tel" placeholder="+254…">
            </div>
            <div class="af-group">
                <label class="af-label" for="email">Email *</label>
                <input type="email" id="email" name="email" class="af-input" required autocomplete="email" placeholder="you@example.com">
            </div>
        </div>

        <div class="af-row af-row--3">
            <div class="af-group">
                <label class="af-label" for="kra_pin">KRA PIN *</label>
                <input type="text" id="kra_pin" name="kra_pin" class="af-input" required placeholder="A000000000X">
            </div>
            <div class="af-group">
                <label class="af-label" for="marital_status">Marital Status</label>
                <select id="marital_status" name="marital_status" class="af-input">
                    <option value="">Select…</option>
                    <option>Single</option>
                    <option>Married</option>
                    <option>Divorced</option>
                    <option>Widowed</option>
                </select>
            </div>
            <div class="af-group">
                <label class="af-label" for="residence">Residence</label>
                <input type="text" id="residence" name="residence" class="af-input" placeholder="Town / Estate">
            </div>
        </div>

        <div class="af-row af-row--3">
            <div class="af-group">
                <label class="af-label" for="postal_address">Postal Address</label>
                <input type="text" id="postal_address" name="postal_address" class="af-input" placeholder="P.O. Box…">
            </div>
            <div class="af-group">
                <label class="af-label" for="postal_code">Postal Code</label>
                <input type="text" id="postal_code" name="postal_code" class="af-input" placeholder="00100">
            </div>
            <div class="af-group">
                <label class="af-label" for="town">Town</label>
                <input type="text" id="town" name="town" class="af-input" placeholder="Nairobi">
            </div>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 2: EMPLOYMENT DETAILS
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-employment">
        <legend class="af-section__legend">Employment Details</legend>

        <div class="af-row af-row--full">
            <div class="af-group">
                <label class="af-label" for="employer">Name of Employer *</label>
                <input type="text" id="employer" name="employer" class="af-input" required placeholder="e.g. Braeburn Mombasa">
            </div>
        </div>

        <div class="af-row af-row--2">
            <div class="af-group">
                <label class="af-label" for="emp_no">Employment No.</label>
                <input type="text" id="emp_no" name="emp_no" class="af-input">
            </div>
            <div class="af-group">
                <label class="af-label" for="designation">Designation</label>
                <input type="text" id="designation" name="designation" class="af-input" placeholder="Job title">
            </div>
        </div>

        <div class="af-row af-row--2">
            <div class="af-group">
                <label class="af-label" for="emp_terms">Employment Terms *</label>
                <select id="emp_terms" name="emp_terms" class="af-input" required>
                    <option value="">Select…</option>
                    <option>Permanent</option>
                    <option>Temporary</option>
                    <option>Contract</option>
                </select>
            </div>
            <div class="af-group">
                <label class="af-label" for="campus">Campus (if applicable)</label>
                <input type="text" id="campus" name="campus" class="af-input" placeholder="Campus name">
            </div>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 3: BANK DETAILS
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-bank">
        <legend class="af-section__legend">Bank Details</legend>

        <div class="af-row af-row--full">
            <div class="af-group">
                <label class="af-label" for="bank_name">Name of Bank *</label>
                <input type="text" id="bank_name" name="bank_name" class="af-input" required placeholder="e.g. Equity Bank">
            </div>
        </div>

        <div class="af-row af-row--2">
            <div class="af-group">
                <label class="af-label" for="bank_account">Account No. *</label>
                <input type="text" id="bank_account" name="bank_account" class="af-input" required>
            </div>
            <div class="af-group">
                <label class="af-label" for="bank_branch">Branch *</label>
                <input type="text" id="bank_branch" name="bank_branch" class="af-input" required placeholder="Branch name">
            </div>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 4: NEXT OF KIN
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-kin">
        <legend class="af-section__legend">Next of Kin
            <small class="af-section__legend-sub">Person to be contacted in case of emergency</small>
        </legend>

        <div class="af-row af-row--full">
            <div class="af-group">
                <label class="af-label" for="kin_name">Full Name *</label>
                <input type="text" id="kin_name" name="kin_name" class="af-input" required>
            </div>
        </div>

        <div class="af-row af-row--2">
            <div class="af-group">
                <label class="af-label" for="kin_relationship">Relationship *</label>
                <input type="text" id="kin_relationship" name="kin_relationship" class="af-input" required placeholder="e.g. Spouse, Parent">
            </div>
            <div class="af-group">
                <label class="af-label" for="kin_mobile">Mobile No. *</label>
                <input type="tel" id="kin_mobile" name="kin_mobile" class="af-input" required placeholder="+254…">
            </div>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 5: BENEFICIARY
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-beneficiary">
        <legend class="af-section__legend">Beneficiary
            <small class="af-section__legend-sub">Person(s) to receive funds/benefits in the unfortunate event of loss of life</small>
        </legend>

        <div class="af-table-wrapper">
            <table class="af-table" id="beneficiary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name *</th>
                        <th>Relationship *</th>
                        <th>% Allocation *</th>
                        <th>ID No.</th>
                        <th>Mobile No.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="beneficiary-tbody">
                    <!-- First 4 rows rendered by PHP; JS handles adding more -->
                    <?php for ($b = 1; $b <= 4; $b++): ?>
                    <tr class="ben-row">
                        <td class="af-table__num ben-row__num"><?php echo $b; ?></td>
                        <td><input type="text"   name="ben_name[]"         class="af-input af-input--table ben-name"         <?php echo $b ===
                        1
                            ? "required"
                            : ""; ?>></td>
                        <td><input type="text"   name="ben_relationship[]" class="af-input af-input--table ben-relationship" <?php echo $b ===
                        1
                            ? "required"
                            : ""; ?> placeholder="e.g. Spouse"></td>
                        <td><input type="number" name="ben_allocation[]"   class="af-input af-input--table ben-allocation"   min="0" max="100" placeholder="%" <?php echo $b ===
                        1
                            ? "required"
                            : ""; ?>></td>
                        <td><input type="text"   name="ben_idno[]"         class="af-input af-input--table"></td>
                        <td><input type="tel"    name="ben_mobile[]"       class="af-input af-input--table" placeholder="+254…"></td>
                        <td class="af-table__action">
                            <?php if ($b === 1): ?>
                            <span class="ben-row__placeholder"></span>
                            <?php else: ?>
                            <button type="button" class="ben-remove-btn" aria-label="Remove this beneficiary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <div class="ben-add-row">
            <button type="button" id="ben-add-btn" class="ben-add-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Another Beneficiary
            </button>
        </div>

        <p class="af-note"><strong>Note:</strong> For minors, ensure you indicate a guardian(s) with their mobile and ID/Alien card numbers. Total allocation must equal 100%.</p>
        <div class="af-allocation-check" id="allocation-check" hidden>
            <span id="allocation-total">Total: 0%</span>
            <span class="af-allocation-check__warn" id="allocation-warn"></span>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 6: REMITTANCES
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-remittances">
        <legend class="af-section__legend">Remittances to the Society</legend>

        <p class="af-section__intro">This is to confirm that my monthly contribution shall be done through:</p>

        <div class="af-payment-methods">
            <label class="af-checkbox-label">
                <input type="checkbox" name="payment_method[]" value="Employee Check-off" id="pm-checkoff" class="af-checkbox">
                <span>Employee Check-off</span>
            </label>
            <div class="af-conditional" id="payroll-row">
                <label class="af-label" for="payroll_no">Enter Payroll Number (Braeburn Staff)</label>
                <input type="text" id="payroll_no" name="payroll_no" class="af-input af-input--inline">
            </div>
            <label class="af-checkbox-label">
                <input type="checkbox" name="payment_method[]" value="Standing Order" class="af-checkbox">
                <span>Standing Order</span>
            </label>
            <label class="af-checkbox-label">
                <input type="checkbox" name="payment_method[]" value="Mobile Money Deposit" class="af-checkbox">
                <span>Mobile Money Deposit</span>
            </label>
            <label class="af-checkbox-label">
                <input type="checkbox" name="payment_method[]" value="Direct Bank Deposit" class="af-checkbox">
                <span>Direct Bank Deposit</span>
            </label>
        </div>

        <div class="af-remit-block">
            <h4 class="af-remit-block__title">Capital Shares <small>(KES 10,000 Total)</small></h4>
            <div class="af-row af-row--2">
                <div class="af-group">
                    <label class="af-label" for="capital_shares">I wish to contribute (KES)</label>
                    <input type="number" id="capital_shares" name="capital_shares" class="af-input" min="0" placeholder="Amount per month">
                </div>
            </div>
            <p class="af-note">Note: This monthly contribution will stop once it sums up to KES 10,000.</p>
        </div>

        <div class="af-remit-block">
            <h4 class="af-remit-block__title">Deposits &amp; Savings Products</h4>
            <div class="af-group">
                <label class="af-label" for="savings_total">Total monthly savings contribution (KES)</label>
                <input type="number" id="savings_total" name="savings_total" class="af-input" min="0" placeholder="Total per month">
            </div>
            <p class="af-remit-block__sub">To be distributed as follows:</p>
            <div class="af-savings-grid">
                <div class="af-group">
                    <label class="af-label" for="dep_deposits">Deposits (KES)</label>
                    <input type="number" id="dep_deposits"  name="dep_deposits"  class="af-input af-savings-input" min="0" placeholder="0">
                </div>
                <div class="af-group">
                    <label class="af-label" for="dep_christmas">Christmas Savings (KES)</label>
                    <input type="number" id="dep_christmas" name="dep_christmas" class="af-input af-savings-input" min="0" placeholder="0">
                </div>
                <div class="af-group">
                    <label class="af-label" for="dep_holiday">Holiday Savings (KES)</label>
                    <input type="number" id="dep_holiday"   name="dep_holiday"   class="af-input af-savings-input" min="0" placeholder="0">
                </div>
                <div class="af-group">
                    <label class="af-label" for="dep_toto">TOTO Savings (KES)</label>
                    <input type="number" id="dep_toto"      name="dep_toto"      class="af-input af-savings-input" min="0" placeholder="0">
                </div>
            </div>
        </div>

        <div class="af-remit-block af-remit-block--total">
            <div class="af-row af-row--2">
                <div class="af-group">
                    <label class="af-label" for="total_monthly">Total Monthly Contributions to Society (Deposits + Capital Shares)</label>
                    <input type="number" id="total_monthly" name="total_monthly" class="af-input" readonly placeholder="Auto-calculated">
                </div>
            </div>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 7: PHOTO & VIDEO CONSENT + DECLARATION
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-consent">
        <legend class="af-section__legend">Photo &amp; Video Consent</legend>

        <p class="af-section__intro">Braemeg NWDT Sacco may take my still or moving images during events, general meetings or functions, and use them appropriately and with due diligence in its platforms listed below.</p>

        <div class="af-consent-table-wrapper">
            <table class="af-consent-table">
                <thead>
                    <tr>
                        <th>Platform</th>
                        <th>I Consent</th>
                        <th>I Do Not Consent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $platforms = [
                        "Website",
                        "Social Media",
                        "Brochures, Pamphlets & Handbooks",
                    ];
                    foreach ($platforms as $pi => $platform):
                        $key = "consent_" . $pi; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($platform); ?></td>
                        <td class="af-consent-table__radio">
                            <label>
                                <input type="radio" name="<?php echo $key; ?>" value="yes" required>
                                <span class="af-consent-table__tick"></span>
                            </label>
                        </td>
                        <td class="af-consent-table__radio">
                            <label>
                                <input type="radio" name="<?php echo $key; ?>" value="no">
                                <span class="af-consent-table__tick af-consent-table__tick--no"></span>
                            </label>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </fieldset>

    <!-- Newsletter double opt-in -->
    <fieldset class="af-section af-section--newsletter" id="section-newsletter">
        <legend class="af-section__legend">Email Communications</legend>

        <p class="af-section__intro">Stay up to date with Braemeg SACCO news, AGM notices, product updates and member benefits.</p>

        <div class="af-newsletter-confirm">
            <label class="af-checkbox-label af-checkbox-label--large">
                <input type="checkbox" name="newsletter_opt_in" value="yes" id="newsletter-optin" class="af-checkbox" required>
                <span>I confirm that I wish to receive email communications, newsletters and important notices from Braemeg SACCO at the email address I have provided. *</span>
            </label>
        </div>

        <div class="af-newsletter-confirm af-newsletter-confirm--second">
            <label class="af-checkbox-label af-checkbox-label--large">
                <input type="checkbox" name="newsletter_confirm" value="yes" id="newsletter-confirm" class="af-checkbox" required>
                <span>I understand that a confirmation email will be sent to my provided address, and I must confirm my subscription to be added to the mailing list. I can unsubscribe at any time. *</span>
            </label>
        </div>
    </fieldset>

    <!-- Declaration -->
    <fieldset class="af-section af-section--declaration" id="section-declaration">
        <legend class="af-section__legend">Declaration</legend>

        <div class="af-declaration-text">
            <p>In making this membership application, I do hereby agree to conform to the society's By-laws and any amendments thereof.</p>
        </div>

        <div class="af-declaration-checkbox">
            <label class="af-checkbox-label af-checkbox-label--large">
                <input type="checkbox" name="declaration" value="yes" id="declaration-check" class="af-checkbox" required>
                <span>I confirm that the information provided in this form is true, complete and accurate to the best of my knowledge. I agree to the Braemeg SACCO By-laws and any amendments thereof. *</span>
            </label>
        </div>
    </fieldset>

    <!-- ══════════════════════════════════════════════════════
         SECTION 8: DOCUMENT UPLOADS
    ══════════════════════════════════════════════════════ -->
    <fieldset class="af-section" id="section-documents">
        <legend class="af-section__legend">Required Documents</legend>

        <div class="af-uploads-grid">

            <div class="af-upload-item">
                <label class="af-upload-label" for="doc-id">
                    <div class="af-upload-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="af-upload-label__title">National ID / Passport *</span>
                    <span class="af-upload-label__hint">PDF format, max 5MB</span>
                    <span class="af-upload-label__btn">Choose File</span>
                    <span class="af-upload-label__chosen" id="doc-id-name">No file chosen</span>
                </label>
                <input type="file" id="doc-id" name="doc_id" class="af-upload-input" accept=".pdf" required>
            </div>

            <div class="af-upload-item">
                <label class="af-upload-label" for="doc-kra">
                    <div class="af-upload-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="af-upload-label__title">KRA PIN Certificate *</span>
                    <span class="af-upload-label__hint">PDF format, max 5MB</span>
                    <span class="af-upload-label__btn">Choose File</span>
                    <span class="af-upload-label__chosen" id="doc-kra-name">No file chosen</span>
                </label>
                <input type="file" id="doc-kra" name="doc_kra" class="af-upload-input" accept=".pdf" required>
            </div>

            <div class="af-upload-item">
                <label class="af-upload-label" for="doc-photo">
                    <div class="af-upload-icon af-upload-icon--photo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                    <span class="af-upload-label__title">Passport-Size Photo *</span>
                    <span class="af-upload-label__hint">JPG/PNG, max 2MB</span>
                    <span class="af-upload-label__btn">Choose File</span>
                    <span class="af-upload-label__chosen" id="doc-photo-name">No file chosen</span>
                </label>
                <input type="file" id="doc-photo" name="doc_photo" class="af-upload-input" accept=".jpg,.jpeg,.png" required>
                <div class="af-photo-preview" id="photo-preview" hidden>
                    <img id="photo-preview-img" src="" alt="Photo preview">
                </div>
            </div>

        </div>
    </fieldset>

    <!-- Special field -->
    <div class="hp-container" aria-hidden="true">
      <label for="hp-field">Leave this field empty</label>
      <input
        type="text"
        id="hp-field"
        name="hp_field"
        autocomplete="new-password"
        tabindex="-1"
      />
    </div>

    <!-- Submit -->
    <div class="af-submit-row">
        <p class="af-submit-note">By submitting this form, you confirm all information provided is accurate. A filled copy of your application will be generated and emailed to you.</p>
        <button type="submit" class="af-submit-btn" id="apply-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            Submit Application
        </button>
    </div>

    </form>
</div><!-- /apply-form-card -->
</main>

</div><!-- /apply-layout -->
</div><!-- /container -->
</section>

<!-- ══════════════════════════════════════════════════════
     SUCCESS MODAL
══════════════════════════════════════════════════════ -->
<div class="af-modal-overlay" id="success-modal" hidden aria-modal="true" role="dialog" aria-labelledby="modal-title">
    <div class="af-modal">
        <div class="af-modal__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        <h2 class="af-modal__title" id="modal-title">Application Submitted!</h2>
        <p class="af-modal__body">
            Your membership application has been received. A confirmation email with a copy of your filled application form has been sent to <strong id="modal-email"></strong>.
        </p>
        <div class="af-modal__ref">
            Reference: <strong id="modal-ref"></strong>
        </div>
        <p class="af-modal__note">Please check your inbox (and spam folder) for two emails — one confirming your application and one to confirm your newsletter subscription. Our Secretariat will review your application and be in touch within 5 working days.</p>
        <div class="af-modal__actions">
            <a href="#" id="modal-download" class="btn-primary" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download My Application Form
            </a>
            <a href="index.php" class="btn-ghost">Return to Home</a>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
<script src="js/main.js"></script>
<script src="js/apply-form.js"></script>
</body>
</html>
