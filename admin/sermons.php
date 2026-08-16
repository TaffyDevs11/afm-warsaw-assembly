<?php
/**
 * admin/sermons.php — Sermon CRUD (with thumbnail upload)
 * -----------------------------------------------------------------------
 * video_url can be a YouTube link (auto-embedded on the public detail
 * page) or an Instagram/Facebook link (shown as a "Watch" button).
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Sermons';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = clean_input($_POST['title'] ?? '');
        $speaker = clean_input($_POST['speaker'] ?? '') ?: 'Pastor Maka';
        $description = clean_input($_POST['description'] ?? '');
        $videoUrl = clean_input($_POST['video_url'] ?? '');
        $sermonDate = clean_input($_POST['sermon_date'] ?? '');
        $sermonDate = $sermonDate !== '' ? $sermonDate : null;

        if ($title === '') {
            flash_set('error', 'Please enter a sermon title.');
            redirect_to('sermons.php');
        }

        $upload = handle_image_upload($_FILES['thumbnail'] ?? [], 'sermons', false);
        if (!$upload['success']) {
            flash_set('error', $upload['error']);
            redirect_to('sermons.php');
        }

        $slug = unique_slug($conn, 'sermons', make_slug($title), $id);

        if ($id > 0) {
            if ($upload['path']) {
                $old = $conn->prepare('SELECT thumbnail FROM sermons WHERE id = ?');
                $old->bind_param('i', $id);
                $old->execute();
                $oldRow = $old->get_result()->fetch_assoc();
                $old->close();
                if ($oldRow) delete_uploaded_file($oldRow['thumbnail']);

                $stmt = $conn->prepare('UPDATE sermons SET title=?, slug=?, speaker=?, description=?, video_url=?, sermon_date=?, thumbnail=? WHERE id=?');
                $stmt->bind_param('sssssssi', $title, $slug, $speaker, $description, $videoUrl, $sermonDate, $upload['path'], $id);
            } else {
                $stmt = $conn->prepare('UPDATE sermons SET title=?, slug=?, speaker=?, description=?, video_url=?, sermon_date=? WHERE id=?');
                $stmt->bind_param('ssssssi', $title, $slug, $speaker, $description, $videoUrl, $sermonDate, $id);
            }
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Sermon updated.');
        } else {
            $stmt = $conn->prepare('INSERT INTO sermons (title, slug, speaker, description, video_url, sermon_date, thumbnail) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('sssssss', $title, $slug, $speaker, $description, $videoUrl, $sermonDate, $upload['path']);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Sermon added.');
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $conn->prepare('SELECT thumbnail FROM sermons WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            delete_uploaded_file($row['thumbnail']);
            $stmt = $conn->prepare('DELETE FROM sermons WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Sermon deleted.');
        }
    }

    redirect_to('sermons.php');
}

$editing = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM sermons WHERE id = ?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $editing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$sermons = $conn->query('SELECT * FROM sermons ORDER BY sermon_date DESC, id DESC');

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<div class="admin-panel">
    <h2><?= $editing ? 'Edit Sermon' : 'Add New Sermon' ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <div class="form-grid">
            <div class="form-field form-field--full">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required value="<?= h($editing['title'] ?? '') ?>">
            </div>
            <div class="form-field">
                <label for="speaker">Speaker</label>
                <input type="text" id="speaker" name="speaker" value="<?= h($editing['speaker'] ?? 'Pastor Maka') ?>">
            </div>
            <div class="form-field">
                <label for="sermon_date">Sermon Date</label>
                <input type="date" id="sermon_date" name="sermon_date" value="<?= h($editing['sermon_date'] ?? '') ?>">
            </div>
            <div class="form-field form-field--full">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= h($editing['description'] ?? '') ?></textarea>
            </div>
            <div class="form-field form-field--full">
                <label for="video_url">Video / Stream Link</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://youtube.com/watch?v=... or Instagram/Facebook link" value="<?= h($editing['video_url'] ?? '') ?>">
                <p class="form-hint">YouTube links are embedded automatically; other links show as a "Watch" button.</p>
            </div>
            <div class="form-field form-field--full">
                <label for="thumbnail">Thumbnail Image <?= $editing ? '(leave empty to keep current)' : '' ?></label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg, image/webp, image/gif" data-preview="#thumbPreview">
                <img id="thumbPreview" alt="" style="display:none;max-width:180px;margin-top:0.6rem;border-radius:8px;">
            </div>
        </div>
        <button type="submit" class="btn btn--navy"><?= $editing ? 'Update Sermon' : 'Add Sermon' ?></button>
        <?php if ($editing): ?>
            <a href="sermons.php" class="btn btn--outline-navy">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-panel">
    <h2>All Sermons (<?= $sermons ? $sermons->num_rows : 0 ?>)</h2>
    <?php if ($sermons && $sermons->num_rows > 0): ?>
        <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead><tr><th>Thumbnail</th><th>Title</th><th>Speaker</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $sermons->fetch_assoc()): ?>
                    <tr>
                        <td><?php if ($row['thumbnail']): ?><img class="admin-table-thumb" src="<?= h(BASE_URL . '/' . $row['thumbnail']) ?>" alt=""><?php else: ?>&mdash;<?php endif; ?></td>
                        <td><?= h($row['title']) ?></td>
                        <td><?= h($row['speaker']) ?></td>
                        <td><?= h(format_date($row['sermon_date'])) ?></td>
                        <td class="admin-table-actions">
                            <a href="<?= h(PUBLIC_URL) ?>/sermon.php?slug=<?= urlencode($row['slug']) ?>" target="_blank" class="btn btn--outline-navy btn--small">View</a>
                            <a href="sermons.php?edit=<?= (int) $row['id'] ?>" class="btn btn--outline-navy btn--small">Edit</a>
                            <form method="POST" data-confirm="Delete this sermon?">
                                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                <button type="submit" class="btn btn--red btn--small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    <?php else: ?>
        <div class="admin-empty">No sermons yet.</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
