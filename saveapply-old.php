<?php
/**
 * saveapply.php
 * AJAX POST handler for the membership application form (apply.php).
 * Echoes "Submitted" on success, "1" if email already exists,
 * or an error string — consumed by js/main.js applyForm handler.
 */
date_default_timezone_set('Africa/Nairobi');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

require_once 'config/dbconn.php';

/* ── Sanitise inputs ─────────────────────────────────────────── */
$surname  = strtoupper(trim(strip_tags($_POST['surname']   ?? '')));
$oname    = strtoupper(trim(strip_tags($_POST['othername'] ?? '')));
$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$mobile   = trim(strip_tags($_POST['mobile']               ?? ''));
$idno     = trim(strip_tags($_POST['idno']                 ?? ''));
$postal   = trim(strip_tags($_POST['postal']               ?? ''));
$resi     = trim(strip_tags($_POST['resi']                 ?? ''));
$career   = trim(strip_tags($_POST['career']               ?? ''));
$employer = trim(strip_tags($_POST['employer']             ?? ''));
$added    = date('Y-m-d H:i:s');

/* ── Validate required ───────────────────────────────────────── */
if (!$surname || !$oname || !$email || !$mobile || !$idno) {
    http_response_code(400);
    exit('missing_fields');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('invalid_email');
}

/* ── Injection guard ─────────────────────────────────────────── */
if (preg_match('/(\n|\r|\t|%0A|%0D|%08|%09)/i', $email)) {
    http_response_code(400);
    exit('invalid_input');
}

/* ── Check for duplicate email ───────────────────────────────── */
$check = $dbc->prepare("SELECT PID FROM tbl_membership WHERE email = :email LIMIT 1");
$check->execute([':email' => $email]);

if ($check->rowCount() > 0) {
    echo '1'; /* signals "already registered" to front end */
    exit;
}

/* ── Insert record ───────────────────────────────────────────── */
$stmt = $dbc->prepare(
    "INSERT INTO tbl_membership
        (surname, othername, IDno, email, mobile, postalAdd, residence, career, employer, dateadded)
     VALUES
        (:surname, :oname, :idno, :email, :mobile, :postal, :resi, :career, :employer, :added)"
);

$stmt->execute([
    ':surname'  => $surname,
    ':oname'    => $oname,
    ':idno'     => $idno,
    ':email'    => $email,
    ':mobile'   => $mobile,
    ':postal'   => $postal,
    ':resi'     => $resi,
    ':career'   => $career,
    ':employer' => $employer,
    ':added'    => $added,
]);

if ($stmt->rowCount() > 0) {
    echo 'Submitted';
} else {
    http_response_code(500);
    echo 'db_error';
}
