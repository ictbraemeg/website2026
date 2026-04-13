<?php
/**
 * about-us/our-vision-and-mission.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'about';
$page_title = 'Our Vision & Mission — ' . htmlspecialchars($rcs['name']);

$page_heading = 'About Us';
$page_sub     = 'Our vision, mission, core values and objectives.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'About Us', 'href' => 'who-we-are.php'],
    ['label' => 'Our Vision & Mission'],
];

$sidebar_title = 'About Us';
$sidebar_items = [
    ['label' => 'Who We Are',           'href' => 'who-we-are.php'],
    ['label' => 'Our Vision & Mission', 'href' => 'our-vision-and-mission.php', 'active' => true],
    ['label' => 'Governance Structure', 'href' => 'governance-structure.php'],
    ['label' => 'Our Growth',           'href' => 'our-growth.php'],
    ['label' => 'Gallery',              'href' => 'gallery.php'],
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
                    <span class="section-tag">Our Purpose</span>
                    <h2 class="inner-page__title">Our Vision and Mission</h2>
                </div>

                <div class="vm-grid animate-on-scroll">
                    <div class="vm-card vm-card--mission">
                        <div class="vm-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        </div>
                        <h3 class="vm-card__label">Our Mission</h3>
                        <p class="vm-card__text">Provide diverse and affordable financial products &amp; services that guarantee competitive returns to members through mobilisation of savings, education and sound management.</p>
                    </div>

                    <div class="vm-card vm-card--vision">
                        <div class="vm-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <h3 class="vm-card__label">Our Vision</h3>
                        <p class="vm-card__text">To be a leading financial institution that guarantees members' growth and financial independence.</p>
                    </div>
                </div>

                <div class="content-divider animate-on-scroll"></div>

                <div class="animate-on-scroll">
                    <h3 class="inner-section-title">Our Core Values</h3>
                    <div class="values-icon-grid">
                        <div class="value-icon-card">
                            <div class="value-icon-card__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </div>
                            <h4 class="value-icon-card__name">Excellent Customer Care</h4>
                        </div>
                        <div class="value-icon-card">
                            <div class="value-icon-card__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h4 class="value-icon-card__name">Integrity</h4>
                        </div>
                        <div class="value-icon-card">
                            <div class="value-icon-card__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <h4 class="value-icon-card__name">Confidentiality</h4>
                        </div>
                        <div class="value-icon-card">
                            <div class="value-icon-card__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <h4 class="value-icon-card__name">Equity &amp; Fairness</h4>
                        </div>
                        <div class="value-icon-card">
                            <div class="value-icon-card__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            </div>
                            <h4 class="value-icon-card__name">Proactive Leadership</h4>
                        </div>
                    </div>
                </div>

                <div class="content-divider animate-on-scroll"></div>

                <div class="animate-on-scroll">
                    <h3 class="inner-section-title">Our Objectives</h3>
                    <ul class="objectives-full-list">
                        <li>Encourage thrift among members by according them an opportunity for accumulating savings.</li>
                        <li>Create a source of funds for credit which shall be lent to members at fair and reasonable interest rates.</li>
                        <li>Provide opportunity for each member to improve their own respective economic and social condition.</li>
                        <li>Offer basic services associated with a Sacco in the Co-Operative Society Sector.</li>
                        <li>Streamline loan processing procedures.</li>
                        <li>Ensure safety and soundness of members' funds through risk management programs or an appropriate insurance scheme.</li>
                    </ul>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
