<?php
session_start();
include 'includes/db_connect.php'; // Include database connection

if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
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
        <h2>Admin Panel</h2>
        <hr class="sidebar-line">
    </div>
    <div class="user-info">
        <p class="user-name">Admin</p>
    </div>
    <ul>
        <li><a href="#" onclick="showSection('users')"><i class="fa-solid fa-users"></i> Users</a></li>
        <li><a href="#" onclick="showSection('shelter')"><i class="fa-solid fa-warehouse"></i> Shelter</a></li>
        <li><a href="#" onclick="showSection('pets')"><i class="fa-solid fa-paw"></i> Pets</a></li>
        <li><a href="#" onclick="showSection('reports')"><i class="fa-solid fa-chart-line"></i> Reports</a></li>
        <li><a href="index.php"><i class="fa-solid fa-globe"></i> Go to Website</a></li>
        <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <div class="top-bar">
        <h1>Welcome, Admin</h1>
    </div>

    <!-- Users Section -->
    <div id="users" class="section">
        <h2>Manage Users</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone no</th>
                <th>Registered Date</th>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM users");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['created_at']}</td>
                </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Shelter Section -->
    <div id="shelter" class="section" style="display:none;">
        <h2>Shelter Management</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Registered Date</th>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM shelters");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                       <td>{$row['created_at']}</td>
                </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Pets Section -->
    <div id="pets" class="section" style="display:none;">
        <h2>Pets Added by Shelters</h2>
        <table border="1">
            <tr>
                <th>Pet ID</th>
                <th>Shelter ID</th>
                <th>Shelter Name</th>
                <th>Pet Type</th>
                <th>Pet Name</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Adoption Status</th>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT pets.id AS pet_id, pets.name, pets.breed, pets.age, pets.pet_type,
            pets.adoption_status, pets.shelter_id, shelters.name AS shelter_name 
            FROM pets 
            INNER JOIN shelters ON pets.shelter_id = shelters.id");

            while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                    <td>{$row['pet_id']}</td>  <!-- Display Pet ID -->
                    <td>{$row['shelter_id']}</td>  <!-- Display Shelter ID -->
                    <td>{$row['shelter_name']}</td>  <!-- Display Shelter Name -->
                    <td>{$row['pet_type']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['breed']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['adoption_status']}</td>
                </tr>";
            }
            ?>
        </table>
    </div>


<!-- Reports Section -->
<div id="reports" class="section" style="display:none;">
    <div class="reports-container">
        <h2>Reports</h2><br>
        
        <!-- Dropdown for selecting report type -->
        <select id="report-type" onchange="showReport()">
            <option value="user-report">User Registration Report</option>
            <option value="shelter-report">Shelter Registration Report</option>
            <option value="pet-report">Pet Adoption Report</option>
        </select>
        
        <!-- User Registration Report -->
        <div id="user-report">
            <h3>Users Registration Report</h3>
            <table>
                <tr>
                    <th>Time Period</th>
                    <th>Total Registered Users</th>
                </tr>
                <?php
                $timeframes = [
                    'Weekly' => "DATE_SUB(CURDATE(), INTERVAL 7 DAY)",
                    'Monthly' => "DATE_SUB(CURDATE(), INTERVAL 1 MONTH)",
                    'Yearly' => "DATE_SUB(CURDATE(), INTERVAL 1 YEAR)"
                ];
                foreach ($timeframes as $label => $interval) {
                    $query = "SELECT COUNT(*) as total FROM users WHERE created_at >= $interval";
                    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
                    echo "<tr><td>$label</td><td>{$result['total']}</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Shelter Registration Report -->
        <div id="shelter-report" style="display:none;">
            <h3>Shelter Registration Report</h3>
            <table>
                <tr>
                    <th>Time Period</th>
                    <th>Total Registered Shelters</th>
                </tr>
                <?php
                foreach ($timeframes as $label => $interval) {
                    $query = "SELECT COUNT(*) as total FROM shelters WHERE created_at >= $interval";
                    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
                    echo "<tr><td>$label</td><td>{$result['total']}</td></tr>";
                }
                ?>
            </table>
        </div>

           <!-- Weekly Report -->
           <div id="pet-report" style="display:none;">
<h3>Weekly Adoption Report</h3>
<table>
    <tr>
        <th>Category</th>
        <th>Details</th>
    </tr>

    <?php
    // Weekly Adoptions & Adoption Rate
    $weekly_adoptions_query = "SELECT COUNT(*) as total FROM pets WHERE adoption_status='Adopted' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $weekly_adoptions_result = mysqli_query($conn, $weekly_adoptions_query);
    $weekly_adoptions = mysqli_fetch_assoc($weekly_adoptions_result)['total'];

    $total_pets_query = "SELECT COUNT(*) as total FROM pets";
    $total_pets_result = mysqli_query($conn, $total_pets_query);
    $total_pets = mysqli_fetch_assoc($total_pets_result)['total'];
    $weekly_adoption_rate = ($total_pets > 0) ? round(($weekly_adoptions / $total_pets) * 100, 2) . "%" : "0%";

    echo "<tr><td>Total Adoptions</td><td>$weekly_adoptions</td></tr>";
    echo "<tr><td>Adoption Rate</td><td>$weekly_adoption_rate</td></tr>";
    ?>

    <!-- Weekly Adopted Animals List -->
    <tr>
        <td>Adopted Animals</td>
        <td>
            <ul>
                <?php
                $weekly_adopted_pets_query = "SELECT name, pet_type FROM pets WHERE adoption_status='Adopted' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                $weekly_adopted_pets_result = mysqli_query($conn, $weekly_adopted_pets_query);
                while ($row = mysqli_fetch_assoc($weekly_adopted_pets_result)) {
                    echo "<li>{$row['name']} ({$row['pet_type']})</li>";
                }
                ?>
            </ul>
        </td>
    </tr> 

    <!-- Weekly Newly Added Animals -->
    <tr>
        <td>Newly Added Animals</td>
        <td>
            <ul>
                <?php
                $weekly_new_pets_query = "SELECT name, breed, age, gender FROM pets WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                $weekly_new_pets_result = mysqli_query($conn, $weekly_new_pets_query);
                while ($row = mysqli_fetch_assoc($weekly_new_pets_result)) {
                    echo "<li>{$row['name']} - Breed: {$row['breed']}, Age: {$row['age']}, Gender: {$row['gender']}</li>";
                }
                ?>
            </ul>
        </td>
    </tr>

    <!-- Weekly Available Animals -->
    <tr>
        <td>Available Animals</td>
        <td>
            <ul>
                <?php
                $weekly_available_pets_query = "SELECT name, pet_type FROM pets WHERE adoption_status='Available'";
                $weekly_available_pets_result = mysqli_query($conn, $weekly_available_pets_query);
                while ($row = mysqli_fetch_assoc($weekly_available_pets_result)) {
                    echo "<li>{$row['name']} ({$row['pet_type']})</li>";
                }
                ?>
            </ul>
        </td>
    </tr>
</table>

<!-- Monthly Report -->
<h3>Monthly Adoption Report</h3>
<table>
    <tr>
        <th>Category</th>
        <th>Details</th>
    </tr>

    <!-- Monthly Adoption Trends -->
    <tr>
        <td>Adoption Trends</td>
        <td>
            <ul>
                <?php
                $monthly_trends_query = "SELECT pet_type, COUNT(*) as total FROM pets WHERE adoption_status='Adopted' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY pet_type ORDER BY total DESC";
                $monthly_trends_result = mysqli_query($conn, $monthly_trends_query);
                while ($row = mysqli_fetch_assoc($monthly_trends_result)) {
                    echo "<li>Most Adopted: {$row['pet_type']} ({$row['total']} adoptions)</li>";
                }
                ?>
            </ul>
        </td>
    </tr>

    <!-- Monthly Animal Outcome -->
    <tr>
        <td>Total Animals Adopted</td>
        <td>
            <?php
            $monthly_adoptions_query = "SELECT COUNT(*) as total FROM pets WHERE adoption_status='Adopted' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $monthly_adoptions_result = mysqli_query($conn, $monthly_adoptions_query);
            $monthly_adoptions = mysqli_fetch_assoc($monthly_adoptions_result)['total'];
            echo $monthly_adoptions;
            ?>
        </td>
    </tr>

</table>

<!-- Yearly Report -->
<h3>Yearly Adoption Report</h3>
<table>
    <tr>
        <th>Category</th>
        <th>Details</th>
    </tr>

    <!-- Yearly Total Adoptions & Adoption Rate -->
    <?php
    $yearly_adoptions_query = "SELECT COUNT(*) as total FROM pets WHERE adoption_status='Adopted' AND created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
    $yearly_adoptions_result = mysqli_query($conn, $yearly_adoptions_query);
    $yearly_adoptions = mysqli_fetch_assoc($yearly_adoptions_result)['total'];

    $yearly_adoption_rate = ($total_pets > 0) ? round(($yearly_adoptions / $total_pets) * 100, 2) . "%" : "0%";

    echo "<tr><td>Total Adoptions</td><td>$yearly_adoptions</td></tr>";
    echo "<tr><td>Adoption Rate</td><td>$yearly_adoption_rate</td></tr>";
    ?>

    <!-- Yearly Most Adopted Breeds -->
    <tr>
        <td>Most Adopted Breeds</td>
        <td>
            <ul>
                <?php
                $yearly_trends_query = "SELECT breed, COUNT(*) as total FROM pets WHERE adoption_status='Adopted' AND created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY breed ORDER BY total DESC LIMIT 5";
                $yearly_trends_result = mysqli_query($conn, $yearly_trends_query);
                while ($row = mysqli_fetch_assoc($yearly_trends_result)) {
                    echo "<li>{$row['breed']} ({$row['total']} adoptions)</li>";
                }
                ?>
            </ul>
        </td>
    </tr>

    <!-- Yearly Total Available Animals -->
    <tr>
        <td>Total Available Animals</td>
        <td>
            <?php
            $yearly_available_pets_query = "SELECT COUNT(*) as total FROM pets WHERE adoption_status='Available'";
            $yearly_available_pets_result = mysqli_query($conn, $yearly_available_pets_query);
            $yearly_available_pets = mysqli_fetch_assoc($yearly_available_pets_result)['total'];
            echo $yearly_available_pets;
            ?>
        </td>
    </tr>
</table>
 <!-- Deleted Pets Report -->

</div></div></div>

<script>
    function showReport() {
        document.getElementById('user-report').style.display = 'none';
        document.getElementById('shelter-report').style.display = 'none';
        document.getElementById('pet-report').style.display = 'none';
        
        var selectedReport = document.getElementById('report-type').value;
        document.getElementById(selectedReport).style.display = 'block';
    }
</script>

</body>
</html>
