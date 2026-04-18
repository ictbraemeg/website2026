<?php
/**
 * products/index.php — Products hub (RICH version)
 *
 * Drop into: products/index.php
 * Queries tbl_products to show live product previews under each category.
 * Falls back gracefully if tbl_products is empty.
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* ── Fetch loans (preview up to 4) ──────────────────────────── */
$loans_qry = $dbc->prepare(
    "SELECT PID, title FROM tbl_products
     WHERE published = '1' AND LOWER(menuid) = 'loans'
     ORDER BY title ASC LIMIT 4",
);
$loans_qry->execute();
$loans = $loans_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── Fetch savings (preview up to 4) ────────────────────────── */
$savings_qry = $dbc->prepare(
    "SELECT PID, title FROM tbl_products
     WHERE published = '1' AND LOWER(menuid) = 'savings'
     ORDER BY title ASC LIMIT 4",
);
$savings_qry->execute();
$savings = $savings_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── Emoji helper (same as viewProduct.php) ──────────────────── */
function product_emoji(string $title): string
{
    $t = strtolower($title);
    if (str_contains($t, "normal")) {
        return "📋";
    }
    if (str_contains($t, "emergency")) {
        return "⚡";
    }
    if (str_contains($t, "education")) {
        return "🎓";
    }
    if (str_contains($t, "development")) {
        return "🏗️";
    }
    if (str_contains($t, "refinanc")) {
        return "🔄";
    }
    if (str_contains($t, "toto")) {
        return "🌱";
    }
    if (str_contains($t, "christmas")) {
        return "🎄";
    }
    if (str_contains($t, "holiday")) {
        return "✈️";
    }
    if (str_contains($t, "benevolent")) {
        return "🤝";
    }
    if (str_contains($t, "deposit")) {
        return "💰";
    }
    return "🏦";
}

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

                <!-- Intro -->
                <div class="animate-on-scroll">
                    <span class="section-tag">SASRA Regulated</span>
                    <h2 class="inner-page__title">Financial products built for members</h2>
                    <p class="inner-page__desc">
                        Since 1988, Braemeg SACCO has offered competitive loans and savings
                        products exclusively for international school employees and their families.
                        All products are regulated by SASRA.
                    </p>
                </div>

                <!-- ── LOANS SECTION ─────────────────────────────── -->
                <div class="phr-section animate-on-scroll" role="region" aria-labelledby="loans-heading">
                    <div class="phr-section__head">
                        <div class="phr-section__head-text">
                            <div class="phr-section__badge phr-section__badge--loans">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <line x1="12" y1="1" x2="12" y2="23"/>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                                Loans
                            </div>
                            <h3 class="phr-section__title" id="loans-heading">Loan Products</h3>
                            <p class="phr-section__sub">
                                From everyday expenses to major life milestones — we have a loan for it.
                            </p>
                        </div>
                        <a href="loan-products.php" class="phr-section__link">
                            View all loans
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <?php if (!empty($loans)): ?>
                    <div class="phr-grid">
                        <?php foreach ($loans as $p): ?>
                        <a href="viewProduct.php?page=<?php echo (int) $p[
                            "PID"
                        ]; ?>"
                           class="phr-card">
                            <span class="phr-card__emoji" aria-hidden="true">
                                <?php echo product_emoji($p["title"]); ?>
                            </span>
                            <span class="phr-card__title">
                                <?php echo htmlspecialchars($p["title"]); ?>
                            </span>
                            <span class="phr-card__arrow" aria-hidden="true">→</span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="phr-empty">
                        <a href="loan-products.php" class="btn-ghost">Browse loan products →</a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="content-divider"></div>

                <!-- ── SAVINGS SECTION ───────────────────────────── -->
                <div class="phr-section animate-on-scroll" role="region" aria-labelledby="savings-heading">
                    <div class="phr-section__head">
                        <div class="phr-section__head-text">
                            <div class="phr-section__badge phr-section__badge--savings">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0H5m14 0h2M5 21H3"/>
                                    <path d="M9 21V9h6v12"/>
                                </svg>
                                Savings
                            </div>
                            <h3 class="phr-section__title" id="savings-heading">Savings Products</h3>
                            <p class="phr-section__sub">
                                Build financial security with our range of savings accounts and schemes.
                            </p>
                        </div>
                        <a href="savings-products.php" class="phr-section__link">
                            View all savings
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <?php if (!empty($savings)): ?>
                    <div class="phr-grid">
                        <?php foreach ($savings as $p): ?>
                        <a href="viewProduct.php?page=<?php echo (int) $p[
                            "PID"
                        ]; ?>"
                           class="phr-card phr-card--savings">
                            <span class="phr-card__emoji" aria-hidden="true">
                                <?php echo product_emoji($p["title"]); ?>
                            </span>
                            <span class="phr-card__title">
                                <?php echo htmlspecialchars($p["title"]); ?>
                            </span>
                            <span class="phr-card__arrow" aria-hidden="true">→</span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="phr-empty">
                        <a href="savings-products.php" class="btn-ghost">Browse savings products →</a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="content-divider"></div>

                <!-- ── CTA STRIP ──────────────────────────────────── -->
                <div class="about-hub__cta animate-on-scroll">
                    <div class="about-hub__cta-text">
                        <h3 class="about-hub__cta-title">Not yet a member?</h3>
                        <p>Join Braemeg SACCO to access all products and member-only rates.</p>
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
                        <a href="../contacts.php" class="btn-ghost">Enquire now</a>
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
