<?php
/**
 * sitemap.php — Dynamic XML sitemap
 * -----------------------------------------------------------------------
 * Lives at the site root so it's reachable at /sitemap.php (referenced
 * from /robots.txt). Static pages are listed by hand below; blog posts
 * and sermons are pulled live from the database so new content is
 * included automatically with no manual updates needed.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

$staticPages = [
    'public/index.php',
    'public/about.php',
    'public/departments.php',
    'public/departments/praise-worship.php',
    'public/departments/media-sound.php',
    'public/departments/catering.php',
    'public/departments/ushering.php',
    'public/departments/decorations-hospitality.php',
    'public/ministries/men.php',
    'public/ministries/women.php',
    'public/ministries/youth.php',
    'public/ministries/sunday-school.php',
    'public/gallery.php',
    'public/announcements.php',
    'public/blog.php',
    'public/sermons.php',
    'public/contact.php',
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($staticPages as $page): ?>
    <url>
        <loc><?= htmlspecialchars(BASE_URL . '/' . $page, ENT_QUOTES) ?></loc>
        <changefreq>weekly</changefreq>
    </url>
    <?php endforeach; ?>

    <?php
    $posts = $conn->query("SELECT slug, updated_at FROM blog_posts WHERE is_published = 1");
    while ($post = $posts->fetch_assoc()):
    ?>
    <url>
        <loc><?= htmlspecialchars(BASE_URL . '/public/blog-post.php?slug=' . $post['slug'], ENT_QUOTES) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($post['updated_at'])) ?></lastmod>
        <changefreq>monthly</changefreq>
    </url>
    <?php endwhile; ?>

    <?php
    $sermons = $conn->query("SELECT slug, updated_at FROM sermons");
    while ($sermon = $sermons->fetch_assoc()):
    ?>
    <url>
        <loc><?= htmlspecialchars(BASE_URL . '/public/sermon.php?slug=' . $sermon['slug'], ENT_QUOTES) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($sermon['updated_at'])) ?></lastmod>
        <changefreq>monthly</changefreq>
    </url>
    <?php endwhile; ?>
</urlset>
