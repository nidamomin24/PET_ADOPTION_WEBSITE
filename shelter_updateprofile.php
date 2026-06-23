<?php
session_start();
include 'includes/db_connect.php'; // Ensure this file connects to your database

// Check if shelter is logged in
if (!isset($_SESSION['shelter_id'])) {
    header("Location: signin.html");
    exit();
}

$shelter_id = $_SESSION['shelter_id']; // Use the correct session variable

// Fetch current email from database
$sql = "SELECT email FROM shelters WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shelter_id);
$stmt->execute();
$stmt->bind_result($existing_email);
$stmt->fetch();
$stmt->close();

// Validate & Assign Input Values (Avoid Undefined Index Errors)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : $existing_email;
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$country_code = isset($_POST['country_code']) ? $_POST['country_code'] : '';
$state = isset($_POST['state']) ? $_POST['state'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$pincode = isset($_POST['pincode']) ? $_POST['pincode'] : '';

// Ensure database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Update shelter details (Preserve email if not updated)
$sql = "UPDATE shelters SET name = ?, email = ?, phone = ?, country_code = ?, state = ?, city = ?, pincode = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssssssi", $name, $email, $phone, $country_code, $state, $city, $pincode, $shelter_id);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['name'] = $name;
        $_SESSION['phone'] = $phone;
        $_SESSION['country_code'] = $country_code;
        $_SESSION['state'] = $state;
        $_SESSION['city'] = $city;
        $_SESSION['pincode'] = $pincode;

        header("Location: shelter_dashboard.php?update=success");
        exit();
    } else {
        header("Location: shelter_dashboard.php?update=error");
        exit();
    }

    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

$conn->close();
?>
