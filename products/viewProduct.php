<?php
/**
 * products/viewProduct.php
 * Single product detail page — DB-driven.
 *
 * Handles:
 *  - Missing optional columns gracefully (description, requirements, formPath)
 *  - Download button that links to an actual PDF, not a PHP page
 *  - No empty space when thumbPath is absent
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* ── Validate page ID ────────────────────────────────────────── */
$page_id = isset($_GET["page"]) ? (int) $_GET["page"] : 0;
if (!$page_id) {
    header("Location: loan-products.php");
    exit();
}

/* ── Fetch product ───────────────────────────────────────────── */
$product_qry = $dbc->prepare(
    "SELECT * FROM tbl_products WHERE PID = :pid AND published = '1' LIMIT 1",
);
$product_qry->execute([":pid" => $page_id]);
$product = $product_qry->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: loan-products.php");
    exit();
}

/* ── Determine section ───────────────────────────────────────── */
$is_loan = strtolower($product["menuid"] ?? "") === "loans";
$listing_pg = $is_loan ? "loan-products.php" : "savings-products.php";
$listing_lb = $is_loan ? "Loan Products" : "Savings Products";

/* ── Sibling products for sidebar ────────────────────────────── */
$siblings_qry = $dbc->prepare(
    "SELECT PID, title FROM tbl_products
     WHERE published = '1' AND menuid = :mid
     ORDER BY title ASC",
);
$siblings_qry->execute([":mid" => $product["menuid"]]);
$siblings = $siblings_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── Optional column helper ──────────────────────────────────── */
function col(array $row, string $key): string
{
    if (!isset($row[$key])) {
        return "";
    }
    $v = trim($row[$key]);
    /* Strip tags to check if there's real content (not just HTML whitespace) */
    return trim(strip_tags($v)) === "" ? "" : $v;
}

$description = col($product, "description");
$requirements = col($product, "requirements");
$thumb_path = isset($product["thumbPath"]) ? trim($product["thumbPath"]) : "";

/*
 * ── Download form resolution ──────────────────────────────────
 *
 * Priority:
 *   1. formPath column in DB (if it exists and is non-empty)
 *   2. Title-based mapping to a known PDF in /files/
 *   3. Fall back to resources page — navigation link, no 'download' attribute
 *
 * IMPORTANT: The HTML 'download' attribute must only appear when href is a real
 * file. Never put 'download' on a link to a .php page — the browser will save
 * the HTML source output as a file instead of navigating.
 */
$form_file = col($product, "formPath");
$form_href = "";
$form_is_file = false;

if ($form_file !== "") {
    /* DB has a direct file path */
    $form_href = "../files/" . htmlspecialchars($form_file);
    $form_is_file = true;
} else {
    /* Map product title keywords → known PDF filenames in /files/ */
    $title_lower = strtolower($product["title"]);
    $map = [
        "normal" => "normal-loan-form.pdf",
        "emergency" => "emergency-loan-form.pdf",
        "education" => "education-loan-form.pdf",
        "development" => "development-loan-form.pdf",
        "refinanc" => "refinancing-form.pdf",
        "toto" => "toto-savings-form.pdf",
        "christmas" => "christmas-savings-form.pdf",
        "holiday" => "holiday-package-form.pdf",
        "benevolent" => "benevolent-scheme-form.pdf",
        "deposit" => "membership-form.pdf",
    ];
    foreach ($map as $keyword => $filename) {
        if (strpos($title_lower, $keyword) !== false) {
            $full_path = __DIR__ . "/../files/" . $filename;
            if (file_exists($full_path)) {
                $form_href = "../files/" . $filename;
                $form_is_file = true;
            }
            break;
        }
    }
}

/* Final fallback: navigate to resources page (no download attribute) */
if ($form_href === "") {
    $form_href = "../resources/application-forms.php";
    $form_is_file = false;
}

/* ── Page metadata ───────────────────────────────────────────── */
$nav_base = "../";
$nav_active = "products";
$page_title =
    htmlspecialchars($product["title"]) .
    " — " .
    htmlspecialchars($rcs["name"]);

$page_heading = $is_loan ? "Loan Products" : "Savings Products";
$page_sub = "";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => $listing_lb, "href" => $listing_pg],
    ["label" => $product["title"]],
];

$sidebar_title = $is_loan ? "Loan Products" : "Savings Products";
$sidebar_items = array_map(function ($s) use ($page_id) {
    return [
        "label" => $s["title"],
        "href" => "viewProduct.php?page=" . (int) $s["PID"],
        "active" => (int) $s["PID"] === (int) $page_id,
    ];
}, $siblings);

/* ── Emoji helper ────────────────────────────────────────────── */
function product_emoji(string $title): string
{
    $t = strtolower($title);
    if (strpos($t, "normal") !== false) {
        return "📋";
    }
    if (strpos($t, "emergency") !== false) {
        return "⚡";
    }
    if (strpos($t, "education") !== false) {
        return "🎓";
    }
    if (strpos($t, "development") !== false) {
        return "🏗️";
    }
    if (strpos($t, "refinanc") !== false) {
        return "🔄";
    }
    if (strpos($t, "toto") !== false) {
        return "🌱";
    }
    if (strpos($t, "christmas") !== false) {
        return "🎄";
    }
    if (strpos($t, "holiday") !== false) {
        return "✈️";
    }
    if (strpos($t, "benevolent") !== false) {
        return "🤝";
    }
    if (strpos($t, "deposit") !== false) {
        return "💰";
    }
    return "🏦";
}
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
                <article class="product-detail animate-on-scroll">

                    <!-- Header bar -->
                    <div class="product-detail__header">
                        <div class="product-detail__emoji" aria-hidden="true">
                            <?php echo product_emoji($product["title"]); ?>
                        </div>
                        <div>
                            <span class="section-tag">
                                <?php echo htmlspecialchars(
                                    $product["menuid"],
                                ); ?>
                            </span>
                            <h2 class="product-detail__title">
                                <?php echo htmlspecialchars(
                                    strtoupper($product["title"]),
                                ); ?>
                            </h2>
                        </div>
                    </div>

                    <!-- Thumbnail — only rendered when a path is stored in DB -->
                    <?php if ($thumb_path !== ""): ?>
                    <div class="product-detail__media">
                        <img src="../images/portfolio/<?php echo htmlspecialchars(
                            $thumb_path,
                        ); ?>"
                             alt="<?php echo htmlspecialchars(
                                 $product["title"],
                             ); ?>"
                             class="product-detail__img">
                    </div>
                    <?php endif; ?>

                    <!-- Description body -->
                    <div class="product-detail__body content-prose">
                        <?php if ($description !== ""): ?>
                            <?php echo $description; ?>
                        <?php else: ?>
                            <p>Full details for this product are available on request.
                               Please <a href="../contacts.php">contact us</a> for more information.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Required documents — only if the column exists and has content -->
                    <?php if ($requirements !== ""): ?>
                    <div class="product-detail__requirements">
                        <h3 class="product-detail__req-title">Required Documents</h3>
                        <div class="content-prose"><?php echo $requirements; ?></div>
                    </div>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="product-detail__actions">
                        <a href="<?php echo $form_href; ?>"
                           class="btn-primary"
                           <?php echo $form_is_file ? "download" : ""; ?>>
                            <?php if ($form_is_file): ?>
                                Download Application Form
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            <?php else: ?>
                                View Application Forms
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            <?php endif; ?>
                        </a>

                        <a href="../contacts.php" class="btn-ghost">
                            Enquire Now
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </article>
            </main>

        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>
</body>
</html>
