<?php
/**
 * includes/head.php
 * Shared <head> block for every page.
 *
 * Variables to set BEFORE including this file:
 *   $page_title  (string)  — <title> content; defaults to company name + slogan
 *   $nav_base    (string)  — '' for root pages, '../' for one level deep, etc.
 *   $rcs         (array)   — company row from tbl_company
 */
$nav_base   = isset($nav_base)   ? $nav_base   : '';
$page_title = isset($page_title) ? $page_title
            : htmlspecialchars($rcs['name']) . ' — ' . htmlspecialchars($rcs['slogan']);
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $page_title; ?></title>
<meta name="description" content="<?php echo htmlspecialchars($rcs['slogan'] ?? ''); ?>">
<meta name="author" content="braemegsacco.co.ke">

<!-- Favicon -->
<link rel="icon" href="<?php echo $nav_base; ?>images/icon/favicon.png" type="image/png">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- Site stylesheet -->
<link rel="stylesheet" href="<?php echo $nav_base; ?>css/style.css">
