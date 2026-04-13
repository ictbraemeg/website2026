<?php
/**
 * resources/faqs.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'resources';
$page_title = 'FAQs — ' . htmlspecialchars($rcs['name']);

$page_heading = 'Resources';
$page_sub     = 'Frequently asked questions about Braemeg SACCO.';
$breadcrumbs  = [
    ['label' => 'Home',      'href' => '../index.php'],
    ['label' => 'Resources', 'href' => 'application-forms.php'],
    ['label' => 'FAQs'],
];

$sidebar_title = 'Resources';
$sidebar_items = [
    ['label' => 'Downloads & Forms', 'href' => 'application-forms.php'],
    ['label' => 'Policies & Bylaws', 'href' => 'policies.php'],
    ['label' => 'Annual Reports',    'href' => 'annual-reports.php'],
    ['label' => 'FAQs',              'href' => 'faqs.php', 'active' => true],
];

$faqs = [
    [
        'q' => 'Who is eligible to join Braemeg SACCO?',
        'a' => 'Membership is open to (i) Employees of all international schools in the Braemeg network, (ii) Spouses and children of Braemeg SACCO members, and (iii) Employees of Braemeg SACCO Limited itself.',
    ],
    [
        'q' => 'How much can I borrow?',
        'a' => 'The maximum loan entitlement is 3 times your total deposits, up to a maximum of KES 3,000,000. For Development Loans, the same maximum applies at 1.125% per month on reducing balance.',
    ],
    [
        'q' => 'What is the interest rate on loans?',
        'a' => 'Normal and Emergency loans attract 1% per month on a reducing balance. Development loans attract 1.125% per month on a reducing balance. A 2% commission is charged on Loan Refinancing.',
    ],
    [
        'q' => 'How long does loan processing take?',
        'a' => 'Loans are disbursed within 30 days from the date a correctly filled application form and all required attachments are received by the Secretariat.',
    ],
    [
        'q' => 'What is the Toto Savings plan?',
        'a' => 'Toto Savings is a children\'s savings plan for members\' children below 18 years. It earns 5% annual interest compounded onto the fund after each 12-month cluster. The minimum contribution is KES 500 per month via checkoff, direct deposit or standing order.',
    ],
    [
        'q' => 'Can I withdraw my savings at any time?',
        'a' => 'For Toto Savings, withdrawal before completion of a 12-month cluster forfeits the interest for that cluster. A refund is only due 45 days after withdrawal notice. For regular deposits, withdrawal conditions are governed by the SACCO bylaws.',
    ],
    [
        'q' => 'How do I apply for membership?',
        'a' => 'Download the membership application form from our Resources page, complete it and submit it to our Secretariat along with your ID, recent passport photo, and any other required documents. You can also apply online through our member portal.',
    ],
    [
        'q' => 'Is Braemeg SACCO regulated?',
        'a' => 'Yes. Braemeg SACCO Society Limited is fully regulated by the Sacco Societies Regulatory Authority (SASRA) in Kenya, ensuring your funds are safe and properly managed.',
    ],
    [
        'q' => 'Can diaspora members join?',
        'a' => 'Yes. Braemeg SACCO has members in the USA, UK, China, Dubai and many other countries. Diaspora members can participate in savings and loan products subject to the same eligibility criteria.',
    ],
    [
        'q' => 'How do I contact the Secretariat?',
        'a' => 'You can reach us by phone at ' . htmlspecialchars($rcs['cellphone']) . ', by email at ' . htmlspecialchars($rcs['email']) . ', or by visiting our offices. You can also use the contact form on our website.',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include '../includes/head.php'; ?></head>
<body>

<?php include '../includes/topbar.php'; ?>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/page-header.php'; ?>

<div class="inner-page">
    <div class="container">
        <div class="inner-page__layout">

            <?php include '../includes/section-sidebar.php'; ?>

            <main class="inner-page__content" id="main-content">

                <div class="animate-on-scroll">
                    <span class="section-tag">Help</span>
                    <h2 class="inner-page__title">Frequently Asked Questions</h2>
                    <p class="inner-page__desc">Find answers to the most common questions about membership, loans and savings. Can't find what you're looking for? <a href="../contacts.php">Contact us</a>.</p>
                </div>

                <div class="faqs-list" id="faqs-list">
                    <?php foreach ($faqs as $i => $faq): ?>
                    <div class="faq-item animate-on-scroll" id="faq-<?php echo $i; ?>">
                        <button class="faq-item__question"
                                aria-expanded="false"
                                aria-controls="faq-answer-<?php echo $i; ?>">
                            <?php echo htmlspecialchars($faq['q']); ?>
                            <span class="faq-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <div class="faq-item__answer" id="faq-answer-<?php echo $i; ?>" hidden>
                            <p><?php echo $faq['a']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="info-note animate-on-scroll">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p>Still have questions? <a href="../contacts.php">Get in touch</a> — our team is happy to help.</p>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
