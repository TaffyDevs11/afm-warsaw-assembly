<?php
/**
 * admin/about_content.php — Edit the About page content
 * -----------------------------------------------------------------------
 * Edits the single row (id = 1) in about_content: mission, vision,
 * values, and the "Meet the Pastor" section (including pastor photo).
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'About / Pastor Content';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();

    $mission = clean_input($_POST['mission_text'] ?? '');
    $vision = clean_input($_POST['vision_text'] ?? '');
    $values = clean_input($_POST['values_text'] ?? '');
    $pastorName = clean_input($_POST['pastor_name'] ?? '');
    $pastorTitle = clean_input($_POST['pastor_title'] ?? '');
    $pastorMessage = clean_input($_POST['pastor_message'] ?? '');

    $upload = handle_image_upload($_FILES['pastor_image'] ?? [], 'about', false);
    if (!$upload['success']) {
        flash_set('error', $upload['error']);
        redirect_to('about_content.php');
    }

    if ($upload['path']) {
        $old = $conn->query('SELECT pastor_image FROM about_content WHERE id = 1')->fetch_assoc();
        if ($old && $old['pastor_image']) delete_uploaded_file($old['pastor_image']);

        $stmt = $conn->prepare('UPDATE about_content SET mission_text=?, vision_text=?, values_text=?, pastor_name=?, pastor_title=?, pastor_message=?, pastor_image=? WHERE id=1');
        $stmt->bind_param('sssssss', $mission, $vision, $values, $pastorName, $pastorTitle, $pastorMessage, $upload['path']);
    } else {
        $stmt = $conn->prepare('UPDATE about_content SET mission_text=?, vision_text=?, values_text=?, pastor_name=?, pastor_title=?, pastor_message=? WHERE id=1');
        $stmt->bind_param('ssssss', $mission, $vision, $values, $pastorName, $pastorTitle, $pastorMessage);
    }
    $stmt->execute();
    $stmt->close();

    flash_set('success', 'About page content updated.');
    redirect_to('about_content.php');
}

$about = $conn->query('SELECT * FROM about_content WHERE id = 1')->fetch_assoc() ?: [];

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

    <div class="admin-panel">
        <h2>Mission, Vision &amp; Values</h2>
        <div class="form-field">
            <label for="mission_text">Our Mission</label>
            <textarea id="mission_text" name="mission_text"><?= h($about['mission_text'] ?? '') ?></textarea>
        </div>
        <div class="form-field">
            <label for="vision_text">Our Vision</label>
            <textarea id="vision_text" name="vision_text"><?= h($about['vision_text'] ?? '') ?></textarea>
        </div>
        <div class="form-field">
            <label for="values_text">Our Values</label>
            <textarea id="values_text" name="values_text"><?= h($about['values_text'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="admin-panel">
        <h2>Meet the Pastor</h2>
        <div class="form-grid">
            <div class="form-field">
                <label for="pastor_name">Pastor Name</label>
                <input type="text" id="pastor_name" name="pastor_name" value="<?= h($about['pastor_name'] ?? '') ?>">
            </div>
            <div class="form-field">
                <label for="pastor_title">Pastor Title</label>
                <input type="text" id="pastor_title" name="pastor_title" value="<?= h($about['pastor_title'] ?? '') ?>">
            </div>
            <div class="form-field form-field--full">
                <label for="pastor_message">Pastor's Message</label>
                <textarea id="pastor_message" name="pastor_message" style="min-height:180px;"><?= h($about['pastor_message'] ?? '') ?></textarea>
            </div>
            <div class="form-field form-field--full">
                <label for="pastor_image">Pastor Photo (leave empty to keep current)</label>
                <input type="file" id="pastor_image" name="pastor_image" accept="image/png, image/jpeg, image/webp, image/gif" data-preview="#pastorPreview">
                <?php if (!empty($about['pastor_image'])): ?>
                    <p class="form-hint">Current photo:</p>
                    <img src="<?= h(BASE_URL . '/' . $about['pastor_image']) ?>" alt="" style="max-width:160px;border-radius:8px;">
                <?php endif; ?>
                <img id="pastorPreview" alt="" style="display:none;max-width:160px;margin-top:0.6rem;border-radius:8px;">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn--navy">Save Changes</button>
    <a href="<?= h(PUBLIC_URL) ?>/about.php" target="_blank" class="btn btn--outline-navy">View About Page</a>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
