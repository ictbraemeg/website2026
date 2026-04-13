<?php
/**
 * reachus.php / reachuspage.php
 * These were footer form partials in the old site.
 * The new site embeds the contact form directly in the footer (includes/footer.php).
 * Redirect any direct hits to the contacts page.
 */
header('Location: ../contacts.php', true, 301);
exit;
