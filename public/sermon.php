<?php
/**
 * public/sermon.php — Single sermon detail page
 * -----------------------------------------------------------------------
 * Expects ?slug=some-sermon-slug in the URL. If the video_url is a
 * YouTube link it is embedded directly; otherwise a "Watch" button links
 * out (e.g. to Instagram/Facebook, which cannot be embedded publicly).
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$slug = clean_input($_GET['slug'] ?? '');

$stmt = $conn->prepare('SELECT * FROM sermons WHERE slug = ? LIMIT 1');
$stmt->bind_param('s', $slug);
$stmt->execute();
$sermon = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$sermon) {
    http_response_code(404);
    $pageTitle = 'Sermon Not Found';
    $pageDescription = 'The sermon you are looking for could not be found.';
    $canonicalPath = 'sermons.php';
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <section class="section text-center">
        <div class="container reveal">
            <h1>Sermon Not Found</h1>
            <p>Sorry, we couldn't find that sermon. It may have been removed.</p>
            <a href="<?= h(PUBLIC_URL) ?>/sermons.php" class="btn btn--navy">Back to Sermons</a>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$pageTitle = $sermon['title'];
$pageDescription = $sermon['description'] ?: ('A sermon by ' . $sermon['speaker'] . ' at AFM Warsaw.');
$canonicalPath = 'sermon.php?slug=' . $sermon['slug'];
$embedUrl = youtube_embed_url($sermon['video_url']);

require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="detail-header text-center reveal">
            <span class="eyebrow">Sermon</span>
            <h1><?= h($sermon['title']) ?></h1>
            <div class="detail-meta" style="justify-content:center;">
                <span><?= h($sermon['speaker']) ?></span>
                <span><?= h(format_date($sermon['sermon_date'])) ?></span>
            </div>
        </div>

        <div class="detail-cover reveal">
            <?php if ($embedUrl): ?>
                <div style="position:relative;padding-top:56.25%;">
                    <iframe src="<?= h($embedUrl) ?>" title="<?= h($sermon['title']) ?>" loading="lazy" allowfullscreen
                        style="position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:var(--radius-lg);"></iframe>
                </div>
            <?php else: ?>
                <img src="<?= h($sermon['thumbnail'] ? BASE_URL . '/' . $sermon['thumbnail'] : ASSETS_URL . '/images/94-978x652.webp') ?>" alt="<?= h($sermon['title']) ?>">
            <?php endif; ?>
        </div>

        <?php if (!empty($sermon['description'])): ?>
            <div class="detail-body reveal"><?= nl2br(h($sermon['description'])) ?></div>
        <?php endif; ?>

        <?php if (!$embedUrl && !empty($sermon['video_url'])): ?>
            <div class="text-center reveal" style="margin-top:1.5rem;">
                <a href="<?= h($sermon['video_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn--red">Watch This Sermon</a>
            </div>
        <?php endif; ?>

        <div class="text-center reveal" style="margin-top:2rem;">
            <a href="<?= h(PUBLIC_URL) ?>/sermons.php" class="btn btn--outline-navy">&larr; Back to All Sermons</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
