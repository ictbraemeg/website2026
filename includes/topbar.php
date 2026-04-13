<?php
/**
 * includes/topbar.php
 * Site-wide top information bar — included on every page
 * Requires: $rcs (company row from tbl_company)
 */
?>
<div class="topbar">
    <div class="container">
        <div class="topbar__inner">
            <div class="topbar__left">
                <span class="topbar__item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                    </svg>
                    <a href="tel:<?php echo htmlspecialchars($rcs['cellphone']); ?>">
                        Call us: <?php echo htmlspecialchars($rcs['cellphone']); ?>
                    </a>
                </span>
                <span class="topbar__item">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <a href="mailto:<?php echo htmlspecialchars($rcs['email']); ?>">
                        Email: <?php echo htmlspecialchars($rcs['email']); ?>
                    </a>
                </span>
            </div>

            <div class="topbar__right">
                <a href="https://www.facebook.com/BraemegSaccoLimited" target="_blank" rel="noopener" aria-label="Facebook" class="topbar__item">fb</a>
                <a href="https://www.instagram.com/braemegsaccolimited" target="_blank" rel="noopener" aria-label="Instagram" class="topbar__item">ig</a>
                <a href="https://twitter.com/Braemegsaccoltd" target="_blank" rel="noopener" aria-label="Twitter" class="topbar__item">𝕏</a>

                <span class="topbar__already">Already a Member?</span>
                <a href="https://portal.braemegsacco.co.ke:8085" target="_blank" rel="noopener" class="topbar__login">
                    LOGIN &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
