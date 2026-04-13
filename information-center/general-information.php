<?php
/**
 * information-center/general-information.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* Paginate resources */
$per_page    = 10;
$current_pg  = max(1, (int)($_GET['pg'] ?? 1));
$offset      = ($current_pg - 1) * $per_page;

$total_qry = $dbc->prepare("SELECT COUNT(*) FROM tbl_resources WHERE published='1'");
$total_qry->execute();
$total = (int)$total_qry->fetchColumn();
$total_pages = (int)ceil($total / $per_page);

$resources_qry = $dbc->prepare(
    "SELECT * FROM tbl_resources WHERE published='1' ORDER BY created_at DESC LIMIT :limit OFFSET :offset"
);
$resources_qry->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$resources_qry->bindValue(':offset', $offset,   PDO::PARAM_INT);
$resources_qry->execute();
$resources = $resources_qry->fetchAll(PDO::FETCH_ASSOC);

/* Single article view */
$view_id = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$article = null;
if ($view_id) {
    $art_qry = $dbc->prepare("SELECT * FROM tbl_resources WHERE PID = :pid AND published='1' LIMIT 1");
    $art_qry->execute([':pid' => $view_id]);
    $article = $art_qry->fetch(PDO::FETCH_ASSOC);
}

$nav_base   = '../';
$nav_active = 'info';
$page_title = ($article ? htmlspecialchars($article['title']) . ' — ' : 'General Information — ') . htmlspecialchars($rcs['name']);

$page_heading = 'Information Center';
$page_sub     = 'News, notices and general information for members.';
$breadcrumbs  = [
    ['label' => 'Home',               'href' => '../index.php'],
    ['label' => 'Information Center', 'href' => 'general-information.php'],
    ['label' => $article ? $article['title'] : 'General Information'],
];

$sidebar_title = 'Information Center';
$sidebar_items = [
    ['label' => 'General Information', 'href' => 'general-information.php', 'active' => !$article],
    ['label' => 'News & Updates',      'href' => 'news-updates.php'],
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

                <?php if ($article): ?>
                <!-- ── SINGLE ARTICLE VIEW ── -->
                <article class="article-detail animate-on-scroll">
                    <a href="general-information.php" class="article-back-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                        Back to Information Center
                    </a>
                    <span class="section-tag">General Information</span>
                    <h2 class="article-detail__title"><?php echo htmlspecialchars($article['title']); ?></h2>
                    <?php if (!empty($article['created_at'])): ?>
                    <time class="article-detail__date" datetime="<?php echo $article['created_at']; ?>">
                        <?php echo date('d F Y', strtotime($article['created_at'])); ?>
                    </time>
                    <?php endif; ?>
                    <?php if (!empty($article['imagePath'])): ?>
                    <img src="../images/gallery/<?php echo htmlspecialchars($article['imagePath']); ?>"
                         alt="<?php echo htmlspecialchars($article['title']); ?>"
                         class="article-detail__img">
                    <?php endif; ?>
                    <div class="content-prose article-detail__body">
                        <?php echo $article['body'] ?? $article['description'] ?? '<p>Content coming soon.</p>'; ?>
                    </div>
                </article>

                <?php else: ?>
                <!-- ── LISTING VIEW ── -->
                <div class="animate-on-scroll">
                    <span class="section-tag">Updates</span>
                    <h2 class="inner-page__title">General Information</h2>
                </div>

                <?php if (!empty($resources)): ?>
                <div class="articles-list">
                    <?php foreach ($resources as $res): ?>
                    <article class="article-card animate-on-scroll">
                        <?php if (!empty($res['imagePath'])): ?>
                        <div class="article-card__thumb">
                            <img src="../images/gallery/<?php echo htmlspecialchars($res['imagePath']); ?>"
                                 alt="<?php echo htmlspecialchars($res['title']); ?>"
                                 loading="lazy">
                        </div>
                        <?php endif; ?>
                        <div class="article-card__body">
                            <h3 class="article-card__title">
                                <a href="general-information.php?page=<?php echo (int)$res['PID']; ?>">
                                    <?php echo htmlspecialchars($res['title']); ?>
                                </a>
                            </h3>
                            <?php if (!empty($res['created_at'])): ?>
                            <time class="article-card__date" datetime="<?php echo $res['created_at']; ?>">
                                <?php echo date('d F Y', strtotime($res['created_at'])); ?>
                            </time>
                            <?php endif; ?>
                            <p class="article-card__excerpt">
                                <?php echo htmlspecialchars(substr(strip_tags($res['body'] ?? $res['description'] ?? ''), 0, 160)); ?>…
                            </p>
                            <a href="general-information.php?page=<?php echo (int)$res['PID']; ?>" class="article-card__link">
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
                    <p class="empty-state__text">No information articles have been published yet. Please check back soon.</p>
                </div>
                <?php endif; ?>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
