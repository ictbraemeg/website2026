<?php
/**
 * includes/navbar.php
 * Main site navigation — included on every page.
 * Requires: $dbc (PDO connection)
 *
 * For sub-pages (e.g. /about-us/), use includes/navbar-sub.php
 * which adjusts the Home href to ../index.php
 */

$nav_base = isset($nav_base) ? $nav_base : ""; // '' for root, '../' for sub-pages
$nav_active = isset($nav_active) ? $nav_active : "";

// e.g. 'home', 'about', 'products'
?>
<nav class="navbar" aria-label="Main navigation">
    <div class="container">
        <div class="navbar__inner">

            <!-- Logo -->
            <a href="<?php echo $nav_base; ?>index.php" class="logo" aria-label="Braemeg SACCO Home">
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

            <!-- Mobile toggle -->
            <button class="nav__hamburger"
                    id="nav-hamburger"
                    aria-controls="nav-list"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Links -->
            <ul class="nav__list" id="nav-list" role="menubar">
                <li role="none">
                    <a href="<?php echo $nav_base; ?>index.php"
                       class="<?php echo $nav_active === "home"
                           ? "active"
                           : ""; ?>"
                       role="menuitem">Home</a>
                </li>

                <?php
                $nav_qry = $dbc->prepare(
                    "SELECT * FROM tbl_mainmenu WHERE published='1' ORDER BY sort_id ASC",
                );
                $nav_qry->execute();

                /*
                 * $nav_active keyword → DB page_link slug mapping.
                 * Add entries here as new sections are added.
                 */
                $nav_active_map = [
                    "about" => "about-us",
                    "products" => "products",
                    "info" => "information-center",
                    "resources" => "resources",
                ];
                $active_slug = $nav_active_map[$nav_active] ?? "";

                while ($nrow = $nav_qry->fetch(PDO::FETCH_ASSOC)):

                    /*
                     * Skip the Contacts entry — it is rendered separately
                     * below the loop with a clean href and active-state logic.
                     * The DB may store it as 'contacts' or 'contacts.php'.
                     */
                    $page_link_clean = strtolower(
                        rtrim($nrow["page_link"], "/"),
                    );
                    if (
                        $page_link_clean === "contacts" ||
                        $page_link_clean === "contacts.php"
                    ) {
                        continue;
                    }

                    $sub_qry = $dbc->prepare(
                        "SELECT tc.*, tm.page_link AS parent_link
                         FROM tbl_content tc
                         JOIN tbl_mainmenu tm ON tm.PID = tc.menuid
                         WHERE tc.menuid = :mid AND tc.published = '1' AND tc.parid = '1'
                         ORDER BY tc.sortID ASC",
                    );
                    $sub_qry->execute([":mid" => $nrow["PID"]]);
                    $sub_items = $sub_qry->fetchAll(PDO::FETCH_ASSOC);
                    $has_sub = count($sub_items) > 0;
                    $item_active =
                        $active_slug && $nrow["page_link"] === $active_slug;
                    $li_classes = array_filter([
                        $has_sub ? "nav__item--drop" : "",
                        $item_active ? "nav__item--active" : "",
                    ]);
                    ?>
                    <?php
                    /*
                     * Build the href. Directory-style slugs (e.g. "about-us") get a
                     * trailing slash. PHP file slugs (e.g. "contacts.php") do not.
                     */
                    $link_slug = htmlspecialchars($nrow["page_link"]);
                    $trailing =
                        substr($nrow["page_link"], -4) === ".php" ? "" : "/";
                    $item_href = $nav_base . $link_slug . $trailing;
                    ?>
                    <li class="<?php echo implode(
                        " ",
                        $li_classes,
                    ); ?>" role="none">
                        <a href="<?php echo $item_href; ?>"
                           class="<?php echo $item_active ? "active" : ""; ?>"
                           role="menuitem"
                           <?php echo $has_sub
                               ? 'aria-haspopup="true" aria-expanded="false"'
                               : ""; ?>>
                            <?php echo htmlspecialchars($nrow["menu_name"]); ?>
                        </a>

                        <?php if ($has_sub): ?>
                        <ul class="nav__dropdown" role="menu">
                            <?php foreach ($sub_items as $sub): ?>
                            <li role="none">
                                <a href="<?php echo $nav_base .
                                    htmlspecialchars($nrow["page_link"]) .
                                    "/" .
                                    htmlspecialchars($sub["plink"]) .
                                    ".php?page=" .
                                    (int) $sub["PID"]; ?>"
                                   role="menuitem">
                                    <?php echo htmlspecialchars(
                                        $sub["title"],
                                    ); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                <?php
                endwhile;
                ?>

                <li class="nav__item--contacts" role="none">
                    <a href="<?php echo $nav_base; ?>contacts.php"
                       class="<?php echo $nav_active === "contacts"
                           ? "active"
                           : ""; ?>"
                       role="menuitem">Contacts</a>
                </li>

                <li class="nav__cta" role="none">
                    <a href="https://portal.braemegsacco.co.ke:8085"
                       target="_blank"
                       rel="noopener"
                       role="menuitem">Member Login</a>
                </li>
            </ul>

        </div>
    </div>
</nav>
