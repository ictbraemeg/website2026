<?php
/**
 * config/mail.php
 * Central mail configuration included by every file that sends email.
 *
 * ENVIRONMENT
 * ───────────
 * Set APP_ENV in your Laragon vhost or .env wrapper:
 *   - 'development'  →  Mailpit on localhost:1025 (no auth)
 *   - anything else  →  Live SMTP server
 *
 * Quickest way in Laragon: add to your vhost config or just flip the
 * constant below to 'development' while testing, 'production' when live.
 */

define("APP_ENV", getenv("APP_ENV") ?: "development");

if (APP_ENV === "development") {
    /* ── Mailpit (Laragon dev) ─────────────────────────────────
     * Mailpit listens on localhost:1025, no authentication needed.
     * View caught emails at http://localhost:8025
     * ─────────────────────────────────────────────────────────── */
    define("MAIL_HOST", "127.0.0.1");
    define("MAIL_PORT", 1025);
    define("MAIL_AUTH", false);
    define("MAIL_USERNAME", "");
    define("MAIL_PASSWORD", "");
    define("MAIL_FROM", "noreply@braemegsacco.local");
    define("MAIL_FROM_NAME", "Braemeg SACCO (Dev)");
} else {
    /* ── Live SMTP (production) ────────────────────────────────
     * Restore real credentials before deploying.
     * ─────────────────────────────────────────────────────────── */
    define("MAIL_HOST", "mail.braemegsacco.co.ke");
    define("MAIL_PORT", 25);
    define("MAIL_AUTH", true);
    define("MAIL_USERNAME", "office@braemegsacco.co.ke");
    define("MAIL_PASSWORD", "**********");
    define("MAIL_FROM", "office@braemegsacco.co.ke");
    define("MAIL_FROM_NAME", "Braemeg SACCO");
}
