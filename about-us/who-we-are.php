<?php
/**
 * about-us/who-we-are.php — Who We Are
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'about';
$page_title = 'Who We Are — ' . htmlspecialchars($rcs['name']);

$page_heading = 'About Us';
$page_sub     = 'Learn about Braemeg SACCO — our history, membership and community.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'About Us', 'href' => '../about-us/who-we-are.php'],
    ['label' => 'Who We Are'],
];

$sidebar_title = 'About Us';
$sidebar_items = [
    ['label' => 'Who We Are',          'href' => 'who-we-are.php',          'active' => true],
    ['label' => 'Our Vision & Mission', 'href' => 'our-vision-and-mission.php'],
    ['label' => 'Governance Structure', 'href' => 'governance-structure.php'],
    ['label' => 'Our Growth',          'href' => 'our-growth.php'],
    ['label' => 'Gallery',             'href' => 'gallery.php'],
];

/* Fetch content from DB if available */
$content_qry = $dbc->prepare(
    "SELECT tc.*, tm.menu_name FROM tbl_content tc
     JOIN tbl_mainmenu tm ON tm.PID = tc.menuid
     WHERE tc.plink = 'who-we-are' AND tc.published = '1' LIMIT 1"
);
$content_qry->execute();
$content = $content_qry->fetch(PDO::FETCH_ASSOC);
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
                <div class="content-block animate-on-scroll">
                    <div class="content-block__media">
                        <?php if (!empty($content['imagePath'])): ?>
                            <img src="../images/gallery/<?php echo htmlspecialchars($content['imagePath']); ?>"
                                 alt="Braemeg SACCO team" class="content-block__img">
                        <?php else: ?>
                            <div class="content-block__img-placeholder" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="content-block__body">
                        <span class="section-tag">Who We Are</span>
                        <h2 class="content-block__title">Braemeg SACCO Society Limited</h2>

                        <?php if (!empty($content['body'])): ?>
                            <div class="content-prose"><?php echo $content['body']; ?></div>
                        <?php else: ?>
                        <div class="content-prose">
                            <p><strong>Braemeg Regulated Non-WDT Sacco Society Limited</strong> was registered in 1988 to facilitate savings and provide credit to employees working at Braeburn Group of International Schools &amp; other International Schools. Over the years the Sacco has witnessed growth in its assets, resources and membership. Braemeg Sacco is regulated by SASRA.</p>
                            <p>Initially the Sacco drew its membership from employees of Braeburn Group of International School. Today the Sacco has the following member organisations, to mention a few:</p>
                        </div>
                        <?php endif; ?>

                        <div class="members-list-grid">
                            <div class="members-list-col">
                                <h3 class="members-list__title">Member Schools</h3>
                                <ul class="members-list">
                                    <li>Braeburn Gitanga Road</li>
                                    <li>Braeburn Garden Estate</li>
                                    <li>Braeside School</li>
                                    <li>Braeburn Arusha</li>
                                    <li>Braeburn Mombasa</li>
                                    <li>Braeburn Kisumu</li>
                                    <li>Braeburn Thika</li>
                                    <li>Braeburn Nanyuki</li>
                                    <li>St. Christopher Schools</li>
                                    <li>Hillcrest</li>
                                    <li>Brookhouse Schools</li>
                                    <li>Peponi</li>
                                    <li>Roselynn International Schools</li>
                                    <li>St. Andrews Turi</li>
                                </ul>
                            </div>
                            <div class="members-list-col">
                                <h3 class="members-list__title">Membership Bond</h3>
                                <ul class="members-list members-list--bond">
                                    <li>Employees of all international schools</li>
                                    <li>Spouses &amp; children of Braemeg SACCO members</li>
                                    <li>Employees of Braemeg Sacco Limited</li>
                                </ul>
                                <div class="diaspora-note">
                                    <p>Braemeg Sacco also has membership in the Diaspora — USA, UK, China, Dubai and many other countries.</p>
                                </div>
                            </div>
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
