# AFM Church in Poland — Warsaw Christian Centre

A full-stack church website built with plain PHP, MySQL, HTML, CSS and
JavaScript. The public site and the admin panel are fully separated:
everything an admin edits (gallery photos, announcements, blog posts,
sermons, about/pastor content, and the live-service widget) is stored in
MySQL and reflected on the public site instantly — no code changes or
redeploys required.

## Folder Structure

```
/public       Public-facing pages (home, about, gallery, blog, sermons, ...)
  /departments  One page per department (Praise & Worship, Media & Sound, ...)
  /ministries   One page per ministry (Men, Women, Youth, Sunday School)
  /includes     public/includes/bootstrap.php - loads shared includes
/admin        Admin panel (login + CRUD for every content type)
  /includes     admin/includes/bootstrap.php, auth.php, admin_header.php, ...
/includes     Shared code used by BOTH /public and /admin
  config.php    Site constants + database connection settings
  db.php        Opens the single mysqli $conn connection
  functions.php Helper functions (sanitizing, slugs, file uploads, CSRF, ...)
  header.php    Public <head> + navigation
  footer.php    Public footer + scripts
  live_widget.php  Floating "We are LIVE" button (only shows when toggled on)
/assets       CSS, JS, images and fonts
  /css          style.css (public site), admin.css (admin panel)
  /js           main.js (loader/nav/animations), gallery.js, admin.js
  /images       Existing church photos used as page/seed content
  /uploads      Files uploaded through the admin panel, organized by type:
                gallery/ posters/ blog/ sermons/ about/
/sql          Database schema and sample data
  schema.sql    Table definitions with comments explaining each table
  seed.sql      Sample content + a default admin login
robots.txt    Search engine crawling rules
sitemap.php   Dynamically generated XML sitemap (includes DB content)
```

## Database Overview

| Table            | Purpose                                                             |
|-------------------|----------------------------------------------------------------------|
| `admin_users`     | Admin login accounts (bcrypt password hashes)                       |
| `about_content`   | Single-row table for the About page (mission/vision/values/pastor)  |
| `gallery`         | Photos shown on the public Gallery page                              |
| `announcements`   | Weekly/monthly announcements, each with an optional poster image     |
| `blog_posts`      | Blog articles (title, slug, content, thumbnail, published flag)      |
| `sermons`         | Sermon library (title, slug, speaker, video link, thumbnail)         |
| `live_status`     | Single-row flag + link controlling the floating "We are LIVE" widget |

See `sql/schema.sql` for full column definitions and comments.

## Setup with XAMPP (local development)

1. **Install XAMPP** (includes Apache, PHP, and MySQL/MariaDB) and start
   the Apache and MySQL services from the XAMPP Control Panel.
2. **Copy this project** into your XAMPP `htdocs` folder, e.g.
   `C:\xampp\htdocs\afm-warsaw-assembly` (Windows) or
   `/Applications/XAMPP/htdocs/afm-warsaw-assembly` (Mac).
3. **Create the database**:
   - Open `http://localhost/phpmyadmin`
   - Click "New", name the database `afm_warsaw` (collation `utf8mb4_general_ci`)
   - Select it, click "Import", choose `sql/schema.sql`, click "Go"
   - Repeat "Import" with `sql/seed.sql` to load sample content + the admin login
4. **Check the database settings** in `includes/config.php` match your
   XAMPP MySQL (defaults are `root` with no password, which is standard
   for a fresh XAMPP install — no changes usually needed).
5. **Make the uploads folder writable** — on Windows this is automatic;
   on Mac/Linux you may need `chmod -R 755 assets/uploads`.
6. **Visit the site**:
   - Public site: `http://localhost/afm-warsaw-assembly/public/index.php`
   - Admin panel: `http://localhost/afm-warsaw-assembly/admin/login.php`

### Default Admin Login

```
Username: admin
Password: AfmWarsaw2026!
```

**Change this password** after your first login in any real deployment
(add a "change password" form, or update the `admin_users.password_hash`
column directly using PHP's `password_hash()`).

## Migrating to Hostinger (production)

1. **Create the database** in hPanel → Databases → MySQL Databases. Note
   the database name, username, password and host it gives you.
2. **Import the schema**: open phpMyAdmin from hPanel and import
   `sql/schema.sql` then `sql/seed.sql` (or skip the seed file and add
   your own content once the admin panel is live).
3. **Update `includes/config.php`**:
   - Comment out the `==== XAMPP (local) ====` block
   - Uncomment the `==== HOSTINGER (production) ====` block and fill in
     the DB host/name/user/password Hostinger gave you
   - Change `APP_ENV` from `'local'` to `'production'` (this hides raw
     PHP error messages from visitors)
4. **Upload the files** via Hostinger's File Manager or FTP/SFTP. If your
   plan's document root is `public_html`, upload the entire project
   *contents* into `public_html` (so `public_html/public/index.php`,
   `public_html/admin/login.php`, etc.).
5. **Set your homepage**: either point visitors to `/public/index.php`,
   or add a tiny `index.php` at the very root that redirects to it:
   ```php
   <?php header('Location: public/index.php'); exit;
   ```
6. **Double check folder permissions** on `assets/uploads/` (usually 755)
   so the admin panel can save uploaded images.
7. **Change the default admin password** immediately after your first login.

`includes/config.php` automatically detects the site's base URL from the
request, so no path changes are needed between local and production —
only the database credentials and `APP_ENV` change.

## Security Notes

- All database queries use prepared statements (`mysqli` with
  `bind_param`) to prevent SQL injection.
- All output is passed through the `h()` helper (`htmlspecialchars`) to
  prevent XSS.
- Every admin form includes a CSRF token, checked with
  `verify_csrf_or_die()` before any write happens.
- File uploads are validated by their real MIME type (not just the file
  extension), capped at 5MB, renamed to a random filename, and
  `assets/uploads/.htaccess` blocks any script from ever executing there.
- Admin passwords are hashed with PHP's `password_hash()` /
  `password_verify()` — never stored in plain text.

## Design System

Colors and type are derived from the church crest: deep navy, gold, and
red accents against light, airy backgrounds. Headings use "Playfair
Display"; body text uses "Inter" (loaded from Google Fonts in
`includes/header.php`). All design tokens (colors, spacing, shadows) live
as CSS custom properties at the top of `assets/css/style.css`.

## Extending the Project

- **Departments & Ministries pages** are static content pages that share
  one template (`public/includes/dept_template.php`) — edit the small
  data file for each page (e.g. `public/departments/praise-worship.php`)
  to change its text/images.
- **Contact form** currently confirms submission without sending an
  email. To send real emails, integrate PHPMailer or Hostinger's SMTP
  and update `public/contact.php`.
- **Clean URLs** for blog posts/sermons currently use `?slug=...` query
  strings. To use path-style URLs (e.g. `/blog/my-post`), add Apache
  `mod_rewrite` rules in a `public/.htaccess` file.
