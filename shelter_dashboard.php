<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['shelter_id']) || $_SESSION['account_type'] != 'Shelter') {
    header("Location: signin.html");
    exit();
}

$shelter_id = $_SESSION['shelter_id'];

// Fetch shelter details
$sql = "SELECT name, email, phone, country_code, state, city, pincode FROM shelters WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shelter_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $country_code, $state, $city, $pincode);
$stmt->fetch();
$stmt->close();


// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shelter Dashboard</title>
    <link rel="stylesheet" href="assets/css/shelter_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="assets/images/logo.jpg" height="90px" width="100px">
            <h2>Paw Heaven</h2>
            <hr class="sidebar-line">
        </div>
        <div class="user-info">
            <p class="user-name"><?php echo htmlspecialchars($name); ?></p>
        </div>
        <ul>
            <li><a href="#" onclick="showSection('profile')"><i class="fa-solid fa-user"></i> My Profile</a></li>
            <li><a href="#" onclick="showSection('manage-pets')"><i class="fa-solid fa-dog"></i> Manage Pets</a></li>
            <li><a href="#" onclick="showSection('adoption-requests')"><i class="fa-solid fa-file-alt"></i> Adoption Requests</a></li>
            <li><a href="#" onclick="showSection('adoption-history')"><i class="fa-solid fa-clock-rotate-left"></i>Adoption History</a></li>
            <li><a href="pawtopia.php" onclick="showSection('website')"><i class="fa-solid fa-globe"></i> Go To Website</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="top-bar">
            <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
        </div>

        <!-- Profile Section -->
        <div id="profile" class="section">
            <h2 align="center">My Profile</h2>
            <div class="profile-block">
                <h3>Profile Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($country_code); ?></p>
                <p><strong>State:</strong> <?php echo htmlspecialchars($state); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
                <p><strong>Pincode:</strong> <?php echo htmlspecialchars($pincode); ?></p>
                <a href="#" onclick="showSection('edit-profile')" class="edit-button">Edit Profile</a>
            </div>
        </div>

         <!-- Edit Profile Section -->
<div id="edit-profile" class="section" style="display:none;">
    <h2 align="center">Edit Profile</h2>
    <form class="edit-profile-form" action="shelter_updateprofile.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" value="<?php echo htmlspecialchars($email); ?>" disabled> <!-- Readonly email field -->
        
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
        
        <label for="country">Country:</label>
        <input type="text" name="country_code" value="<?php echo htmlspecialchars($country_code); ?>" required>
        
        <label for="state">State:</label>
        <input type="text" name="state" value="<?php echo htmlspecialchars($state); ?>" required>
        
        <label for="city">City:</label>
        <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
        
        <label for="pincode">Pincode:</label>
        <input type="text" name="pincode" value="<?php echo htmlspecialchars($pincode); ?>" required>
        
        <button type="submit">Save Changes</button>
        <a href="shelter_dashboard.php" class="back-button">
            <button type="button">Back to Profile</button>
        </a>
    </form>
</div>
    <!-- Add Pet Section -->
<div id="add-pet" class="section" style="display:none;">
    <h2 align="center">Add New Pet</h2>
    <form action="manage_pets.php" method="POST" enctype="multipart/form-data">
        <label for="name">Pet Name:</label>
        <input type="text" name="name" required>

        <label for="breed">Breed:</label>
        <input type="text" name="breed" required>

        <label for="age">Age (Years):</label>
        <input type="number" name="age" required>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label for="color">Color:</label>
        <input type="text" name="color" required>

        <label for="vaccination">Vaccination Status:</label>
        <select name="vaccination" required>
            <option value="Vaccinated">Vaccinated</option>
            <option value="Not Vaccinated">Not Vaccinated</option>
        </select>

        <label for="adoption_status">Adoption Status:</label>
        <select name="adoption_status" required>
            <option value="Available">Available</option>
            <option value="Adopted">Adopted</option>
        </select>

        <label for="pet_type">Pet Type:</label>
        <select name="pet_type" required>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
        </select>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="location">Location:</label>
        <input type="text" name="location" required>

        <label for="photo">Upload Pet Photo:</label>
        <input type="file" name="photo" accept="image/*" required>

        <button type="submit">Add Pet</button>
        <button type="button" onclick="showSection('manage-pets')">Back</button>
    </form>
</div>

<!-- Manage Pets Section -->
<div id="manage-pets" class="section">
    <h2 align="center">Manage Pets</h2>
    <button onclick="showSection('add-pet')" class="add-button">Add New Pet</button>

    <table class="pet-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Color</th>
                <th>Vaccination Status</th>
                <th>Adoption Status</th>
                <th>Pet Type</th>
                <th>Description</th>
                <th>Location</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'includes/db_connect.php';

            // Fetch pets associated with the logged-in shelter
            $sql = "SELECT id, name, breed, age, gender, color, vaccination, adoption_status, pet_type, description, location, photo FROM pets WHERE shelter_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $shelter_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($pet = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($pet['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['breed']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['color']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['vaccination']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['adoption_status']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['pet_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($pet['location']) . "</td>";
                    echo "<td><img src='uploads/" . htmlspecialchars($pet['photo']) . "' alt='" . htmlspecialchars($pet['name']) . "' width='80'></td>";
                    echo "<td>
                            <button onclick='btn_action(" . $pet['id'] . ",`edit`)'>Edit</button><br><br>
                            <button onclick='btn_action(" . $pet['id'] . ",`delete`)'>Delete</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11' align='center'>No pets found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Manage Pets Section -->
<div id="adoption-requests" class="section">
    <h2 style="text-align:center">Adoption Requests</h2>
    <table class="pet-table">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Pet ID</th>
                <th>Name of Requester</th>
                <th>Name of Pet</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'includes/db_connect.php';

            $sql = "SELECT u.name as user_name , p.name as pet_name, p.id as pet_id, p.photo, a.*
                    FROM adoption_requests a
                    JOIN pets p ON a.pet_id = p.id
                    JOIN users u ON a.user_id = u.id
                    JOIN shelters s ON a.shelter_id = s.id
                    WHERE a.shelter_id = " . $_SESSION['shelter_id'];

            $result = $conn->query($sql); $sr = 1;
            if ($result && $result->num_rows > 0):
                while($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td><?php echo $sr; ?></td>
                    <td><?php echo $row['pet_id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['pet_name']; ?></td>
                    <td><img src='uploads/<?php echo $row['photo']; ?>' width='80'></td>
                    <td>
                        <button onclick="req_btn_action('<?php echo $row['id']; ?>','view')">View</button>
                        <button onclick="req_btn_action('<?php echo $row['id']; ?>','delete')">Delete</button>
                    </td>
                </tr>
                <?php $sr++; 
                endwhile; else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No requests found</td>
                </tr>
            <?php 
            endif; 
            ?>
        </tbody>
    </table>
</div>

<div id="adoption-history" class="section">
    <h2 style="text-align:center">Adoption History</h2>
    <table class="pet-table">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Pet ID</th>
                <th>Name of Pet</th>
                <th>Photo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'includes/db_connect.php';

            $sql = "SELECT p.name as pet_name, p.id as pet_id, p.photo, r.status
                    FROM adoption_history a
                    JOIN pets p ON a.pet_id = p.id
                    JOIN shelters s ON a.shelter_id = s.id
                    JOIN adoption_requests r ON a.request_id = r.id
                    WHERE a.shelter_id = " . $_SESSION['shelter_id'];

            $result = $conn->query($sql); $sr = 1;
            if ($result && $result->num_rows > 0):
            while($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?php echo $sr; ?></td>
                <td><?php echo $row['pet_id']; ?></td>
                <td><?php echo $row['pet_name']; ?></td>
                <td><img src='uploads/<?php echo $row['photo']; ?>' width='80'></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php $sr++; endwhile; else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No History found</td>
                </tr>
            <?php 
            endif; 
            ?>
        </tbody>
    </table>
</div>

<script src="includes/jquery.min.js"></script>
<script>
    function btn_action(id,action){
        if(action == "delete"){
            var c = confirm('Are you sure you want to delete this pet?');
            if(!c){
                return;
            }
        }
        $.ajax({
            url: 'petsajax.php',
            type: 'POST', 
            data: { id: id , action: action }, 
            success: function(response) {
                if(action == "delete"){
                    alert(response);
                    location.reload();
                }else{
                    $("#manage-pets").html(response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error); // Handle error
            }
        });
    }
    function req_btn_action(id,action){
        var data = {
            request_id: id,
            action: action,
            for: "adoption_requests"
        };
        if(action == "delete"){
            var c = confirm('Are you sure you want to delete this request?');
            if(!c){
                return;
            }
        }
        $.ajax({
            url: 'adoption-req.php', 
            type: 'POST', 
            data: data, 
            success: function(response) {
                if(action == "view"){
                    $("#adoption-requests").html(response); 
                }else{
                    alert(response);
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error); // Handle error
            }
        });
    }


// Function to show/hide sections
function showSection(sectionId) {
    document.querySelectorAll(".section").forEach(section => section.style.display = "none");
    document.getElementById(sectionId).style.display = "block";
}
</script>


</body>
</html>

<?php


?>
