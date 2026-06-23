<?php 
include('includes/header.php'); 
include('includes/db_connect.php'); 

if(!isset($_GET['id'])){
    echo "<script>
    window.location.href='pawtopia.php';
    </script>";
}

$s = "SELECT * FROM pets WHERE id = " . $_GET['id'];
$result = $conn->query($s);
$data = $result->fetch_assoc();

?>
<link rel="stylesheet" type="text/css" href="assets/css/pawtopia.css">
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

<br><br><br>
 <!-- Image Box -->
 <div class="details-image-box">
    <div class="details-image-container">
        <!-- Multiple Images for Animation -->
         <div class="Charlie">
        <img src="uploads/<?php echo $data['photo']; ?>" alt="Image 1" class="details-image">
        </div>
        
    </div>
</div>


<br><br>
<!-- Details Box -->
<div class="details-info-box">
    <!-- Location with Icon -->
    <p id="location" class="location">
        <img src="assets/images/location.jpg" alt="Location Icon" width="65px" height="65px"> 
        <?php echo $data['location']; ?>
    </p><br>

    <h2 id="details-title" class="details-title">Pet Name: <?php echo $data['name']; ?></h2>
    <p id="details-description" class="details-description">Age: <?php echo $data['age']; ?> years</p>
    <p id="details-breed" class="details-description">Breed: <?php echo $data['breed']; ?></p>
    <p id="details-color" class="details-description">Color: <?php echo $data['color']; ?></p>
    <p id="details-gender" class="details-description">Gender: <?php echo $data['gender']; ?></p>
    <p id="details-vaccination" class="details-description">Vaccination Status: <?php echo $data['vaccination']; ?></p>
    <p id="details-status" class="details-status">Adoption Status: <?php echo $data['adoption_status']; ?></p>
<br>
    <!-- Separate Description Box -->
    <div class="description-box">
        <h3>Description:</h3><br>
        <p><?php echo $data['description']; ?></p>
    </div><br>
    <?php if($data['adoption_status'] == "Available"):  ?>
        <a href="adopt_pet.php?pet_id=<?php echo $data['id'] ?>" class="back-btn">Adopt Me! 🐾</a>
    <?php endif; ?>

    <a href="pawtopia.php" class="back-btn">Back to Gallery ⬅️</a>
</div>

</div>
</div><br><br><br>
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

</style>