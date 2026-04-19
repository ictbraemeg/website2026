<?php
/**
 * information-center/index.php — Information Center hub (CARDS STYLE)
 * Drop into: information-center/index.php
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

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

$cards = [
    [
        "href" => "general-information.php",
        "title" => "General Information",
        "desc" =>
            "Notices, circulars and informational articles published for Braemeg SACCO members.",
        "icon" =>
            '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        "tag" => "Notices & Circulars",
    ],
    [
        "href" => "news-updates.php",
        "title" => "News & Updates",
        "desc" =>
            "The latest announcements, events and news from Braemeg SACCO and the wider SACCO sector.",
        "icon" =>
            '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/>',
        "tag" => "Latest News",
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
                        Braemeg SACCO. Select a section below to get started.
                    </p>
                </div>

                <!-- Stat strip -->
                <div class="about-hub__stats animate-on-scroll">
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">2</span>
                        <span class="about-hub__stat-label">Sections</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">Live</span>
                        <span class="about-hub__stat-label">Updated regularly</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">Free</span>
                        <span class="about-hub__stat-label">Open access</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">24/7</span>
                        <span class="about-hub__stat-label">Always available</span>
                    </div>
                </div>

                <div class="content-divider"></div>

                <!-- Cards -->
                <p class="about-hub__nav-label">Browse sections</p>
                <div class="about-hub__grid">
                    <?php foreach ($cards as $card): ?>
                    <a href="<?php echo htmlspecialchars($card["href"]); ?>"
                       class="about-hub__card animate-on-scroll">
                        <div class="about-hub__card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <?php echo $card["icon"]; ?>
                            </svg>
                        </div>
                        <div class="rh-card__tag"><?php echo htmlspecialchars(
                            $card["tag"],
                        ); ?></div>
                        <h3 class="about-hub__card-title"><?php echo htmlspecialchars(
                            $card["title"],
                        ); ?></h3>
                        <p class="about-hub__card-desc"><?php echo htmlspecialchars(
                            $card["desc"],
                        ); ?></p>
                        <span class="about-hub__card-arrow" aria-hidden="true">
                            View section
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </a>
                    <?php endforeach; ?>
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
/* Reuses .about-hub__* and .rh-card__tag — already defined in about-us/index.php.
   No new CSS needed here once those are in style.css. */
</style>

</body>
</html>
