<?php
/**
 * live_widget.php
 * -----------------------------------------------------------------------
 * Floating "We are LIVE" widget. Included near the bottom of footer.php
 * on every public page. Only renders anything when the admin has set
 * is_live = 1 in the live_status table, so it never shows a stale or
 * broken embed link when the church is not streaming.
 *
 * Requires $conn (db.php) to already be included by the caller.
 * -----------------------------------------------------------------------
 */

$liveResult = $conn->query('SELECT is_live, embed_link, label FROM live_status WHERE id = 1 LIMIT 1');
$liveStatus = $liveResult ? $liveResult->fetch_assoc() : null;

if ($liveStatus && (int) $liveStatus['is_live'] === 1 && !empty($liveStatus['embed_link'])):
?>
<a href="<?= h($liveStatus['embed_link']) ?>" target="_blank" rel="noopener noreferrer" class="live-widget" aria-label="<?= h($liveStatus['label']) ?> - opens Instagram Live">
    <span class="live-widget__dot"></span>
    <span class="live-widget__text"><?= h($liveStatus['label']) ?></span>
</a>
<?php endif; ?>
