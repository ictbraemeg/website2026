<?php
/**
 * about-us/governance-structure.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* Fetch office bearers if stored in DB */
$bearers_qry = $dbc->prepare(
    "SELECT * FROM tbl_content WHERE plink = 'governance-structure' AND published = '1' LIMIT 1"
);
$bearers_qry->execute();
$gov_content = $bearers_qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'about';
$page_title = 'Governance Structure — ' . htmlspecialchars($rcs['name']);

$page_heading = 'About Us';
$page_sub     = 'Our governance structure, committees and office bearers.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'About Us', 'href' => 'who-we-are.php'],
    ['label' => 'Governance Structure'],
];

$sidebar_title = 'About Us';
$sidebar_items = [
    ['label' => 'Who We Are',           'href' => 'who-we-are.php'],
    ['label' => 'Our Vision & Mission', 'href' => 'our-vision-and-mission.php'],
    ['label' => 'Governance Structure', 'href' => 'governance-structure.php', 'active' => true],
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
                    <span class="section-tag">How We're Run</span>
                    <h2 class="inner-page__title">Governance Structure</h2>
                </div>

                <div class="content-block animate-on-scroll">
                    <div class="content-block__media">
                        <?php if (!empty($gov_content['imagePath'])): ?>
                            <img src="../images/gallery/<?php echo htmlspecialchars($gov_content['imagePath']); ?>"
                                 alt="Governance structure diagram" class="content-block__img">
                        <?php else: ?>
                            <div class="governance-diagram" aria-label="Governance structure illustration">
                                <div class="gov-level gov-level--top">
                                    <div class="gov-box gov-box--primary">General Meeting</div>
                                </div>
                                <div class="gov-level">
                                    <div class="gov-box">Board of Directors</div>
                                    <div class="gov-box">Supervisory Committee</div>
                                </div>
                                <div class="gov-level">
                                    <div class="gov-box gov-box--small">Credit Committee</div>
                                    <div class="gov-box gov-box--small">Education &amp; Business Development</div>
                                    <div class="gov-box gov-box--small">Finance &amp; Admin Committee</div>
                                </div>
                                <div class="gov-level gov-level--bottom">
                                    <div class="gov-box gov-box--secondary">Office Secretariat</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="content-block__body">
                        <div class="content-prose">
                            <?php if (!empty($gov_content['body'])): ?>
                                <?php echo $gov_content['body']; ?>
                            <?php else: ?>
                            <p>The Board &amp; Supervisory members serve for a term of 2 years after which they can opt to resign from the Board or offer themselves for re-election. Election for a position in the Board is open to all members.</p>
                            <p>Within the Board there are several sub-committees including the following:</p>
                            <ul class="content-prose__list">
                                <li>Credit Committee</li>
                                <li>Education &amp; Business Development Committee</li>
                                <li>Finance &amp; Admin Committee</li>
                            </ul>
                            <p>BRAEMEG SACCO has an Office Secretariat that runs the day to day operations of the Sacco. The Secretariat comprises of a CEO, Sacco Accountant, Accounts Assistant, Credit Relations Officer &amp; Customer Care Officer.</p>
                            <p>The SACCO is privileged to have a wealth of diversity in its membership both locally and foreign. While such diversity may come with associated challenges, the Society has fostered unity of purpose among members.</p>
                            <p>BRAEMEG also holds a unique position as a SACCO that has a Board and management team who are professionals in either finance, management or other business areas. In this regard the SACCO is confident that all decisions are carefully thought through before they are made.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="content-divider animate-on-scroll"></div>

                <div class="animate-on-scroll">
                    <h3 class="inner-section-title">Our Office Bearers</h3>
                    <div class="bearers-grid">
                        <div class="bearer-card">
                            <div class="bearer-card__avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h4 class="bearer-card__role">Chairperson</h4>
                            <p class="bearer-card__name">Board of Directors</p>
                        </div>
                        <div class="bearer-card">
                            <div class="bearer-card__avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h4 class="bearer-card__role">Vice Chairperson</h4>
                            <p class="bearer-card__name">Board of Directors</p>
                        </div>
                        <div class="bearer-card">
                            <div class="bearer-card__avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h4 class="bearer-card__role">Treasurer</h4>
                            <p class="bearer-card__name">Board of Directors</p>
                        </div>
                        <div class="bearer-card">
                            <div class="bearer-card__avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h4 class="bearer-card__role">Secretary</h4>
                            <p class="bearer-card__name">Board of Directors</p>
                        </div>
                        <div class="bearer-card">
                            <div class="bearer-card__avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h4 class="bearer-card__role">CEO</h4>
                            <p class="bearer-card__name">Office Secretariat</p>
                        </div>
                        <div class="bearer-card">
                            <div class="bearer-card__avatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h4 class="bearer-card__role">Sacco Accountant</h4>
                            <p class="bearer-card__name">Office Secretariat</p>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
