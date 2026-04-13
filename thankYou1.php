<?php
/**
 * thankYou1.php
 * Confirmation page shown after a successful contact form or enquiry submission.
 * Also aliased as thankyou.php via a redirect below.
 */
require_once 'config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '';
$nav_active = '';
$page_title = 'Thank You — ' . htmlspecialchars($rcs['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="thankyou-section">
    <div class="container">
        <div class="thankyou-card">

            <div class="thankyou-card__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>

            <h1 class="thankyou-card__title">Message Received!</h1>
            <p class="thankyou-card__body">
                Thank you for getting in touch. Your message has been forwarded to our team and we will respond within 2 working days.
            </p>

            <div class="thankyou-card__contacts">
                <h2 class="thankyou-card__contacts-title">In the meantime, you can also reach us at:</h2>

                <div class="thankyou-contacts-grid">
                    <div class="thankyou-contact-item">
                        <span class="thankyou-contact-item__label">Main Enquiries</span>
                        <a href="mailto:braemegsacco@yahoo.com">braemegsacco@yahoo.com</a>
                        <a href="mailto:<?php echo htmlspecialchars($rcs['email']); ?>">
                            <?php echo htmlspecialchars($rcs['email']); ?>
                        </a>
                    </div>
                    <div class="thankyou-contact-item">
                        <span class="thankyou-contact-item__label">Loan Enquiries</span>
                        <a href="mailto:creditchair.braemeg@gmail.com">creditchair.braemeg@gmail.com</a>
                        <a href="mailto:creditsecretary.braemeg@gmail.com">creditsecretary.braemeg@gmail.com</a>
                        <a href="mailto:accnts.braemeg@gmail.com">accnts.braemeg@gmail.com</a>
                    </div>
                    <div class="thankyou-contact-item">
                        <span class="thankyou-contact-item__label">Finance Enquiries</span>
                        <a href="mailto:finance.braemeg@gmail.com">finance.braemeg@gmail.com</a>
                        <a href="mailto:accts.braemeg@gmail.com">accts.braemeg@gmail.com</a>
                    </div>
                    <div class="thankyou-contact-item">
                        <span class="thankyou-contact-item__label">Administrative</span>
                        <a href="mailto:thechair.braemeg@gmail.com">Chairperson</a>
                        <a href="mailto:thevc.braemeg@gmail.com">Vice Chairperson</a>
                        <a href="mailto:thetreasurer.braemeg@gmail.com">Treasurer</a>
                        <a href="mailto:thesecretary.braemeg@gmail.com">Secretary</a>
                    </div>
                </div>
            </div>

            <div class="thankyou-card__actions">
                <a href="index.php" class="btn-primary">
                    Back to Home
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="products/loan-products.php" class="btn-ghost">
                    Explore Products
                </a>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/main.js"></script>
</body>
</html>
