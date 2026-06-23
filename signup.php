<?php
session_start();
include 'includes/db_connect.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $country_code = mysqli_real_escape_string($conn, $_POST['country_code']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $account_type = mysqli_real_escape_string($conn, $_POST['select_opt']);

    // Password hashing for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into the respective table based on account type
    if ($account_type == "User") {
        $sql = "INSERT INTO users (name, email, country_code, phone, password) 
                VALUES ('$name', '$email', '$country_code', '$phone', '$hashed_password')";
    } elseif ($account_type == "Shelter") {
        $sql = "INSERT INTO shelters (name, email, country_code, phone, password) 
                VALUES ('$name', '$email', '$country_code', '$phone', '$hashed_password')";
    } else {
        echo "Invalid account type selected.";
        exit();
    }

    if (mysqli_query($conn, $sql)) {
        // Redirect to Sign In page after successful registration
        header("Location: signin.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>
