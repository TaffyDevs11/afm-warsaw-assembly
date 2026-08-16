<?php
/**
 * admin/gallery.php — Gallery photo upload / delete
 * -----------------------------------------------------------------------
 * Any photo added here appears instantly on public/gallery.php (it's
 * just a SELECT * ordered by newest first - no caching, no rebuild step).
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Gallery';

// ---------------------------------------------------------------------
// Handle form submissions (upload a new photo / delete an existing one)
// ---------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $altText = clean_input($_POST['alt_text'] ?? '') ?: 'AFM Warsaw Assembly';
        $caption = clean_input($_POST['caption'] ?? '');

        $upload = handle_image_upload($_FILES['image'] ?? [], 'gallery', true);

        if (!$upload['success']) {
            flash_set('error', $upload['error']);
        } else {
            $stmt = $conn->prepare('INSERT INTO gallery (image_path, alt_text, caption) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $upload['path'], $altText, $caption);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Photo uploaded successfully.');
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);

        $stmt = $conn->prepare('SELECT image_path FROM gallery WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            delete_uploaded_file($row['image_path']);
            $stmt = $conn->prepare('DELETE FROM gallery WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Photo deleted.');
        }
    }

    redirect_to('gallery.php');
}

$photos = $conn->query('SELECT id, image_path, alt_text, caption FROM gallery ORDER BY uploaded_at DESC');

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<div class="admin-panel">
    <h2>Upload a New Photo</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action" value="add">
        <div class="form-grid">
            <div class="form-field form-field--full">
                <label for="image">Photo</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/webp, image/gif" required data-preview="#imagePreview">
                <p class="form-hint">JPG, PNG, WEBP or GIF - max 5MB.</p>
                <img id="imagePreview" alt="" style="display:none;max-width:220px;margin-top:0.75rem;border-radius:8px;">
            </div>
            <div class="form-field">
                <label for="alt_text">Alt Text (for SEO &amp; accessibility)</label>
                <input type="text" id="alt_text" name="alt_text" placeholder="e.g. Sunday worship service" required>
            </div>
            <div class="form-field">
                <label for="caption">Caption (optional)</label>
                <input type="text" id="caption" name="caption" placeholder="e.g. Sunday Worship">
            </div>
        </div>
        <button type="submit" class="btn btn--navy">Upload Photo</button>
    </form>
</div>

<div class="admin-panel">
    <h2>All Photos (<?= $photos ? $photos->num_rows : 0 ?>)</h2>
    <?php if ($photos && $photos->num_rows > 0): ?>
        <div class="admin-grid">
            <?php while ($photo = $photos->fetch_assoc()): ?>
                <div class="admin-thumb-card">
                    <img src="<?= h(BASE_URL . '/' . $photo['image_path']) ?>" alt="<?= h($photo['alt_text']) ?>">
                    <div class="admin-thumb-card__body">
                        <p><?= h($photo['caption'] ?: $photo['alt_text']) ?></p>
                        <form method="POST" data-confirm="Delete this photo? This cannot be undone.">
                            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $photo['id'] ?>">
                            <button type="submit" class="btn btn--red btn--small btn--block">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="admin-empty">No photos uploaded yet.</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
