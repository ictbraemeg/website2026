<?php
/**
 * blog/post.php — Single blog post
 *
 * Table: tbl_blog_posts
 * Columns: PID, postTitle, postDesc, postContent, postImage,
 *          postDate, published, addedby, dateadded, lastuser, datemodified
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* ── Fetch post ──────────────────────────────────────────────── */
$page_id = isset($_GET["page"]) ? (int) $_GET["page"] : 0;

if (!$page_id) {
    header("Location: index.php");
    exit();
}

$post_qry = $dbc->prepare(
    "SELECT * FROM tbl_blog_posts WHERE PID = :pid AND published = '1' LIMIT 1",
);
$post_qry->execute([":pid" => $page_id]);
$post = $post_qry->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: index.php");
    exit();
}

/* ── Helper ──────────────────────────────────────────────────── */
function pcol(array $row, string $key): string
{
    return isset($row[$key]) ? trim((string) $row[$key]) : "";
}

$title = pcol($post, "postTitle");
$content = pcol($post, "postContent");
$image = pcol($post, "postImage");
$date = pcol($post, "postDate");

/* ── Recent posts for sidebar (excluding current) ────────────── */
$recent_qry = $dbc->prepare(
    "SELECT PID, postTitle, postImage, postDate
     FROM tbl_blog_posts
     WHERE published = '1' AND PID != :pid
     ORDER BY PID DESC
     LIMIT 5",
);
$recent_qry->execute([":pid" => $page_id]);
$recent_posts = $recent_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── Prev / Next navigation ──────────────────────────────────── */
$prev_qry = $dbc->prepare(
    "SELECT PID, postTitle FROM tbl_blog_posts
     WHERE published = '1' AND PID < :pid
     ORDER BY PID DESC LIMIT 1",
);
$prev_qry->execute([":pid" => $page_id]);
$prev_post = $prev_qry->fetch(PDO::FETCH_ASSOC);

$next_qry = $dbc->prepare(
    "SELECT PID, postTitle FROM tbl_blog_posts
     WHERE published = '1' AND PID > :pid
     ORDER BY PID ASC LIMIT 1",
);
$next_qry->execute([":pid" => $page_id]);
$next_post = $next_qry->fetch(PDO::FETCH_ASSOC);

/* ── Page meta ───────────────────────────────────────────────── */
$nav_base = "../";
$nav_active = "blog";
$page_title = htmlspecialchars($title) . " — " . htmlspecialchars($rcs["name"]);

$page_heading = "Blog";
$page_sub = "";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Blog", "href" => "index.php"],
    ["label" => $title],
];
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include "../includes/head.php"; ?></head>
<body>

<?php include "../includes/topbar.php"; ?>
<?php include "../includes/navbar.php"; ?>
<?php include "../includes/page-header.php"; ?>

<div class="inner-page blog-post-page">
    <div class="container">
        <div class="blog-post-layout">

            <!-- ── Main post content ──────────────────────── -->
            <main class="blog-post-main" id="main-content">

                <article class="blog-post animate-on-scroll">

                    <!-- Post header -->
                    <header class="blog-post__header">
                        <?php if ($date !== ""): ?>
                        <div class="blog-post__meta">
                            <time class="blog-card__date">
                                <?php echo htmlspecialchars($date); ?>
                            </time>
                        </div>
                        <?php endif; ?>
                        <h1 class="blog-post__title"><?php echo htmlspecialchars(
                            $title,
                        ); ?></h1>
                    </header>

                    <!-- Featured image -->
                    <?php if ($image !== ""): ?>
                    <div class="blog-post__hero-img">
                        <img src="../images/blog/<?php echo htmlspecialchars(
                            $image,
                        ); ?>"
                             alt="<?php echo htmlspecialchars($title); ?>"
                             class="blog-post__img">
                    </div>
                    <?php endif; ?>

                    <!-- Post body -->
                    <div class="blog-post__body content-prose">
                        <?php echo $content !== ""
                            ? $content
                            : "<p>Content coming soon.</p>"; ?>
                    </div>

                    <!-- Prev / Next navigation -->
                    <div class="blog-post__prevnext">
                        <div class="blog-post__prevnext-item">
                            <?php if ($prev_post): ?>
                            <span class="blog-post__prevnext-label">← Previous</span>
                            <a href="post.php?page=<?php echo (int) $prev_post[
                                "PID"
                            ]; ?>"
                               class="blog-post__prevnext-title">
                                <?php echo htmlspecialchars(
                                    $prev_post["postTitle"],
                                ); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="blog-post__prevnext-item blog-post__prevnext-item--right">
                            <?php if ($next_post): ?>
                            <span class="blog-post__prevnext-label">Next →</span>
                            <a href="post.php?page=<?php echo (int) $next_post[
                                "PID"
                            ]; ?>"
                               class="blog-post__prevnext-title">
                                <?php echo htmlspecialchars(
                                    $next_post["postTitle"],
                                ); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Back / Enquire -->
                    <div class="blog-post__nav">
                        <a href="index.php" class="blog-post__back">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Back to Blog
                        </a>
                        <a href="../contacts.php" class="btn-ghost">Get in Touch</a>
                    </div>

                </article>

            </main>

            <!-- ── Sidebar ────────────────────────────────── -->
            <aside class="blog-post-sidebar" aria-label="Blog sidebar">

                <!-- Recent posts -->
                <?php if (!empty($recent_posts)): ?>
                <div class="blog-sidebar-block">
                    <h3 class="blog-sidebar-block__title">Recent Posts</h3>
                    <ul class="blog-recent-list">
                        <?php foreach ($recent_posts as $rp):

                            $rp_pid = (int) $rp["PID"];
                            $rp_title = isset($rp["postTitle"])
                                ? trim($rp["postTitle"])
                                : "";
                            $rp_date = isset($rp["postDate"])
                                ? trim($rp["postDate"])
                                : "";
                            $rp_img = isset($rp["postImage"])
                                ? trim($rp["postImage"])
                                : "";
                            ?>
                        <li class="blog-recent-item">
                            <?php if ($rp_img !== ""): ?>
                            <a href="post.php?page=<?php echo $rp_pid; ?>"
                               class="blog-recent-item__thumb" tabindex="-1" aria-hidden="true">
                                <img src="../images/blog/<?php echo htmlspecialchars(
                                    $rp_img,
                                ); ?>"
                                     alt="<?php echo htmlspecialchars(
                                         $rp_title,
                                     ); ?>"
                                     loading="lazy">
                            </a>
                            <?php endif; ?>
                            <div class="blog-recent-item__text">
                                <a href="post.php?page=<?php echo $rp_pid; ?>"
                                   class="blog-recent-item__title">
                                    <?php echo htmlspecialchars($rp_title); ?>
                                </a>
                                <?php if ($rp_date !== ""): ?>
                                <time class="blog-card__date"><?php echo htmlspecialchars(
                                    $rp_date,
                                ); ?></time>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php
                        endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- CTA -->
                <div class="blog-sidebar-block blog-sidebar-block--cta">
                    <h3 class="blog-sidebar-block__title">Ready to Join?</h3>
                    <p>Become a member today and start your journey to financial freedom.</p>
                    <a href="../apply.php" class="btn-primary" style="margin-top:1rem;width:100%;justify-content:center;">
                        Apply for Membership
                    </a>
                </div>

            </aside>

        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>
</body>
</html>
