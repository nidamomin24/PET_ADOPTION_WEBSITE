<?php
session_start(); // Ensure session is started
include('includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if shelter_id is set
    if (!isset($_SESSION['shelter_id'])) {
        die("Shelter ID not found. Please log in.");
    }

    // Get form data
    $name = trim($_POST['name']);
    $breed = trim($_POST['breed']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $color = trim($_POST['color']);
    $vaccination = trim($_POST['vaccination']);
    $adoption_status = trim($_POST['adoption_status']);
    $pet_type = trim($_POST['pet_type']);
    $description = trim($_POST['description']);
    $shelter_id = $_SESSION['shelter_id'];
    $location = $_POST['location'];
    

    // File upload handling
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    $photo = $_FILES['photo']['name'];
    $photo_tmp = $_FILES['photo']['tmp_name'];
    $photo_size = $_FILES['photo']['size'];
    $photo_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));

  
    // Generate unique filename
    $new_filename = uniqid("pet_", true) . "." . $photo_ext;
    $target_file = $target_dir . $new_filename;

    // Move uploaded file
    if (move_uploaded_file($photo_tmp, $target_file)) {
        echo "File uploaded successfully.<br>";
    } else {
        die("Error uploading file.");
    }

    // Insert data using prepared statements
    $sql = "INSERT INTO pets (name, breed, age, gender, color, vaccination, adoption_status, pet_type, description, location, photo, shelter_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssissssssssi", 
        $name, $breed, $age, $gender, $color, $vaccination, 
        $adoption_status, $pet_type, $description, $location, 
        $new_filename, $shelter_id
    );
        
        if ($stmt->execute()) {
            echo "
            <script>
            alert('Pet added successfully!');
            window.location.href='shelter_dashboard.php';
            </script>
            ";
        } else {
            echo "
            <script>
            alert('Error: " . $stmt->error . "');
            window.location.href='shelter_dashboard.php';
            </script>";
            // echo ";
        }
        
        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
}

$conn->close();
?>
