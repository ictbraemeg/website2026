<?php
/**
 * information-center/index.php — Information Center hub (RICH STYLE)
 * Drop into: information-center/index.php
 *
 * Previews:
 *   - 3 most recent rows from tbl_resources  → General Information
 *   - 3 most recent rows from tbl_news       → News & Updates
 *
 * Note: tbl_resources uses `dateadded` (VARCHAR), tbl_news uses `created_at`.
 * Both ordered by PID DESC as the safest fallback if date fields are inconsistent.
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* ── General information preview (tbl_resources) ─────────────── */
$info_qry = $dbc->prepare(
    "SELECT PID, title, dateadded, imagePath FROM tbl_resources
     WHERE published = '1'
     ORDER BY PID DESC LIMIT 3",
);
$info_qry->execute();
$info_items = $info_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── News preview (tbl_blog_posts) ───────────────────────────── */
$news_qry = $dbc->prepare(
    "SELECT PID, postTitle, postDate, postImage FROM tbl_blog_posts
     WHERE published = '1'
     ORDER BY postDate DESC LIMIT 3",
);
$news_qry->execute();
$news_items = $news_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base = "../";
$nav_active = "info";
$page_title = "Information Center — " . htmlspecialchars($rcs["name"]);

$page_heading = "Information Center";
$page_sub = "News, notices and general information for Braemeg SACCO members.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Information Center"],
];

$sidebar_title = "Information Center";
$sidebar_items = [
    [
        "label" => "General Information",
        "href" => "general-information.php",
        "active" => false,
    ],
    [
        "label" => "News & Updates",
        "href" => "news-updates.php",
        "active" => false,
    ],
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

                <!-- Intro -->
                <div class="animate-on-scroll">
                    <span class="section-tag">Stay Informed</span>
                    <h2 class="inner-page__title">Member Information Center</h2>
                    <p class="inner-page__desc">
                        Keep up with announcements, notices and the latest news from
                        Braemeg SACCO. Showing the most recent items from each section.
                    </p>
                </div>

                <!-- ── GENERAL INFORMATION PREVIEW ───────────────── -->
                <div class="rhr-section animate-on-scroll">
                    <div class="rhr-section__head">
                        <div class="rhr-section__head-text">
                            <div class="rhr-section__badge rhr-section__badge--green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </div>
                            <h3 class="rhr-section__title">General Information</h3>
                            <p class="rhr-section__sub">Notices, circulars and informational articles for members.</p>
                        </div>
                        <a href="general-information.php" class="phr-section__link">
                            View all
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <?php if (!empty($info_items)): ?>
                    <div class="ic-preview-grid">
                        <?php foreach ($info_items as $item): ?>
                        <a href="general-information.php?page=<?php echo (int) $item[
                            "PID"
                        ]; ?>"
                           class="ic-preview-card">
                            <?php if (
                                !empty($item["imagePath"]) &&
                                $item["imagePath"] !== "default.jpg"
                            ): ?>
                            <div class="ic-preview-card__thumb">
                                <img src="../images/gallery/<?php echo htmlspecialchars(
                                    $item["imagePath"],
                                ); ?>"
                                     alt=""
                                     loading="lazy">
                            </div>
                            <?php else: ?>
                            <div class="ic-preview-card__thumb ic-preview-card__thumb--placeholder" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                            <div class="ic-preview-card__body">
                                <?php if (!empty($item["dateadded"])): ?>
                                <time class="ic-preview-card__date">
                                    <?php
                                    $ts = strtotime($item["dateadded"]);
                                    echo $ts
                                        ? date("d M Y", $ts)
                                        : htmlspecialchars($item["dateadded"]);
                                    ?>
                                </time>
                                <?php endif; ?>
                                <h4 class="ic-preview-card__title">
                                    <?php echo htmlspecialchars(
                                        $item["title"],
                                    ); ?>
                                </h4>
                                <span class="ic-preview-card__link" aria-hidden="true">
                                    Read more →
                                </span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="rhr-empty">
                        <a href="general-information.php" class="btn-ghost">
                            Browse general information →
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="content-divider"></div>

                <!-- ── NEWS & UPDATES PREVIEW ─────────────────────── -->
                <div class="rhr-section animate-on-scroll">
                    <div class="rhr-section__head">
                        <div class="rhr-section__head-text">
                            <div class="rhr-section__badge rhr-section__badge--gold">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                    <path d="M18 14h-8"/><path d="M15 18h-5"/>
                                    <path d="M10 6h8v4h-8V6z"/>
                                </svg>
                            </div>
                            <h3 class="rhr-section__title">News &amp; Updates</h3>
                            <p class="rhr-section__sub">The latest announcements and news from Braemeg SACCO.</p>
                        </div>
                        <a href="news-updates.php" class="phr-section__link">
                            View all news
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <?php if (!empty($news_items)): ?>
                    <div class="ic-preview-grid">
                        <?php foreach ($news_items as $item): ?>
                        <a href="news-updates.php?page=<?php echo (int) $item[
                            "PID"
                        ]; ?>"
                           class="ic-preview-card ic-preview-card--news">
                            <?php if (!empty($item["postImage"])): ?>
                            <div class="ic-preview-card__thumb">
                                <img src="../images/gallery/<?php echo htmlspecialchars(
                                    $item["postImage"],
                                ); ?>"
                                     alt=""
                                     loading="lazy">
                            </div>
                            <?php else: ?>
                            <div class="ic-preview-card__thumb ic-preview-card__thumb--placeholder" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                    <path d="M18 14h-8"/><path d="M15 18h-5"/>
                                    <path d="M10 6h8v4h-8V6z"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                            <div class="ic-preview-card__body">
                                <?php if (!empty($item["postDate"])): ?>
                                <time class="ic-preview-card__date"
                                      datetime="<?php echo htmlspecialchars(
                                          $item["postDate"],
                                      ); ?>">
                                    <?php
                                    $ts = strtotime($item["postDate"]);
                                    echo $ts
                                        ? date("d M Y", $ts)
                                        : htmlspecialchars($item["postDate"]);
                                    ?>
                                </time>
                                <?php endif; ?>
                                <h4 class="ic-preview-card__title">
                                    <?php echo htmlspecialchars(
                                        $item["postTitle"],
                                    ); ?>
                                </h4>
                                <span class="ic-preview-card__link" aria-hidden="true">
                                    Read more →
                                </span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="rhr-empty">
                        <a href="news-updates.php" class="btn-ghost">
                            Browse news &amp; updates →
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="content-divider"></div>

                <!-- CTA -->
                <div class="about-hub__cta animate-on-scroll">
                    <div class="about-hub__cta-text">
                        <h3 class="about-hub__cta-title">Want to stay updated?</h3>
                        <p>Subscribe to our newsletter to receive news and notices directly to your inbox.</p>
                    </div>
                    <div class="about-hub__cta-actions">
                        <a href="../apply.php" class="btn-primary">
                            Join &amp; Subscribe
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="../contacts.php" class="btn-ghost">Contact us</a>
                    </div>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>

<style>
/* ── INFORMATION CENTER HUB (RICH) — move to style.css once confirmed ──
   Reuses: .rhr-section, .rhr-section__head, .rhr-section__badge,
           .rhr-section__title, .rhr-section__sub, .phr-section__link,
           .rhr-empty, .about-hub__cta  (all defined in resources/products hubs).
   New classes: .ic-preview-* only.
   ────────────────────────────────────────────────────────────────────── */

/* 3-column preview card grid */
.ic-preview-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

/* Preview card */
.ic-preview-card {
    display: flex;
    flex-direction: column;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    overflow: hidden;
    text-decoration: none;
    color: var(--color-text);
    background: var(--color-off-white);
    transition:
        box-shadow var(--transition),
        transform var(--transition),
        border-color var(--transition);
}

.ic-preview-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
    border-color: var(--color-green-mid);
    color: var(--color-text);
}

.ic-preview-card--news:hover {
    border-color: var(--color-gold);
}

/* Thumbnail */
.ic-preview-card__thumb {
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: var(--color-green-light);
    flex-shrink: 0;
}

.ic-preview-card__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.ic-preview-card:hover .ic-preview-card__thumb img {
    transform: scale(1.04);
}

.ic-preview-card__thumb--placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
}

.ic-preview-card__thumb--placeholder svg {
    width: 32px;
    height: 32px;
    stroke: var(--color-green-mid);
    opacity: 0.5;
}

.ic-preview-card--news .ic-preview-card__thumb {
    background: #fef9e7;
}

.ic-preview-card--news .ic-preview-card__thumb--placeholder svg {
    stroke: var(--color-gold);
}

/* Card body */
.ic-preview-card__body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    flex: 1;
}

.ic-preview-card__date {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.ic-preview-card__title {
    font-family: var(--font-display);
    font-size: 0.95rem;
    color: var(--color-dark);
    line-height: 1.35;
    margin: 0;
    flex: 1;
    /* Clamp to 3 lines */
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ic-preview-card__link {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--color-green-mid);
    margin-top: 0.25rem;
    transition: color var(--transition);
}

.ic-preview-card--news .ic-preview-card__link {
    color: var(--color-gold);
}

.ic-preview-card:hover .ic-preview-card__link {
    color: var(--color-green-deep);
}

/* Responsive */
@media (max-width: 768px) {
    .ic-preview-grid { grid-template-columns: 1fr; }
}

@media (min-width: 480px) and (max-width: 768px) {
    .ic-preview-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

</body>
</html>
