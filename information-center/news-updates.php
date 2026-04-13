<?php
/**
 * information-center/news-updates.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$per_page   = 9;
$current_pg = max(1, (int)($_GET['pg'] ?? 1));
$offset     = ($current_pg - 1) * $per_page;

$total_qry = $dbc->prepare("SELECT COUNT(*) FROM tbl_news WHERE published='1'");
$total_qry->execute();
$total = (int)$total_qry->fetchColumn();
$total_pages = (int)ceil($total / $per_page);

$news_qry = $dbc->prepare(
    "SELECT * FROM tbl_news WHERE published='1' ORDER BY created_at DESC LIMIT :limit OFFSET :offset"
);
$news_qry->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$news_qry->bindValue(':offset', $offset,   PDO::PARAM_INT);
$news_qry->execute();
$news_items = $news_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'info';
$page_title = 'News & Updates — ' . htmlspecialchars($rcs['name']);

$page_heading = 'Information Center';
$page_sub     = 'Stay up to date with the latest news and announcements from Braemeg SACCO.';
$breadcrumbs  = [
    ['label' => 'Home',               'href' => '../index.php'],
    ['label' => 'Information Center', 'href' => 'general-information.php'],
    ['label' => 'News & Updates'],
];

$sidebar_title = 'Information Center';
$sidebar_items = [
    ['label' => 'General Information', 'href' => 'general-information.php'],
    ['label' => 'News & Updates',      'href' => 'news-updates.php', 'active' => true],
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
                    <span class="section-tag">Latest</span>
                    <h2 class="inner-page__title">News &amp; Updates</h2>
                </div>

                <?php if (!empty($news_items)): ?>
                <div class="news-grid">
                    <?php foreach ($news_items as $item): ?>
                    <article class="news-card animate-on-scroll">
                        <?php if (!empty($item['imagePath'])): ?>
                        <div class="news-card__thumb">
                            <img src="../images/gallery/<?php echo htmlspecialchars($item['imagePath']); ?>"
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 class="news-card__img"
                                 loading="lazy">
                        </div>
                        <?php endif; ?>
                        <div class="news-card__body">
                            <?php if (!empty($item['created_at'])): ?>
                            <time class="news-card__date" datetime="<?php echo $item['created_at']; ?>">
                                <?php echo date('d M Y', strtotime($item['created_at'])); ?>
                            </time>
                            <?php endif; ?>
                            <h3 class="news-card__title">
                                <a href="news-updates.php?page=<?php echo (int)$item['PID']; ?>">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </a>
                            </h3>
                            <p class="news-card__excerpt">
                                <?php echo htmlspecialchars(substr(strip_tags($item['body'] ?? $item['description'] ?? ''), 0, 120)); ?>…
                            </p>
                            <a href="news-updates.php?page=<?php echo (int)$item['PID']; ?>" class="article-card__link">
                                Read More
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <nav class="pagination" aria-label="Page navigation">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <a href="?pg=<?php echo $p; ?>"
                       class="pagination__btn <?php echo $p === $current_pg ? 'pagination__btn--active' : ''; ?>"
                       <?php echo $p === $current_pg ? 'aria-current="page"' : ''; ?>>
                        <?php echo $p; ?>
                    </a>
                    <?php endfor; ?>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="empty-state animate-on-scroll">
                    <div class="empty-state__icon" aria-hidden="true">📰</div>
                    <p class="empty-state__text">No news articles have been published yet. Please check back soon.</p>
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
