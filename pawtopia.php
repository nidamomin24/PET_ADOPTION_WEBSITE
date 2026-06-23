<?php 
include('includes/header.php'); 
include('includes/db_connect.php'); 
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

<section class="about">
    <div class="about-content">
        <div class="animated-text">
        <br><br><br><h1>Pawtopia</h1></div>
    </div>
</section>

<?php 
// Initialize the filter variables
$pet_type = isset($_GET['pet_type']) ? $_GET['pet_type'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$breed = isset($_GET['breed']) ? $_GET['breed'] : '';

// Base query for fetching pets
$sql = "SELECT * FROM pets WHERE 1";

// Apply filters
if ($pet_type != '') {
    $sql .= " AND pet_type = '" . $conn->real_escape_string($pet_type) . "'";
}
if ($location != '') {
    $sql .= " AND location LIKE '%" . $conn->real_escape_string($location) . "%'";
}
if ($breed != '') {
    $sql .= " AND breed LIKE '%" . $conn->real_escape_string($breed) . "%'";
}

// Execute the query
$result = $conn->query($sql);

// Fetch the pets based on filters
$pets = array();
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}
?>

<!-- ------------------------ Filter Section ------------------------ -->
<section class="filter-section">
    <form id="filter-form">
        <div class="filter-container">
            <label for="pet_type">Pet Type:</label>
            <select name="pet_type" id="pet_type">
                <option value="">All Types</option>
                <option value="Dog" <?php if($pet_type == 'Dog') echo 'selected'; ?>>Dog</option>
                <option value="Cat" <?php if($pet_type == 'Cat') echo 'selected'; ?>>Cat</option>
                <!-- Add more pet types if necessary -->
            </select>
            
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" placeholder="Enter location" value="<?php echo htmlspecialchars($location); ?>">
            
            <label for="breed">Breed:</label>
            <input type="text" name="breed" id="breed" placeholder="Enter breed" value="<?php echo htmlspecialchars($breed); ?>">
            
            <button type="submit" class="filter-btn">Apply Filter</button>
        </div>
    </form>
</section>

<!-- ------------------------ Gallery Section ------------------------ -->
<section>
    <div class="gallery-section">
        <div class="gallery-container" id="gallery-container">
            <?php if (!empty($pets)): ?>
                <?php foreach ($pets as $pet): ?>
                    <div class="gallery-item">
                        <a href="image-details.php?id=<?php echo $pet['id']; ?>">
                            <img src="uploads/<?= htmlspecialchars($pet['photo']); ?>" alt="<?= htmlspecialchars($pet['name']); ?>" class="gallery-image">
                            <div class="info">
                                <h3><?= htmlspecialchars($pet['name']); ?></h3>
                                <span class="status"><?= htmlspecialchars($pet['adoption_status']); ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No pets available for adoption matching the selected filters.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- ------------------------ Styles ------------------------ -->
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

.filter-section {
    margin-top: 20px;
    padding: 20px;
    background-color: #f4f4f4;
}

.filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.filter-container label {
    font-size: 16px;
    color: #333;
}

.filter-container select, .filter-container input {
    padding: 8px;
    font-size: 14px;
    width: 200px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.filter-btn {
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.filter-btn:hover {
    background-color: #0056b3;
}


</style>

<!-- ------------------------ AJAX Script ------------------------ -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#filter-form').submit(function(e) {
        e.preventDefault();

        // Get filter values
        var pet_type = $('#pet_type').val();
        var location = $('#location').val();
        var breed = $('#breed').val();

        $.ajax({
            url: '', // Current page URL
            method: 'GET',
            data: {
                pet_type: pet_type,
                location: location,
                breed: breed
            },
            success: function(response) {
                // Get the updated gallery content
                var newContent = $(response).find('#gallery-container').html();
                $('#gallery-container').html(newContent);
            }
        });
    });
});
</script>
