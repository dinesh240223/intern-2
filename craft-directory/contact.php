<?php
include 'includes/db.php';
include 'includes/header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO contacts (name,email,subject,message) VALUES ('$name','$email','$subject','$message')");
    $msg = "Thank you! Your message has been received. We'll get back to you shortly.";
}
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Get in Touch</span>
    <h1>Contact Us</h1>
    <p>Questions, collaborations, or want to list an artisan? We'd love to hear from you.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-wrapper">
      <div class="contact-info-box">
        <h3>Let's Connect</h3>
        <p>Whether you're a researcher, buyer, artisan, or cultural institution — we're here to help.</p>

        <div class="info-line">
          <span>📍</span>
          <div><strong>Address</strong>Ministry of Textiles, Udyog Bhawan, New Delhi — 110011</div>
        </div>
        <div class="info-line">
          <span>📞</span>
          <div><strong>Phone</strong>+91-11-2306-1234</div>
        </div>
        <div class="info-line">
          <span>✉</span>
          <div><strong>Email</strong>contact@craftdirectory.in</div>
        </div>
        <div class="info-line">
          <span>⏰</span>
          <div><strong>Hours</strong>Mon — Fri · 9:00 AM to 6:00 PM IST</div>
        </div>
      </div>

      <form method="POST" class="contact-form">
        <h3>Send Us a Message</h3>
        <?php if($msg): ?><div class="alert-success">✔ <?= $msg ?></div><?php endif; ?>
        <input type="text" name="name" placeholder="Your Full Name" required>
        <input type="email" name="email" placeholder="Your Email Address" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" rows="6" placeholder="Tell us how we can help..." required></textarea>
        <button class="btn btn-primary">Send Message →</button>
      </form>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>