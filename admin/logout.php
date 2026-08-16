<?php
/**
 * admin/logout.php — Destroys the admin session and returns to login.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$_SESSION = [];
session_destroy();

redirect_to('login.php');
