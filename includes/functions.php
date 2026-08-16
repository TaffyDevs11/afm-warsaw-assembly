<?php
/**
 * functions.php
 * -----------------------------------------------------------------------
 * Small, reusable helper functions shared by the public site and the
 * admin panel. Kept as plain procedural functions (no classes) so they
 * are easy to follow for beginners.
 * -----------------------------------------------------------------------
 */

/**
 * Escape a string for safe HTML output. Use this every time you print
 * database or form data inside HTML to prevent XSS attacks.
 */
function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Clean up plain text form input: trims whitespace and strips tags.
 * Note: this does NOT protect against SQL injection - we always use
 * prepared statements (mysqli bind_param) for that.
 */
function clean_input($value)
{
    return trim(strip_tags((string) $value));
}

/**
 * Turn a title into a URL-friendly slug, e.g. "Sunday Service!" ->
 * "sunday-service". Used for blog posts and sermons.
 */
function make_slug($text)
{
    $slug = strtolower(trim((string) $text));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug !== '' ? $slug : 'post-' . time();
}

/**
 * Make sure a slug is unique in a given table/column before saving.
 * If "sunday-service" already exists, tries "sunday-service-2", etc.
 *
 * @param mysqli $conn
 * @param string $table   table name (must be one of a trusted whitelist!)
 * @param string $slug    the candidate slug
 * @param int    $ignoreId row id to ignore (used when editing an existing row)
 */
function unique_slug(mysqli $conn, $table, $slug, $ignoreId = 0)
{
    $allowedTables = ['blog_posts', 'sermons'];
    if (!in_array($table, $allowedTables, true)) {
        throw new InvalidArgumentException('Invalid table for unique_slug()');
    }

    $base = $slug;
    $suffix = 2;

    while (true) {
        $stmt = $conn->prepare("SELECT id FROM {$table} WHERE slug = ? AND id != ? LIMIT 1");
        $stmt->bind_param('si', $slug, $ignoreId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return $slug;
        }

        $stmt->close();
        $slug = $base . '-' . $suffix;
        $suffix++;
    }
}

/**
 * Format a MySQL date/datetime string into something human-friendly,
 * e.g. "16 Aug 2026".
 */
function format_date($mysqlDate)
{
    if (empty($mysqlDate) || $mysqlDate === '0000-00-00') {
        return '';
    }
    $timestamp = strtotime($mysqlDate);
    return $timestamp ? date('j M Y', $timestamp) : '';
}

/**
 * Redirect the browser to another page and stop executing the script.
 */
function redirect_to($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * Generate (or reuse) a CSRF token for the current session and return it.
 * Include it as a hidden field in every admin form:
 *   <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Check a submitted CSRF token against the one stored in the session.
 * Kills the request with a 400 error if it doesn't match.
 */
function verify_csrf_or_die()
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $submitted)) {
        http_response_code(400);
        die('Security check failed (invalid or expired form token). Please go back and try again.');
    }
}

/**
 * Handle a single uploaded image file: validates type/size, moves it into
 * assets/uploads/{$subfolder}, and returns the relative path to store in
 * the database (e.g. "assets/uploads/gallery/1699999999_abcd1234.webp").
 *
 * Returns ['success' => true, 'path' => '...'] on success or
 * ['success' => false, 'error' => '...'] on failure.
 *
 * @param array  $file      one entry of $_FILES, e.g. $_FILES['image']
 * @param string $subfolder gallery|posters|blog|sermons|about
 * @param bool   $required  if false, a missing file is not an error
 */
function handle_image_upload(array $file, $subfolder, $required = true)
{
    $allowedSubfolders = ['gallery', 'posters', 'blog', 'sermons', 'about'];
    if (!in_array($subfolder, $allowedSubfolders, true)) {
        return ['success' => false, 'error' => 'Invalid upload destination.'];
    }

    // No file selected at all.
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $required
            ? ['success' => false, 'error' => 'Please choose an image to upload.']
            : ['success' => true, 'path' => null];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed (error code ' . $file['error'] . ').'];
    }

    if ($file['size'] > MAX_UPLOAD_BYTES) {
        return ['success' => false, 'error' => 'Image is too large. Maximum size is 5 MB.'];
    }

    // Verify the file is really an image by reading its actual MIME type
    // (never trust the client-supplied name/extension alone).
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    if (!isset($allowedMimes[$mime])) {
        return ['success' => false, 'error' => 'Only JPG, PNG, WEBP or GIF images are allowed.'];
    }

    $extension = $allowedMimes[$mime];
    $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
    $destinationDir = UPLOAD_PATH . '/' . $subfolder;

    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $destinationPath = $destinationDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
        return ['success' => false, 'error' => 'Could not save the uploaded file. Check folder permissions.'];
    }

    return ['success' => true, 'path' => 'assets/uploads/' . $subfolder . '/' . $filename];
}

/**
 * Store a one-time "flash" message in the session (e.g. "Saved!") to show
 * after a redirect, then clear it. Type is 'success' or 'error'.
 */
function flash_set($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Retrieve and clear the current flash message, if any.
 * Returns null when there is nothing to show.
 */
function flash_get()
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * If a sermon's video_url is a YouTube link, return an embeddable iframe
 * URL. Returns null for any other link (e.g. Instagram/Facebook), which
 * the sermon detail page then shows as a plain "Watch" button instead -
 * Instagram/Facebook do not support public iframe embedding of arbitrary
 * live/video links, so we link out rather than showing a broken embed.
 */
function youtube_embed_url($url)
{
    if (empty($url)) {
        return null;
    }
    if (preg_match('~youtu\.be/([A-Za-z0-9_-]+)~', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    if (preg_match('~youtube\.com/watch\?v=([A-Za-z0-9_-]+)~', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return null;
}

/**
 * Delete an uploaded file (if it exists) given its relative path as
 * stored in the database, e.g. "assets/uploads/gallery/xyz.webp".
 * Silently does nothing if the path is empty or the file is missing.
 */
function delete_uploaded_file($relativePath)
{
    if (empty($relativePath)) {
        return;
    }
    $fullPath = dirname(__DIR__) . '/' . ltrim($relativePath, '/');
    if (is_file($fullPath)) {
        @unlink($fullPath);
    }
}
