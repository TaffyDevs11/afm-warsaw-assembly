<?php
/**
 * admin/live_toggle.php — Live Service on/off switch
 * -----------------------------------------------------------------------
 * Controls the floating "We are LIVE" widget on the public site
 * (includes/live_widget.php). Turn it on right before the service and
 * off right after, so the widget never links to a stale/broken stream.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Live Service';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();

    $isLive = isset($_POST['is_live']) ? 1 : 0;
    $embedLink = clean_input($_POST['embed_link'] ?? '');
    $label = clean_input($_POST['label'] ?? '') ?: 'We are LIVE on Instagram!';

    if ($isLive && $embedLink === '') {
        flash_set('error', 'Please provide the Instagram Live link before going live.');
        redirect_to('live_toggle.php');
    }

    $stmt = $conn->prepare('UPDATE live_status SET is_live=?, embed_link=?, label=? WHERE id=1');
    $stmt->bind_param('iss', $isLive, $embedLink, $label);
    $stmt->execute();
    $stmt->close();

    flash_set('success', $isLive ? 'You are now LIVE on the public site!' : 'Live widget turned off.');
    redirect_to('live_toggle.php');
}

$live = $conn->query('SELECT * FROM live_status WHERE id = 1')->fetch_assoc() ?: [];

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<div class="admin-panel">
    <div class="admin-toolbar">
        <h2 style="margin:0;">Live Service Widget</h2>
        <?php if (!empty($live['is_live'])): ?>
            <span class="badge badge--live">Currently LIVE</span>
        <?php else: ?>
            <span class="badge badge--off">Offline</span>
        <?php endif; ?>
    </div>
    <p>When switched on, a floating "We are LIVE" button appears on every public page, linking to your Instagram Live stream. Switch it off as soon as the service ends.</p>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="form-field">
            <label style="display:flex;align-items:center;gap:0.5rem;font-weight:600;">
                <input type="checkbox" name="is_live" value="1" style="width:auto;" <?= !empty($live['is_live']) ? 'checked' : '' ?>>
                We are LIVE right now
            </label>
        </div>
        <div class="form-field">
            <label for="embed_link">Instagram Live Link</label>
            <input type="url" id="embed_link" name="embed_link" placeholder="https://www.instagram.com/afmwarsaw/" value="<?= h($live['embed_link'] ?? '') ?>">
        </div>
        <div class="form-field">
            <label for="label">Widget Label</label>
            <input type="text" id="label" name="label" value="<?= h($live['label'] ?? 'We are LIVE on Instagram!') ?>">
        </div>
        <button type="submit" class="btn btn--navy">Save Live Status</button>
        <a href="<?= h(PUBLIC_URL) ?>/index.php" target="_blank" class="btn btn--outline-navy">Preview Site</a>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
