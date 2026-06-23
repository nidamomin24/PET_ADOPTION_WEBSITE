<?php 
session_start();
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['action'] == "adopt_request") {
        $sql = "INSERT INTO adoption_requests (shelter_id, user_id, pet_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Check if prepare() was successful
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("iii", $_POST['shelter_id'], $_POST['user_id'], $_POST['pet_id']);

        if ($stmt->execute()) {
            echo "Adoption Request Sent Successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }else if ($_POST['action'] == "view") {
        $sql = "SELECT p.name, p.id AS pet_id, p.photo, a.*, u.name AS user_name
                    FROM adoption_requests a
                    JOIN pets p ON a.pet_id = p.id
                    JOIN users u ON a.user_id = u.id
                    JOIN shelters s ON a.shelter_id = s.id
                    WHERE a.id = " . $_POST['request_id'] . " AND  a.user_id = " . $_SESSION['user_id'];

        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        // print_r($data);

        $s = "
        <h2>Adoption Request #" . $data['id'] . "</h2>
        <h4>Status: " . ucfirst($data['status']) . "</h4>
        <ul>
            <li>Name of Pet: " . $data['name'] . "</li>
            <li>Requester's Name: " . $data['user_name'] . "</li>
            <li>Request sent on: " . $data['created_at'] . "</li>
            <li>
                Details shared by Shelter:
                <ul>
                    <li>Date of Adoption: " . ($data['adopt_date'] == "" ? "None" : $data['adopt_date']) . "</li>
                    <li>Time of Adoption: " . ($data['adopt_time'] == "" ? "None" : $data['adopt_time']) . "</li>
                    <li>Destination of Adoption: " . ($data['destination'] == "" ? "None" : $data['destination']) . "</li>
                </ul>
            </li>
        </ul>
        <button class='adopt-btns' data-action='delete' data-id='" . $data['id'] . "'>Delete Request</button>
        ";

        echo $s;
    }else if ($_POST['action'] == "delete") {
        $sql = "DELETE FROM adoption_requests WHERE id = " . $_POST['request_id'];
        if($conn->query($sql)){
            echo "Request Deleted Successfully!";
        }
    }
}
?>
