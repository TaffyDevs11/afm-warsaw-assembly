<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = "Women's Ministry";
$pageDescription = "The Women's Ministry at AFM Warsaw nurtures women in faith, purpose, and sisterhood through fellowship, prayer, and the Word of God.";
$canonicalPath = 'ministries/women.php';

$heroEyebrow = 'Ministry';
$heroText = 'Nurturing women in faith, purpose, and sisterhood through fellowship, prayer, and the Word of God.';
$heroImage = '01-819x1092.webp';
$introTitle = 'Women Rooted in Faith';
$introText = 'Our Women\'s Ministry creates a safe, loving space for women of all ages and seasons of life to grow spiritually, support one another, and discover their God-given purpose - as individuals, wives, mothers, and leaders.';
$activities = [
    ['title' => 'Fellowship Gatherings', 'text' => 'Building genuine friendships through regular meetings, prayer, and shared testimonies.'],
    ['title' => 'Bible Study', 'text' => 'Digging into God\'s Word together to grow in wisdom, faith, and character.'],
    ['title' => 'Mentorship & Outreach', 'text' => 'Mentoring younger women and reaching out to women in the community with love and support.'],
];
$joinText = 'You belong in this sisterhood. Come as you are and grow alongside women who will walk with you.';

require __DIR__ . '/../includes/dept_template.php';
