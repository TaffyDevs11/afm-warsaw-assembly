<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Catering';
$pageDescription = 'The Catering department at AFM Warsaw serves the church family with food and hospitality during fellowship gatherings and special events.';
$canonicalPath = 'departments/catering.php';

$heroEyebrow = 'Department';
$heroText = 'Serving the church family with food, warmth, and hospitality during fellowship gatherings and special events.';
$heroImage = '46-819x546.webp';
$introTitle = 'Fellowship Around the Table';
$introText = 'Just as Jesus often shared meals with His disciples, our Catering team creates space for fellowship around food. From Sunday refreshments to special event meals, we serve with generosity and care.';
$activities = [
    ['title' => 'Sunday Refreshments', 'text' => 'Preparing light refreshments that welcome members and visitors after service.'],
    ['title' => 'Event Catering', 'text' => 'Planning and serving meals for church programs, conferences, and celebrations.'],
    ['title' => 'Kitchen & Hygiene', 'text' => 'Maintaining a clean, well-organised kitchen so every meal is prepared safely and with excellence.'],
];
$joinText = 'Love to cook or serve others through food? The Catering team would be blessed to have your hands and heart.';

require __DIR__ . '/../includes/dept_template.php';
