<?php
/**
 * public/blog-post.php — Single blog post detail page
 * -----------------------------------------------------------------------
 * Expects ?slug=some-post-slug in the URL. Looks the post up with a
 * prepared statement (never trust $_GET directly in a query).
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$slug = clean_input($_GET['slug'] ?? '');

$stmt = $conn->prepare('SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1 LIMIT 1');
$stmt->bind_param('s', $slug);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    http_response_code(404);
    $pageTitle = 'Post Not Found';
    $pageDescription = 'The blog post you are looking for could not be found.';
    $canonicalPath = 'blog.php';
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <section class="section text-center">
        <div class="container reveal">
            <h1>Post Not Found</h1>
            <p>Sorry, we couldn't find that article. It may have been unpublished or removed.</p>
            <a href="<?= h(PUBLIC_URL) ?>/blog.php" class="btn btn--navy">Back to Blog</a>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$pageTitle = $post['title'];
$pageDescription = $post['excerpt'] ?: mb_strimwidth(strip_tags($post['content']), 0, 160, '...');
$canonicalPath = 'blog-post.php?slug=' . $post['slug'];

require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="detail-header text-center reveal">
            <span class="eyebrow">Blog</span>
            <h1><?= h($post['title']) ?></h1>
            <div class="detail-meta" style="justify-content:center;">
                <span><?= h($post['author']) ?></span>
                <span><?= h(format_date($post['published_at'])) ?></span>
            </div>
        </div>

        <?php if (!empty($post['thumbnail'])): ?>
            <div class="detail-cover reveal">
                <img src="<?= h(BASE_URL . '/' . $post['thumbnail']) ?>" alt="<?= h($post['title']) ?>">
            </div>
        <?php endif; ?>

        <div class="detail-body reveal"><?= nl2br(h($post['content'])) ?></div>

        <div class="text-center reveal" style="margin-top:3rem;">
            <a href="<?= h(PUBLIC_URL) ?>/blog.php" class="btn btn--outline-navy">&larr; Back to All Posts</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
