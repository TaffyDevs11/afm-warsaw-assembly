<?php
/**
 * admin/includes/auth.php
 * -----------------------------------------------------------------------
 * Include this (after bootstrap.php) at the top of every protected admin
 * page. It sends anyone who is not logged in back to the login page.
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/bootstrap.php';

if (empty($_SESSION['admin_id'])) {
    redirect_to(ADMIN_URL . '/login.php');
}
