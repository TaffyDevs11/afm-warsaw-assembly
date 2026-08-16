<?php
/**
 * admin/includes/flash.php
 * -----------------------------------------------------------------------
 * Prints the current flash message (if any) as a dismissible banner.
 * Include this right after admin_header.php on any page that might
 * redirect here after a form submission.
 * -----------------------------------------------------------------------
 */
$flash = flash_get();
if ($flash):
?>
<div class="admin-flash admin-flash--<?= h($flash['type']) ?>" role="alert">
    <?= h($flash['message']) ?>
</div>
<?php endif; ?>
