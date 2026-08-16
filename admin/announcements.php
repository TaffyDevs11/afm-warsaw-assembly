<?php
/**
 * admin/announcements.php — Announcements CRUD (weekly / monthly)
 * -----------------------------------------------------------------------
 * One form handles both "add" and "edit" (edit pre-fills the form when
 * ?edit=<id> is in the URL). Poster image upload is optional.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Announcements';

// ---------------------------------------------------------------------
// Handle form submissions
// ---------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = clean_input($_POST['title'] ?? '');
        $description = clean_input($_POST['description'] ?? '');
        $category = ($_POST['category'] ?? '') === 'monthly' ? 'monthly' : 'weekly';
        $eventDate = clean_input($_POST['event_date'] ?? '');
        $eventDate = $eventDate !== '' ? $eventDate : null;

        if ($title === '') {
            flash_set('error', 'Please enter a title for the announcement.');
            redirect_to('announcements.php');
        }

        $upload = handle_image_upload($_FILES['poster_image'] ?? [], 'posters', false);
        if (!$upload['success']) {
            flash_set('error', $upload['error']);
            redirect_to('announcements.php');
        }

        if ($id > 0) {
            // Editing an existing announcement.
            if ($upload['path']) {
                // Replacing the poster: delete the old file first.
                $old = $conn->prepare('SELECT poster_image FROM announcements WHERE id = ?');
                $old->bind_param('i', $id);
                $old->execute();
                $oldRow = $old->get_result()->fetch_assoc();
                $old->close();
                if ($oldRow) {
                    delete_uploaded_file($oldRow['poster_image']);
                }

                $stmt = $conn->prepare('UPDATE announcements SET title=?, description=?, category=?, event_date=?, poster_image=? WHERE id=?');
                $stmt->bind_param('sssssi', $title, $description, $category, $eventDate, $upload['path'], $id);
            } else {
                $stmt = $conn->prepare('UPDATE announcements SET title=?, description=?, category=?, event_date=? WHERE id=?');
                $stmt->bind_param('ssssi', $title, $description, $category, $eventDate, $id);
            }
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Announcement updated.');
        } else {
            // Creating a new announcement.
            $stmt = $conn->prepare('INSERT INTO announcements (title, description, category, event_date, poster_image) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $title, $description, $category, $eventDate, $upload['path']);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Announcement created.');
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $conn->prepare('SELECT poster_image FROM announcements WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            delete_uploaded_file($row['poster_image']);
            $stmt = $conn->prepare('DELETE FROM announcements WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Announcement deleted.');
        }
    }

    redirect_to('announcements.php');
}

// ---------------------------------------------------------------------
// Load a record into the form when editing
// ---------------------------------------------------------------------
$editing = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM announcements WHERE id = ?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $editing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$announcements = $conn->query('SELECT * FROM announcements ORDER BY category ASC, created_at DESC');

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<div class="admin-panel">
    <h2><?= $editing ? 'Edit Announcement' : 'Add New Announcement' ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
        <div class="form-grid">
            <div class="form-field form-field--full">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required value="<?= h($editing['title'] ?? '') ?>">
            </div>
            <div class="form-field form-field--full">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= h($editing['description'] ?? '') ?></textarea>
            </div>
            <div class="form-field">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="weekly" <?= ($editing['category'] ?? '') === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                    <option value="monthly" <?= ($editing['category'] ?? '') === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                </select>
            </div>
            <div class="form-field">
                <label for="event_date">Event Date (optional)</label>
                <input type="date" id="event_date" name="event_date" value="<?= h($editing['event_date'] ?? '') ?>">
            </div>
            <div class="form-field form-field--full">
                <label for="poster_image">Poster Image <?= $editing ? '(leave empty to keep current poster)' : '(optional)' ?></label>
                <input type="file" id="poster_image" name="poster_image" accept="image/png, image/jpeg, image/webp, image/gif" data-preview="#posterPreview">
                <img id="posterPreview" alt="" style="display:none;max-width:220px;margin-top:0.75rem;border-radius:8px;">
                <?php if (!empty($editing['poster_image'])): ?>
                    <p class="form-hint">Current poster:</p>
                    <img src="<?= h(BASE_URL . '/' . $editing['poster_image']) ?>" alt="" style="max-width:160px;border-radius:8px;">
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" class="btn btn--navy"><?= $editing ? 'Update Announcement' : 'Add Announcement' ?></button>
        <?php if ($editing): ?>
            <a href="announcements.php" class="btn btn--outline-navy">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-panel">
    <h2>All Announcements (<?= $announcements ? $announcements->num_rows : 0 ?>)</h2>
    <?php if ($announcements && $announcements->num_rows > 0): ?>
        <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Poster</th><th>Title</th><th>Category</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($row = $announcements->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if ($row['poster_image']): ?>
                                <img class="admin-table-thumb" src="<?= h(BASE_URL . '/' . $row['poster_image']) ?>" alt="">
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                        <td><?= h($row['title']) ?></td>
                        <td><span class="badge badge--<?= h($row['category']) ?>"><?= h(ucfirst($row['category'])) ?></span></td>
                        <td><?= h(format_date($row['event_date'])) ?></td>
                        <td class="admin-table-actions">
                            <a href="announcements.php?edit=<?= (int) $row['id'] ?>" class="btn btn--outline-navy btn--small">Edit</a>
                            <form method="POST" data-confirm="Delete this announcement?">
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
        <div class="admin-empty">No announcements yet.</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
