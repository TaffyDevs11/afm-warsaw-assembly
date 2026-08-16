<?php
/**
 * public/index.php — Home page
 * -----------------------------------------------------------------------
 * Welcoming entry point for first-time visitors and returning members.
 * Pulls a couple of the latest announcements and gallery photos straight
 * from the database so the homepage always reflects fresh content.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Home';
$pageDescription = 'Welcome to AFM Church in Poland, Warsaw Christian Centre. A vibrant, spirit-filled church dedicated to worship, discipleship, and community outreach. Join us every Sunday at 11am.';
$canonicalPath = 'index.php';

// Latest 3 gallery photos for the homepage preview strip.
$galleryPreview = $conn->query('SELECT image_path, alt_text FROM gallery ORDER BY uploaded_at DESC LIMIT 6');

// Next couple of announcements (weekly first).
$announcementsPreview = $conn->query("SELECT id, title, description, category FROM announcements ORDER BY category ASC, created_at DESC LIMIT 3");

// Latest sermon, for the "recent message" teaser.
$latestSermon = $conn->query('SELECT title, slug, thumbnail, sermon_date FROM sermons ORDER BY sermon_date DESC, id DESC LIMIT 1')->fetch_assoc();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero">
    <div class="container hero__inner">
        <div class="hero__content reveal">
            <span class="eyebrow">Welcome Home</span>
            <h1>AFM Church in Poland<br>Warsaw Christian Centre</h1>
            <p>A vibrant, spirit-filled family dedicated to worship, discipleship, and community outreach. Whatever brought you here, we are glad you came.</p>
            <div class="hero__actions">
                <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--gold">Plan Your Visit</a>
                <a href="<?= h(PUBLIC_URL) ?>/sermons.php" class="btn btn--outline">Watch a Sermon</a>
            </div>
        </div>
        <div class="hero__media reveal reveal--scale">
            <img src="<?= h(ASSETS_URL) ?>/images/201-770x514.webp" alt="Congregation worshipping at AFM Warsaw" loading="eager">
            <div class="hero__badge">Every Sunday<br>11:00 AM</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="split">
            <div class="reveal reveal--left">
                <span class="eyebrow">Vibrant Worship Services</span>
                <h2>Show love through service</h2>
                <p>Join us in making everyone feel valued and at home in God's house. Every service is an opportunity to encounter God's presence, hear His Word, and grow alongside a family that cares for you.</p>
                <div class="stat-row">
                    <div><strong>11AM</strong><span>Sunday Service</span></div>
                    <div><strong>4</strong><span>Ministries</span></div>
                    <div><strong>5</strong><span>Departments</span></div>
                </div>
                <a href="<?= h(PUBLIC_URL) ?>/about.php" class="btn btn--navy" style="margin-top:2rem;">Learn Our Story</a>
            </div>
            <div class="reveal reveal--right">
                <img src="<?= h(ASSETS_URL) ?>/images/94-978x652.webp" alt="Pastor Maka ministering the Word at AFM Warsaw">
            </div>
        </div>
    </div>
</section>

<section class="section section--navy">
    <div class="container text-center reveal">
        <span class="eyebrow">Stay Connected</span>
        <h2>What's Happening at AFM Warsaw</h2>
        <p style="max-width:60ch;margin:0 auto 2.5rem;">The latest news and announcements from our church family.</p>
    </div>
    <div class="container grid grid--3">
        <?php if ($announcementsPreview && $announcementsPreview->num_rows > 0): ?>
            <?php while ($row = $announcementsPreview->fetch_assoc()): ?>
                <div class="icon-card reveal" style="background:#fff;">
                    <span class="card__tag"><?= $row['category'] === 'weekly' ? 'Weekly' : 'Monthly' ?></span>
                    <h3><?= h($row['title']) ?></h3>
                    <p><?= h(mb_strimwidth($row['description'] ?? '', 0, 130, '…')) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state reveal">No announcements yet. Please check back soon.</div>
        <?php endif; ?>
    </div>
    <div class="text-center reveal" style="margin-top:2.5rem;">
        <a href="<?= h(PUBLIC_URL) ?>/announcements.php" class="btn btn--gold">View All Announcements</a>
    </div>
</section>

<section class="section">
    <div class="container text-center reveal">
        <span class="eyebrow">Photo Gallery</span>
        <h2>Moments From Our Church Family</h2>
    </div>
    <div class="container masonry" style="column-count:3;">
        <?php if ($galleryPreview && $galleryPreview->num_rows > 0): ?>
            <?php while ($photo = $galleryPreview->fetch_assoc()): ?>
                <div class="masonry__item reveal reveal--scale">
                    <img src="<?= h(BASE_URL . '/' . $photo['image_path']) ?>" alt="<?= h($photo['alt_text']) ?>" loading="lazy">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state reveal">Gallery photos will appear here once uploaded.</div>
        <?php endif; ?>
    </div>
    <div class="text-center reveal" style="margin-top:2rem;">
        <a href="<?= h(PUBLIC_URL) ?>/gallery.php" class="btn btn--outline-navy">View Full Gallery</a>
    </div>
</section>

<?php if ($latestSermon): ?>
<section class="section section--alt">
    <div class="container split">
        <div class="reveal reveal--left">
            <img src="<?= h($latestSermon['thumbnail'] ? BASE_URL . '/' . $latestSermon['thumbnail'] : ASSETS_URL . '/images/94-978x652.webp') ?>" alt="<?= h($latestSermon['title']) ?>">
        </div>
        <div class="reveal reveal--right">
            <span class="eyebrow">Latest Message</span>
            <h2><?= h($latestSermon['title']) ?></h2>
            <p>Catch up on our most recent sermon and be encouraged wherever you are.</p>
            <a href="<?= h(PUBLIC_URL) ?>/sermon.php?slug=<?= urlencode($latestSermon['slug']) ?>" class="btn btn--navy">Watch Now</a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--navy text-center">
    <div class="container reveal">
        <span class="eyebrow">Join Us</span>
        <h2>You Have a Place Here</h2>
        <p style="max-width:60ch;margin:0 auto 2rem;">Whether you're firm in your faith or seeking answers, you'll find a warm welcome at AFM Warsaw. Come as you are.</p>
        <div class="hero__actions" style="justify-content:center;">
            <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--gold">Get In Touch</a>
            <a href="<?= h(PUBLIC_URL) ?>/departments.php" class="btn btn--outline">Explore Ministries</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
