<?php
/**
 * admin/includes/admin_header.php
 * -----------------------------------------------------------------------
 * Shared <head> + sidebar layout for admin pages. Requires auth.php to
 * have already run (so $_SESSION['admin_id'] / admin_name exist).
 * The calling page should set $pageTitle before including this file.
 * -----------------------------------------------------------------------
 */

$pageTitle = $pageTitle ?? 'Dashboard';
$currentFile = basename($_SERVER['PHP_SELF']);

$adminNav = [
    ['label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => '&#9673;'],
    ['label' => 'Gallery', 'href' => 'gallery.php', 'icon' => '&#128247;'],
    ['label' => 'Announcements', 'href' => 'announcements.php', 'icon' => '&#128276;'],
    ['label' => 'Blog Posts', 'href' => 'blog.php', 'icon' => '&#9998;'],
    ['label' => 'Sermons', 'href' => 'sermons.php', 'icon' => '&#127911;'],
    ['label' => 'About / Pastor', 'href' => 'about_content.php', 'icon' => '&#128100;'],
    ['label' => 'Live Service', 'href' => 'live_toggle.php', 'icon' => '&#128225;'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?> | Admin - <?= h(SITE_SHORT_NAME) ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="<?= h(ASSETS_URL) ?>/images/logowhite.png-171x160.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= h(ASSETS_URL) ?>/css/style.css">
<link rel="stylesheet" href="<?= h(ASSETS_URL) ?>/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-shell">
    <button class="admin-sidebar-toggle" id="adminSidebarToggle" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>

    <aside class="admin-sidebar" id="adminSidebar">
        <a href="dashboard.php" class="admin-brand">
            <img src="<?= h(ASSETS_URL) ?>/images/logowhite-479x486.webp" alt="AFM Warsaw crest">
            <span>AFM Admin</span>
        </a>
        <nav aria-label="Admin navigation">
            <ul>
                <?php foreach ($adminNav as $item): ?>
                    <li class="<?= $currentFile === $item['href'] ? 'is-active' : '' ?>">
                        <a href="<?= h($item['href']) ?>"><span aria-hidden="true"><?= $item['icon'] ?></span> <?= h($item['label']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="admin-sidebar__footer">
            <p>Signed in as<br><strong><?= h($_SESSION['admin_name'] ?? 'Admin') ?></strong></p>
            <a href="<?= h(PUBLIC_URL) ?>/index.php" target="_blank">View site &rarr;</a>
            <a href="logout.php" class="admin-logout">Log out</a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <h1><?= h($pageTitle) ?></h1>
        </header>
        <div class="admin-content">
