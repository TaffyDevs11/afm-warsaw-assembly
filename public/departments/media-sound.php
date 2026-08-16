<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Media & Sound';
$pageDescription = 'The Media & Sound department at AFM Warsaw handles live sound, streaming, photography, and visuals so every service is heard and shared clearly.';
$canonicalPath = 'departments/media-sound.php';

$heroEyebrow = 'Department';
$heroText = 'Behind the scenes with sound, cameras, and streaming so every message is heard clearly, near and far.';
$heroImage = '38-819x1021.webp';
$introTitle = 'Excellence Behind the Screen';
$introText = 'From mixing live sound to running our Instagram Live stream and capturing photos of church life, the Media & Sound team makes sure every service reaches the congregation - in the room and online - with clarity and quality.';
$activities = [
    ['title' => 'Live Sound Engineering', 'text' => 'Mixing microphones, instruments, and playback for a clear, balanced sound every service.'],
    ['title' => 'Livestream & Recording', 'text' => 'Running our Instagram Live broadcasts and recording sermons for the sermon library.'],
    ['title' => 'Photography', 'text' => 'Capturing the moments that make up our church story for the gallery and social media.'],
];
$joinText = 'Technically minded and want to serve? Join the team running sound, cameras, and streams behind the scenes.';

require __DIR__ . '/../includes/dept_template.php';
