<?php
/**
 * resources/index.php — Resources hub (ABOUT-US STYLE)
 * Drop into: resources/index.php
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base = "../";
$nav_active = "resources";
$page_title = "Resources — " . htmlspecialchars($rcs["name"]);

$page_heading = "Resources";
$page_sub =
    "Forms, policies, reports and answers — everything you need in one place.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Resources"],
];

$sidebar_title = "Resources";
$sidebar_items = [
    [
        "label" => "Downloads & Forms",
        "href" => "application-forms.php",
        "active" => false,
    ],
    [
        "label" => "Policies & Bylaws",
        "href" => "policies.php",
        "active" => false,
    ],
    [
        "label" => "Annual Reports",
        "href" => "annual-reports.php",
        "active" => false,
    ],
    ["label" => "FAQs", "href" => "faqs.php", "active" => false],
];

$resource_cards = [
    [
        "href" => "application-forms.php",
        "title" => "Downloads & Forms",
        "desc" =>
            "Membership, loan and savings application forms — all available as downloadable PDFs.",
        "icon" =>
            '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
        "tag" => "PDF Downloads",
    ],
    [
        "href" => "policies.php",
        "title" => "Policies & Bylaws",
        "desc" =>
            "Our governing documents, member policies, bylaws and regulatory compliance materials.",
        "icon" => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        "tag" => "Governance",
    ],
    [
        "href" => "annual-reports.php",
        "title" => "Annual Reports",
        "desc" =>
            "Year-by-year financial performance reports and AGM documents for members.",
        "icon" =>
            '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        "tag" => "Financial",
    ],
    [
        "href" => "faqs.php",
        "title" => "FAQs",
        "desc" =>
            "Answers to common questions about membership, loans, savings and SACCO operations.",
        "icon" =>
            '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        "tag" => "Help",
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
                    <span class="section-tag">Member Resources</span>
                    <h2 class="inner-page__title">Everything you need, in one place</h2>
                    <p class="inner-page__desc">
                        Access application forms, governing documents, financial reports and
                        answers to common questions — all available to members and prospective
                        members at any time.
                    </p>
                </div>

                <!-- Stat strip -->
                <div class="about-hub__stats animate-on-scroll">
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">4</span>
                        <span class="about-hub__stat-label">Resource sections</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">PDF</span>
                        <span class="about-hub__stat-label">All forms</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">Free</span>
                        <span class="about-hub__stat-label">No login required</span>
                    </div>
                    <div class="about-hub__stat-divider" aria-hidden="true"></div>
                    <div class="about-hub__stat">
                        <span class="about-hub__stat-num">24/7</span>
                        <span class="about-hub__stat-label">Always available</span>
                    </div>
                </div>

                <div class="content-divider"></div>

                <!-- Cards -->
                <p class="about-hub__nav-label">Browse resources</p>
                <div class="about-hub__grid">
                    <?php foreach ($resource_cards as $card): ?>
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
                        <h3 class="about-hub__cta-title">Can't find what you need?</h3>
                        <p>Our team is happy to help — reach out and we'll send it directly.</p>
                    </div>
                    <div class="about-hub__cta-actions">
                        <a href="../contacts.php" class="btn-primary">
                            Contact us
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="application-forms.php" class="btn-ghost">Browse forms</a>
                    </div>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>

<style>
/* ── RESOURCES HUB (ABOUT-US STYLE) ─────────────────────────────
   Reuses .about-hub__* classes already defined in about-us/index.php
   (or style.css if migrated). Only new class here is .rh-card__tag.
   ────────────────────────────────────────────────────────────── */
.rh-card__tag {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--color-green-mid);
    margin-bottom: -0.15rem;
}
</style>

</body>
</html>
