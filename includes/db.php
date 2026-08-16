<?php
/**
 * db.php
 * -----------------------------------------------------------------------
 * Opens a single MySQLi database connection using the settings from
 * config.php. Every page that needs the database does:
 *
 *   require_once __DIR__ . '/../includes/config.php';
 *   require_once __DIR__ . '/../includes/db.php';
 *
 * and then has a ready-to-use $conn variable (a mysqli object).
 * -----------------------------------------------------------------------
 */

// mysqli_report makes mysqli throw exceptions on error instead of silently
// returning false. This makes mistakes easier to spot while learning.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // In production we don't want to leak DB details to visitors.
    if (APP_ENV === 'local') {
        die('Database connection failed: ' . $e->getMessage() .
            '<br>Have you created the "afm_warsaw" database and imported sql/schema.sql?');
    }
    die('Sorry, the site is temporarily unavailable. Please try again shortly.');
}
