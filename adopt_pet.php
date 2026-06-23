<?php
session_start();
include('includes/db_connect.php');

// Get the pet ID from the URL
if (!isset($_GET['pet_id']) || empty($_GET['pet_id'])) {
    die("Pet ID is missing!");
}

$pet_id = (int) $_GET['pet_id'];  // Sanitize pet ID

// Check if the pet exists in the database
$pet_check_query = "SELECT id FROM pets WHERE id = ?";
$stmt = $conn->prepare($pet_check_query);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$pet_check_result = $stmt->get_result();

if (!$pet_check_result || $pet_check_result->num_rows === 0) {
    die("Error: Pet not found!");
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['pending_pet_id'] = $pet_id;  // Store for later use
    header("Location: signup.html");
    exit();
} else {
    $user_id = $_SESSION['user_id'];

    // Check if the pet is already in the user's favorite list
    $check_query = "SELECT 1 FROM favorite_pets WHERE pet_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $pet_id, $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if (!$result) {  
        // Log the error and stop execution  
        error_log("MySQL Error: " . $conn->error);  
        die("Database error. Please try again later.");
    }

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Pet already exists in favorites list');
            window.location.href='pawtopia.php';
            </script>";
        exit();
    }

    // Insert the pet into the user's favorites
    $insert_query = "INSERT INTO favorite_pets (user_id, pet_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ii", $user_id, $pet_id);

    if ($stmt->execute()) {
        header("Location: user_dashboard.php");
        exit();
    } else {
        die("Error: Failed to add pet to favorites. " . $stmt->error);
    }
}
?>
