<?php
/**
 * contacts.php — Braemeg SACCO Contact Page
 */

require_once 'config/shikisho.php';

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base   = '';
$nav_active = 'contacts';
$page_title = 'Contact Us — ' . htmlspecialchars($rcs['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- ── PAGE HEADER ─────────────────────────────────────── -->
<div class="page-header" role="banner">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="index.php" class="breadcrumb__item">Home</a>
            <span class="breadcrumb__sep" aria-hidden="true">/</span>
            <span class="breadcrumb__item breadcrumb__item--current" aria-current="page">Contact Us</span>
        </nav>
        <h1 class="page-header__title">Get In Touch</h1>
        <p class="page-header__sub">We're here to help. Reach out through any of the channels below.</p>
    </div>
</div>

<!-- ── CONTACT DETAILS STRIP ───────────────────────────── -->
<section class="contact-details-section" aria-labelledby="contact-details-title">
    <div class="container">
        <h2 class="sr-only" id="contact-details-title">Contact Details</h2>
        <div class="contact-details-grid">

            <div class="contact-detail-card animate-on-scroll">
                <div class="contact-detail-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <h3 class="contact-detail-card__title">Our Location</h3>
                <p class="contact-detail-card__value"><?php echo nl2br(htmlspecialchars($rcs['physicaladd'])); ?></p>
            </div>

            <div class="contact-detail-card animate-on-scroll">
                <div class="contact-detail-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                    </svg>
                </div>
                <h3 class="contact-detail-card__title">Call Us Anytime</h3>
                <p class="contact-detail-card__value">
                    <a href="tel:<?php echo htmlspecialchars($rcs['cellphone']); ?>">
                        <?php echo htmlspecialchars($rcs['cellphone']); ?>
                    </a>
                </p>
                <?php if (!empty($rcs['postaladd'])): ?>
                <p class="contact-detail-card__secondary"><?php echo htmlspecialchars($rcs['postaladd']); ?></p>
                <?php endif; ?>
            </div>

            <div class="contact-detail-card animate-on-scroll">
                <div class="contact-detail-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </div>
                <h3 class="contact-detail-card__title">Write to Us</h3>
                <p class="contact-detail-card__value">
                    <a href="mailto:<?php echo htmlspecialchars($rcs['email']); ?>">
                        <?php echo htmlspecialchars($rcs['email']); ?>
                    </a>
                </p>
            </div>

            <div class="contact-detail-card animate-on-scroll">
                <div class="contact-detail-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <h3 class="contact-detail-card__title">WhatsApp</h3>
                <p class="contact-detail-card__value">
                    <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $rcs['cellphone']); ?>"
                       target="_blank" rel="noopener">
                        Chat with us
                    </a>
                </p>
            </div>

        </div>
    </div>
</section>

<!-- ── FORM + MAP ───────────────────────────────────────── -->
<section class="contact-main-section" aria-labelledby="contact-form-title">
    <div class="container">
        <div class="contact-main-grid">

            <!-- Contact Form -->
            <div class="contact-form-wrapper animate-on-scroll">
                <span class="section-tag">Send a Message</span>
                <h2 class="section-title" id="contact-form-title">Leave Us a Message</h2>
                <p class="section-desc">Fill in the form and a member of our team will get back to you as soon as possible.</p>

                <form id="contact-form" class="contact-form-page" action="reachToUs.php" method="POST" novalidate>

                    <div class="form-group-page">
                        <label class="form-label-page" for="cp-name">Full Name *</label>
                        <input type="text"
                               id="cp-name"
                               name="name"
                               class="form-input-page"
                               placeholder="Your full name"
                               required
                               autocomplete="name">
                    </div>

                    <div class="form-row-page">
                        <div class="form-group-page">
                            <label class="form-label-page" for="cp-email">Email Address *</label>
                            <input type="email"
                                   id="cp-email"
                                   name="email"
                                   class="form-input-page"
                                   placeholder="email@example.com"
                                   required
                                   autocomplete="email">
                        </div>
                        <div class="form-group-page">
                            <label class="form-label-page" for="cp-phone">Mobile Number *</label>
                            <input type="tel"
                                   id="cp-phone"
                                   name="phone"
                                   class="form-input-page"
                                   placeholder="+254 ..."
                                   required
                                   autocomplete="tel">
                        </div>
                    </div>

                    <div class="form-group-page">
                        <label class="form-label-page" for="cp-subject">Subject *</label>
                        <input type="text"
                               id="cp-subject"
                               name="subject"
                               class="form-input-page"
                               placeholder="What is your enquiry about?"
                               required>
                    </div>

                    <div class="form-group-page">
                        <label class="form-label-page" for="cp-message">Message</label>
                        <textarea id="cp-message"
                                  name="message"
                                  class="form-input-page form-textarea-page"
                                  placeholder="Tell us how we can help…"
                                  rows="5"></textarea>
                    </div>

                    <div class="form-group-page">
                        <label class="form-label-page" for="cp-captcha">
                            Spam Check — type the characters shown *
                        </label>
                        <div class="captcha-row">
                            <input type="text"
                                   id="cp-captcha"
                                   name="captcha"
                                   class="form-input-page captcha-input"
                                   placeholder="Enter characters"
                                   autocomplete="off"
                                   required>
                            <img src="mycaptcha.php"
                                 id="captcha-img"
                                 class="captcha-img"
                                 width="120"
                                 height="40"
                                 alt="CAPTCHA code">
                            <button type="button" class="captcha-refresh" id="captcha-refresh" aria-label="Refresh CAPTCHA">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true">
                                    <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-page" id="form-submit">
                        Send Your Message &rarr;
                    </button>

                    <div class="form-success-page" id="form-success" role="alert" aria-live="polite">
                        ✓ Thank you! Your message has been sent. We'll be in touch shortly.
                    </div>
                    <div class="form-error-page" id="form-error" role="alert" aria-live="polite"></div>
                </form>
            </div>

            <!-- Social / Hours sidebar -->
            <aside class="contact-sidebar animate-on-scroll" aria-label="Additional contact info">
                <div class="contact-sidebar__block">
                    <h3 class="contact-sidebar__title">Office Hours</h3>
                    <dl class="office-hours">
                        <div class="office-hours__row">
                            <dt>Monday – Friday</dt>
                            <dd>8:00 AM – 5:00 PM</dd>
                        </div>
                        <div class="office-hours__row">
                            <dt>Saturday</dt>
                            <dd>9:00 AM – 1:00 PM</dd>
                        </div>
                        <div class="office-hours__row office-hours__row--closed">
                            <dt>Sunday &amp; Public Holidays</dt>
                            <dd>Closed</dd>
                        </div>
                    </dl>
                </div>

                <div class="contact-sidebar__block">
                    <h3 class="contact-sidebar__title">Follow Us</h3>
                    <nav class="contact-socials" aria-label="Social media">
                        <a href="https://www.facebook.com/BraemegSaccoLimited"
                           target="_blank" rel="noopener"
                           class="contact-social-link">
                            <span class="contact-social-link__icon" aria-hidden="true">fb</span>
                            Facebook
                        </a>
                        <a href="https://www.instagram.com/braemegsaccolimited"
                           target="_blank" rel="noopener"
                           class="contact-social-link">
                            <span class="contact-social-link__icon" aria-hidden="true">ig</span>
                            Instagram
                        </a>
                        <a href="https://twitter.com/Braemegsaccoltd"
                           target="_blank" rel="noopener"
                           class="contact-social-link">
                            <span class="contact-social-link__icon" aria-hidden="true">𝕏</span>
                            Twitter / X
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $rcs['cellphone']); ?>"
                           target="_blank" rel="noopener"
                           class="contact-social-link">
                            <span class="contact-social-link__icon" aria-hidden="true">wa</span>
                            WhatsApp
                        </a>
                    </nav>
                </div>

                <div class="contact-sidebar__block contact-sidebar__block--cta">
                    <h3 class="contact-sidebar__title">Already a Member?</h3>
                    <p>Log in to the member portal to view your account, statements and loan details.</p>
                    <a href="https://portal.braemegsacco.co.ke:8085"
                       target="_blank" rel="noopener"
                       class="btn-ghost">
                        Member Portal &rarr;
                    </a>
                </div>
            </aside>

        </div>
    </div>
</section>

<!-- ── MAP ─────────────────────────────────────────────── -->
<section class="map-section" aria-label="Office location map">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3988.8094520629143!2d36.74744301691208!3d-1.2885089980938464!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x835a2b194f2694b5!2sPolla%20House!5e0!3m2!1sen!2ske!4v1664567156409!5m2!1sen!2ske"
        width="100%"
        height="420"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Braemeg SACCO office location on Google Maps"
        aria-label="Google Maps showing Braemeg SACCO office at Polla House, Nairobi"></iframe>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/main.js"></script>

</body>
</html>
