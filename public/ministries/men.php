<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = "Men's Ministry";
$pageDescription = "The Men's Ministry at AFM Warsaw equips men to walk boldly in faith as husbands, fathers, and leaders in their homes, church, and community.";
$canonicalPath = 'ministries/men.php';

$heroEyebrow = 'Ministry';
$heroText = 'Equipping men to walk boldly in faith as husbands, fathers, and leaders in their homes and community.';
$heroImage = '11-819x1021.webp';
$introTitle = 'Men of Purpose and Integrity';
$introText = 'Our Men\'s Ministry exists to build godly men who lead with integrity, love their families well, and stand firm in their faith. Through fellowship, mentorship, and the study of God\'s Word, we sharpen one another as iron sharpens iron.';
$activities = [
    ['title' => 'Brotherhood Meetings', 'text' => 'Regular gatherings for fellowship, accountability, and encouragement among the men of AFM Warsaw.'],
    ['title' => 'Bible Study & Mentorship', 'text' => 'Studying Scripture together and mentoring younger men in their walk with Christ.'],
    ['title' => 'Community Service', 'text' => 'Serving the wider Warsaw community through practical acts of love and outreach.'],
];
$joinText = 'Every man has a place in this brotherhood. Join us as we grow together in faith and purpose.';

require __DIR__ . '/../includes/dept_template.php';
