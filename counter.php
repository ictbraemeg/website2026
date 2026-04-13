<?php
/**
 * counter.php
 * Self-hosted visitor counter.
 *
 * - Stores the count in counter/visits.txt (no DB schema changes needed)
 * - Uses PHP sessions to count each visitor only once per browser session,
 *   not once per page load
 * - Provides get_visitor_count() for display and bump_visitor_count() to increment
 *
 * SETUP: Create the directory and file on your server:
 *   mkdir counter && echo "0" > counter/visits.txt
 *   chmod 755 counter && chmod 644 counter/visits.txt
 *
 * Include this file once at the top of index.php, before HTML output.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('COUNTER_FILE', __DIR__ . '/counter/visits.txt');
define('COUNTER_DIR',  __DIR__ . '/counter');

/**
 * Read the current visitor count from the file.
 * Returns 0 on any error rather than crashing.
 */
function get_visitor_count(): int {
    if (!file_exists(COUNTER_FILE)) { return 0; }
    $val = (int) trim(file_get_contents(COUNTER_FILE));
    return max(0, $val);
}

/**
 * Increment the count by 1 using an exclusive file lock
 * so concurrent requests don't corrupt the value.
 */
function bump_visitor_count(): void {
    if (!is_dir(COUNTER_DIR)) {
        mkdir(COUNTER_DIR, 0755, true);
    }

    $fh = fopen(COUNTER_FILE, file_exists(COUNTER_FILE) ? 'r+' : 'w+');
    if (!$fh) { return; }

    if (flock($fh, LOCK_EX)) {
        $current = (int) trim(fgets($fh) ?: '0');
        $new     = $current + 1;
        rewind($fh);
        ftruncate($fh, 0);
        fwrite($fh, (string) $new);
        fflush($fh);
        flock($fh, LOCK_UN);
    }

    fclose($fh);
}

/**
 * Format a large number nicely: 1234567 → "1,234,567"
 */
function format_count(int $n): string {
    return number_format($n);
}

/* ── Count this visit if it's a new session ─────────────────── */
if (empty($_SESSION['braemeg_visited'])) {
    $_SESSION['braemeg_visited'] = true;
    bump_visitor_count();
}

$visitor_count = get_visitor_count();
