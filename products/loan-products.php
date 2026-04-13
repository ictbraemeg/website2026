<?php
/**
 * products/loan-products.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$loans_qry = $dbc->prepare(
    "SELECT * FROM tbl_products WHERE published='1' AND menuid='Loans' ORDER BY title ASC"
);
$loans_qry->execute();
$loans = $loans_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'products';
$page_title = 'Loan Products — ' . htmlspecialchars($rcs['name']);

$page_heading = 'Our Loan Products';
$page_sub     = 'Affordable credit solutions designed around your needs.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'Products', 'href' => 'loan-products.php'],
    ['label' => 'Loan Products'],
];

$sidebar_title = 'Products';
$sidebar_items = [
    ['label' => 'Loan Products',    'href' => 'loan-products.php',    'active' => true],
    ['label' => 'Savings Products', 'href' => 'savings-products.php'],
];

/* Emoji map for loan types */
$loan_emojis = [
    'normal'      => '📋',
    'emergency'   => '⚡',
    'education'   => '🎓',
    'development' => '🏗️',
    'refinanc'    => '🔄',
];

function get_loan_emoji($title) {
    global $loan_emojis;
    $lower = strtolower($title);
    foreach ($loan_emojis as $key => $emoji) {
        if (strpos($lower, $key) !== false) { return $emoji; }
    }
    return '🏦';
}
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include '../includes/head.php'; ?></head>
<body>

<?php include '../includes/topbar.php'; ?>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/page-header.php'; ?>

<div class="inner-page">
    <div class="container">
        <div class="inner-page__layout">

            <?php include '../includes/section-sidebar.php'; ?>

            <main class="inner-page__content" id="main-content">

                <div class="animate-on-scroll">
                    <span class="section-tag">Borrow</span>
                    <h2 class="inner-page__title">Our Loan Products</h2>
                    <p class="inner-page__desc">We offer a range of loan products at competitive interest rates. All loans are processed within 30 days upon receipt of a correctly completed application with all required attachments.</p>
                </div>

                <?php if (!empty($loans)): ?>
                <div class="products-listing">
                    <?php foreach ($loans as $loan): ?>
                    <article class="product-listing-card animate-on-scroll">
                        <div class="product-listing-card__icon" aria-hidden="true">
                            <?php echo get_loan_emoji($loan['title']); ?>
                        </div>
                        <div class="product-listing-card__body">
                            <h3 class="product-listing-card__title"><?php echo htmlspecialchars($loan['title']); ?></h3>
                            <div class="product-listing-card__desc">
                                <?php echo htmlspecialchars(substr(strip_tags($loan['description'] ?? ''), 0, 180)); ?>…
                            </div>
                        </div>
                        <a href="viewProduct.php?page=<?php echo (int)$loan['PID']; ?>"
                           class="product-listing-card__cta">
                            Discover More
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <!-- Static fallback -->
                <div class="products-listing">
                    <?php
                    $static_loans = [
                        ['emoji'=>'📋','title'=>'Normal Loans','desc'=>'Up to 3× your deposits. 48-month repayment at 1% per month on reducing balance. Maximum entitlement is KES 3 million. Disbursed within 30 days of application.','id'=>'1'],
                        ['emoji'=>'⚡','title'=>'Emergency Loans','desc'=>'Fast-access credit for urgent needs with minimal documentation requirements. Designed to support you when life happens unexpectedly.','id'=>'2'],
                        ['emoji'=>'🎓','title'=>'Education Loan','desc'=>'Invest in knowledge — yours or your children\'s. Flexible terms designed specifically for educational expenses at any level.','id'=>'3'],
                        ['emoji'=>'🏗️','title'=>'Development Loans','desc'=>'For bigger projects — up to 60 months repayment at 1.125% per month on reducing balance. Maximum KES 3 million. Ideal for land, construction, and business development.','id'=>'4'],
                        ['emoji'=>'🔄','title'=>'Loan Refinancing','desc'=>'Access a new, bigger loan on fresh terms while still servicing an existing loan. 2% commission charged on the balance. Permissible after repaying a Normal/Development loan halfway.','id'=>'5'],
                    ];
                    foreach ($static_loans as $loan):
                    ?>
                    <article class="product-listing-card animate-on-scroll">
                        <div class="product-listing-card__icon" aria-hidden="true"><?php echo $loan['emoji']; ?></div>
                        <div class="product-listing-card__body">
                            <h3 class="product-listing-card__title"><?php echo $loan['title']; ?></h3>
                            <p class="product-listing-card__desc"><?php echo $loan['desc']; ?></p>
                        </div>
                        <a href="viewProduct.php?page=<?php echo $loan['id']; ?>" class="product-listing-card__cta">
                            Discover More
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../js/main.js"></script>
</body>
</html>
