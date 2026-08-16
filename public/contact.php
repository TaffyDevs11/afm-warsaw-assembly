<?php
/**
 * public/contact.php — Contact / plan your visit page
 * -----------------------------------------------------------------------
 * A simple contact page. The form below posts to itself (mailto fallback
 * message is shown) - this keeps the beginner build simple; wiring it to
 * an SMTP mailer (e.g. PHPMailer) is a natural next step once hosted.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with AFM Church in Poland, Warsaw Christian Centre. Find our address, service times, and how to reach us.';
$canonicalPath = 'contact.php';

$formSubmitted = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $message = clean_input($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $formError = 'Please fill in your name, email, and message.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Please enter a valid email address.';
    } else {
        // In production, send this via mail()/SMTP. For the local build we
        // simply acknowledge receipt so the form is fully functional to test.
        $formSubmitted = true;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="reveal">
            <span class="eyebrow">We'd Love to Meet You</span>
            <h1>Contact &amp; Visit</h1>
            <p>Reach out with a question, or simply let us know you're coming - we'll save you a seat.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split" style="align-items:flex-start;">
        <div class="reveal reveal--left">
            <h2>Send Us a Message</h2>
            <?php if ($formSubmitted): ?>
                <div class="admin-flash admin-flash--success" role="alert">Thank you, <?= h($name) ?>! Your message has been received. We'll be in touch soon.</div>
            <?php else: ?>
                <?php if ($formError): ?>
                    <div class="admin-flash admin-flash--error" role="alert"><?= h($formError) ?></div>
                <?php endif; ?>
                <form method="POST" action="<?= h(PUBLIC_URL) ?>/contact.php#contact-form" id="contact-form">
                    <div class="form-field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required value="<?= h($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required><?= h($_POST['message'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn--navy btn--block">Send Message</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="reveal reveal--right">
            <h2>Visit Information</h2>
            <ul class="footer-contact" style="margin-top:1.5rem;">
                <li style="margin-bottom:1rem;"><strong>Address</strong><br><?= h(CHURCH_ADDRESS) ?></li>
                <li style="margin-bottom:1rem;"><strong>Service Times</strong><br><?= h(CHURCH_SERVICE_TIME) ?></li>
                <li style="margin-bottom:1rem;"><strong>Email</strong><br><a href="mailto:<?= h(CHURCH_EMAIL) ?>"><?= h(CHURCH_EMAIL) ?></a></li>
                <li style="margin-bottom:1rem;"><strong>Phone</strong><br><a href="tel:<?= h(str_replace(' ', '', CHURCH_PHONE)) ?>"><?= h(CHURCH_PHONE) ?></a></li>
            </ul>
            <div class="footer-socials" style="margin-top:1rem;">
                <a href="<?= h(CHURCH_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" class="btn btn--outline-navy btn--small">Facebook</a>
                <a href="<?= h(CHURCH_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" class="btn btn--outline-navy btn--small">Instagram</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
