<?php
/**
 * admin/includes/bootstrap.php
 * -----------------------------------------------------------------------
 * Loads the shared config/functions/db files for every admin page.
 * Include this at the very top of every file in /admin (before any HTML
 * output), then include auth.php too on pages that require a logged-in
 * admin (i.e. every page except login.php).
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';
