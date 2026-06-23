<?php include('includes/header.php'); ?>
<link rel="stylesheet" type="text/css" href="assets/css/about.css">
<?php
    session_start(); 
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

<!-- About Section -->
<section class="about">
    <div class="about-content">
        <div class="animated-text">
        <br><br><br><h1>About Us</h1></div>
</div>
</section>
<div class="about-info"><br><br><br><br>
    <h1 class="process" style="margin-left: 5%;color:#2C3E50;">Who Are We?</h1><br><br>
        <p class="process">At Paw Heaven, our mission is to connect loving families with furry companions who need a forever home. 
            We believe in responsible pet adoption, and we work tirelessly to ensure each animal finds a suitable 
            and caring environment. Our goal is not just to adopt out pets, but to foster long-term relationships 
            between owners and their pets, ensuring a lifetime of love and care.
       We provide guidance throughout the adoption process, offering resources to help new pet parents adjust 
            and thrive. Whether you’re a first-time pet owner or a seasoned pro, 
            we’re here to support you every step of the way.</p>

            <h1 class="process" style="margin-left: 5%; color:#2C3E50;"><br><br>Why Adopt With Us?</h1>
            
            <div class="content">
                <div class="leftSide">
                <ul>
                    <li><strong>Rescue First:</strong> We prioritize rescuing pets from shelters and unfortunate situations.</li><br>
                    <li><strong>Comprehensive Support:</strong> We offer advice and guidance throughout the adoption process.</li><br>
                    <li><strong>Health & Wellness:</strong> Every pet is vetted, vaccinated, and ready for a healthy new home.</li><br>
                    <li><strong>Forever Families:</strong> We believe in long-term placements and fostering loving relationships.</li><br>
        </ul>
                </div>
              </div>
            </div>
<!-- ---------------------------------------faq-------------------- -->
<div class="faq-section">
    <h2>Frequently Asked Questions (FAQs)</h2>
    
    <div class="faq-item">
        <button class="faq-question">How do I adopt a pet?</button>
        <div class="faq-answer">
            <p>To adopt a pet, browse through our available animals on our website or visit our adoption center. Once you find a pet that catches your eye, fill out an adoption application. Afterward, we'll review your application, and if everything looks good, we’ll arrange a meeting to ensure the pet is a perfect fit for you and your family. Once approved, you’ll complete the adoption process and bring your new pet home!</p>
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">How long does the adoption process take?</button>
        <div class="faq-answer">
            <p>The adoption process typically takes a few days to a couple of weeks, depending on the shelter or rescue organization. This includes completing the application, meeting the pet, and finalizing paperwork. Some shelters may conduct home visits or require additional information. Be sure to ask the shelter for an estimated timeline.</p>
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">What should I prepare before adopting a pet?</button>
        <div class="faq-answer">
            <p>Before adopting, you should prepare your home to ensure a safe and welcoming environment for your new pet. This includes setting up a designated area for your pet to sleep and eat, ensuring that your home is pet-proofed (e.g., removing hazards like toxic plants or unsecured items), and making sure you have all the necessary supplies (food, toys, grooming tools, etc.). We provide resources to guide you through the preparation process, and we’re here to answer any questions you may have.</p>
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">What happens if my pet needs medical care after adoption?</button>
        <div class="faq-answer">
            <p>Once you adopt a pet, it’s your responsibility to provide medical care, including regular vet check-ups, vaccinations, and treatment for any illnesses or injuries. However, if you need guidance on where to go for veterinary services, we can provide recommendations. Additionally, we’re always here to offer advice and support in caring for your pet’s health.</p>
        </div>
    </div>
</div>

<script>
    window.addEventListener("scroll", function () {
const elements = document.querySelectorAll('.about, .process, .care');
elements.forEach(function (element) {
if (element.getBoundingClientRect().top < window.innerHeight) {
element.classList.add('show');
}
});
});



// Script to toggle FAQ answers
document.addEventListener("DOMContentLoaded", function() {
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(function(question) {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling; // Find the corresponding answer div

            // Toggle visibility of the answer
            if (answer.style.display === "block") {
                answer.style.display = "none";
                answer.classList.remove('show');
            } else {
                answer.style.display = "block";
                setTimeout(() => {
                    answer.classList.add('show');
                }, 50); // Slight delay to trigger transition
            }
        });
    });
});

</script>

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
</style>