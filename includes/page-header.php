<?php
/**
 * includes/page-header.php
 * Reusable page header banner with breadcrumb.
 *
 * Variables to set before including:
 *   $page_heading  (string) — large H1 text
 *   $page_sub      (string) — optional subtitle
 *   $breadcrumbs   (array)  — [ ['label'=>'Home','href'=>'../index.php'], ... ]
 *                             Last item is current page (no href needed).
 */
$page_heading = isset($page_heading) ? $page_heading : '';
$page_sub     = isset($page_sub)     ? $page_sub     : '';
$breadcrumbs  = isset($breadcrumbs)  ? $breadcrumbs  : [];
?>
<div class="page-header" role="banner">
    <div class="container">
        <?php if (!empty($breadcrumbs)): ?>
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>
                <?php $is_last = ($i === count($breadcrumbs) - 1); ?>
                <?php if (!$is_last): ?>
                    <a href="<?php echo htmlspecialchars($crumb['href']); ?>" class="breadcrumb__item">
                        <?php echo htmlspecialchars($crumb['label']); ?>
                    </a>
                    <span class="breadcrumb__sep" aria-hidden="true">/</span>
                <?php else: ?>
                    <span class="breadcrumb__item breadcrumb__item--current" aria-current="page">
                        <?php echo htmlspecialchars($crumb['label']); ?>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <h1 class="page-header__title"><?php echo htmlspecialchars($page_heading); ?></h1>
        <?php if ($page_sub): ?>
        <p class="page-header__sub"><?php echo htmlspecialchars($page_sub); ?></p>
        <?php endif; ?>
    </div>
</div>
