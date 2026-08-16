<?php
/**
 * public/blog.php — Blog list
 * -----------------------------------------------------------------------
 * Grid of published blog posts, newest first. Managed from
 * admin/blog.php. Clicking a card opens blog-post.php?slug=...
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Blog';
$pageDescription = 'Read articles, reflections, and updates from AFM Church in Poland, Warsaw Christian Centre.';
$canonicalPath = 'blog.php';

$posts = $conn->query("SELECT id, title, slug, excerpt, thumbnail, author, published_at FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC");

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">From Our Church</span>
            <h1>Blog</h1>
            <p>Articles, reflections, and updates from the AFM Warsaw family.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($posts && $posts->num_rows > 0): ?>
            <div class="grid grid--3">
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <a href="<?= h(PUBLIC_URL) ?>/blog-post.php?slug=<?= urlencode($post['slug']) ?>" class="card reveal">
                        <div class="card__media">
                            <img src="<?= h($post['thumbnail'] ? BASE_URL . '/' . $post['thumbnail'] : ASSETS_URL . '/images/201-770x514.webp') ?>" alt="<?= h($post['title']) ?>" loading="lazy">
                        </div>
                        <div class="card__body">
                            <span class="card__tag">Blog</span>
                            <h3><?= h($post['title']) ?></h3>
                            <p><?= h($post['excerpt'] ?: '') ?></p>
                            <div class="card__meta"><?= h($post['author']) ?> &middot; <?= h(format_date($post['published_at'])) ?></div>
                            <span class="card__link">Read More &rarr;</span>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state reveal">
                <h3>No articles yet</h3>
                <p>New posts from our church family will appear here soon.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
