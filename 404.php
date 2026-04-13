<?php
/**
 * 404.php — Page Not Found
 */
http_response_code(404);
require_once 'config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '';
$nav_active = '';
$page_title = 'Page Not Found — ' . htmlspecialchars($rcs['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="error-page-section">
    <div class="container">
        <div class="error-page-card">
            <div class="error-page-code" aria-hidden="true">404</div>
            <h1 class="error-page-title">Page Not Found</h1>
            <p class="error-page-desc">
                The page you're looking for doesn't exist or may have been moved.
                Try navigating from the menu above, or use the links below.
            </p>
            <div class="error-page-actions">
                <a href="index.php" class="btn-primary">
                    Go to Homepage
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="contacts.php" class="btn-ghost">Contact Us</a>
            </div>
            <nav class="error-page-links" aria-label="Quick navigation">
                <a href="about-us/who-we-are.php">About Us</a>
                <a href="products/loan-products.php">Loan Products</a>
                <a href="products/savings-products.php">Savings Products</a>
                <a href="resources/faqs.php">FAQs</a>
            </nav>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/main.js"></script>
</body>
</html>
