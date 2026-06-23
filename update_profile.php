<?php
session_start();
include 'includes/db_connect.php'; // Ensure this file connects to your database

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$country_code = $_POST['country_code'];
$state = $_POST['state'];
$city = $_POST['city'];
$pincode = $_POST['pincode'];

// Update user info in the database
$sql = "UPDATE users SET name = ?, phone = ?, country_code = ?, state = ?, city = ?, pincode = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $name, $phone, $country_code, $state, $city, $pincode, $user_id);


if ($stmt->execute()) {
    // Update session variables to reflect changes
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    $_SESSION['country_code'] = $country_code;
    $_SESSION['state'] = $state;
    $_SESSION['city'] = $city;
    $_SESSION['pincode'] = $pincode;

    header("Location: user_dashboard.php?update=success");
} else {
    header("Location: user_dashboard.php?update=error");
}

$stmt->close();
$conn->close();
?>