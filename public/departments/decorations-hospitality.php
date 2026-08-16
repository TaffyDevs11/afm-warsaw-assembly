<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Decorations & Hospitality';
$pageDescription = 'The Decorations & Hospitality department at AFM Warsaw creates a beautiful, welcoming atmosphere for services and special church events.';
$canonicalPath = 'departments/decorations-hospitality.php';

$heroEyebrow = 'Department';
$heroText = 'Creating a beautiful, welcoming atmosphere that honours God and blesses everyone who walks in.';
$heroImage = '74-819x1229.webp';
$introTitle = 'Beauty That Welcomes';
$introText = 'From arranging the sanctuary to preparing special decor for weddings, dedications, and celebrations, the Decorations & Hospitality team pays attention to the small details that make our church feel like home.';
$activities = [
    ['title' => 'Sanctuary Decor', 'text' => 'Preparing the worship space each week so it reflects order, beauty, and excellence.'],
    ['title' => 'Special Events', 'text' => 'Designing decor for weddings, dedications, anniversaries, and church celebrations.'],
    ['title' => 'Guest Hospitality', 'text' => 'Looking after visiting ministers and guests, making sure they feel honoured and cared for.'],
];
$joinText = 'Have an eye for detail and design? Bring your creativity to a team that beautifies God\'s house.';

require __DIR__ . '/../includes/dept_template.php';
