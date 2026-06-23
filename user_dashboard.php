<?php 
session_start();
include 'includes/db_connect.php'; // Database connection file

if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] != 'User') {
    header("Location: signin.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch updated user info from database
$sql = "SELECT name, email, phone, country_code, state, city, pincode FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $country_code, $state, $city, $pincode);
$stmt->fetch();
$stmt->close();
// $conn->close();
// Update session variables with latest values
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['phone'] = $phone;
$_SESSION['country_code'] = $country_code;
$_SESSION['state'] = $state;
$_SESSION['city'] = $city;
$_SESSION['pincode'] = $pincode;
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="assets/css/user_dashboard.css">
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
            <p class="user-name"><?php echo htmlspecialchars($name); ?></p> <!-- Display User's Name -->
        </div>
        <ul>
            <li><a href="#" onclick="showSection('profile')"><i class="fa-solid fa-user"></i> My Profile</a></li>
            <li><a href="#" onclick="showSection('favorites')"><i class="fa-solid fa-heart"></i> Favorite Pets</a></li>
            <li><a href="#" onclick="showSection('adopt')"><i class="fa-solid fa-paw"></i> Your Adoption Requests</a></li>
            <li><a href="index.php"><i class="fa-solid fa-globe"></i> Go to Website</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="top-bar">
            <h1>Hello, <?php echo htmlspecialchars($name); ?></h1> <!-- User's Name on Top Bar -->
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
        <!-- Edit Profile Section -->
<div id="edit-profile" class="section" style="display:none;">
    <h2 align="center">Edit Profile</h2>
    <form class="edit-profile-form" action="update_profile.php" method="POST">
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
        <a href="user_dashboard.php" class="back-button">
            <button type="button">Back to Profile</button>
        </a>
    </form>
</div>


        <!-- Favorite Pets Section -->
        <div id="favorites" class="section" style="display:none;margin-left:50px">
            <h2>Favorite Pets</h2>
            <p>View and manage your favorite pets here.</p>
            <?php 
            $sql = "SELECT p.*,f.id as fav_id
            FROM favorite_pets f, pets p
            WHERE f.pet_id = p.id AND user_id = " . $_SESSION['user_id'];
            $result = $conn->query($sql);
            $sr = 1;
            // print_r($result);
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while($row = mysqli_fetch_assoc($result)): 
                        if($row['adoption_status'] == "Adopted"){
                            continue; 
                        }
                    ?>
                    <tr>
                        <td><?php echo $sr; ?></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><img src='uploads/<?php echo $row['photo']; ?>' width="80"></td>
                        <td>
                            <button id="bt" onclick='window.location.href="image-details.php?id=<?php echo $row["id"] ?>"'>See more details</button>
                            <button id="adopt-btn" data-shelter_id="<?php echo $row['shelter_id']; ?>" data-user_id="<?php echo $_SESSION['user_id']; ?>" data-pet_id="<?php echo $row['id']; ?>">Send Adoption Request</a>
                            <button id="bt" onclick='window.location.href="user_dashboard.php?fav_id=<?php echo $row["fav_id"] ?>"'>Remove from favorites</button>
                        </td>
                    </tr>
                    <?php $sr++; endwhile; ?>
                </tbody>
            </table>
        </div> 

        <!-- Adoption Section -->
        <div id="adopt" class="section" style="display:none;margin-left:50px">
            <h2>Proceed to Adopt</h2>
            <p>Choose a pet and proceed with the adoption process.</p>
            <!-- <button>Start Adoption</button> -->
            <h2>Your adoption requests:</h2>
            <?php 
            $sql = "SELECT p.name, p.id AS pet_id, p.photo, a.id AS adoption_id, a.status, u.name AS user_name
                    FROM adoption_requests a
                    JOIN pets p ON a.pet_id = p.id
                    JOIN users u ON a.user_id = u.id
                    JOIN shelters s ON a.shelter_id = s.id
                    WHERE a.user_id = " . $_SESSION['user_id'];

            $result = $conn->query($sql);

            if (!$result) {
                die("Query Failed: " . $conn->error); // Debugging
            }
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Sr</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sr=1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $sr; ?></td>
                        <td><?php echo $row['pet_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><img src='uploads/<?php echo $row['photo']; ?>' width="80"></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <button class="adopt-btns" data-action="view" data-id="<?php echo $row['adoption_id']; ?>">View Request</button>
                            <button class="adopt-btns" data-action="delete" data-id="<?php echo $row['adoption_id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php $sr++; endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>
<!-- JavaScript to set active class -->
<script>
    function showSection(sectionId) {
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }

    function setActiveLink(link) {
        // Remove the 'active' class from all links
        const links = document.querySelectorAll('.sidebar ul li a');
        links.forEach(link => link.classList.remove('active'));
        
        // Add 'active' class to the clicked link
        link.classList.add('active');
    }
</script>

<script src="includes/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $(document).on("click","#adopt-btn",function(){
            var data = {
                action: "adopt_request",
                user_id: $(this).data('user_id'),
                pet_id: $(this).data('pet_id'),
                shelter_id: $(this).data('shelter_id'),
            };
            // console.log(data);
            $.ajax({
                url: 'userdashajax.php',
                type: 'POST', 
                data: data, 
                success: function(response) {
                    alert(response);
                },
                error: function(xhr, status, error) {
                    alert('An error occured. Try again later');
                    console.error("Error: " + error); // Handle error
                }
            });
        });
        $(document).on("click",".adopt-btns",function(){
            var data = {
                action: $(this).data('action'),
                request_id: $(this).data('id')
            };
            if(data.action == "delete"){
                var c = confirm("Are you sure you want to delete this request?");
                if(!c){
                    return;
                }
            }
            $.ajax({
                url: 'userdashajax.php', 
                type: 'POST', // HTTP method
                data: data, // Expected response type
                success: function(response) {
                    if(data.action == "view"){
                        $("#adopt").html(response);
                    }else if(data.action == "delete"){
                        alert(response);
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occured. Try again later');
                    console.error("Error: " + error); // Handle error
                }
            });
        });
    });
</script>

</body>
</html>


<?php 

//to remove from faviorates
if(!empty($_GET['fav_id'])){
    $sql = "DELETE FROM favorite_pets WHERE id = " .$_GET['fav_id'];
    if($conn->query($sql)){
        echo "<script>
        alert('Pet removed from faviorates');
        window.location.href='user_dashboard.php';
        </script>";
    }
}

?>