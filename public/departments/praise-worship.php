<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Praise & Worship';
$pageDescription = 'The Praise & Worship department at AFM Warsaw leads the congregation into God\'s presence through music, song, and spirit-filled worship every Sunday.';
$canonicalPath = 'departments/praise-worship.php';

$heroEyebrow = 'Department';
$heroText = 'Leading the congregation into God\'s presence through music, song, and spirit-filled worship.';
$heroImage = '96-819x546.webp';
$introTitle = 'Ushering In His Presence';
$introText = 'Our Praise & Worship team exists to create an atmosphere where the congregation can freely encounter God. Through vocals, instruments, and heartfelt song, we lead every service from the very first note to the final "Amen".';
$activities = [
    ['title' => 'Sunday Worship Leading', 'text' => 'Leading congregational singing and setting the tone for an encounter with God every service.'],
    ['title' => 'Instrumentalists', 'text' => 'Keyboard, drums, guitar and more - musicians who serve with excellence and a servant\'s heart.'],
    ['title' => 'Rehearsals & Training', 'text' => 'Weekly rehearsals to grow in musicianship and prepare our hearts to lead others in worship.'],
];
$joinText = 'Do you sing or play an instrument? We would love to have you on the team - talent plus a heart for God is all it takes to start.';

require __DIR__ . '/../includes/dept_template.php';
