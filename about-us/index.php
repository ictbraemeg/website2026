<?php
/**
 * about-us/index.php — About Us landing page
 *
 * Drop into: about-us/index.php
 * No DB content query needed here — the landing page is structural/editorial.
 * It pulls only the company row (name, slogan) which every page needs.
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base = "../";
$nav_active = "about";
$page_title = "About Us — " . htmlspecialchars($rcs["name"]);

$page_heading = "About Us";
$page_sub =
    "Learn about Braemeg SACCO — our history, membership and community.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "About Us"],
];

$sidebar_title = "About Us";
$sidebar_items = [
    ["label" => "Who We Are", "href" => "who-we-are.php", "active" => false],
    [
        "label" => "Our Vision & Mission",
        "href" => "our-vision-and-mission.php",
        "active" => false,
    ],
    [
        "label" => "Governance Structure",
        "href" => "governance-structure.php",
        "active" => false,
    ],
    ["label" => "Our Growth", "href" => "our-growth.php", "active" => false],
    ["label" => "Gallery", "href" => "gallery.php", "active" => false],
];

/* Sub-page cards shown in the hub */
$about_cards = [
    [
        "href" => "who-we-are.php",
        "title" => "Who We Are",
        "desc" =>
            "Our founding story, member organisations, and the communities we serve across Kenya and the diaspora.",
        "icon" =>
            '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    ],
    [
        "href" => "our-vision-and-mission.php",
        "title" => "Vision & Mission",
        "desc" =>
            "The purpose that drives every product, policy, and decision at Braemeg SACCO.",
        "icon" =>
            '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
    ],
    [
        "href" => "governance-structure.php",
        "title" => "Governance Structure",
        "desc" =>
            "How we are led — our board, management, and compliance with SASRA regulations.",
        "icon" =>
            '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
    ],
    [
        "href" => "our-growth.php",
        "title" => "Our Growth",
        "desc" =>
            "Three decades of growth in assets, membership and financial impact for our members.",
        "icon" =>
            '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
    ],
    [
        "href" => "gallery.php",
        "title" => "Gallery",
        "desc" =>
            "Photos from our events, AGMs, community outreach and member activities.",
        "icon" =>
            '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
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

                <!-- ── INTRO ─────────────────────────────────────── -->
                <div class="about-hub__intro animate-on-scroll">
                    <span class="section-tag">Est. 1988</span>
                    <h2 class="inner-page__title">
                        Serving international school employees<br>
                        for over three decades
                    </h2>
                    <p class="inner-page__desc">
                        Braemeg Regulated Non-WDT Sacco Society Limited was registered in 1988
                        to provide savings and affordable credit to employees of the Braeburn Group
                        of International Schools. Today we serve members across Kenya and the
                        diaspora, regulated by SASRA.
                    </p>
                </div>

                <!-- ── STAT STRIP ─────────────────────────────────── -->
                <div class="about-hub__stats animate-on-scroll">
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">1988</span>
                        <span class="about-hub__stat-label">Year founded</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">14+</span>
                        <span class="about-hub__stat-label">Member schools</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">SASRA</span>
                        <span class="about-hub__stat-label">Regulated</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">5+</span>
                        <span class="about-hub__stat-label">Countries (diaspora)</span>
                    </div>
                </div>

                <div class="content-divider"></div>

                <!-- ── SUB-PAGE CARDS ─────────────────────────────── -->
                <p class="about-hub__nav-label">Explore this section</p>
                <div class="about-hub__grid">
                    <?php foreach ($about_cards as $card): ?>
                    <a href="<?php echo htmlspecialchars($card["href"]); ?>"
                       class="about-hub__card animate-on-scroll">
                        <div class="about-hub__card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <?php echo $card["icon"]; ?>
                            </svg>
                        </div>
                        <h3 class="about-hub__card-title">
                            <?php echo htmlspecialchars($card["title"]); ?>
                        </h3>
                        <p class="about-hub__card-desc">
                            <?php echo htmlspecialchars($card["desc"]); ?>
                        </p>
                        <span class="about-hub__card-arrow" aria-hidden="true">
                            Read more
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

                <!-- ── CTA STRIP ──────────────────────────────────── -->
                <div class="about-hub__cta animate-on-scroll">
                    <div class="about-hub__cta-text">
                        <h3 class="about-hub__cta-title">Ready to join?</h3>
                        <p>Membership is open to employees of international schools and their families.</p>
                    </div>
                    <div class="about-hub__cta-actions">
                        <a href="../apply.php" class="btn-primary">
                            Apply for membership
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

</body>
</html>
