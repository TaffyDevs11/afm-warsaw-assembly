<?php
/**
 * header.php
 * -----------------------------------------------------------------------
 * Shared HTML <head> + site navigation for every PUBLIC page.
 *
 * The calling page must set these variables BEFORE including this file:
 *   $pageTitle       (string) e.g. "About Us"
 *   $pageDescription (string) unique meta description for SEO
 *   $canonicalPath    (string) e.g. "about.php" or "departments/worship.php"
 *
 * Optional:
 *   $bodyClass (string) extra class on <body> for page-specific styling
 * -----------------------------------------------------------------------
 */

$pageTitle = $pageTitle ?? SITE_SHORT_NAME;
$pageDescription = $pageDescription ?? 'AFM Church in Poland, Warsaw Christian Centre - a vibrant, spirit-filled church dedicated to worship, discipleship, and community.';
$canonicalPath = $canonicalPath ?? 'index.php';
$bodyClass = $bodyClass ?? '';

$navItems = [
    ['label' => 'Home', 'href' => PUBLIC_URL . '/index.php', 'match' => 'index.php'],
    ['label' => 'About', 'href' => PUBLIC_URL . '/about.php', 'match' => 'about.php'],
    [
        'label' => 'Departments & Ministries',
        'href' => PUBLIC_URL . '/departments.php',
        'match' => 'departments.php',
        'children' => [
            ['label' => 'Praise & Worship', 'href' => PUBLIC_URL . '/departments/praise-worship.php'],
            ['label' => 'Media & Sound', 'href' => PUBLIC_URL . '/departments/media-sound.php'],
            ['label' => 'Catering', 'href' => PUBLIC_URL . '/departments/catering.php'],
            ['label' => 'Ushering', 'href' => PUBLIC_URL . '/departments/ushering.php'],
            ['label' => 'Decorations & Hospitality', 'href' => PUBLIC_URL . '/departments/decorations-hospitality.php'],
            ['label' => 'Men\'s Ministry', 'href' => PUBLIC_URL . '/ministries/men.php'],
            ['label' => 'Women\'s Ministry', 'href' => PUBLIC_URL . '/ministries/women.php'],
            ['label' => 'Youth Ministry', 'href' => PUBLIC_URL . '/ministries/youth.php'],
            ['label' => 'Sunday School', 'href' => PUBLIC_URL . '/ministries/sunday-school.php'],
        ],
    ],
    ['label' => 'Gallery', 'href' => PUBLIC_URL . '/gallery.php', 'match' => 'gallery.php'],
    ['label' => 'Announcements', 'href' => PUBLIC_URL . '/announcements.php', 'match' => 'announcements.php'],
    ['label' => 'Blog', 'href' => PUBLIC_URL . '/blog.php', 'match' => 'blog.php'],
    ['label' => 'Sermons', 'href' => PUBLIC_URL . '/sermons.php', 'match' => 'sermons.php'],
    ['label' => 'Contact', 'href' => PUBLIC_URL . '/contact.php', 'match' => 'contact.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?> | <?= h(SITE_SHORT_NAME) ?></title>
<meta name="description" content="<?= h($pageDescription) ?>">
<link rel="canonical" href="<?= h(PUBLIC_URL . '/' . ltrim($canonicalPath, '/')) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= h(SITE_NAME) ?>">
<meta property="og:title" content="<?= h($pageTitle) ?>">
<meta property="og:description" content="<?= h($pageDescription) ?>">
<meta property="og:image" content="<?= h(ASSETS_URL . '/images/logowhite-479x486.webp') ?>">
<meta name="theme-color" content="#12224A">
<link rel="icon" type="image/png" href="<?= h(ASSETS_URL) ?>/images/logowhite.png-171x160.png">

<!-- Google Fonts: Playfair Display (headings) + Inter (body) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= h(ASSETS_URL) ?>/css/style.css">
</head>
<body class="<?= h($bodyClass) ?>">

<!-- Page loader: shown while assets load, then faded out by main.js -->
<div class="page-loader" id="pageLoader" aria-hidden="true">
    <img src="<?= h(ASSETS_URL) ?>/images/logowhite-479x486.webp" alt="" class="page-loader__crest">
    <span class="page-loader__text">AFM Warsaw</span>
</div>

<a class="skip-link" href="#main-content">Skip to main content</a>

<header class="site-header" id="siteHeader">
    <div class="container site-header__inner">
        <a href="<?= h(PUBLIC_URL) ?>/index.php" class="brand">
            <img src="<?= h(ASSETS_URL) ?>/images/logowhite-479x486.webp" alt="AFM Church in Poland crest" class="brand__crest">
            <span class="brand__text">
                <strong>AFM Church in Poland</strong>
                <small>Warsaw Christian Centre</small>
            </span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Toggle navigation menu">
            <span></span><span></span><span></span>
        </button>

        <nav class="primary-nav" id="primaryNav" aria-label="Primary">
            <ul>
                <?php foreach ($navItems as $item): ?>
                    <?php
                        $isActive = isset($item['match']) && basename($canonicalPath) === $item['match'];
                        $hasChildren = !empty($item['children']);
                    ?>
                    <li class="<?= $hasChildren ? 'has-dropdown' : '' ?> <?= $isActive ? 'is-active' : '' ?>">
                        <a href="<?= h($item['href']) ?>"><?= h($item['label']) ?></a>
                        <?php if ($hasChildren): ?>
                            <ul class="dropdown">
                                <?php foreach ($item['children'] as $child): ?>
                                    <li><a href="<?= h($child['href']) ?>"><?= h($child['label']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>

<main id="main-content">
