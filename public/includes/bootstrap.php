<?php
/**
 * public/includes/bootstrap.php
 * -----------------------------------------------------------------------
 * Loads the shared config/functions/db files for every public page.
 * Include this at the very top of every file in /public (and its
 * subfolders departments/ and ministries/) before any HTML output.
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';
