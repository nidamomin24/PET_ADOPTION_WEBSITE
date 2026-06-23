<?php include('includes/header.php'); ?>
<link rel="stylesheet" type="text/css" href="assets/css/contact.css">
<?php
    session_start(); // Ensure the session starts at the top
?>
<?php if (isset($_SESSION['account_type'])): ?>
    <div class="profile-section">
        <span class="welcome-text">Welcome,</span>
        <a href="<?php echo ($_SESSION['account_type'] === 'User') ? 'user_dashboard.php' : (($_SESSION['account_type'] === 'Shelter') ? 'shelter_dashboard.php' : 'admin_dashboard.php'); ?>">
            <?php 
                if ($_SESSION['account_type'] === "User") {
                    echo htmlspecialchars($_SESSION['user_name']); 
                } elseif ($_SESSION['account_type'] === "Shelter") {
                    echo htmlspecialchars($_SESSION['shelter_name']);
                } elseif ($_SESSION['account_type'] === "Admin") {
                    echo "Admin";
                }
            ?>
        </a>
    </div>
<?php endif; ?>

<section class="about">
    <div class="about-content">
        <div class="animated-text">
        <br><br><br><h1>Contact Us</h1></div>
    </div>
</section>


<!-- ------------------------ Contact Section ------------------------ -->
<section class="contact-section">
    <div class="contact-container">
        <!-- Left Side - Contact Info -->
        <div class="contact-info">
            <h2>Contact Us</h2><br>
            <p>We're here to help! Whether you have questions or feedback, feel free to get in touch.</p>
            <!-- Contact Details with Icons -->
             <br>
            <div class="contact-details">
                <div class="detail-item">
                    <i class="fa-solid fa-envelope"></i>
                    <p><strong>Email:</strong> info@pawheaven.com</p>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-phone"></i>
                    <p><strong>Phone:</strong> +1 234 567 890</p>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <p><strong>Address:</strong> 123 Paw Heaven Street, City, Country</p>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Contact Form -->
        <div class="contact-form">
            <h3>We'd love to hear from you!</h3><br>
            <form id="contactForm" action="sendMail.php" method="POST">
    <div class="form-group">
        <label for="name">Your Name</label><br>
        <input type="text" id="name" name="Name" placeholder="Enter your name" required>
    </div>
    <div class="form-group">
        <label for="email">Your Email</label><br>
        <input type="email" id="email" name="Email" placeholder="Enter your email" required>
    </div>
    <div class="form-group">
        <label for="message">Your Message</label><br>
        <textarea id="message" name="Message" rows="4" placeholder="Enter your message" required></textarea>
    </div>
    <button type="submit" class="submit-btn" name="submit">Submit</button>
</form><br>
        </div>
    </div>
</section>

<!-- ------------------------ Google Map Section ------------------------ -->
<section class="map-section">
    <h3>Find Us Here:</h3>
    <!-- Replace with your own Google Maps iframe link -->
    <iframe src="https://www.google.com/maps/embed?pb=...your-google-map-embed-url..." width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</section>


<?php include('includes/footer.php'); ?>
<style>
    .profile-section {
    background: #f4f4f4;
    padding: 5px 10px;
    text-align: right;
    align-items: center;
    display: flex;
    justify-content: flex-end;
    gap: 5px;
}
.profile-section a {
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
    font-size: 16px;
    margin-right: 20px;
}
.welcome-text {
    font-size: 16px;
    color: #333;
}

#msg{
    color: #61b752;
    margin-top:-10px;
    display:block;
}

</style>

