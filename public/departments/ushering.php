<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Ushering';
$pageDescription = 'The Ushering department at AFM Warsaw welcomes every guest with warmth, order, and care from the moment they arrive.';
$canonicalPath = 'departments/ushering.php';

$heroEyebrow = 'Department';
$heroText = 'The first smiling faces you meet - welcoming every guest with warmth, order, and genuine care.';
$heroImage = '36-561x699.webp';
$introTitle = 'The Ministry of First Impressions';
$introText = 'Our Ushering team sets the tone for every service - greeting visitors and members alike, guiding seating, managing the flow of the room, and making sure everyone feels seen and welcomed the moment they walk through the door.';
$activities = [
    ['title' => 'Welcoming Guests', 'text' => 'Greeting everyone with a warm smile and helping first-time visitors feel at home.'],
    ['title' => 'Seating & Order', 'text' => 'Guiding the congregation to seats and maintaining a peaceful, orderly atmosphere.'],
    ['title' => 'Offering & Logistics', 'text' => 'Coordinating the practical flow of service - offerings, programs, and assisting where needed.'],
];
$joinText = 'If you love making people feel welcome, the Ushering team is a wonderful place to serve every Sunday.';

require __DIR__ . '/../includes/dept_template.php';
