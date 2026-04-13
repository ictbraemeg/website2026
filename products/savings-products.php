<?php
/**
 * products/savings-products.php
 */
require_once '../config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$savings_qry = $dbc->prepare(
    "SELECT * FROM tbl_products WHERE published='1' AND menuid='Savings' ORDER BY title ASC"
);
$savings_qry->execute();
$savings = $savings_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base   = '../';
$nav_active = 'products';
$page_title = 'Savings Products — ' . htmlspecialchars($rcs['name']);

$page_heading = 'Our Savings Products';
$page_sub     = 'Grow your wealth steadily with our range of savings plans.';
$breadcrumbs  = [
    ['label' => 'Home',     'href' => '../index.php'],
    ['label' => 'Products', 'href' => 'loan-products.php'],
    ['label' => 'Savings Products'],
];

$sidebar_title = 'Products';
$sidebar_items = [
    ['label' => 'Loan Products',    'href' => 'loan-products.php'],
    ['label' => 'Savings Products', 'href' => 'savings-products.php', 'active' => true],
];

function get_savings_emoji($title) {
    $lower = strtolower($title);
    if (strpos($lower, 'toto') !== false)       { return '🌱'; }
    if (strpos($lower, 'christmas') !== false)  { return '🎄'; }
    if (strpos($lower, 'holiday') !== false)    { return '✈️'; }
    if (strpos($lower, 'benevolent') !== false) { return '🤝'; }
    if (strpos($lower, 'deposit') !== false)    { return '💰'; }
    return '💳';
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
                    <span class="section-tag">Save</span>
                    <h2 class="inner-page__title">Our Savings Products</h2>
                    <p class="inner-page__desc">Building financial security starts with consistent saving. Our savings products are designed to help every member accumulate wealth at every stage of life.</p>
                </div>

                <?php if (!empty($savings)): ?>
                <div class="products-listing">
                    <?php foreach ($savings as $product): ?>
                    <article class="product-listing-card animate-on-scroll">
                        <div class="product-listing-card__icon" aria-hidden="true">
                            <?php echo get_savings_emoji($product['title']); ?>
                        </div>
                        <div class="product-listing-card__body">
                            <h3 class="product-listing-card__title"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <div class="product-listing-card__desc">
                                <?php echo htmlspecialchars(substr(strip_tags($product['description'] ?? ''), 0, 180)); ?>…
                            </div>
                        </div>
                        <a href="viewProduct.php?page=<?php echo (int)$product['PID']; ?>"
                           class="product-listing-card__cta">
                            Discover More
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="products-listing">
                    <?php
                    $static_savings = [
                        ['emoji'=>'💰','title'=>'Deposits','desc'=>'Monthly contributions that earn attractive annual dividends. Your deposits also determine your loan eligibility — up to 3× your deposit balance.','id'=>'6'],
                        ['emoji'=>'🌱','title'=>'Braemeg Toto Savings','desc'=>'A children\'s savings plan earning 5% annual interest. For members\' children below 18 years. Minimum KES 500/month via checkoff or standing order. Withdrawal refund due after 45 days.','id'=>'7'],
                        ['emoji'=>'🤝','title'=>'Braemeg Benevolent Scheme','desc'=>'A community support fund providing financial assistance to members in times of bereavement. A safety net built on shared responsibility.','id'=>'8'],
                        ['emoji'=>'🎄','title'=>'Christmas Savings','desc'=>'Plan ahead for the festive season. Save consistently throughout the year and receive a financial boost when you need it most during the holiday period.','id'=>'9'],
                        ['emoji'=>'✈️','title'=>'Holiday Package','desc'=>'Dream big, save smart. Our Holiday Package savings plan helps you travel locally or internationally without financial strain.','id'=>'10'],
                    ];
                    foreach ($static_savings as $product):
                    ?>
                    <article class="product-listing-card animate-on-scroll">
                        <div class="product-listing-card__icon" aria-hidden="true"><?php echo $product['emoji']; ?></div>
                        <div class="product-listing-card__body">
                            <h3 class="product-listing-card__title"><?php echo $product['title']; ?></h3>
                            <p class="product-listing-card__desc"><?php echo $product['desc']; ?></p>
                        </div>
                        <a href="viewProduct.php?page=<?php echo $product['id']; ?>" class="product-listing-card__cta">
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
