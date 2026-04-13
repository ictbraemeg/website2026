<?php
/**
 * apply.php — Membership Application Form
 */
require_once 'config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '';
$nav_active = '';
$page_title = 'Apply for Membership — ' . htmlspecialchars($rcs['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- ── PAGE HEADER ────────────────────────────────────────── -->
<?php
$page_heading = 'Apply for Membership';
$page_sub     = 'Join thousands of members building financial freedom with Braemeg SACCO.';
$breadcrumbs  = [
    ['label' => 'Home', 'href' => 'index.php'],
    ['label' => 'Apply for Membership'],
];
include 'includes/page-header.php';
?>

<!-- ── APPLICATION FORM ───────────────────────────────────── -->
<section class="apply-section">
    <div class="container">
        <div class="apply-grid">

            <!-- Left: eligibility info -->
            <aside class="apply-info">
                <div class="apply-info__block">
                    <span class="section-tag">Eligibility</span>
                    <h2 class="apply-info__title">Who Can Join?</h2>
                    <ul class="apply-info__list">
                        <li>Employees of all international schools in the Braemeg network</li>
                        <li>Spouses &amp; children of existing Braemeg SACCO members</li>
                        <li>Employees of Braemeg Sacco Limited</li>
                    </ul>
                </div>

                <div class="apply-info__block apply-info__block--highlight">
                    <h3 class="apply-info__sub-title">What You'll Need</h3>
                    <ul class="apply-info__checklist">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            National ID or Passport
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            Recent passport-size photograph
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            Proof of employment / payslip
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            Completed application form (below)
                        </li>
                    </ul>
                </div>

                <div class="apply-info__block">
                    <h3 class="apply-info__sub-title">Need Help?</h3>
                    <p>Our team is available during office hours to guide you through the application process.</p>
                    <a href="contacts.php" class="btn-ghost" style="margin-top:1rem;display:inline-flex;">
                        Contact Us
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </aside>

            <!-- Right: application form -->
            <main id="main-content">
                <div class="apply-form-card">
                    <div class="apply-form-card__header">
                        <h2 class="apply-form-card__title">New Member Application</h2>
                        <p class="apply-form-card__sub">Complete all required (*) fields and submit. Our Secretariat will review your application and be in touch within 5 working days.</p>
                    </div>

                    <form id="apply-form" action="javascript:void(0)" method="post" novalidate>
                        <input type="hidden" name="rid" value="">

                        <!-- Feedback areas -->
                        <div class="apply-alert apply-alert--error" id="apply-error" role="alert" aria-live="polite" hidden></div>
                        <div class="apply-alert apply-alert--success" id="apply-success" role="alert" aria-live="polite" hidden>
                            <strong>Application submitted!</strong> Thank you, we will be in touch shortly.
                        </div>
                        <div class="apply-alert apply-alert--warning" id="apply-duplicate" role="alert" aria-live="polite" hidden>
                            <strong>Email already registered.</strong> If you are already a member, please use the <a href="https://portal.braemegsacco.co.ke:8085" target="_blank" rel="noopener">member portal</a> to log in.
                        </div>

                        <!-- Personal details -->
                        <fieldset class="apply-fieldset">
                            <legend class="apply-fieldset__legend">Personal Details</legend>

                            <div class="apply-form-row">
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-surname">Surname *</label>
                                    <input type="text" id="af-surname" name="surname"
                                           class="apply-input" placeholder="Family name"
                                           required autocomplete="family-name">
                                </div>
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-othername">Other Name(s) *</label>
                                    <input type="text" id="af-othername" name="othername"
                                           class="apply-input" placeholder="Given name(s)"
                                           required autocomplete="given-name">
                                </div>
                            </div>

                            <div class="apply-form-row">
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-email">Email Address *</label>
                                    <input type="email" id="af-email" name="email"
                                           class="apply-input" placeholder="you@example.com"
                                           required autocomplete="email">
                                </div>
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-mobile">Mobile Number *</label>
                                    <input type="tel" id="af-mobile" name="mobile"
                                           class="apply-input" placeholder="+254 ..."
                                           required autocomplete="tel">
                                </div>
                            </div>

                            <div class="apply-form-row">
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-idno">ID / Passport Number *</label>
                                    <input type="text" id="af-idno" name="idno"
                                           class="apply-input" placeholder="National ID or Passport no."
                                           required>
                                </div>
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-postal">Postal Address</label>
                                    <input type="text" id="af-postal" name="postal"
                                           class="apply-input" placeholder="P.O. Box ...">
                                </div>
                            </div>

                            <div class="apply-form-group">
                                <label class="apply-label" for="af-resi">Current Residence</label>
                                <input type="text" id="af-resi" name="resi"
                                       class="apply-input" placeholder="Town / Estate">
                            </div>
                        </fieldset>

                        <!-- Employment details -->
                        <fieldset class="apply-fieldset">
                            <legend class="apply-fieldset__legend">Employment Details</legend>

                            <div class="apply-form-row">
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-career">Employment Status *</label>
                                    <select id="af-career" name="career" class="apply-input" required>
                                        <option value="">Select status…</option>
                                        <option value="Employed">Employed</option>
                                        <option value="Self-Employed">Self-Employed</option>
                                    </select>
                                </div>
                                <div class="apply-form-group">
                                    <label class="apply-label" for="af-employer">Employer / School *</label>
                                    <input type="text" id="af-employer" name="employer"
                                           class="apply-input" placeholder="e.g. Braeburn Mombasa"
                                           required>
                                </div>
                            </div>
                        </fieldset>

                        <button type="submit" class="apply-submit" id="apply-submit">
                            Submit Application
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            </main>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/main.js"></script>
</body>
</html>
