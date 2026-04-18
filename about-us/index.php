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

<style>
/* ── ABOUT HUB — scoped styles ───────────────────────────────────
   These live here temporarily. Once confirmed, move the block
   into css/style.css (after the .inner-page section, around line 2090).
   ────────────────────────────────────────────────────────────── */

/* Stat strip */
.about-hub__stats {
    display: flex;
    align-items: center;
    gap: 0;
    background: var(--color-off-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: 1.75rem 2rem;
    margin: 2rem 0 2.5rem;
    flex-wrap: wrap;
}

.about-hub__stat {
    flex: 1;
    min-width: 100px;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    text-align: center;
}

.about-hub__stat-num {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 2.5vw, 1.8rem);
    font-weight: 700;
    color: var(--color-green-deep);
    line-height: 1.1;
}

.about-hub__stat-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.about-hub__stat-divider {
    width: 1px;
    height: 40px;
    background: var(--color-border);
    flex-shrink: 0;
    margin: 0 1rem;
}

/* Nav label */
.about-hub__nav-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--color-text-muted);
    margin-bottom: 1.25rem;
}

/* Card grid */
.about-hub__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 0.5rem;
}

/* Individual card */
.about-hub__card {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    background: var(--color-off-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    padding: 1.75rem;
    color: var(--color-text);
    text-decoration: none;
    position: relative;
    overflow: hidden;
    transition:
        box-shadow var(--transition),
        transform var(--transition),
        border-color var(--transition);
}

.about-hub__card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--color-green-mid), var(--color-green-bright));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform var(--transition);
}

.about-hub__card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-3px);
    border-color: var(--color-green-mid);
    color: var(--color-text);
}

.about-hub__card:hover::before {
    transform: scaleX(1);
}

.about-hub__card-icon {
    width: 48px;
    height: 48px;
    background: var(--color-green-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.about-hub__card-icon svg {
    width: 22px;
    height: 22px;
    stroke: var(--color-green-mid);
}

.about-hub__card-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    color: var(--color-dark);
    line-height: 1.25;
    margin: 0;
}

.about-hub__card-desc {
    font-size: 0.87rem;
    color: var(--color-text-muted);
    line-height: 1.65;
    flex: 1;
}

.about-hub__card-arrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--color-green-mid);
    margin-top: auto;
    transition: gap var(--transition), color var(--transition);
}

.about-hub__card-arrow svg {
    width: 14px;
    height: 14px;
    transition: transform var(--transition);
}

.about-hub__card:hover .about-hub__card-arrow {
    gap: 0.65rem;
    color: var(--color-green-deep);
}

.about-hub__card:hover .about-hub__card-arrow svg {
    transform: translateX(3px);
}

/* CTA strip */
.about-hub__cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    background: linear-gradient(135deg, var(--color-green-deep), var(--color-green-mid));
    border-radius: var(--radius-md);
    padding: 2rem 2.5rem;
    flex-wrap: wrap;
}

.about-hub__cta-text {
    color: rgba(255,255,255,0.9);
}

.about-hub__cta-title {
    font-family: var(--font-display);
    font-size: 1.3rem;
    color: var(--color-white);
    margin-bottom: 0.25rem;
}

.about-hub__cta-text p {
    font-size: 0.88rem;
    color: rgba(255,255,255,0.65);
    font-weight: 300;
}

.about-hub__cta-actions {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .about-hub__grid {
        grid-template-columns: 1fr;
    }
    .about-hub__stats {
        gap: 1.25rem;
    }
    .about-hub__stat-divider {
        display: none;
    }
    .about-hub__stat {
        text-align: left;
        flex: 0 0 calc(50% - 0.625rem);
    }
}

@media (max-width: 480px) {
    .about-hub__cta {
        flex-direction: column;
        align-items: flex-start;
        padding: 1.5rem;
    }
    .about-hub__stat {
        flex: 0 0 100%;
    }
}
</style>

</body>
</html>
