<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Youth Ministry';
$pageDescription = 'The Youth Ministry at AFM Warsaw disciples teenagers and young adults to know Christ and live boldly for Him in every area of life.';
$canonicalPath = 'ministries/youth.php';

$heroEyebrow = 'Ministry';
$heroText = 'Raising a generation of young people who know Christ and live boldly for Him.';
$heroImage = '30-561x561.webp';
$introTitle = 'A Generation on Fire for God';
$introText = 'Our Youth Ministry is a vibrant, energetic space where teenagers and young adults grow in their relationship with God, build lasting friendships, and are equipped to live out their faith at school, at home, and online.';
$activities = [
    ['title' => 'Youth Services', 'text' => 'Dynamic, relevant teaching that connects biblical truth to everyday teenage life.'],
    ['title' => 'Small Groups', 'text' => 'Close-knit groups for honest conversation, prayer, and discipleship.'],
    ['title' => 'Events & Outreach', 'text' => 'Fun events, camps, and outreach programs that build community and share the Gospel with peers.'],
];
$joinText = 'Are you a teenager or young adult looking for real community and purpose? This is your family.';

require __DIR__ . '/../includes/dept_template.php';
