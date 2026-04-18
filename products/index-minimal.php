<?php
/**
 * products/index.php — Products hub (MINIMAL version)
 *
 * Drop into: products/index.php
 * No DB product query needed — this page routes to the two listing pages.
 * Pulls only the company row shared by every page.
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base = "../";
$nav_active = "products";
$page_title = "Products — " . htmlspecialchars($rcs["name"]);

$page_heading = "Our Products";
$page_sub = "Affordable loans and savings products designed for our members.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Products"],
];

$sidebar_title = "Products";
$sidebar_items = [
    [
        "label" => "Loan Products",
        "href" => "loan-products.php",
        "active" => false,
    ],
    [
        "label" => "Savings Products",
        "href" => "savings-products.php",
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

                <div class="animate-on-scroll">
                    <span class="section-tag">SASRA Regulated</span>
                    <h2 class="inner-page__title">Financial products built for members</h2>
                    <p class="inner-page__desc">
                        Braemeg SACCO offers a range of loan and savings products tailored
                        to the needs of international school employees and their families.
                        Select a category below to explore what's available.
                    </p>
                </div>

                <!-- Two-column category chooser -->
                <div class="ph-split animate-on-scroll">

                    <a href="loan-products.php" class="ph-split__card ph-split__card--loans">
                        <div class="ph-split__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <h3 class="ph-split__title">Loan Products</h3>
                        <p class="ph-split__desc">
                            Normal, emergency, education, development and refinancing loans
                            with competitive rates for members.
                        </p>
                        <span class="ph-split__cta">
                            View loan products
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </a>

                    <a href="savings-products.php" class="ph-split__card ph-split__card--savings">
                        <div class="ph-split__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0H5m14 0h2M5 21H3"/>
                                <path d="M9 21V9h6v12"/>
                            </svg>
                        </div>
                        <h3 class="ph-split__title">Savings Products</h3>
                        <p class="ph-split__desc">
                            Toto savings, Christmas savings, holiday packages, deposit accounts
                            and the benevolent scheme.
                        </p>
                        <span class="ph-split__cta">
                            View savings products
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </a>

                </div>

                <div class="content-divider"></div>

                <!-- Lightweight reassurance strip -->
                <div class="ph-trust animate-on-scroll">
                    <div class="ph-trust__item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span>SASRA regulated</span>
                    </div>
                    <div class="ph-trust__item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>Quick processing</span>
                    </div>
                    <div class="ph-trust__item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Members-only rates</span>
                    </div>
                    <div class="ph-trust__item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                        <span>Downloadable forms</span>
                    </div>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>

<style>
/* ── PRODUCTS HUB (MINIMAL) — move to style.css once confirmed ── */

/* Two-card split */
.ph-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin: 2rem 0 2.5rem;
}

.ph-split__card {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    border-radius: var(--radius-md);
    padding: 2.25rem 2rem;
    text-decoration: none;
    color: var(--color-text);
    border: 1px solid var(--color-border);
    background: var(--color-off-white);
    position: relative;
    overflow: hidden;
    transition:
        transform var(--transition),
        box-shadow var(--transition),
        border-color var(--transition);
}

.ph-split__card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    transition: transform var(--transition);
    transform: scaleX(0);
    transform-origin: left;
}

.ph-split__card--loans::after {
    background: linear-gradient(90deg, var(--color-green-deep), var(--color-green-bright));
}

.ph-split__card--savings::after {
    background: linear-gradient(90deg, var(--color-gold), #f5c842);
}

.ph-split__card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-green-mid);
    color: var(--color-text);
}

.ph-split__card:hover::after {
    transform: scaleX(1);
}

.ph-split__icon {
    width: 52px;
    height: 52px;
    background: var(--color-green-light);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ph-split__card--savings .ph-split__icon {
    background: #fef9e7;
}

.ph-split__icon svg {
    width: 24px;
    height: 24px;
    stroke: var(--color-green-mid);
}

.ph-split__card--savings .ph-split__icon svg {
    stroke: var(--color-gold);
}

.ph-split__title {
    font-family: var(--font-display);
    font-size: 1.3rem;
    color: var(--color-dark);
    margin: 0;
}

.ph-split__desc {
    font-size: 0.88rem;
    color: var(--color-text-muted);
    line-height: 1.7;
    flex: 1;
}

.ph-split__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-green-mid);
    margin-top: auto;
    transition: gap var(--transition);
}

.ph-split__cta svg {
    width: 14px;
    height: 14px;
    transition: transform var(--transition);
}

.ph-split__card:hover .ph-split__cta {
    gap: 0.7rem;
}

.ph-split__card:hover .ph-split__cta svg {
    transform: translateX(3px);
}

/* Trust strip */
.ph-trust {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.ph-trust__item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.83rem;
    font-weight: 500;
    color: var(--color-text-muted);
    padding: 0.75rem 0;
}

.ph-trust__item svg {
    width: 18px;
    height: 18px;
    stroke: var(--color-green-mid);
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 640px) {
    .ph-split { grid-template-columns: 1fr; }
    .ph-trust { grid-template-columns: 1fr 1fr; }
}
</style>

</body>
</html>
