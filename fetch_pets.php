<?php
include('includes/db_connect.php');

if (!isset($_SESSION['shelter_id']) || $_SESSION['account_type'] != 'Shelter') {
    header("Location: signin.html");
    exit();
}

$sql = "SELECT * FROM pets WHERE shelter_id = " . $_SESSION['shelter_id'];
$result = $conn->query($sql);

$pets = array();
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}

$conn->close();

echo json_encode($pets);
?>