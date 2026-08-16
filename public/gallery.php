<?php
/**
 * public/gallery.php — Photo Gallery
 * -----------------------------------------------------------------------
 * Masonry-style gallery pulling every row from the `gallery` table.
 * Photos are managed entirely from admin/gallery.php - upload/delete
 * there and this page reflects the change immediately (plain SELECT *,
 * no caching, no redeploy needed).
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Gallery';
$pageDescription = 'Browse photos from worship services, fellowship, and events at AFM Church in Poland, Warsaw Christian Centre.';
$canonicalPath = 'gallery.php';

$photos = $conn->query('SELECT id, image_path, alt_text, caption FROM gallery ORDER BY uploaded_at DESC');

$extraScripts = ['gallery.js'];

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">Church Life</span>
            <h1>Photo Gallery</h1>
            <p>Moments of worship, fellowship, and community from across AFM Warsaw.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($photos && $photos->num_rows > 0): ?>
            <div class="masonry">
                <?php while ($photo = $photos->fetch_assoc()): ?>
                    <figure class="masonry__item reveal reveal--scale">
                        <img src="<?= h(BASE_URL . '/' . $photo['image_path']) ?>" alt="<?= h($photo['alt_text']) ?>" loading="lazy">
                        <?php if (!empty($photo['caption'])): ?>
                            <figcaption class="masonry__caption"><?= h($photo['caption']) ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state reveal">
                <h3>No photos yet</h3>
                <p>Check back soon - new photos are added regularly by our media team.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox for full-size photo viewing -->
<div class="lightbox" id="lightbox">
    <button type="button" class="lightbox__close" aria-label="Close">&times;</button>
    <img src="" alt="" id="lightboxImage">
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
