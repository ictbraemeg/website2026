<?php
/**
 * about-us/our-growth.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* Fetch growth chart images from DB */
$charts_qry = $dbc->prepare(
    "SELECT * FROM tbl_gallery WHERE category='growth' AND published='1' ORDER BY sortID ASC"
);
$charts_qry->execute();
$charts = $charts_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'about';
$page_title = 'Our Growth — ' . htmlspecialchars($rcs['name']);

$page_heading = 'About Us';
$page_sub     = 'Our path to prosperity — tracking our asset and membership growth over the years.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'About Us', 'href' => 'who-we-are.php'],
    ['label' => 'Our Growth'],
];

$sidebar_title = 'About Us';
$sidebar_items = [
    ['label' => 'Who We Are',           'href' => 'who-we-are.php'],
    ['label' => 'Our Vision & Mission', 'href' => 'our-vision-and-mission.php'],
    ['label' => 'Governance Structure', 'href' => 'governance-structure.php'],
    ['label' => 'Our Growth',           'href' => 'our-growth.php', 'active' => true],
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
                    <span class="section-tag">Performance</span>
                    <h2 class="inner-page__title">Our Path to Prosperity</h2>
                    <p class="inner-page__desc">Since 1988, Braemeg SACCO has grown steadily in assets, membership and financial impact. Below are key performance indicators across recent years.</p>
                </div>

                <!-- Stats summary cards -->
                <div class="growth-stats-row animate-on-scroll">
                    <div class="growth-stat-card">
                        <div class="growth-stat-card__num">35+</div>
                        <div class="growth-stat-card__label">Years in operation</div>
                    </div>
                    <div class="growth-stat-card">
                        <div class="growth-stat-card__num">KES 3M</div>
                        <div class="growth-stat-card__label">Max loan entitlement</div>
                    </div>
                    <div class="growth-stat-card">
                        <div class="growth-stat-card__num">14+</div>
                        <div class="growth-stat-card__label">Member schools</div>
                    </div>
                    <div class="growth-stat-card">
                        <div class="growth-stat-card__num">5%</div>
                        <div class="growth-stat-card__label">Toto savings interest p.a.</div>
                    </div>
                </div>

                <div class="content-divider animate-on-scroll"></div>

                <!-- Charts grid — from DB or fallback message -->
                <div class="animate-on-scroll">
                    <h3 class="inner-section-title">Growth Charts</h3>
                </div>

                <?php if (!empty($charts)): ?>
                <div class="charts-grid">
                    <?php foreach ($charts as $chart): ?>
                    <figure class="chart-figure animate-on-scroll">
                        <img src="../images/gallery/<?php echo htmlspecialchars($chart['imagePath']); ?>"
                             alt="<?php echo htmlspecialchars($chart['title'] ?? 'Growth chart'); ?>"
                             class="chart-figure__img">
                        <?php if (!empty($chart['title'])): ?>
                        <figcaption class="chart-figure__caption">
                            <?php echo htmlspecialchars($chart['title']); ?>
                        </figcaption>
                        <?php endif; ?>
                    </figure>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <!-- Fallback: static chart images from original site if stored in images/portfolio -->
                <div class="charts-grid">
                    <figure class="chart-figure animate-on-scroll">
                        <img src="../images/portfolio/society-asset-base.jpg"
                             alt="Society Asset Base chart"
                             class="chart-figure__img"
                             onerror="this.parentElement.style.display='none'">
                        <figcaption class="chart-figure__caption">Society Asset Base</figcaption>
                    </figure>
                    <figure class="chart-figure animate-on-scroll">
                        <img src="../images/portfolio/members-deposits.jpg"
                             alt="Members Deposits chart"
                             class="chart-figure__img"
                             onerror="this.parentElement.style.display='none'">
                        <figcaption class="chart-figure__caption">Members Deposits</figcaption>
                    </figure>
                    <figure class="chart-figure animate-on-scroll">
                        <img src="../images/portfolio/loans-to-members.jpg"
                             alt="Loans to Members chart"
                             class="chart-figure__img"
                             onerror="this.parentElement.style.display='none'">
                        <figcaption class="chart-figure__caption">Loans to Members</figcaption>
                    </figure>
                </div>
                <div class="empty-state animate-on-scroll" id="growth-empty-msg" style="display:none;">
                    <div class="empty-state__icon" aria-hidden="true">📊</div>
                    <p class="empty-state__text">Growth charts will appear here once uploaded to the system.</p>
                </div>
                <script>
                    /* Show empty state if all chart images failed to load */
                    document.addEventListener('DOMContentLoaded', function () {
                        var imgs = document.querySelectorAll('.chart-figure__img');
                        var allHidden = Array.from(imgs).every(function (img) {
                            return img.parentElement.style.display === 'none';
                        });
                        if (allHidden) {
                            document.getElementById('growth-empty-msg').style.display = 'block';
                        }
                    });
                </script>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
