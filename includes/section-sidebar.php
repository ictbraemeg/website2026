<?php
/**
 * includes/section-sidebar.php
 * Left sidebar listing all sub-pages of the current section.
 *
 * Variables to set before including:
 *   $sidebar_items  (array)  — [ ['label'=>'Who We Are','href'=>'who-we-are.php', 'active'=>false], ... ]
 *   $sidebar_title  (string) — section name e.g. 'About Us'
 */
$sidebar_items = isset($sidebar_items) ? $sidebar_items : [];
$sidebar_title = isset($sidebar_title) ? $sidebar_title : '';
?>
<aside class="section-sidebar" aria-label="<?php echo htmlspecialchars($sidebar_title); ?> navigation">
    <?php if ($sidebar_title): ?>
    <h2 class="section-sidebar__title"><?php echo htmlspecialchars($sidebar_title); ?></h2>
    <?php endif; ?>
    <nav>
        <ul class="section-sidebar__list">
            <?php foreach ($sidebar_items as $item): ?>
            <li class="section-sidebar__item">
                <a href="<?php echo htmlspecialchars($item['href']); ?>"
                   class="section-sidebar__link <?php echo !empty($item['active']) ? 'section-sidebar__link--active' : ''; ?>">
                    <span class="section-sidebar__arrow" aria-hidden="true">›</span>
                    <?php echo htmlspecialchars($item['label']); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</aside>
