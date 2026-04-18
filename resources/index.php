<?php
/**
 * resources/index.php — Resources hub (RICH / DB-PREVIEW STYLE)
 * Drop into: resources/index.php
 *
 * Shows a live preview of the 3 most recent items from tbl_resources
 * per category, with a "View all" link to the full listing page.
 */
require_once "../config/shikisho.php";

$qry = $dbc->prepare("SELECT * FROM tbl_company WHERE published='1' LIMIT 1");
$qry->execute();
$rcs = $qry->fetch(PDO::FETCH_ASSOC);

/* ── Per-section preview queries ─────────────────────────────── */
function fetch_preview(PDO $dbc, string $catID, int $limit = 3): array
{
    $q = $dbc->prepare(
        "SELECT PID, title, docPath, infoYear FROM tbl_resources
         WHERE published = '1' AND catID = :cat
         ORDER BY PID DESC LIMIT :lim",
    );
    $q->bindValue(":cat", $catID, PDO::PARAM_STR);
    $q->bindValue(":lim", $limit, PDO::PARAM_INT);
    $q->execute();
    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_preview_multi(PDO $dbc, array $catIDs, int $limit = 3): array
{
    $placeholders = implode(",", array_fill(0, count($catIDs), "?"));
    $q = $dbc->prepare(
        "SELECT PID, title, docPath, infoYear FROM tbl_resources
         WHERE published = '1' AND catID IN ($placeholders)
         ORDER BY PID DESC LIMIT $limit",
    );
    $q->execute($catIDs);
    return $q->fetchAll(PDO::FETCH_ASSOC);
}

$forms_preview = fetch_preview($dbc, "forms", 3);
$reports_preview = fetch_preview($dbc, "plans", 3);
$policies_preview = fetch_preview_multi($dbc, ["policies", "bylaws"], 3);

$nav_base = "../";
$nav_active = "resources";
$page_title = "Resources — " . htmlspecialchars($rcs["name"]);

$page_heading = "Resources";
$page_sub =
    "Forms, policies, reports and answers — everything you need in one place.";
$breadcrumbs = [
    ["label" => "Home", "href" => "../index.php"],
    ["label" => "Resources"],
];

$sidebar_title = "Resources";
$sidebar_items = [
    [
        "label" => "Downloads & Forms",
        "href" => "application-forms.php",
        "active" => false,
    ],
    [
        "label" => "Policies & Bylaws",
        "href" => "policies.php",
        "active" => false,
    ],
    [
        "label" => "Annual Reports",
        "href" => "annual-reports.php",
        "active" => false,
    ],
    ["label" => "FAQs", "href" => "faqs.php", "active" => false],
];

/* Section definitions */
$sections = [
    [
        "id" => "forms",
        "title" => "Downloads & Forms",
        "sub" =>
            "Application forms for membership, loans and savings products.",
        "href" => "application-forms.php",
        "items" => $forms_preview,
        "icon" =>
            '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
        "color" => "green",
        "link_label" => "View all forms",
    ],
    [
        "id" => "policies",
        "title" => "Policies & Bylaws",
        "sub" =>
            "Governing documents, member policies and regulatory compliance materials.",
        "href" => "policies.php",
        "items" => $policies_preview,
        "icon" => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        "color" => "gold",
        "link_label" => "View all policies",
    ],
    [
        "id" => "reports",
        "title" => "Annual Reports",
        "sub" => "Year-by-year financial performance and AGM documents.",
        "href" => "annual-reports.php",
        "items" => $reports_preview,
        "icon" =>
            '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        "color" => "green",
        "link_label" => "View all reports",
    ],
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

                <!-- Intro -->
                <div class="animate-on-scroll">
                    <span class="section-tag">Member Resources</span>
                    <h2 class="inner-page__title">Everything you need, in one place</h2>
                    <p class="inner-page__desc">
                        Access application forms, governing documents, financial reports and
                        answers to common questions — available to members and prospective
                        members at any time.
                    </p>
                </div>

                <!-- ── DB-PREVIEW SECTIONS ───────────────────────── -->
                <?php foreach ($sections as $i => $sec): ?>

                <?php if ($i > 0): ?>
                <div class="content-divider"></div>
                <?php endif; ?>

                <div class="rhr-section animate-on-scroll">
                    <div class="rhr-section__head">
                        <div class="rhr-section__head-text">
                            <div class="rhr-section__badge rhr-section__badge--<?php echo $sec[
                                "color"
                            ]; ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <?php echo $sec["icon"]; ?>
                                </svg>
                            </div>
                            <h3 class="rhr-section__title"><?php echo htmlspecialchars(
                                $sec["title"],
                            ); ?></h3>
                            <p class="rhr-section__sub"><?php echo htmlspecialchars(
                                $sec["sub"],
                            ); ?></p>
                        </div>
                        <a href="<?php echo htmlspecialchars(
                            $sec["href"],
                        ); ?>" class="phr-section__link">
                            <?php echo htmlspecialchars($sec["link_label"]); ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <?php if (!empty($sec["items"])): ?>
                    <div class="rhr-list">
                        <?php foreach ($sec["items"] as $item): ?>
                        <div class="rhr-item">
                            <div class="rhr-item__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <span class="rhr-item__title">
                                <?php echo htmlspecialchars($item["title"]); ?>
                                <?php if (!empty($item["infoYear"])): ?>
                                <span class="rhr-item__year"><?php echo (int) $item[
                                    "infoYear"
                                ]; ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if (!empty($item["docPath"])): ?>
                            <a href="../files/<?php echo htmlspecialchars(
                                $item["docPath"],
                            ); ?>"
                               class="rhr-item__dl"
                               download
                               aria-label="Download <?php echo htmlspecialchars(
                                   $item["title"],
                               ); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="rhr-empty">
                        <a href="<?php echo htmlspecialchars(
                            $sec["href"],
                        ); ?>" class="btn-ghost btn-ghost--sm">
                            Browse <?php echo htmlspecialchars(
                                $sec["title"],
                            ); ?> →
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <?php endforeach; ?>

                <div class="content-divider"></div>

                <!-- FAQs — static card, not DB-driven -->
                <div class="rhr-faq-card animate-on-scroll">
                    <div class="rhr-faq-card__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="rhr-faq-card__text">
                        <h3 class="rhr-faq-card__title">Frequently Asked Questions</h3>
                        <p>Have a question about membership, loans, savings or how Braemeg SACCO works? Browse our FAQ for quick answers.</p>
                    </div>
                    <a href="faqs.php" class="btn-primary">
                        Browse FAQs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                </div>

                <div class="content-divider"></div>

                <!-- CTA -->
                <div class="about-hub__cta animate-on-scroll">
                    <div class="about-hub__cta-text">
                        <h3 class="about-hub__cta-title">Can't find what you need?</h3>
                        <p>Our team is happy to help — reach out and we'll send it directly.</p>
                    </div>
                    <div class="about-hub__cta-actions">
                        <a href="../contacts.php" class="btn-primary">
                            Contact us
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="application-forms.php" class="btn-ghost">Browse forms</a>
                    </div>
                </div>

            </main>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
<script src="../js/main.js"></script>

<style>
/* ── RESOURCES HUB (RICH) — move to style.css once confirmed ────
   Reuses: .about-hub__cta, .phr-section__link (already defined).
   New classes: .rhr-* (resources hub rich).
   ────────────────────────────────────────────────────────────── */

/* Section wrapper */
.rhr-section {
    margin: 0 0 0.5rem;
}

/* Section header */
.rhr-section__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 1.1rem;
    flex-wrap: wrap;
}

.rhr-section__head-text {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

/* Badge */
.rhr-section__badge {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.25rem;
}

.rhr-section__badge svg {
    width: 18px;
    height: 18px;
}

.rhr-section__badge--green {
    background: var(--color-green-light);
}
.rhr-section__badge--green svg { stroke: var(--color-green-mid); }

.rhr-section__badge--gold {
    background: #fef9e7;
}
.rhr-section__badge--gold svg { stroke: var(--color-gold); }

.rhr-section__title {
    font-family: var(--font-display);
    font-size: 1.15rem;
    color: var(--color-dark);
    margin: 0;
    line-height: 1.2;
}

.rhr-section__sub {
    font-size: 0.84rem;
    color: var(--color-text-muted);
    margin: 0;
    max-width: 380px;
}

/* Item list */
.rhr-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.rhr-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.8rem 1rem;
    background: var(--color-off-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-sm);
    transition: border-color var(--transition), background var(--transition);
}

.rhr-item:hover {
    border-color: var(--color-green-mid);
    background: var(--color-green-light);
}

.rhr-item__icon {
    width: 32px;
    height: 32px;
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.rhr-item__icon svg {
    width: 15px;
    height: 15px;
    stroke: var(--color-green-mid);
}

.rhr-item__title {
    flex: 1;
    font-size: 0.88rem;
    font-weight: 500;
    color: var(--color-text);
    line-height: 1.35;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rhr-item__year {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--color-text-muted);
    background: var(--color-border);
    padding: 0.1rem 0.45rem;
    border-radius: 100px;
}

.rhr-item__dl {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-green-mid);
    border-radius: 8px;
    flex-shrink: 0;
    transition: background var(--transition), color var(--transition);
}

.rhr-item__dl svg {
    width: 15px;
    height: 15px;
}

.rhr-item__dl:hover {
    background: var(--color-green-mid);
    color: var(--color-white);
}

.rhr-item__dl:hover svg { stroke: var(--color-white); }

.rhr-empty {
    padding: 0.75rem 0;
}

/* FAQs card */
.rhr-faq-card {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1.5rem;
    background: var(--color-off-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    flex-wrap: wrap;
}

.rhr-faq-card__icon {
    width: 48px;
    height: 48px;
    background: var(--color-green-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.rhr-faq-card__icon svg {
    width: 22px;
    height: 22px;
    stroke: var(--color-green-mid);
}

.rhr-faq-card__text {
    flex: 1;
    min-width: 180px;
}

.rhr-faq-card__title {
    font-family: var(--font-display);
    font-size: 1.05rem;
    color: var(--color-dark);
    margin-bottom: 0.2rem;
}

.rhr-faq-card__text p {
    font-size: 0.85rem;
    color: var(--color-text-muted);
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
    .rhr-section__head { flex-direction: column; gap: 0.75rem; }
    .rhr-faq-card { flex-direction: column; align-items: flex-start; }
    .about-hub__cta { flex-direction: column; align-items: flex-start; padding: 1.5rem; }
}
</style>

</body>
</html>
