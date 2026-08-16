-- =========================================================================
-- AFM Church in Poland — Warsaw Christian Centre
-- Sample / seed data
-- =========================================================================
-- Import this AFTER schema.sql. It gives you a working admin login and a
-- few example records so every public page has real content to preview.
--
-- DEFAULT ADMIN LOGIN (change the password after first login!):
--   username: admin
--   password: AfmWarsaw2026!
-- =========================================================================

SET NAMES utf8mb4;

-- -------------------------------------------------------------------------
-- Default admin account. The hash below was generated with PHP's
-- password_hash() for the password "AfmWarsaw2026!".
-- -------------------------------------------------------------------------
INSERT INTO admin_users (username, password_hash, display_name)
VALUES ('admin', '$2y$12$kjp6RVdEHhvcwIpuPRMu2.n8O9ICMIJxG0tN29OB.2uDTRwtmYTYS', 'Church Admin')
ON DUPLICATE KEY UPDATE username = username;

-- -------------------------------------------------------------------------
-- Gallery: reuse a handful of images already present in assets/images so
-- the masonry gallery has content to render out of the box.
-- -------------------------------------------------------------------------
INSERT INTO gallery (image_path, alt_text, caption) VALUES
('assets/images/02-819x1092.webp', 'Congregation worshipping at AFM Warsaw', 'Sunday Worship'),
('assets/images/16-819x1024.webp', 'AFM Warsaw members in fellowship', 'Fellowship Time'),
('assets/images/96-819x546.webp', 'Praise and worship team leading service', 'Praise & Worship'),
('assets/images/48-819x1229.webp', 'Church members praying together', 'Prayer Meeting'),
('assets/images/30-561x561.webp', 'Youth ministry gathering', 'Youth Sunday'),
('assets/images/38-819x1021.webp', 'Choir singing during service', 'Choir Ministration'),
('assets/images/36-561x699.webp', 'Ushers welcoming guests', 'Welcome Team'),
('assets/images/74-819x1229.webp', 'Baptism service at AFM Warsaw', 'Baptism Sunday'),
('assets/images/94-978x652.webp', 'Pastor Maka preaching the Word', 'Sunday Sermon'),
('assets/images/53-1187x791.webp', 'Congregation during a special service', 'Special Service'),
('assets/images/70-1187x791.webp', 'Community outreach event', 'Community Outreach'),
('assets/images/221-978x652.webp', 'Children ministry activity', 'Sunday School');

-- -------------------------------------------------------------------------
-- Announcements: a mix of weekly and monthly examples.
-- -------------------------------------------------------------------------
INSERT INTO announcements (title, description, category, poster_image, event_date) VALUES
('Sunday Worship Service', 'Join us this Sunday at 11:00 AM for a powerful time of worship, the Word, and fellowship. Doors open at 10:30 AM.', 'weekly', NULL, CURDATE()),
('Midweek Prayer Meeting', 'Come and seek God with us every Wednesday evening. Prayer strengthens our walk with Christ and our church family.', 'weekly', NULL, CURDATE()),
('Monthly Communion Service', 'Our monthly communion service will hold this Sunday during the main service. All believers are welcome to partake.', 'monthly', NULL, CURDATE()),
('Members Fellowship Meeting', 'A monthly gathering for all registered members to review church updates, give feedback, and pray together.', 'monthly', NULL, CURDATE());

-- -------------------------------------------------------------------------
-- Blog posts
-- -------------------------------------------------------------------------
INSERT INTO blog_posts (title, slug, excerpt, content, thumbnail, author, is_published) VALUES
(
    'Welcome to AFM Warsaw Assembly',
    'welcome-to-afm-warsaw-assembly',
    'A warm welcome to everyone visiting our church family for the first time, in person or online.',
    'Welcome to AFM Warsaw Assembly, a place of faith, love, and transformation. Whether you are visiting for the first time or joining us online, you are not here by accident. God has a purpose for your life, and there is a place for you in this family.\n\nAt AFM Warsaw, we are passionate about the Word, led by the Holy Spirit, and committed to raising disciples who impact their world. Come worship with us, grow with us, and experience the life-changing power of Jesus Christ.',
    'assets/images/201-770x514.webp',
    'Pastor Maka',
    1
),
(
    'Growing Together in Fellowship',
    'growing-together-in-fellowship',
    'Why relationships and community are at the heart of everything we do as a church family.',
    'We believe the church is a family where everyone is welcomed, valued, and supported. Relationships matter to us, and we strive to create an environment of love, care, and mutual encouragement.\n\nThrough fellowship and service, we grow stronger together in faith and unity. We invite you to join one of our departments or ministries and take your next step in community.',
    'assets/images/46-819x546.webp',
    'AFM Warsaw',
    1
);

-- -------------------------------------------------------------------------
-- Sermons
-- -------------------------------------------------------------------------
INSERT INTO sermons (title, slug, speaker, description, video_url, thumbnail, sermon_date) VALUES
(
    'Vibrant Worship: Living in God''s Presence',
    'vibrant-worship-living-in-gods-presence',
    'Pastor Maka',
    'A message on what it means to truly worship God with our whole lives, not just on Sundays.',
    'https://www.instagram.com/afmwarsaw/',
    'assets/images/94-978x652.webp',
    CURDATE()
),
(
    'Faith That Moves Mountains',
    'faith-that-moves-mountains',
    'Pastor Maka',
    'An encouraging message about trusting God fully, even when circumstances seem impossible.',
    'https://www.instagram.com/afmwarsaw/',
    'assets/images/53-1187x791.webp',
    CURDATE()
);
