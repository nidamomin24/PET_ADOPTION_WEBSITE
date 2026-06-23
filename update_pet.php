<?php
include 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $color = $_POST['color'];
    $vaccination = $_POST['vaccination'];
    $adoption_status = $_POST['adoption_status'];
    $pet_type = $_POST['pet_type'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    // Fetch existing photo before making any changes
    $s = "SELECT photo FROM pets WHERE id = ?";
    $stmt = $conn->prepare($s);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $r = $stmt->get_result();
    $d = $r->fetch_assoc();

    $photo = $d['photo']; // Default to existing photo

    // Handle photo upload only if a new file is provided
    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === 0) {
        $oldImage = "uploads/" . $photo;

        // Generate a unique name for the new photo
        $photoExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $newPhotoName = uniqid("pet_", true) . "." . $photoExt;
        $target = "uploads/" . $newPhotoName;

        // Move file and only update $photo if successful
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            // Delete old photo only after successfully uploading new one
            if (!empty($photo) && file_exists($oldImage)) {
                unlink($oldImage);
            }
            $photo = $newPhotoName; 
        }
    }

    // Debug: Check if photo variable is properly set
    if (empty($photo)) {
        die("Error: Photo variable is empty before database update.");
    }

    // Using prepared statements to avoid SQL injection and handle special characters
    $sql = "UPDATE pets SET name = ?, breed = ?, age = ?, gender = ?, color = ?, 
            vaccination = ?, adoption_status = ?, pet_type = ?, description = ?, 
            location = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssssssi", $name, $breed, $age, $gender, $color, $vaccination, 
                      $adoption_status, $pet_type, $description, $location, $photo, $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
            alert('Pet details updated successfully!');
            window.location.href = 'shelter_dashboard.php';
            </script>";
    } else {
        die("Database update error: " . $stmt->error);
    }
}
?>
