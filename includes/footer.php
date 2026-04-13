<?php
/**
 * includes/footer.php
 * Site-wide footer — included on every page.
 * Requires: $rcs (company row), $nav_base ('' or '../')
 */
$nav_base = isset($nav_base) ? $nav_base : '';
?>
<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="footer__grid">

            <!-- Brand -->
            <div class="footer__brand">
                <a href="<?php echo $nav_base; ?>index.php" class="logo footer__logo" aria-label="Braemeg SACCO Home">
                    <div class="logo__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                        </svg>
                    </div>
                    <div class="logo__text">
                        <span class="logo__name">BRAEMEG</span>
                        <span class="logo__tagline">Sacco Society Limited</span>
                    </div>
                </a>
                <p class="footer__brand-desc">
                    Regulated by SASRA. Providing diverse and affordable financial products
                    to members since 1988. Serving international school employees across Kenya and the diaspora.
                </p>
                <nav class="social-links" aria-label="Social media links">
                    <a href="https://www.facebook.com/BraemegSaccoLimited"
                       target="_blank" rel="noopener"
                       class="social-link" aria-label="Facebook">fb</a>
                    <a href="https://www.instagram.com/braemegsaccolimited"
                       target="_blank" rel="noopener"
                       class="social-link" aria-label="Instagram">in</a>
                    <a href="https://twitter.com/Braemegsaccoltd"
                       target="_blank" rel="noopener"
                       class="social-link" aria-label="Twitter">𝕏</a>
                    <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $rcs['cellphone']); ?>"
                       target="_blank" rel="noopener"
                       class="social-link" aria-label="WhatsApp">wa</a>
                </nav>
            </div>

            <!-- About -->
            <div class="footer__col">
                <h3 class="footer__col-title">About</h3>
                <nav class="footer__links" aria-label="About links">
                    <a href="<?php echo $nav_base; ?>about-us/who-we-are.php">Who We Are</a>
                    <a href="<?php echo $nav_base; ?>about-us/our-vision-and-mission.php">Vision &amp; Mission</a>
                    <a href="<?php echo $nav_base; ?>about-us/governance-structure.php">Governance</a>
                    <a href="<?php echo $nav_base; ?>about-us/our-growth.php">Our Growth</a>
                    <a href="<?php echo $nav_base; ?>about-us/gallery.php">Gallery</a>
                </nav>
            </div>

            <!-- Products -->
            <div class="footer__col">
                <h3 class="footer__col-title">Products</h3>
                <nav class="footer__links" aria-label="Products links">
                    <a href="<?php echo $nav_base; ?>products/loan-products.php">Loan Products</a>
                    <a href="<?php echo $nav_base; ?>products/savings-products.php">Savings Products</a>
                </nav>
            </div>

            <!-- Quick Links -->
            <div class="footer__col">
                <h3 class="footer__col-title">Quick Links</h3>
                <nav class="footer__links" aria-label="Quick links">
                    <a href="https://portal.braemegsacco.co.ke:8085" target="_blank" rel="noopener">Member Login</a>
                    <a href="<?php echo $nav_base; ?>resources/">Resources</a>
                    <a href="<?php echo $nav_base; ?>contacts.php">Contacts</a>
                    <a href="<?php echo $nav_base; ?>apply.php">Apply for Membership</a>
                </nav>
            </div>

        </div>

        <div class="footer__bottom">
            <p class="footer__copy">
                &copy; <?php echo date('Y'); ?>
                <?php echo htmlspecialchars($rcs['name']); ?>.
                All rights reserved.
            </p>
            <p class="footer__reg">Regulated by <strong>SASRA</strong></p>
        </div>
    </div>
</footer>

<!-- WhatsApp floating button -->
<a href="https://wa.me/<?php echo preg_replace('/\D/', '', $rcs['cellphone']); ?>"
   class="whatsapp-float"
   target="_blank"
   rel="noopener"
   aria-label="Chat with us on WhatsApp">
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
</a>
