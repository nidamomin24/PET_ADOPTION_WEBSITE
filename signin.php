<?php
session_start();
include 'includes/db_connect.php'; // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the user is admin
    if ($email == "admin@gmail.com" && $password == "admin123") {
        $_SESSION['admin_email'] = $email;
        
        // Redirect to Admin Dashboard
        header("Location: admin_dashboard.php");
        exit(); 
    }
    

    // Check if the user exists in users table
    $sql_user = "SELECT * FROM users WHERE email = '$email'";
    $result_user = mysqli_query($conn, $sql_user);
    
    if (mysqli_num_rows($result_user) > 0) {
        $row = mysqli_fetch_assoc($result_user);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['account_type'] = "User";
            
            // Redirect to User Dashboard
            header("Location: user_dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
            exit();
        }
    }

    // Check if the user exists in shelters table
    $sql_shelter = "SELECT * FROM shelters WHERE email = '$email'";
    $result_shelter = mysqli_query($conn, $sql_shelter);

    if (mysqli_num_rows($result_shelter) > 0) {
        $row = mysqli_fetch_assoc($result_shelter);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['shelter_id'] = $row['id'];
            $_SESSION['shelter_name'] = $row['name'];
            $_SESSION['shelter_email'] = $row['email'];
            $_SESSION['account_type'] = "Shelter";
            
            // Redirect to Shelter Dashboard
            header("Location: shelter_dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
            exit();
        }
    }

    // If no match found
    echo "Invalid email or account not found!";
    exit();
}

// Close database connection
mysqli_close($conn);
?>
