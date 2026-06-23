<?php 

include('includes/db_connect.php');

if(isset($_POST['id']) && isset($_POST['action'])) {
    if($_POST['action'] == "edit") {
        $id = $_POST['id'];
        $s = "SELECT * FROM pets WHERE id = ?";
        
        $stmt = $conn->prepare($s);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if($data) { 
            echo '<h2 align="center">Edit Pet</h2>
            <form action="update_pet.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="' . $data['id'] . '">

                <label for="name">Pet Name:</label>
                <input type="text" name="name" value="' . htmlspecialchars($data['name']) . '" required>

                <label for="breed">Breed:</label>
                <input type="text" name="breed" value="' . htmlspecialchars($data['breed']) . '" required>

                <label for="age">Age (Years):</label>
                <input type="number" name="age" value="' . htmlspecialchars($data['age']) . '" required>

                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="Male" ' . ($data['gender'] == "Male" ? "selected" : "") . '>Male</option>
                    <option value="Female" ' . ($data['gender'] == "Female" ? "selected" : "") . '>Female</option>
                </select>

                <label for="color">Color:</label>
                <input type="text" name="color" value="' . htmlspecialchars($data['color']) . '" required>

                <label for="vaccination">Vaccination Status:</label>
                <select name="vaccination" required>
                    <option value="Vaccinated" ' . ($data['vaccination'] == "Vaccinated" ? "selected" : "") . '>Vaccinated</option>
                    <option value="Not Vaccinated" ' . ($data['vaccination'] == "Not Vaccinated" ? "selected" : "") . '>Not Vaccinated</option>
                </select>

                <label for="adoption_status">Adoption Status:</label>
                <select name="adoption_status" required>
                    <option value="Available" ' . ($data['adoption_status'] == "Available" ? "selected" : "") . '>Available</option>
                    <option value="Adopted" ' . ($data['adoption_status'] == "Adopted" ? "selected" : "") . '>Adopted</option>
                </select>

                <label for="pet_type">Pet Type:</label>
                <select name="pet_type" required>
                    <option value="Dog" ' . ($data['pet_type'] == "Dog" ? "selected" : "") . '>Dog</option>
                    <option value="Cat" ' . ($data['pet_type'] == "Cat" ? "selected" : "") . '>Cat</option>
                </select>

                <label for="description">Description:</label>
                <textarea name="description" required>' . htmlspecialchars($data['description']) . '</textarea>

                 <label for="description">Location:</label>
                <textarea name="location" required>' . htmlspecialchars($data['location']) . '</textarea>

                <label for="photo">Upload Pet Photo:</label>
                <input type="file" name="photo" accept="image/*">
                
                <img src="uploads/' . htmlspecialchars($data['photo']) . '" alt="Current Pet Photo" width="100">
                
                <button type="submit">Update Pet</button>
            </form>';
        } else {
            echo "<p>Error: Pet not found.</p>";
        }
    }else if($_POST['action'] == "delete"){
        $s = "SELECT photo FROM pets WHERE id = " . $_POST['id'];
        $result = $conn->query($s);
        $data = $result->fetch_assoc();
        $image = "uploads/" . $data['photo'];
        if(file_exists($image)){
            unlink($image);
            $delete = "DELETE FROM pets WHERE id = " . $_POST['id'];
            if($conn->query($delete)){
                echo "Pet Deleted successfully!";
            }
        }
    }
}

?>
