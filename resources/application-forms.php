<?php
/**
 * resources/application-forms.php
 *
 * Fix log:
 *  - tbl_downloads → tbl_resources (table did not exist)
 *  - category      → catID         (column name in tbl_resources)
 *  - filePath      → docPath        (column name in tbl_resources)
 *  - Added catID filter — adjust the value(s) below to match your data.
 *    Run: SELECT DISTINCT catID FROM tbl_resources; to confirm the right value.
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/*
 * ── Fetch application forms from tbl_resources ───────────────
 * catID = 'forms' scopes this page to application forms only.
 * Other pages: 'policies', 'bylaws', 'plans', 'financial', etc.
 */
$downloads_qry = $dbc->prepare(
    "SELECT * FROM tbl_resources
     WHERE published = '1'
       AND catID = 'forms'
     ORDER BY title ASC",
);
$downloads_qry->execute();
$downloads = $downloads_qry->fetchAll(PDO::FETCH_ASSOC);

$nav_base = "../";
$nav_active = "resources";
$page_title = "Application Forms — " . htmlspecialchars($rcs["name"]);

$page_heading = "Resources";
$page_sub = "Download forms, policies, and documents you need.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Resources", "href" => "application-forms.php"],
    ["label" => "Downloads & Forms"],
];

$sidebar_title = "Resources";
$sidebar_items = [
    [
        "label" => "Downloads & Forms",
        "href" => "application-forms.php",
        "active" => true,
    ],
    ["label" => "Policies & Bylaws", "href" => "policies.php"],
    ["label" => "Annual Reports", "href" => "annual-reports.php"],
    ["label" => "FAQs", "href" => "faqs.php"],
];
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
                    <span class="section-tag">Downloads</span>
                    <h2 class="inner-page__title">Application Forms &amp; Documents</h2>
                    <p class="inner-page__desc">Download the forms you need to apply for membership, loans or savings products. All forms are in PDF format.</p>
                </div>

                <?php if (!empty($downloads)): ?>
                    <div class="downloads-section animate-on-scroll">
                        <div class="downloads-list">
                            <?php foreach ($downloads as $dl): ?>
                            <div class="download-item">
                                <div class="download-item__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </div>
                                <div class="download-item__info">
                                    <h4 class="download-item__title">
                                        <?php echo htmlspecialchars(
                                            $dl["title"],
                                        ); ?>
                                    </h4>
                                    <?php if (!empty($dl["description"])): ?>
                                    <p class="download-item__desc">
                                        <?php echo htmlspecialchars(
                                            $dl["description"],
                                        ); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($dl["docPath"])): ?>
                                <a href="../downloads/<?php echo htmlspecialchars(
                                    $dl["docPath"],
                                ); ?>"
                                   class="download-item__btn"
                                   download
                                   aria-label="Download <?php echo htmlspecialchars(
                                       $dl["title"],
                                   ); ?>">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" width="16" height="16" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Download
                                </a>
                                <?php else: ?>
                                <span class="download-item__btn download-item__btn--unavailable"
                                      title="File not yet available">
                                    Unavailable
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php else: ?>
                <!-- ── Static fallback — shown when DB returns no rows ── -->
                <!-- Remove this block once tbl_resources is populated.   -->

                <div class="downloads-section animate-on-scroll">
                    <h3 class="inner-section-title">Membership Forms</h3>
                    <div class="downloads-list">
                        <div class="download-item">
                            <div class="download-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <div class="download-item__info">
                                <h4 class="download-item__title">Membership Application Form</h4>
                                <p class="download-item__desc">New member registration — for employees of member schools and their eligible family members.</p>
                            </div>
                            <a href="../downloads/membership-form.pdf" class="download-item__btn" download>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>

                <div class="downloads-section animate-on-scroll">
                    <h3 class="inner-section-title">Loan Application Forms</h3>
                    <div class="downloads-list">
                        <?php
                        $loan_forms = [
                            [
                                "title" => "Normal Loan Application",
                                "file" => "normal-loan-form.pdf",
                                "desc" =>
                                    "Standard loan application for Normal and Development loans.",
                            ],
                            [
                                "title" => "Emergency Loan Application",
                                "file" => "emergency-loan-form.pdf",
                                "desc" =>
                                    "Fast-track emergency loan application.",
                            ],
                            [
                                "title" => "Education Loan Application",
                                "file" => "education-loan-form.pdf",
                                "desc" =>
                                    "For education-related loan requests.",
                            ],
                            [
                                "title" => "Loan Refinancing Application",
                                "file" => "refinancing-form.pdf",
                                "desc" =>
                                    "For members seeking to refinance an existing loan.",
                            ],
                        ];
                        foreach ($loan_forms as $form): ?>
                        <div class="download-item">
                            <div class="download-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <div class="download-item__info">
                                <h4 class="download-item__title"><?php echo $form[
                                    "title"
                                ]; ?></h4>
                                <p class="download-item__desc"><?php echo $form[
                                    "desc"
                                ]; ?></p>
                            </div>
                            <a href="../downloads/<?php echo $form[
                                "file"
                            ]; ?>" class="download-item__btn" download>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download
                            </a>
                        </div>
                        <?php endforeach;
                        ?>
                    </div>
                </div>

                <div class="downloads-section animate-on-scroll">
                    <h3 class="inner-section-title">Savings Forms</h3>
                    <div class="downloads-list">
                        <div class="download-item">
                            <div class="download-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <div class="download-item__info">
                                <h4 class="download-item__title">Toto Savings Application</h4>
                                <p class="download-item__desc">Children's savings plan application — requires child birth certificate.</p>
                            </div>
                            <a href="../downloads/toto-savings-form.pdf" class="download-item__btn" download>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>

                <?php endif; ?>

                <div class="info-note animate-on-scroll">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         width="20" height="20" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p>If you cannot find the form you need, please <a href="../contacts.php">contact our office</a> and we will send it to you directly.</p>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>
</body>
</html>
