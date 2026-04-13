<?php
/**
 * index.php — Braemeg SACCO Homepage
 */

require_once "config/shikisho.php";
require_once "counter.php"; /* visitor counter — must be before HTML output */

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$marquee_qry = $dbc->prepare(
    "SELECT * FROM tbl_resources WHERE ismarquee='1' AND published='1' ORDER BY RAND() LIMIT 1",
);
$marquee_qry->execute();
$marquee = $marquee_qry->fetch(PDO::FETCH_ASSOC);

$products_qry = $dbc->prepare(
    "SELECT * FROM tbl_products WHERE published='1' ORDER BY RAND() LIMIT 6",
);
$products_qry->execute();
$products = $products_qry->fetchAll(PDO::FETCH_ASSOC);

$loans_qry = $dbc->prepare(
    "SELECT * FROM tbl_products WHERE published='1' AND menuid='Loans' ORDER BY title ASC",
);
$loans_qry->execute();
$loans = $loans_qry->fetchAll(PDO::FETCH_ASSOC);

$savings_qry = $dbc->prepare(
    "SELECT * FROM tbl_products WHERE published='1' AND menuid='Savings' ORDER BY title ASC",
);
$savings_qry->execute();
$savings = $savings_qry->fetchAll(PDO::FETCH_ASSOC);

/* ── Latest blog posts (3) for homepage preview ───────────────── */
$blog_qry = $dbc->prepare(
    "SELECT PID, postTitle, postDesc, postContent, postImage, postDate
     FROM tbl_blog_posts WHERE published='1'
     ORDER BY PID DESC LIMIT 3",
);
$blog_qry->execute();
$blog_posts = $blog_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base = "";
$nav_active = "home";
$page_title =
    htmlspecialchars($rcs["name"]) . " — " . htmlspecialchars($rcs["slogan"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "includes/head.php"; ?>
</head>
<body>

<?php include "includes/topbar.php"; ?>
<?php include "includes/navbar.php"; ?>

<!-- ── ANNOUNCEMENT TICKER ───────────────────────────────── -->
<?php if (!empty($marquee["title"])): ?>
<div class="ticker-bar" aria-label="Announcements">
    <div class="ticker-bar__label" aria-hidden="true">Notice</div>
    <div class="ticker-bar__track">
        <div class="ticker-bar__content">
            <span><?php echo htmlspecialchars($marquee["title"]); ?></span>
            <a href="resources/general-information.php?page=<?php echo (int) $marquee[
                "PID"
            ]; ?>"
               class="ticker-bar__link">Read More &rarr;</a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ── HERO ─────────────────────────────────────────────── -->
<section class="hero" aria-label="Hero banner">
    <div class="hero__pattern" aria-hidden="true"></div>
    <div class="hero__orb hero__orb--1" aria-hidden="true"></div>
    <div class="hero__orb hero__orb--2" aria-hidden="true"></div>

    <div class="hero__inner">
        <div class="hero__content">
            <div class="hero__badge">Regulated by SASRA &middot; Est. 1988</div>

            <h1 class="hero__title">
                Akiba Yangu
                <span class="hero__title-accent">Maisha Yangu</span>
            </h1>

            <p class="hero__sub">
                Small Steps, Big Returns. Join thousands of members building lasting financial
                freedom through affordable savings, credit and community support.
            </p>

            <div class="hero__ctas">
                <a href="apply.php" class="btn-primary">
                    Join Us Now
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#products" class="btn-outline">
                    Explore Products
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                </a>
            </div>

            <div class="hero__stats">
                <div class="hero__stat">
                    <div class="hero__stat-num">1988</div>
                    <div class="hero__stat-label">Founded</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-num">14+</div>
                    <div class="hero__stat-label">Member Schools</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-num">KES 3M</div>
                    <div class="hero__stat-label">Max Loan Limit</div>
                </div>
            </div>
        </div>

        <!-- Quick product access card — hidden on mobile via CSS -->
        <div class="hero__card" aria-label="Quick product links">
            <h2 class="hero__card-title">Our Products</h2>
            <div class="product-quick-grid">
                <a href="products/loan-products.php" class="product-quick-tile">
                    <div class="product-quick-tile__icon" aria-hidden="true">🏦</div>
                    <div class="product-quick-tile__name">Normal Loans</div>
                    <div class="product-quick-tile__desc">Up to 3&times; deposits</div>
                </a>
                <a href="products/loan-products.php" class="product-quick-tile">
                    <div class="product-quick-tile__icon" aria-hidden="true">⚡</div>
                    <div class="product-quick-tile__name">Emergency Loans</div>
                    <div class="product-quick-tile__desc">Fast disbursement</div>
                </a>
                <a href="products/loan-products.php" class="product-quick-tile">
                    <div class="product-quick-tile__icon" aria-hidden="true">🎓</div>
                    <div class="product-quick-tile__name">Education Loan</div>
                    <div class="product-quick-tile__desc">Fund your learning</div>
                </a>
                <a href="products/savings-products.php" class="product-quick-tile">
                    <div class="product-quick-tile__icon" aria-hidden="true">🌱</div>
                    <div class="product-quick-tile__name">Toto Savings</div>
                    <div class="product-quick-tile__desc">5% p.a. for children</div>
                </a>
                <a href="products/savings-products.php" class="product-quick-tile">
                    <div class="product-quick-tile__icon" aria-hidden="true">🎄</div>
                    <div class="product-quick-tile__name">Christmas Savings</div>
                    <div class="product-quick-tile__desc">Festive financial plan</div>
                </a>
                <a href="products/savings-products.php" class="product-quick-tile">
                    <div class="product-quick-tile__icon" aria-hidden="true">✈️</div>
                    <div class="product-quick-tile__name">Holiday Package</div>
                    <div class="product-quick-tile__desc">Travel savings plan</div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── WHY BRAEMEG ───────────────────────────────────────── -->
<section aria-labelledby="why-title">
    <div class="container">
        <span class="section-tag">Why Choose Us</span>
        <h2 class="section-title" id="why-title">
            A SACCO Built on Trust,<br>Grown by Community
        </h2>
        <p class="section-desc">
            For over 35 years, Braemeg SACCO has served employees of international schools
            across Kenya and beyond — delivering real financial impact to every member.
        </p>

        <div class="why-grid">
            <article class="why-card animate-on-scroll">
                <div class="why-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="why-card__title">SASRA Regulated</h3>
                <p class="why-card__desc">Fully regulated and compliant with Kenya's Sacco Societies Regulatory Authority. Your funds are safe, audited and protected.</p>
            </article>

            <article class="why-card animate-on-scroll">
                <div class="why-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 class="why-card__title">Competitive Returns</h3>
                <p class="why-card__desc">Earn attractive dividends on your deposits and enjoy low-interest loans — because your growth is our mission.</p>
            </article>

            <article class="why-card animate-on-scroll">
                <div class="why-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 class="why-card__title">Diverse Membership</h3>
                <p class="why-card__desc">Members from 14+ international schools across Kenya, plus diaspora members in USA, UK, China, Dubai — a truly global community.</p>
            </article>

            <article class="why-card animate-on-scroll">
                <div class="why-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3 class="why-card__title">Affordable Credit</h3>
                <p class="why-card__desc">Borrow at 1% per month on reducing balance. Normal, Development, Emergency and Education loans — all with fair, transparent terms.</p>
            </article>

            <article class="why-card animate-on-scroll">
                <div class="why-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <h3 class="why-card__title">Professional Management</h3>
                <p class="why-card__desc">Our Board and Secretariat are finance and management professionals, ensuring every decision is carefully considered and sound.</p>
            </article>

            <article class="why-card animate-on-scroll">
                <div class="why-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <h3 class="why-card__title">Multiple Savings Plans</h3>
                <p class="why-card__desc">From Toto children's savings to Christmas packages and holiday plans — we have a savings product for every life stage and goal.</p>
            </article>
        </div>
    </div>
</section>

<!-- ── PRODUCTS ──────────────────────────────────────────── -->
<section class="section--dark" id="products" aria-labelledby="products-title">
    <div class="container">
        <span class="section-tag">Financial Products</span>
        <h2 class="section-title" id="products-title">Smart Products for Every Goal</h2>
        <p class="section-desc">
            Whether you need to borrow or save, we have flexible products designed around your life as a member.
        </p>

        <div class="products-tabs" role="tablist" aria-label="Product categories">
            <button class="tab-btn is-active"
                    data-tab="loans"
                    role="tab"
                    aria-selected="true"
                    aria-controls="tab-loans">Loan Products</button>
            <button class="tab-btn"
                    data-tab="savings"
                    role="tab"
                    aria-selected="false"
                    aria-controls="tab-savings">Savings Products</button>
        </div>

        <!-- Loans tab -->
        <div class="tab-panel is-active" id="tab-loans" role="tabpanel" aria-label="Loan Products">
            <?php if (!empty($loans)): ?>
                <?php foreach ($loans as $product): ?>
                <a href="products/viewProduct.php?page=<?php echo (int) $product[
                    "PID"
                ]; ?>"
                   class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🏦</div>
                    <h3 class="product-card__title"><?php echo htmlspecialchars(
                        $product["title"],
                    ); ?></h3>
                    <p class="product-card__desc"><?php echo htmlspecialchars(
                        substr(
                            strip_tags($product["description"] ?? ""),
                            0,
                            100,
                        ),
                    ); ?>…</p>
                    <span class="product-card__link">
                        Discover More
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback static content if DB has no loans yet -->
                <a href="products/loan-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">📋</div>
                    <h3 class="product-card__title">Normal Loans</h3>
                    <p class="product-card__desc">Up to 3&times; your deposits. 48-month repayment at 1% p.m. on reducing balance.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/loan-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">⚡</div>
                    <h3 class="product-card__title">Emergency Loans</h3>
                    <p class="product-card__desc">Fast-access credit for urgent needs with minimal documentation.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/loan-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🎓</div>
                    <h3 class="product-card__title">Education Loan</h3>
                    <p class="product-card__desc">Invest in knowledge — flexible terms for educational expenses.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/loan-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🏗️</div>
                    <h3 class="product-card__title">Development Loans</h3>
                    <p class="product-card__desc">Up to 60 months at 1.125% p.m. on reducing balance. Maximum KES 3M.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/loan-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🔄</div>
                    <h3 class="product-card__title">Loan Refinancing</h3>
                    <p class="product-card__desc">Access a new, bigger loan on fresh terms while servicing an existing one.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Savings tab -->
        <div class="tab-panel" id="tab-savings" role="tabpanel" aria-label="Savings Products">
            <?php if (!empty($savings)): ?>
                <?php foreach ($savings as $product): ?>
                <a href="products/viewProduct.php?page=<?php echo (int) $product[
                    "PID"
                ]; ?>"
                   class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">💰</div>
                    <h3 class="product-card__title"><?php echo htmlspecialchars(
                        $product["title"],
                    ); ?></h3>
                    <p class="product-card__desc"><?php echo htmlspecialchars(
                        substr(
                            strip_tags($product["description"] ?? ""),
                            0,
                            100,
                        ),
                    ); ?>…</p>
                    <span class="product-card__link">
                        Discover More
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback static content -->
                <a href="products/savings-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">💰</div>
                    <h3 class="product-card__title">Deposits</h3>
                    <p class="product-card__desc">Earn attractive dividends on your monthly deposits — the bedrock of your wealth.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/savings-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🌱</div>
                    <h3 class="product-card__title">Braemeg Toto Savings</h3>
                    <p class="product-card__desc">5% annual interest for children under 18. Save from KES 500/month.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/savings-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🤝</div>
                    <h3 class="product-card__title">Benevolent Scheme</h3>
                    <p class="product-card__desc">Community support in times of bereavement.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/savings-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">🎄</div>
                    <h3 class="product-card__title">Christmas Savings</h3>
                    <p class="product-card__desc">Plan ahead for the festive season — save throughout the year.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="products/savings-products.php" class="product-card animate-on-scroll">
                    <div class="product-card__emoji" aria-hidden="true">✈️</div>
                    <h3 class="product-card__title">Holiday Package</h3>
                    <p class="product-card__desc">Dream big, save smart — travel without the financial stress.</p>
                    <span class="product-card__link">Discover More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── MISSION / VALUES ──────────────────────────────────── -->
<section class="section--alt" aria-labelledby="mission-title">
    <div class="container">
        <div class="mission-grid">
            <div class="mission-visual">
                <div class="mission-card">
                    <h2 class="mission-card__title" id="mission-title">
                        To be a leading financial institution guaranteeing members' growth and financial independence.
                    </h2>
                    <p class="mission-card__body">
                        We provide diverse and affordable financial products and services that guarantee
                        competitive returns to members through mobilisation of savings, education and sound management.
                    </p>
                    <div class="mission-stat">
                        <span class="mission-stat__num">35+</span>
                        <span class="mission-stat__label">Years of Service</span>
                    </div>
                </div>
            </div>

            <div class="mission-content">
                <span class="section-tag">Our Core Values</span>
                <h2 class="section-title">What We Stand For</h2>

                <ul class="values-list" aria-label="Core values">
                    <li class="values-list__item"><span class="values-list__dot" aria-hidden="true"></span>Excellent Customer Care</li>
                    <li class="values-list__item"><span class="values-list__dot" aria-hidden="true"></span>Integrity in All We Do</li>
                    <li class="values-list__item"><span class="values-list__dot" aria-hidden="true"></span>Confidentiality &amp; Privacy</li>
                    <li class="values-list__item"><span class="values-list__dot" aria-hidden="true"></span>Equity &amp; Fairness</li>
                    <li class="values-list__item"><span class="values-list__dot" aria-hidden="true"></span>Proactive Leadership</li>
                </ul>

                <div class="objectives-list">
                    <p class="objectives-list__label">Our Objectives</p>
                    <div class="objectives-list__items">
                        <p class="objectives-list__item">Encourage thrift among members by providing opportunities for accumulating savings.</p>
                        <p class="objectives-list__item">Create sources of funds for credit lent at fair and reasonable interest rates.</p>
                        <p class="objectives-list__item">Streamline loan processing and ensure the safety of members' funds through risk management.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── TESTIMONIALS ──────────────────────────────────────── -->
<section aria-labelledby="testimonials-title">
    <div class="container">
        <span class="section-tag">Member Stories</span>
        <h2 class="section-title" id="testimonials-title">Our Happy Members</h2>
        <p class="section-desc">
            Real experiences from the Braemeg SACCO community — people whose financial lives have been transformed.
        </p>

        <div class="testimonials-grid">
            <article class="testimonial-card animate-on-scroll">
                <span class="testimonial-card__quote-mark" aria-hidden="true">"</span>
                <p class="testimonial-card__text">
                    Joining Braemeg SACCO has been a game-changer in my financial journey.
                    Before joining, saving was a challenge, and accessing emergency funds was difficult.
                    Now, I can save consistently and access affordable credit.
                </p>
                <div class="testimonial-card__author">
                    <div class="author-avatar" aria-hidden="true">PW</div>
                    <div>
                        <div class="author-name">Purity Wambui</div>
                        <div class="author-role">SACCO Member</div>
                        <div class="author-stars" aria-label="5 stars">★★★★★</div>
                    </div>
                </div>
            </article>

            <article class="testimonial-card animate-on-scroll">
                <span class="testimonial-card__quote-mark" aria-hidden="true">"</span>
                <p class="testimonial-card__text">
                    Braemeg SACCO has enabled me to achieve long-held dreams through loans while gaining
                    valuable budgeting and business management skills from fellow members. It's more than
                    financial support — it's community.
                </p>
                <div class="testimonial-card__author">
                    <div class="author-avatar" aria-hidden="true">JM</div>
                    <div>
                        <div class="author-name">James Mutua</div>
                        <div class="author-role">Member since 2015</div>
                        <div class="author-stars" aria-label="5 stars">★★★★★</div>
                    </div>
                </div>
            </article>

            <article class="testimonial-card animate-on-scroll">
                <span class="testimonial-card__quote-mark" aria-hidden="true">"</span>
                <p class="testimonial-card__text">
                    The Toto Savings plan has been a blessing. I started saving for my children's future
                    and the 5% annual interest keeps growing their fund. I highly recommend Braemeg SACCO
                    to every parent.
                </p>
                <div class="testimonial-card__author">
                    <div class="author-avatar" aria-hidden="true">AK</div>
                    <div>
                        <div class="author-name">Alice Kamau</div>
                        <div class="author-role">Member, Braeburn Mombasa</div>
                        <div class="author-stars" aria-label="5 stars">★★★★★</div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- ── BLOG PREVIEW ────────────────────────────────────────── -->
<?php if (!empty($blog_posts)): ?>
<section class="section--blog-preview" aria-labelledby="blog-preview-title">
    <div class="container">
        <div class="blog-preview__header">
            <div>
                <span class="section-tag">From the Blog</span>
                <h2 class="section-title" id="blog-preview-title">News &amp; Insights</h2>
                <p class="section-desc">Updates, financial tips and community stories from Braemeg SACCO.</p>
            </div>
            <a href="blog/index.php" class="blog-preview__all-link">
                View all posts
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="blog-preview__grid">
            <?php foreach ($blog_posts as $i => $post):

                $bp_pid = (int) $post["PID"];
                $bp_title = isset($post["postTitle"])
                    ? trim($post["postTitle"])
                    : "";
                $bp_image = isset($post["postImage"])
                    ? trim($post["postImage"])
                    : "";
                $bp_date = isset($post["postDate"])
                    ? trim($post["postDate"])
                    : "";
                $bp_excerpt =
                    isset($post["postDesc"]) && trim($post["postDesc"]) !== ""
                        ? trim($post["postDesc"])
                        : mb_substr(
                                strip_tags($post["postContent"] ?? ""),
                                0,
                                120,
                            ) . "…";
                $bp_url = "blog/post.php?page=" . $bp_pid;
                ?>
            <article class="blog-preview-card <?php echo $i === 0
                ? "blog-preview-card--lead"
                : ""; ?> animate-on-scroll">
                <?php if ($bp_image !== ""): ?>
                <a href="<?php echo htmlspecialchars(
                    $bp_url,
                ); ?>" class="blog-preview-card__thumb" tabindex="-1" aria-hidden="true">
                    <img src="images/blog/<?php echo htmlspecialchars(
                        $bp_image,
                    ); ?>"
                         alt="<?php echo htmlspecialchars($bp_title); ?>"
                         class="blog-preview-card__img"
                         loading="lazy">
                </a>
                <?php endif; ?>

                <div class="blog-preview-card__body">
                    <?php if ($bp_date !== ""): ?>
                    <div class="blog-card__meta">
                        <time class="blog-card__date"><?php echo htmlspecialchars(
                            $bp_date,
                        ); ?></time>
                    </div>
                    <?php endif; ?>

                    <h3 class="blog-preview-card__title">
                        <a href="<?php echo htmlspecialchars($bp_url); ?>">
                            <?php echo htmlspecialchars($bp_title); ?>
                        </a>
                    </h3>

                    <?php if ($bp_excerpt !== "" && $bp_excerpt !== "…"): ?>
                    <p class="blog-preview-card__excerpt">
                        <?php echo htmlspecialchars($bp_excerpt); ?>
                    </p>
                    <?php endif; ?>

                    <a href="<?php echo htmlspecialchars(
                        $bp_url,
                    ); ?>" class="blog-card__read-more">
                        Read more
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            <?php
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── HOW TO JOIN ────────────────────────────────────────── -->
<section class="section--join" id="join" aria-labelledby="join-title">
    <div class="container">
        <div class="section__header">
            <span class="section-tag">Get Started</span>
            <h2 class="section-title" id="join-title">How to Become a Member</h2>
            <p class="section-desc">
                Membership is open to employees of international schools, their spouses and children,
                and employees of Braemeg SACCO Limited.
            </p>
        </div>

        <ol class="steps-grid" aria-label="Steps to join">
            <li class="step-card animate-on-scroll">
                <div class="step-card__num" aria-hidden="true">1</div>
                <h3 class="step-card__title">Check Eligibility</h3>
                <p class="step-card__desc">Confirm you're an employee of a member school, a spouse/child of a member, or a SACCO employee.</p>
            </li>
            <li class="step-card animate-on-scroll">
                <div class="step-card__num" aria-hidden="true">2</div>
                <h3 class="step-card__title">Download Forms</h3>
                <p class="step-card__desc">Get the membership application form from our Resources page or visit our office.</p>
            </li>
            <li class="step-card animate-on-scroll">
                <div class="step-card__num" aria-hidden="true">3</div>
                <h3 class="step-card__title">Submit Documents</h3>
                <p class="step-card__desc">Attach your ID, passport photo and required documents. Submit to our Secretariat.</p>
            </li>
            <li class="step-card animate-on-scroll">
                <div class="step-card__num" aria-hidden="true">4</div>
                <h3 class="step-card__title">Start Saving!</h3>
                <p class="step-card__desc">Begin your monthly contributions and unlock access to all our loan and savings products.</p>
            </li>
        </ol>

        <div class="section__cta-center">
            <a href="contacts.php" class="btn-ghost">
                Contact Us to Apply
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ── CONTACT / CTA ─────────────────────────────────────── -->
<section class="section--cta" id="contact" aria-labelledby="contact-title">
    <div class="container">
        <div class="cta-grid">
            <div class="cta-left">
                <span class="section-tag">Get In Touch</span>
                <h2 class="cta-left__title" id="contact-title">Request a Call Back</h2>
                <p class="cta-left__desc">
                    Talk to us today to find out how we can suggest long-term and short-term strategies
                    that will help you realise your financial dreams.
                </p>

                <div class="cta-left__contacts">
                    <div class="contact-item">
                        <div class="contact-item__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
                        </div>
                        <div>
                            <div class="contact-item__label">Phone</div>
                            <div class="contact-item__value">
                                <a href="tel:<?php echo htmlspecialchars(
                                    $rcs["cellphone"],
                                ); ?>">
                                    <?php echo htmlspecialchars(
                                        $rcs["cellphone"],
                                    ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div>
                            <div class="contact-item__label">Email</div>
                            <div class="contact-item__value">
                                <a href="mailto:<?php echo htmlspecialchars(
                                    $rcs["email"],
                                ); ?>">
                                    <?php echo htmlspecialchars(
                                        $rcs["email"],
                                    ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div>
                            <div class="contact-item__label">Location</div>
                            <div class="contact-item__value">
                                <?php echo htmlspecialchars(
                                    $rcs["physicaladd"] ?? "Nairobi, Kenya",
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact form — posts to contactmail.php -->
            <div class="contact-form">
                <form id="contact-form" action="contactmail.php" method="POST" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="field-name">Your Name *</label>
                            <input type="text"
                                   id="field-name"
                                   name="mname"
                                   class="form-control"
                                   placeholder="Full name"
                                   required
                                   autocomplete="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="field-email">Your Email *</label>
                            <input type="email"
                                   id="field-email"
                                   name="email"
                                   class="form-control"
                                   placeholder="email@example.com"
                                   required
                                   autocomplete="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-phone">Phone Number</label>
                        <input type="tel"
                               id="field-phone"
                               name="phone"
                               class="form-control"
                               placeholder="+254 ..."
                               autocomplete="tel">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-subject">How Can We Help? *</label>
                        <select id="field-subject" name="subject" class="form-control" required>
                            <option value="">Select a topic…</option>
                            <option value="Loan Query">Loan Queries</option>
                            <option value="Finance Query">Finance Queries</option>
                            <option value="Membership Enquiry">Membership Enquiry</option>
                            <option value="Admin Query">Administrative Queries</option>
                            <option value="General Enquiry">General Enquiry</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-message">Message</label>
                        <textarea id="field-message"
                                  name="message"
                                  class="form-control form-control--textarea"
                                  rows="3"
                                  placeholder="Tell us more…"></textarea>
                    </div>

                    <button type="submit" class="btn-submit" id="form-submit">
                        Send Message &rarr;
                    </button>
                </form>

                <div class="form-success" id="form-success" role="alert" aria-live="polite">
                    ✓ Thank you! We'll be in touch shortly.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── VISITOR COUNTER STRIP ─────────────────────────────── -->
<div class="counter-strip" aria-label="Site statistics">
    <div class="container">
        <div class="counter-strip__inner">
            <div class="counter-strip__stat">
                <span class="counter-strip__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
                <div>
                    <span class="counter-strip__num"
                          data-target="<?php echo $visitor_count; ?>"
                          id="visitor-count-display">
                        <?php echo format_count($visitor_count); ?>
                    </span>
                    <span class="counter-strip__label">Visitors since launch</span>
                </div>
            </div>

            <div class="counter-strip__divider" aria-hidden="true"></div>

            <div class="counter-strip__stat">
                <span class="counter-strip__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </span>
                <div>
                    <span class="counter-strip__num">1988</span>
                    <span class="counter-strip__label">Year established</span>
                </div>
            </div>

            <div class="counter-strip__divider" aria-hidden="true"></div>

            <div class="counter-strip__stat">
                <span class="counter-strip__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </span>
                <div>
                    <span class="counter-strip__num">SASRA</span>
                    <span class="counter-strip__label">Regulated &amp; licensed</span>
                </div>
            </div>

            <div class="counter-strip__divider" aria-hidden="true"></div>

            <div class="counter-strip__stat">
                <span class="counter-strip__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </span>
                <div>
                    <span class="counter-strip__num">14+</span>
                    <span class="counter-strip__label">Member schools</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

<!-- Site JavaScript — loaded at end of body -->
<script src="js/main.js"></script>

</body>
</html>
