<?php
/**
 * portal.php
 * Immediately redirects to the external member portal.
 * Kept as a server-side redirect so the URL can be changed in one place.
 */
header('Location: https://portal.braemegsacco.co.ke:8085', true, 302);
exit;
