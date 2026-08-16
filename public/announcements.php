<?php
/**
 * public/announcements.php — Announcements
 * -----------------------------------------------------------------------
 * Lists announcements split into Weekly / Monthly tabs, each optionally
 * with a poster image. Fully managed from admin/announcements.php.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Announcements';
$pageDescription = 'Stay up to date with weekly and monthly announcements from AFM Church in Poland, Warsaw Christian Centre.';
$canonicalPath = 'announcements.php';

$weekly = $conn->query("SELECT * FROM announcements WHERE category = 'weekly' ORDER BY created_at DESC");
$monthly = $conn->query("SELECT * FROM announcements WHERE category = 'monthly' ORDER BY created_at DESC");

require_once __DIR__ . '/../includes/header.php';

/**
 * Renders one announcement's markup. Kept as a small local function so
 * the weekly/monthly loops below don't repeat the same HTML twice.
 */
function render_announcement_card(array $item)
{
    ?>
    <div class="announcement-card reveal">
        <div class="announcement-card__poster <?= empty($item['poster_image']) ? 'announcement-card__poster--placeholder' : '' ?>">
            <?php if (!empty($item['poster_image'])): ?>
                <img src="<?= h(BASE_URL . '/' . $item['poster_image']) ?>" alt="Poster for <?= h($item['title']) ?>" loading="lazy">
            <?php else: ?>
                <span aria-hidden="true">&#128276;</span>
            <?php endif; ?>
        </div>
        <div>
            <?php if (!empty($item['event_date'])): ?>
                <div class="announcement-card__date"><?= h(format_date($item['event_date'])) ?></div>
            <?php endif; ?>
            <h3><?= h($item['title']) ?></h3>
            <p><?= nl2br(h($item['description'])) ?></p>
        </div>
    </div>
    <?php
}
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">Don't Miss Out</span>
            <h1>Announcements</h1>
            <p>Everything happening at AFM Warsaw - this week and this month.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="announcement-tabs reveal" role="tablist">
            <button type="button" class="is-active" data-target="weekly-panel" role="tab" aria-selected="true">Weekly</button>
            <button type="button" data-target="monthly-panel" role="tab" aria-selected="false">Monthly</button>
        </div>

        <div id="weekly-panel" data-panel>
            <?php if ($weekly && $weekly->num_rows > 0): ?>
                <?php while ($item = $weekly->fetch_assoc()): render_announcement_card($item); endwhile; ?>
            <?php else: ?>
                <div class="empty-state reveal">No weekly announcements right now. Check back soon.</div>
            <?php endif; ?>
        </div>

        <div id="monthly-panel" data-panel hidden>
            <?php if ($monthly && $monthly->num_rows > 0): ?>
                <?php while ($item = $monthly->fetch_assoc()): render_announcement_card($item); endwhile; ?>
            <?php else: ?>
                <div class="empty-state reveal">No monthly announcements right now. Check back soon.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Simple tab switcher for the Weekly / Monthly panels above (no page reload).
(function () {
    var tabs = document.querySelectorAll('.announcement-tabs button');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');

            document.querySelectorAll('[data-panel]').forEach(function (panel) {
                panel.hidden = panel.id !== tab.getAttribute('data-target');
            });
        });
    });
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
