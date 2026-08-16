<?php
/**
 * admin/dashboard.php — Admin overview
 * -----------------------------------------------------------------------
 * Quick snapshot of site content: counts for each content type plus the
 * current Live Service status.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Dashboard';

$counts = [
    'gallery' => $conn->query('SELECT COUNT(*) AS c FROM gallery')->fetch_assoc()['c'],
    'announcements' => $conn->query('SELECT COUNT(*) AS c FROM announcements')->fetch_assoc()['c'],
    'blog_posts' => $conn->query('SELECT COUNT(*) AS c FROM blog_posts')->fetch_assoc()['c'],
    'sermons' => $conn->query('SELECT COUNT(*) AS c FROM sermons')->fetch_assoc()['c'],
];

$live = $conn->query('SELECT is_live, embed_link FROM live_status WHERE id = 1')->fetch_assoc();

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<div class="stat-cards">
    <div class="stat-card">
        <strong><?= (int) $counts['gallery'] ?></strong>
        <span>Gallery Photos</span>
    </div>
    <div class="stat-card">
        <strong><?= (int) $counts['announcements'] ?></strong>
        <span>Announcements</span>
    </div>
    <div class="stat-card">
        <strong><?= (int) $counts['blog_posts'] ?></strong>
        <span>Blog Posts</span>
    </div>
    <div class="stat-card">
        <strong><?= (int) $counts['sermons'] ?></strong>
        <span>Sermons</span>
    </div>
</div>

<div class="admin-panel">
    <div class="admin-toolbar">
        <h2 style="margin:0;">Live Service Status</h2>
        <?php if ($live && (int) $live['is_live'] === 1): ?>
            <span class="badge badge--live">Currently LIVE</span>
        <?php else: ?>
            <span class="badge badge--off">Offline</span>
        <?php endif; ?>
    </div>
    <p>The floating "We are LIVE" widget <?= ($live && (int) $live['is_live'] === 1) ? 'is currently showing on the public site.' : 'is currently hidden from the public site.' ?></p>
    <a href="live_toggle.php" class="btn btn--navy btn--small">Manage Live Status</a>
</div>

<div class="admin-panel">
    <h2>Quick Actions</h2>
    <div class="grid grid--4" style="gap:1rem;">
        <a href="gallery.php" class="btn btn--outline-navy">+ Add Photo</a>
        <a href="announcements.php" class="btn btn--outline-navy">+ Add Announcement</a>
        <a href="blog.php" class="btn btn--outline-navy">+ Write Blog Post</a>
        <a href="sermons.php" class="btn btn--outline-navy">+ Add Sermon</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
