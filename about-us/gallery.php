<?php
/**
 * about-us/gallery.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* Fetch gallery images */
$gallery_qry = $dbc->prepare(
    "SELECT * FROM tbl_gallery WHERE published='1' ORDER BY sortID ASC"
);
$gallery_qry->execute();
$gallery_items = $gallery_qry->fetchAll(PDO::FETCH_ASSOC);

/* Distinct categories for filter tabs */
$cats_qry = $dbc->prepare(
    "SELECT DISTINCT category FROM tbl_gallery WHERE published='1' ORDER BY category ASC"
);
$cats_qry->execute();
$categories = $cats_qry->fetchAll(PDO::FETCH_COLUMN);

$nav_base   = '../';
$nav_active = 'about';
$page_title = 'Gallery — ' . htmlspecialchars($rcs['name']);

$page_heading = 'About Us';
$page_sub     = 'A glimpse into the Braemeg SACCO community.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'About Us', 'href' => 'who-we-are.php'],
    ['label' => 'Gallery'],
];

$sidebar_title = 'About Us';
$sidebar_items = [
    ['label' => 'Who We Are',           'href' => 'who-we-are.php'],
    ['label' => 'Our Vision & Mission', 'href' => 'our-vision-and-mission.php'],
    ['label' => 'Governance Structure', 'href' => 'governance-structure.php'],
    ['label' => 'Our Growth',           'href' => 'our-growth.php'],
    ['label' => 'Gallery',              'href' => 'gallery.php', 'active' => true],
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
                    <span class="section-tag">Photo Gallery</span>
                    <h2 class="inner-page__title">Our Gallery</h2>
                </div>

                <!-- Category filter tabs -->
                <?php if (!empty($categories)): ?>
                <div class="gallery-filters animate-on-scroll" role="tablist" aria-label="Filter gallery by category">
                    <button class="gallery-filter-btn is-active" data-filter="all" role="tab" aria-selected="true">All</button>
                    <?php foreach ($categories as $cat): ?>
                    <button class="gallery-filter-btn" data-filter="<?php echo htmlspecialchars(strtolower($cat)); ?>" role="tab" aria-selected="false">
                        <?php echo htmlspecialchars(ucwords($cat)); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="gallery-filters animate-on-scroll">
                    <button class="gallery-filter-btn is-active" data-filter="all">All</button>
                </div>
                <?php endif; ?>

                <!-- Gallery grid -->
                <?php if (!empty($gallery_items)): ?>
                <div class="gallery-grid" id="gallery-grid">
                    <?php foreach ($gallery_items as $item): ?>
                    <figure class="gallery-item animate-on-scroll"
                            data-category="<?php echo htmlspecialchars(strtolower($item['category'] ?? 'all')); ?>">
                        <a href="../images/gallery/<?php echo htmlspecialchars($item['imagePath']); ?>"
                           class="gallery-item__link"
                           aria-label="View <?php echo htmlspecialchars($item['title'] ?? 'image'); ?>">
                            <img src="../images/gallery/<?php echo htmlspecialchars($item['imagePath']); ?>"
                                 alt="<?php echo htmlspecialchars($item['title'] ?? 'Gallery image'); ?>"
                                 class="gallery-item__img"
                                 loading="lazy">
                            <div class="gallery-item__overlay" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                            </div>
                        </a>
                        <?php if (!empty($item['title'])): ?>
                        <figcaption class="gallery-item__caption"><?php echo htmlspecialchars($item['title']); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="empty-state animate-on-scroll">
                    <div class="empty-state__icon" aria-hidden="true">🖼️</div>
                    <p class="empty-state__text">Gallery images will appear here once uploaded to the system.</p>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
