<?php 
    session_start(); 
    include('includes/header.php'); 
?>

<link rel="stylesheet" type="text/css" href="assets/css/home.css">

<!-- User Profile Section -->
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

<div class="banner">
    <div class="leftSide">
        <div class="animated-text">
            <h2>Welcome To Paw Heaven<br>Find Your Perfect Furry Companion Today!</h2>
        </div>
    </div>
</div>
</header>

<section class="about">
    <div class="leftSide"><br><br>
        <h1>How Our Adoption<br> Process Works?</h1>
        <p>We don’t just help you adopt a pet<span>  we’re here to support you in providing the best care for your new furry friend!</span></p>
        <a href="#procedure"><button class="primary-btn">Get Started >></button></a>
    </div>
    <div class="rightSide">
        <img src="assets/images/img.jpg">
    </div>
</section>

<div id="procedure">
    <section class="process">
        <div class="box">
            <span class="bullet">1</span><br><br><br>
            <h2>Find Your Perfect Pet</h2>
            <p class="para">Browse through our available pets and find the one that best fits your family and lifestyle. Each pet profile includes important details and
                  photos to help you make an informed decision.</p>
        </div>
        <div class="box">
            <span class="bullet">2</span><br><br><br>
            <h2>Register and Log In</h2>
            <p class="para">After finding the pet you'd like to adopt, create an account by registering and logging in to start your 
                adoption application. This 
                helps us keep track of your adoption journey and ensures a secure process.</p>
        </div>
        <div class="box">
            <span class="bullet">3</span><br><br><br>
            <h2>Connect with Us</h2>
            <p class="para"> Once logged in, reach out to us to discuss the next steps. We’ll guide you through the 
                adoption process and provide more details about your chosen pet, ensuring it's a great fit for you.</p>
        </div>
    </section>
</div>

<!-- -------------------care instruction-------------------- -->
<section class="care">
    <div class="care-ins">
        <h1>CARE INSTRUCTIONS</h1><br><br>
        <h3>Adopting a pet is a lifelong commitment that requires time,
            attention, and financial readiness. Ensure you can provide 
            a safe, loving, and stable environment with proper nutrition,
            regular vet care, and daily interaction. If you cannot fully 
            take care of their needs or commit to their well-being for life,
            please do not adopt, as every pet deserves a responsible and caring home.
        </h3>
    </div>
</section>

<script>
window.addEventListener("scroll", function () {
    const elements = document.querySelectorAll('.about, .process, .care');
    elements.forEach(function (element) {
        if (element.getBoundingClientRect().top < window.innerHeight) {
            element.classList.add('show');
        }
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
        gap: 2px;
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
