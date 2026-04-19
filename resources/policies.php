<?php
/**
 * resources/policies.php
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

$nav_base = "../";
$nav_active = "resources";
$page_title = "Policies & Bylaws — " . htmlspecialchars($rcs["name"]);

$page_heading = "Resources";
$page_sub =
    "Governing documents, bylaws and operational policies of Braemeg SACCO.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Resources", "href" => "application-forms.php"],
    ["label" => "Policies & Bylaws"],
];

$sidebar_title = "Resources";
$sidebar_items = [
    ["label" => "Downloads & Forms", "href" => "application-forms.php"],
    [
        "label" => "Policies & Bylaws",
        "href" => "policies.php",
        "active" => true,
    ],
    ["label" => "Annual Reports", "href" => "annual-reports.php"],
    ["label" => "FAQs", "href" => "faqs.php"],
];

/*
 * Fetch policies and bylaws from tbl_resources.
 * catID IN ('policies', 'bylaws') based on confirmed values in the DB.
 */
$policies_qry = $dbc->prepare(
    "SELECT * FROM tbl_resources
     WHERE published = '1' AND catID IN ('policies', 'bylaws')
     ORDER BY catID ASC, title ASC",
);
$policies_qry->execute();
$policies_raw = $policies_qry->fetchAll(PDO::FETCH_ASSOC);

/* Group by catID with human-readable labels */
$cat_labels = [
    "policies" => "Policies",
    "bylaws" => "Bylaws",
];
$policies_grouped = [];
foreach ($policies_raw as $p) {
    $cat = $p["catID"] ?? "policies";
    $policies_grouped[$cat][] = $p;
}
/* Ensure consistent display order */
$policies_grouped = array_merge(
    array_intersect_key($policies_grouped, $cat_labels),
    array_diff_key($policies_grouped, $cat_labels),
);
?>
<!DOCTYPE html>
<html lang="en">
<head><?php include "../includes/head.php"; ?></head>
<body>

<?php include "../includes/topbar.php"; ?>
<?php include "../includes/navbar.php"; ?>
<?php include "../includes/page-header.php"; ?>

<div class="inner-page">
    <div class="container">
        <div class="inner-page__layout">

            <?php include "../includes/section-sidebar.php"; ?>

            <main class="inner-page__content" id="main-content">

                <div class="animate-on-scroll">
                    <span class="section-tag">Governance</span>
                    <h2 class="inner-page__title">Policies &amp; Bylaws</h2>
                    <p class="inner-page__desc">Our governing documents set out the rules, rights and responsibilities of all members and the management of Braemeg SACCO.</p>
                </div>

                <?php if (!empty($policies_grouped)): ?>
                    <?php foreach ($policies_grouped as $catID => $items): ?>
                    <div class="downloads-section animate-on-scroll">
                        <h3 class="inner-section-title">
                            <?php echo htmlspecialchars(
                                $cat_labels[$catID] ?? ucfirst($catID),
                            ); ?>
                        </h3>
                        <div class="downloads-list">
                            <?php foreach ($items as $pol): ?>
                            <div class="download-item">
                                <div class="download-item__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <div class="download-item__info">
                                    <h4 class="download-item__title"><?php echo htmlspecialchars(
                                        $pol["title"],
                                    ); ?></h4>
                                    <?php if (!empty($pol["description"])): ?>
                                    <p class="download-item__desc"><?php echo htmlspecialchars(
                                        $pol["description"],
                                    ); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($pol["docPath"])): ?>
                                <a href="../files/<?php echo htmlspecialchars(
                                    $pol["docPath"],
                                ); ?>"
                                   class="download-item__btn"
                                   download
                                   aria-label="Download <?php echo htmlspecialchars(
                                       $pol["title"],
                                   ); ?>">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Download
                                </a>
                                <?php else: ?>
                                <span class="download-item__btn download-item__btn--unavailable"
                                      title="File not yet available">Unavailable</span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state animate-on-scroll">
                    <div class="empty-state__icon" aria-hidden="true">📄</div>
                    <p class="empty-state__text">Policy documents will appear here once uploaded. Please <a href="../contacts.php">contact us</a> to request a copy.</p>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>
</body>
</html>
