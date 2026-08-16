<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Sunday School';
$pageDescription = 'Sunday School at AFM Warsaw teaches children biblical foundations for life through Bible stories, songs, and age-appropriate lessons.';
$canonicalPath = 'ministries/sunday-school.php';

$heroEyebrow = 'Ministry';
$heroText = 'Planting the seed of God\'s Word early - teaching children biblical foundations for life.';
$heroImage = '221-978x652.webp';
$introTitle = 'Training Up a Child';
$introText = '"Train up a child in the way he should go" (Proverbs 22:6). Our Sunday School team creates a fun, safe, and age-appropriate environment where children encounter God\'s love through Bible stories, songs, crafts, and prayer.';
$activities = [
    ['title' => 'Bible Story Time', 'text' => 'Engaging, age-appropriate lessons that bring Scripture to life for young hearts and minds.'],
    ['title' => 'Worship & Activities', 'text' => 'Songs, crafts, and games that make learning about God joyful and memorable.'],
    ['title' => 'Safe Environment', 'text' => 'A caring, well-supervised space where every child is loved, valued, and kept safe.'],
];
$joinText = 'Do you love working with children? Help us build strong spiritual foundations for the next generation.';

require __DIR__ . '/../includes/dept_template.php';
