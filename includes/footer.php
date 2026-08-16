<?php
/**
 * footer.php
 * -----------------------------------------------------------------------
 * Shared site footer + closing scripts for every PUBLIC page.
 * Closes the <main> tag opened at the bottom of header.php.
 * -----------------------------------------------------------------------
 */
?>
</main>

<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="footer-col footer-col--brand">
            <img src="<?= h(ASSETS_URL) ?>/images/logowhite-479x486.webp" alt="AFM Church in Poland crest" class="footer-crest">
            <h2>AFM Church in Poland</h2>
            <p>Warsaw Christian Centre</p>
            <div class="footer-socials">
                <a href="<?= h(CHURCH_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="AFM Warsaw on Facebook">Facebook</a>
                <a href="<?= h(CHURCH_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" aria-label="AFM Warsaw on Instagram">Instagram</a>
            </div>
        </div>

        <nav class="footer-col" aria-label="Quick links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="<?= h(PUBLIC_URL) ?>/about.php">About Us</a></li>
                <li><a href="<?= h(PUBLIC_URL) ?>/departments.php">Departments &amp; Ministries</a></li>
                <li><a href="<?= h(PUBLIC_URL) ?>/gallery.php">Gallery</a></li>
                <li><a href="<?= h(PUBLIC_URL) ?>/announcements.php">Announcements</a></li>
                <li><a href="<?= h(PUBLIC_URL) ?>/blog.php">Blog</a></li>
                <li><a href="<?= h(PUBLIC_URL) ?>/sermons.php">Sermons</a></li>
            </ul>
        </nav>

        <div class="footer-col">
            <h3>Visit Us</h3>
            <ul class="footer-contact">
                <li><strong>Address:</strong> <?= h(CHURCH_ADDRESS) ?></li>
                <li><strong>Service:</strong> <?= h(CHURCH_SERVICE_TIME) ?></li>
                <li><strong>Email:</strong> <a href="mailto:<?= h(CHURCH_EMAIL) ?>"><?= h(CHURCH_EMAIL) ?></a></li>
                <li><strong>Phone:</strong> <a href="tel:<?= h(str_replace(' ', '', CHURCH_PHONE)) ?>"><?= h(CHURCH_PHONE) ?></a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Join Us</h3>
            <p>New to AFM Warsaw? We would love to welcome you in person this Sunday.</p>
            <a href="<?= h(PUBLIC_URL) ?>/contact.php" class="btn btn--gold btn--small">Plan Your Visit</a>
        </div>
    </div>

    <div class="site-footer__bottom container">
        <p>&copy; <?= date('Y') ?> AFM Church in Poland — Warsaw Christian Centre. All rights reserved.</p>
        <p><a href="<?= h(ADMIN_URL) ?>/login.php">Admin Login</a></p>
    </div>
</footer>

<?php require_once __DIR__ . '/live_widget.php'; ?>

<script src="<?= h(ASSETS_URL) ?>/js/main.js" defer></script>
<?php if (!empty($extraScripts)) foreach ($extraScripts as $script): ?>
<script src="<?= h(ASSETS_URL) ?>/js/<?= h($script) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
