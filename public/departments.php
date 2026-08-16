<?php
/**
 * public/departments.php — Departments & Ministries landing page
 * -----------------------------------------------------------------------
 * A directory page linking out to each of the 5 department pages and
 * 4 ministry pages. Content here is intentionally static (hand-edited
 * in code) since these pages don't change often; only Gallery,
 * Announcements, Blog, and Sermons are database-driven per the spec.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Departments & Ministries';
$pageDescription = 'Explore the departments and ministries at AFM Church in Poland, Warsaw Christian Centre - from Praise & Worship to Youth Ministry, find where you belong.';
$canonicalPath = 'departments.php';

$departments = [
    ['title' => 'Praise & Worship', 'href' => 'departments/praise-worship.php', 'image' => '96-819x546.webp', 'text' => 'Leading the congregation into God\'s presence through music and song.'],
    ['title' => 'Media & Sound', 'href' => 'departments/media-sound.php', 'image' => '38-819x1021.webp', 'text' => 'Sound, streaming, and photography that carry the message clearly.'],
    ['title' => 'Catering', 'href' => 'departments/catering.php', 'image' => '46-819x546.webp', 'text' => 'Serving the church family with food and hospitality.'],
    ['title' => 'Ushering', 'href' => 'departments/ushering.php', 'image' => '36-561x699.webp', 'text' => 'Welcoming every guest with warmth, order, and care.'],
    ['title' => 'Decorations & Hospitality', 'href' => 'departments/decorations-hospitality.php', 'image' => '74-819x1229.webp', 'text' => 'Creating a beautiful atmosphere for services and events.'],
];

$ministries = [
    ['title' => "Men's Ministry", 'href' => 'ministries/men.php', 'image' => '11-819x1021.webp', 'text' => 'Equipping men to lead with integrity at home, church, and community.'],
    ['title' => "Women's Ministry", 'href' => 'ministries/women.php', 'image' => '01-819x1092.webp', 'text' => 'Nurturing women in faith, purpose, and sisterhood.'],
    ['title' => 'Youth Ministry', 'href' => 'ministries/youth.php', 'image' => '30-561x561.webp', 'text' => 'Raising a generation who know Christ and live boldly for Him.'],
    ['title' => 'Sunday School', 'href' => 'ministries/sunday-school.php', 'image' => '221-978x652.webp', 'text' => 'Teaching children biblical foundations for life.'],
];

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">Find Your Place</span>
            <h1>Departments &amp; Ministries</h1>
            <p>Every believer has a place to serve and grow. Explore our departments and ministries and find where you belong.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:2.5rem;">
            <span class="eyebrow">Serving Together</span>
            <h2>Departments</h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ($departments as $dept): ?>
                <a href="<?= h(PUBLIC_URL . '/' . $dept['href']) ?>" class="card reveal">
                    <div class="card__media"><img src="<?= h(ASSETS_URL . '/images/' . $dept['image']) ?>" alt="<?= h($dept['title']) ?> at AFM Warsaw" loading="lazy"></div>
                    <div class="card__body">
                        <span class="card__tag">Department</span>
                        <h3><?= h($dept['title']) ?></h3>
                        <p><?= h($dept['text']) ?></p>
                        <span class="card__link">Learn More &rarr;</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:2.5rem;">
            <span class="eyebrow">Growing Together</span>
            <h2>Ministries</h2>
        </div>
        <div class="grid grid--4">
            <?php foreach ($ministries as $min): ?>
                <a href="<?= h(PUBLIC_URL . '/' . $min['href']) ?>" class="card reveal">
                    <div class="card__media"><img src="<?= h(ASSETS_URL . '/images/' . $min['image']) ?>" alt="<?= h($min['title']) ?> at AFM Warsaw" loading="lazy"></div>
                    <div class="card__body">
                        <span class="card__tag">Ministry</span>
                        <h3><?= h($min['title']) ?></h3>
                        <p><?= h($min['text']) ?></p>
                        <span class="card__link">Learn More &rarr;</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--navy text-center">
    <div class="container reveal">
        <span class="eyebrow">Not Sure Where to Start?</span>
        <h2>We'll Help You Find Your Place</h2>
        <p style="max-width:56ch;margin:0 auto 2rem;">Reach out and we'll connect you with the department or ministry that fits your gifts and season of life.</p>
        <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--gold">Contact Us</a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
