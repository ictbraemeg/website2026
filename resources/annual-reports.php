<?php
/**
 * resources/annual-reports.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* Fetch from DB */
$reports_qry = $dbc->prepare(
    "SELECT * FROM tbl_downloads WHERE published='1' AND category='Annual Reports' ORDER BY title DESC"
);
$reports_qry->execute();
$reports = $reports_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'resources';
$page_title = 'Annual Reports — ' . htmlspecialchars($rcs['name']);

$page_heading = 'Resources';
$page_sub     = 'Audited financial statements and AGM reports.';
$breadcrumbs  = [
    ['label' => 'Home',      'href' => '../index.php'],
    ['label' => 'Resources', 'href' => 'application-forms.php'],
    ['label' => 'Annual Reports'],
];

$sidebar_title = 'Resources';
$sidebar_items = [
    ['label' => 'Downloads & Forms', 'href' => 'application-forms.php'],
    ['label' => 'Policies & Bylaws', 'href' => 'policies.php'],
    ['label' => 'Annual Reports',    'href' => 'annual-reports.php', 'active' => true],
    ['label' => 'FAQs',              'href' => 'faqs.php'],
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
                    <span class="section-tag">Transparency</span>
                    <h2 class="inner-page__title">Annual Reports</h2>
                    <p class="inner-page__desc">Braemeg SACCO is committed to full transparency with our members. Download our audited annual reports and AGM minutes below.</p>
                </div>

                <?php if (!empty($reports)): ?>
                <div class="downloads-section animate-on-scroll">
                    <div class="downloads-list">
                        <?php foreach ($reports as $report): ?>
                        <div class="download-item">
                            <div class="download-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </div>
                            <div class="download-item__info">
                                <h4 class="download-item__title"><?php echo htmlspecialchars($report['title']); ?></h4>
                                <?php if (!empty($report['description'])): ?>
                                <p class="download-item__desc"><?php echo htmlspecialchars($report['description']); ?></p>
                                <?php endif; ?>
                            </div>
                            <a href="../files/<?php echo htmlspecialchars($report['filePath']); ?>"
                               class="download-item__btn"
                               download
                               aria-label="Download <?php echo htmlspecialchars($report['title']); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-state animate-on-scroll">
                    <div class="empty-state__icon" aria-hidden="true">📊</div>
                    <p class="empty-state__text">Annual reports will be published here following each AGM. Please <a href="../contacts.php">contact us</a> to request a copy.</p>
                </div>
                <?php endif; ?>

                <div class="info-note animate-on-scroll">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p>Members may also request printed copies of any annual report by contacting the <a href="../contacts.php">Office Secretariat</a>.</p>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
