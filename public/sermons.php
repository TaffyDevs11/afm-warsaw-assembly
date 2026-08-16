<?php
/**
 * public/sermons.php — Sermon list
 * -----------------------------------------------------------------------
 * Grid of sermons with thumbnails, newest first. Managed from
 * admin/sermons.php. Clicking a card opens sermon.php?slug=...
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Sermons';
$pageDescription = 'Watch and listen to sermons from AFM Church in Poland, Warsaw Christian Centre.';
$canonicalPath = 'sermons.php';

$sermons = $conn->query("SELECT id, title, slug, speaker, thumbnail, sermon_date FROM sermons ORDER BY sermon_date DESC, id DESC");

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">The Word</span>
            <h1>Sermons</h1>
            <p>Catch up on messages from AFM Warsaw, wherever you are.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($sermons && $sermons->num_rows > 0): ?>
            <div class="grid grid--3">
                <?php while ($sermon = $sermons->fetch_assoc()): ?>
                    <a href="<?= h(PUBLIC_URL) ?>/sermon.php?slug=<?= urlencode($sermon['slug']) ?>" class="card reveal">
                        <div class="card__media">
                            <img src="<?= h($sermon['thumbnail'] ? BASE_URL . '/' . $sermon['thumbnail'] : ASSETS_URL . '/images/94-978x652.webp') ?>" alt="<?= h($sermon['title']) ?>" loading="lazy">
                        </div>
                        <div class="card__body">
                            <span class="card__tag">Sermon</span>
                            <h3><?= h($sermon['title']) ?></h3>
                            <div class="card__meta"><?= h($sermon['speaker']) ?> &middot; <?= h(format_date($sermon['sermon_date'])) ?></div>
                            <span class="card__link">Watch Now &rarr;</span>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state reveal">
                <h3>No sermons yet</h3>
                <p>New sermons from our services will appear here soon.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
