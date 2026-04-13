<?php
/**
 * blog/index.php — Blog listing page
 *
 * Table: tbl_blog_posts
 * Columns: PID, postTitle, postDesc, postContent, postImage,
 *          postDate, published, addedby, dateadded, lastuser, datemodified
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* ── Pagination ──────────────────────────────────────────────── */
$per_page = 9;
$current_pg = max(1, (int) ($_GET["pg"] ?? 1));
$offset = ($current_pg - 1) * $per_page;

/* ── Total count ─────────────────────────────────────────────── */
$count_qry = $dbc->prepare(
    "SELECT COUNT(*) FROM tbl_blog_posts WHERE published='1'",
);
$count_qry->execute();
$total = (int) $count_qry->fetchColumn();
$total_pages = max(1, (int) ceil($total / $per_page));

/* ── Posts ───────────────────────────────────────────────────── */
$posts_qry = $dbc->prepare(
    "SELECT * FROM tbl_blog_posts
     WHERE published = '1'
     ORDER BY PID DESC
     LIMIT :lim OFFSET :off",
);
$posts_qry->bindValue(":lim", $per_page, PDO::PARAM_INT);
$posts_qry->bindValue(":off", $offset, PDO::PARAM_INT);
$posts_qry->execute();
$posts = $posts_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── Helpers ─────────────────────────────────────────────────── */
function bcol(array $row, string $key): string
{
    return isset($row[$key]) ? trim((string) $row[$key]) : "";
}

/* Use postDesc as excerpt; fall back to truncated postContent */
function blog_excerpt(array $post, int $max = 130): string
{
    $desc = bcol($post, "postDesc");
    if ($desc !== "") {
        return $desc;
    }
    $body = strip_tags(bcol($post, "postContent"));
    return mb_strlen($body) > $max ? mb_substr($body, 0, $max) . "…" : $body;
}

/* ── Page meta ───────────────────────────────────────────────── */
$nav_base = "../";
$nav_active = "blog";
$page_title = "Blog — " . htmlspecialchars($rcs["name"]);

$page_heading = "Blog";
$page_sub = "News, insights and updates from the Braemeg SACCO community.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Blog"],
];

$sidebar_title = "Blog";
$sidebar_items = [
    ["label" => "All Posts", "href" => "index.php", "active" => true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include "../includes/head.php"; ?></head>
<body>

<?php include "../includes/topbar.php"; ?>
<?php include "../includes/navbar.php"; ?>
<?php include "../includes/page-header.php"; ?>

<div class="inner-page">
    <div class="container">
        <div class="inner-page__layout">

            <?php include "../includes/section-sidebar.php"; ?>

            <main class="inner-page__content" id="main-content">

                <div class="blog-listing-header animate-on-scroll">
                    <span class="section-tag">All Posts</span>
                    <h2 class="inner-page__title">Latest from the Blog</h2>
                    <?php if ($total > 0): ?>
                    <p class="blog-listing-header__count">
                        <?php echo $total; ?> <?php echo $total === 1
     ? "post"
     : "posts"; ?>
                    </p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($posts)): ?>
                <div class="blog-grid">
                    <?php foreach ($posts as $i => $post):

                        $pid = (int) $post["PID"];
                        $title = bcol($post, "postTitle");
                        $excerpt = blog_excerpt($post);
                        $image = bcol($post, "postImage");
                        $date = bcol($post, "postDate");
                        $is_featured = $i === 0 && $current_pg === 1;
                        ?>
                    <article class="blog-card <?php echo $is_featured
                        ? "blog-card--featured"
                        : ""; ?> animate-on-scroll">

                        <?php if ($image !== ""): ?>
                        <a href="post.php?page=<?php echo $pid; ?>"
                           class="blog-card__thumb" tabindex="-1" aria-hidden="true">
                            <img src="../images/blog/<?php echo htmlspecialchars(
                                $image,
                            ); ?>"
                                 alt="<?php echo htmlspecialchars($title); ?>"
                                 class="blog-card__img"
                                 loading="lazy">
                        </a>
                        <?php endif; ?>

                        <div class="blog-card__body">

                            <?php if ($date !== ""): ?>
                            <div class="blog-card__meta">
                                <time class="blog-card__date">
                                    <?php echo htmlspecialchars($date); ?>
                                </time>
                            </div>
                            <?php endif; ?>

                            <h3 class="blog-card__title">
                                <a href="post.php?page=<?php echo $pid; ?>">
                                    <?php echo htmlspecialchars($title); ?>
                                </a>
                            </h3>

                            <?php if ($excerpt !== ""): ?>
                            <p class="blog-card__excerpt">
                                <?php echo htmlspecialchars($excerpt); ?>
                            </p>
                            <?php endif; ?>

                            <div class="blog-card__footer">
                                <a href="post.php?page=<?php echo $pid; ?>" class="blog-card__read-more">
                                    Read more
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            </div>

                        </div>
                    </article>
                    <?php
                    endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav class="pagination" aria-label="Blog page navigation">
                    <?php if ($current_pg > 1): ?>
                    <a href="?pg=<?php echo $current_pg - 1; ?>"
                       class="pagination__btn" aria-label="Previous page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    </a>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <a href="?pg=<?php echo $p; ?>"
                       class="pagination__btn <?php echo $p === $current_pg
                           ? "pagination__btn--active"
                           : ""; ?>"
                       <?php echo $p === $current_pg
                           ? 'aria-current="page"'
                           : ""; ?>>
                        <?php echo $p; ?>
                    </a>
                    <?php endfor; ?>

                    <?php if ($current_pg < $total_pages): ?>
                    <a href="?pg=<?php echo $current_pg + 1; ?>"
                       class="pagination__btn" aria-label="Next page">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="empty-state animate-on-scroll">
                    <div class="empty-state__icon" aria-hidden="true">✍️</div>
                    <p class="empty-state__text">No posts have been published yet. Check back soon.</p>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>
</body>
</html>
