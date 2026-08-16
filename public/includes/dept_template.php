<?php
/**
 * public/includes/dept_template.php
 * -----------------------------------------------------------------------
 * Shared layout for every Department and Ministry detail page. Instead
 * of repeating the same HTML nine times, each page (e.g.
 * public/departments/praise-worship.php) just sets a handful of
 * variables below and then requires this template.
 *
 * Expected variables (set by the calling page before requiring this file):
 *   $pageTitle, $pageDescription, $canonicalPath  (SEO / <head>)
 *   $heroEyebrow, $heroText                        (hero copy; $pageTitle is the H1)
 *   $heroImage                                      (path relative to assets/images)
 *   $introTitle, $introText                         (intro/mission section)
 *   $activities  array of ['title' => ..., 'text' => ...]  ("What We Do" cards)
 *   $joinText                                        (closing call-to-action copy)
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/../../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow"><?= h($heroEyebrow) ?></span>
            <h1><?= h($pageTitle) ?></h1>
            <p><?= h($heroText) ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div class="reveal reveal--left">
            <span class="eyebrow">Overview</span>
            <h2><?= h($introTitle) ?></h2>
            <p><?= h($introText) ?></p>
            <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--navy" style="margin-top:1rem;">Get Involved</a>
        </div>
        <div class="reveal reveal--right">
            <img src="<?= h(ASSETS_URL) ?>/images/<?= h($heroImage) ?>" alt="<?= h($pageTitle) ?> at AFM Warsaw">
        </div>
    </div>
</section>

<?php if (!empty($activities)): ?>
<section class="section section--alt">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:2.5rem;">
            <span class="eyebrow">What We Do</span>
            <h2>Serving With Purpose</h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ($activities as $activity): ?>
                <div class="icon-card reveal">
                    <div class="icon-card__glyph" aria-hidden="true">&#10022;</div>
                    <h3><?= h($activity['title']) ?></h3>
                    <p><?= h($activity['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--navy text-center">
    <div class="container reveal">
        <span class="eyebrow">Join This Team</span>
        <h2>Serve Where You're Gifted</h2>
        <p style="max-width:56ch;margin:0 auto 2rem;"><?= h($joinText) ?></p>
        <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--gold">Contact Us to Join</a>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
