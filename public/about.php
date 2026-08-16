<?php
/**
 * public/about.php — About page
 * -----------------------------------------------------------------------
 * Displays vision, mission, values and the "Meet the Pastor" section.
 * All text is pulled from the about_content table (single row, id = 1)
 * so the admin panel (admin/about_content.php) can edit it without any
 * code changes.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'About Us';
$pageDescription = 'Discover AFM Warsaw Church - a community of faith, worship, and service. Learn about our mission, vision, values, and meet Pastor Maka.';
$canonicalPath = 'about.php';

$about = $conn->query('SELECT * FROM about_content WHERE id = 1 LIMIT 1')->fetch_assoc();
$about = $about ?: [];

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">Our Story</span>
            <h1>About AFM Warsaw</h1>
            <p>A community of faith, worship, and service - committed to spreading the Gospel in Poland.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div class="reveal reveal--left">
            <span class="eyebrow">Our Mission</span>
            <h2>Why We Gather</h2>
            <p><?= nl2br(h($about['mission_text'] ?? '')) ?></p>
        </div>
        <div class="reveal reveal--right">
            <img src="<?= h(ASSETS_URL) ?>/images/06-819x819.webp" alt="AFM Warsaw congregation in worship">
        </div>
    </div>
</section>

<section class="section section--navy text-center">
    <div class="container reveal">
        <span class="eyebrow">Our Vision</span>
        <h2 style="max-width:22ch;margin:0 auto;"><?= h($about['vision_text'] ?? '') ?></h2>
    </div>
</section>

<section class="section section--alt">
    <div class="container split">
        <div class="reveal reveal--left">
            <img src="<?= h(ASSETS_URL) ?>/images/16-819x1024.webp" alt="AFM Warsaw members in fellowship">
        </div>
        <div class="reveal reveal--right">
            <span class="eyebrow">Our Values</span>
            <h2>Family, Faith, Fellowship</h2>
            <p><?= nl2br(h($about['values_text'] ?? '')) ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:2.5rem;">
            <span class="eyebrow">Meet the Pastor</span>
            <h2>Message From Our Pastor</h2>
        </div>
        <div class="split">
            <div class="reveal reveal--left">
                <img src="<?= h(BASE_URL . '/' . ($about['pastor_image'] ?? 'assets/images/94-978x652.webp')) ?>" alt="<?= h($about['pastor_name'] ?? 'Pastor') ?>">
            </div>
            <div class="reveal reveal--right">
                <div class="pastor-message"><?= nl2br(h($about['pastor_message'] ?? '')) ?></div>
                <h3 style="margin-top:1.5rem;"><?= h($about['pastor_name'] ?? '') ?></h3>
                <p><?= h($about['pastor_title'] ?? '') ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section section--navy text-center">
    <div class="container reveal">
        <h2>Come Grow With Us</h2>
        <p style="max-width:56ch;margin:0 auto 2rem;">There is a place for you in this family. Join us this Sunday.</p>
        <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--gold">Plan Your Visit</a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
