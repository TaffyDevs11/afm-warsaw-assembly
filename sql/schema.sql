-- =========================================================================
-- AFM Church in Poland — Warsaw Christian Centre
-- Database schema
-- =========================================================================
-- HOW TO USE (XAMPP):
--   1. Open phpMyAdmin (http://localhost/phpmyadmin)
--   2. Create a database named "afm_warsaw" (utf8mb4_general_ci)
--   3. Select it, click "Import", choose this file, click "Go"
--      -- or from a terminal: mysql -u root afm_warsaw < sql/schema.sql
--   4. Then import sql/seed.sql the same way to get sample content.
--
-- HOW TO USE (Hostinger):
--   Same steps, but through hPanel > Databases > phpMyAdmin using the
--   database name/user Hostinger generated for you (see includes/config.php).
-- =========================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- -------------------------------------------------------------------------
-- Table: admin_users
-- Purpose: login accounts for the admin dashboard. Passwords are stored
-- as bcrypt hashes (PHP password_hash), never plain text.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL DEFAULT 'Admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------------
-- Table: about_content
-- Purpose: holds the editable text for the About page (mission, vision,
-- values, pastor bio). Designed as a single-row "settings" style table so
-- the admin form is a simple edit form rather than a list of records.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS about_content (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mission_text TEXT,
    vision_text TEXT,
    values_text TEXT,
    pastor_name VARCHAR(150),
    pastor_title VARCHAR(150),
    pastor_message TEXT,
    pastor_image VARCHAR(255),
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------------
-- Table: gallery
-- Purpose: photos shown on the public Gallery page. Admin can add/delete
-- rows; the public page simply SELECTs everything ordered by newest first,
-- so uploads appear instantly with no code changes.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,   -- relative path inside assets/uploads/gallery
    alt_text VARCHAR(255) NOT NULL DEFAULT 'AFM Warsaw Assembly', -- SEO/accessibility
    caption VARCHAR(255) DEFAULT NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------------
-- Table: announcements
-- Purpose: weekly/monthly notices, each with an optional poster image.
-- The "category" column drives which section (Weekly / Monthly) the
-- announcement is displayed in on the public Announcements page.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category ENUM('weekly', 'monthly') NOT NULL DEFAULT 'weekly',
    poster_image VARCHAR(255) DEFAULT NULL, -- relative path inside assets/uploads/posters
    event_date DATE DEFAULT NULL,           -- optional date the announcement refers to
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------------
-- Table: blog_posts
-- Purpose: written articles published from the admin panel. "slug" builds
-- clean SEO-friendly URLs like /public/blog-post.php?slug=welcome-2026.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt VARCHAR(300) DEFAULT NULL,   -- short summary used on the list/grid view
    content MEDIUMTEXT NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL, -- relative path inside assets/uploads/blog
    author VARCHAR(120) DEFAULT 'AFM Warsaw',
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    published_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------------
-- Table: sermons
-- Purpose: sermon library entries with a thumbnail and a video/audio link
-- (e.g. a YouTube/Facebook link). "slug" builds the detail page URL.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sermons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    speaker VARCHAR(150) DEFAULT 'Pastor Maka',
    description TEXT,
    video_url VARCHAR(400) DEFAULT NULL,  -- YouTube/Facebook/Instagram link to the sermon
    thumbnail VARCHAR(255) DEFAULT NULL,  -- relative path inside assets/uploads/sermons
    sermon_date DATE DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------------------
-- Table: live_status
-- Purpose: single-row "flag" table controlling the floating Live Service
-- widget shown on the public site. When is_live = 1, the widget appears
-- and links/embeds embed_link (the Instagram Live URL). Admin flips this
-- off after the service ends so the widget disappears and never shows a
-- stale/broken embed.
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS live_status (
    id INT UNSIGNED PRIMARY KEY DEFAULT 1,
    is_live TINYINT(1) NOT NULL DEFAULT 0,
    embed_link VARCHAR(400) DEFAULT NULL,
    label VARCHAR(150) DEFAULT 'We are LIVE on Instagram!',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Make sure there is always exactly one live_status row (id = 1) so the
-- admin toggle page can always UPDATE it instead of worrying about INSERT.
INSERT INTO live_status (id, is_live, embed_link, label)
VALUES (1, 0, 'https://www.instagram.com/afmwarsaw/', 'We are LIVE on Instagram!')
ON DUPLICATE KEY UPDATE id = id;

-- Make sure there is always exactly one about_content row (id = 1) too.
INSERT INTO about_content (id, mission_text, vision_text, values_text, pastor_name, pastor_title, pastor_message, pastor_image)
VALUES (
    1,
    'We are believers of all ages and backgrounds, united by our faith in Jesus Christ. Whether you''re firm in your faith or seeking answers, you''ll find a warm welcome here. Our church is a place of acceptance, where questions are encouraged, and everyone is supported on their spiritual journey.',
    'To spread the love and teachings of Jesus throughout Warsaw.',
    'We believe the church is a family where everyone is welcomed, valued, and supported. Relationships matter to us, and we strive to create an environment of love, care, and mutual encouragement. Through fellowship and service, we grow stronger together in faith and unity.',
    'Pastor Maka',
    'Lead Pastor of AFM, Warsaw',
    'Welcome to AFM Warsaw Assembly, a place of faith, love, and transformation. Whether you are visiting for the first time or joining us online, you are not here by accident. God has a purpose for your life, and there is a place for you in this family.\n\nAt AFM Warsaw, we are passionate about the Word, led by the Holy Spirit, and committed to raising disciples who impact their world. Come worship with us, grow with us, and experience the life-changing power of Jesus Christ.\n\nGod bless you,\nPastor Maka',
    'assets/images/94-978x652.webp'
) ON DUPLICATE KEY UPDATE id = id;
