<?php
/**
 * admin/blog.php — Blog post CRUD
 * -----------------------------------------------------------------------
 * A slug is generated automatically from the title (and kept unique) so
 * admins never have to think about URLs.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Blog Posts';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = clean_input($_POST['title'] ?? '');
        $excerpt = clean_input($_POST['excerpt'] ?? '');
        $content = clean_input($_POST['content'] ?? '');
        $author = clean_input($_POST['author'] ?? '') ?: 'AFM Warsaw';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if ($title === '' || $content === '') {
            flash_set('error', 'Please provide both a title and content.');
            redirect_to('blog.php');
        }

        $upload = handle_image_upload($_FILES['thumbnail'] ?? [], 'blog', false);
        if (!$upload['success']) {
            flash_set('error', $upload['error']);
            redirect_to('blog.php');
        }

        $slug = unique_slug($conn, 'blog_posts', make_slug($title), $id);

        if ($id > 0) {
            if ($upload['path']) {
                $old = $conn->prepare('SELECT thumbnail FROM blog_posts WHERE id = ?');
                $old->bind_param('i', $id);
                $old->execute();
                $oldRow = $old->get_result()->fetch_assoc();
                $old->close();
                if ($oldRow) delete_uploaded_file($oldRow['thumbnail']);

                $stmt = $conn->prepare('UPDATE blog_posts SET title=?, slug=?, excerpt=?, content=?, author=?, is_published=?, thumbnail=? WHERE id=?');
                $stmt->bind_param('sssssisi', $title, $slug, $excerpt, $content, $author, $isPublished, $upload['path'], $id);
            } else {
                $stmt = $conn->prepare('UPDATE blog_posts SET title=?, slug=?, excerpt=?, content=?, author=?, is_published=? WHERE id=?');
                $stmt->bind_param('sssssii', $title, $slug, $excerpt, $content, $author, $isPublished, $id);
            }
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Blog post updated.');
        } else {
            $stmt = $conn->prepare('INSERT INTO blog_posts (title, slug, excerpt, content, thumbnail, author, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssssssi', $title, $slug, $excerpt, $content, $upload['path'], $author, $isPublished);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Blog post published.');
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $conn->prepare('SELECT thumbnail FROM blog_posts WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            delete_uploaded_file($row['thumbnail']);
            $stmt = $conn->prepare('DELETE FROM blog_posts WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            flash_set('success', 'Blog post deleted.');
        }
    }

    redirect_to('blog.php');
}

$editing = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $editing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$posts = $conn->query('SELECT * FROM blog_posts ORDER BY published_at DESC');

require_once __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/includes/flash.php';
?>

<div class="admin-panel">
    <h2><?= $editing ? 'Edit Blog Post' : 'Write a New Blog Post' ?></h2>
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
                <label for="excerpt">Short Excerpt (shown on the blog grid)</label>
                <input type="text" id="excerpt" name="excerpt" maxlength="300" value="<?= h($editing['excerpt'] ?? '') ?>">
            </div>
            <div class="form-field form-field--full">
                <label for="content">Full Content</label>
                <textarea id="content" name="content" style="min-height:260px;" required><?= h($editing['content'] ?? '') ?></textarea>
                <p class="form-hint">Plain text/paragraphs - line breaks are preserved automatically.</p>
            </div>
            <div class="form-field">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="<?= h($editing['author'] ?? 'AFM Warsaw') ?>">
            </div>
            <div class="form-field">
                <label for="thumbnail">Thumbnail Image <?= $editing ? '(leave empty to keep current)' : '' ?></label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg, image/webp, image/gif" data-preview="#thumbPreview">
                <img id="thumbPreview" alt="" style="display:none;max-width:180px;margin-top:0.6rem;border-radius:8px;">
            </div>
            <div class="form-field form-field--full">
                <label style="display:flex;align-items:center;gap:0.5rem;font-weight:600;">
                    <input type="checkbox" name="is_published" value="1" style="width:auto;" <?= (!$editing || $editing['is_published']) ? 'checked' : '' ?>>
                    Published (visible on the public site)
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn--navy"><?= $editing ? 'Update Post' : 'Publish Post' ?></button>
        <?php if ($editing): ?>
            <a href="blog.php" class="btn btn--outline-navy">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-panel">
    <h2>All Blog Posts (<?= $posts ? $posts->num_rows : 0 ?>)</h2>
    <?php if ($posts && $posts->num_rows > 0): ?>
        <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead><tr><th>Thumbnail</th><th>Title</th><th>Status</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($row = $posts->fetch_assoc()): ?>
                    <tr>
                        <td><?php if ($row['thumbnail']): ?><img class="admin-table-thumb" src="<?= h(BASE_URL . '/' . $row['thumbnail']) ?>" alt=""><?php else: ?>&mdash;<?php endif; ?></td>
                        <td><?= h($row['title']) ?></td>
                        <td><?= $row['is_published'] ? '<span class="badge badge--weekly">Published</span>' : '<span class="badge badge--off">Draft</span>' ?></td>
                        <td><?= h(format_date($row['published_at'])) ?></td>
                        <td class="admin-table-actions">
                            <a href="<?= h(PUBLIC_URL) ?>/blog-post.php?slug=<?= urlencode($row['slug']) ?>" target="_blank" class="btn btn--outline-navy btn--small">View</a>
                            <a href="blog.php?edit=<?= (int) $row['id'] ?>" class="btn btn--outline-navy btn--small">Edit</a>
                            <form method="POST" data-confirm="Delete this blog post?">
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
        <div class="admin-empty">No blog posts yet.</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
