<?php 
include('includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $adopt_date = $_POST['adopt_date'];  
    $adopt_time = $_POST['adopt_time'];  
    $destination = $_POST['destination']; 
    $request_id = intval($_POST['request_id']); 

    // Prepare the SQL statement
    $sql = "UPDATE adoption_requests 
            SET adopt_date = ?, adopt_time = ?, destination = ?
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters (s = string, i = integer)
        $stmt->bind_param("sssi", $adopt_date, $adopt_time, $destination, $request_id);
        
        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Adoption request updated successfully!'); window.location.href='shelter_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating request: " . $stmt->error . "');</script>";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "<script>alert('Invalid request!');</script>";
}


?>