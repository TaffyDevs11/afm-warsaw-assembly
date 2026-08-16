<?php
/**
 * config.php
 * -----------------------------------------------------------------------
 * Central configuration file for the AFM Warsaw Assembly website.
 * Every page (public + admin) should start by requiring this file.
 *
 * It is responsible for:
 *   - Starting the PHP session (needed for admin login)
 *   - Defining site-wide constants (name, URL, upload paths, etc.)
 *   - Defining the database connection settings
 *   - Basic error display settings for local development
 *
 * NOTE FOR BEGINNERS:
 * This file uses simple PHP constants (define()) instead of classes so it
 * is easy to read top to bottom. When you move the site to a live server
 * (Hostinger), you only need to change the values in the two sections
 * marked "XAMPP (local)" and "HOSTINGER (production)" below.
 * -----------------------------------------------------------------------
 */

// Start the session on every page so admin login state is available.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------------------------------------------
// ENVIRONMENT: set to 'local' while developing in XAMPP, 'production'
// once the site is live on Hostinger. This only affects error display.
// -------------------------------------------------------------------
define('APP_ENV', 'local');

if (APP_ENV === 'local') {
    // Show errors while developing locally in XAMPP - helps beginners debug.
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Hide raw PHP errors from visitors in production.
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
}

// -------------------------------------------------------------------
// DATABASE CONNECTION SETTINGS
// -------------------------------------------------------------------
//
// ==== XAMPP (local) ====
// Default XAMPP MySQL has a "root" user with NO password.
// Uncomment this block while developing locally:
//
define('DB_HOST', 'localhost');
define('DB_NAME', 'afm_warsaw');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

// ==== HOSTINGER (production) ====
// When you deploy to Hostinger, comment the block above and uncomment
// this one. Hostinger gives you these values in hPanel > Databases.
// The host is usually "localhost" too, but Hostinger will show the
// exact value for your account.
//
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'u123456789_afmwarsaw');
// define('DB_USER', 'u123456789_admin');
// define('DB_PASS', 'REPLACE_WITH_YOUR_HOSTINGER_DB_PASSWORD');
// define('DB_PORT', 3306);

// -------------------------------------------------------------------
// SITE-WIDE CONSTANTS
// -------------------------------------------------------------------
define('SITE_NAME', 'AFM Church in Poland — Warsaw Christian Centre');
define('SITE_SHORT_NAME', 'AFM Warsaw');

// BASE_URL is used to build absolute links/asset paths. It is built
// automatically from the current request so it works both in XAMPP
// (e.g. http://localhost/afm-warsaw-assembly) and on Hostinger
// (e.g. https://afmwarsaw.pl) without editing this file.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

// Work out the URL path prefix for the project root by comparing the
// filesystem path of this project (the parent of /includes) against the
// web server's document root. This makes links correct whether the site
// is served from the domain root (Hostinger) or a subfolder like
// http://localhost/afm-warsaw-assembly/ (XAMPP) - and works the same for
// pages at any depth (/public/index.php, /public/departments/x.php,
// /admin/login.php, or root files like /sitemap.php).
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$documentRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');

$rootPath = '';
if ($documentRoot !== '' && strpos($projectRoot, $documentRoot) === 0) {
    $rootPath = substr($projectRoot, strlen($documentRoot));
}
define('BASE_URL', $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $rootPath);

define('ASSETS_URL', BASE_URL . '/assets');
define('PUBLIC_URL', BASE_URL . '/public');
define('ADMIN_URL', BASE_URL . '/admin');

// Absolute filesystem path to the assets/uploads folder (for move_uploaded_file).
define('UPLOAD_PATH', dirname(__DIR__) . '/assets/uploads');

// Contact / service info shown across the site (edit here to update everywhere).
define('CHURCH_ADDRESS', 'Domus Carmeli, Solec 61, Warsaw, Poland');
define('CHURCH_SERVICE_TIME', 'Sundays, 11:00 AM - 2:00 PM');
define('CHURCH_EMAIL', 'info@afmwarsaw.pl');
define('CHURCH_PHONE', '+48 123 456 789');
define('CHURCH_FACEBOOK', 'https://www.facebook.com/people/AFM-Warsaw/61555394020487/');
define('CHURCH_INSTAGRAM', 'https://www.instagram.com/afmwarsaw/');

// Maximum upload size for images (5 MB), used by the admin upload forms.
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024);
