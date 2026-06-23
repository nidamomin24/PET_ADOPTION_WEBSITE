<?php 
include('includes/db_connect.php');

if(!empty($_POST['for']) && $_POST['for'] == "adoption_requests"){
    $request_id = intval($_POST['request_id']); // Ensure it's an integer

    if($_POST['action'] == "view"){ 

        $sql = "SELECT u.name AS user_name, u.email, u.phone, u.city, u.state, u.pincode, 
                    p.name AS pet_name, p.id AS pet_id, p.photo, a.*
                FROM adoption_requests a
                JOIN pets p ON a.pet_id = p.id
                JOIN users u ON a.user_id = u.id
                JOIN shelters s ON a.shelter_id = s.id
                WHERE a.id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Query Failed: " . $conn->error);
        }

        $data = $result->fetch_assoc();
        // print_r($data);

        $s = "
        <h2>Adoption Request #" . $data['id'] . "</h2>
        <h4>Status: " . ucfirst($data['status']) . "</h4>
        <b>Request User Details:</b>
        <ul>
            <li>Name: " . ucfirst($data['user_name']) . "</li>
            <li>Email: " . $data['email'] . "</li>
            <li>Phone: " . $data['phone'] . "</li>
            <li>City: " . ucfirst($data['city']) . "</li>
            <li>State: " . ucfirst($data['state']) . "</li>
            <li>Pin Code: " . $data['pincode'] . "</li>
        </ul>
        <b>Pet Details</b>
        <ul>
            <li>Pet ID: " . $data['pet_id'] . "</li>
            <li>Name of Pet: " . $data['pet_name'] . "</li>
            <li>Pet Image: <img src='uploads/" . htmlspecialchars($data['photo']) . "' width='80'></li>
        </ul>
        ";

        if ($data['status'] == "pending") {
            $s .= "
                <button onclick=\"req_btn_action(" . $request_id . ",'approve')\">Approve Request</button>
                <button onclick=\"req_btn_action(" . $request_id . ",'reject')\">Reject Request</button>
                <button onclick=\"req_btn_action(" . $request_id . ",'delete')\">Delete Request</button>
            ";
        } else if ($data['status'] == "rejected") {
            $s .= "<button onclick=\"req_btn_action(" . $request_id . ",'delete')\">Delete Request</button>";
        }else if($data['status'] == "approved"){
            $s .= "
            <h4>Set Adoption Details for customer:</h4>
            <form action='update-request.php' method='POST'>
                <input type='hidden' name='request_id' value='" . $request_id . "'>
                <label for='date'>Set Adopt Date:</label>
                    <input type='date' name='adopt_date' value='" . ($data['adopt_date'] != '' ? $data['adopt_date'] : "") . "' required>
                </label>
                <label for='date'>Set Adopt Time:</label>
                    <input type='time' name='adopt_time' value='" . ($data['adopt_time'] != '' ? $data['adopt_time'] : "") . "' required>
                </label>
                <label for='date'>Set Adopt Destination:</label>
                    <textarea name='destination' required>" . ($data['destination'] != '' ? $data['destination'] : "") . "</textarea>
                </label>
                
                <input type='submit'>
            </form>
            <button onclick=\"req_btn_action(" . $request_id . ",'delete')\">Delete Request</button>
            ";
        }

        $s .= '
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
        function req_btn_action(id,action){
        var data = {
            request_id: id,
            action: action,
            for: "adoption_requests"
        };
        if(action == "delete"){
            var c = confirm("Are you sure you want to delete this request?");
            if(!c){
                return;
            }
        }
        $.ajax({
            url: "adoption-req.php", // API endpoint
            type: "POST", // HTTP method
            data: data, // Expected response type
            success: function(response) {
                if(action == "view"){
                    $("#manage-pets").html(response); // Handle success
                }else{
                    alert(response);
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error); // Handle error
            }
        });
        </script>';

        echo $s;
    }else if($_POST['action'] == "delete"){
        $sql = "DELETE FROM adoption_requests WHERE id = " . $request_id;
        if($conn->query($sql)){
            echo "Request Deleted Successfully!";
        }
    }else if($_POST['action'] == "approve"){
        // Get data to enter in adoption history
        $req_data = "SELECT id, shelter_id, pet_id FROM adoption_requests WHERE id = ?";
        $stmt = $conn->prepare($req_data);
        $stmt->bind_param('i', $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $req_data = $result->fetch_assoc();

        if (!$req_data) {
            die("Error: No matching request found!");
        }

        // Update query
        $sql = "UPDATE adoption_requests SET status = 'approved' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $request_id);
        if ($stmt->execute()) {
            $sql = "INSERT INTO adoption_history (pet_id, shelter_id, request_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            // Check if the prepare() was successful
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $sql1 = "UPDATE pets SET adoption_status = 'adopted' WHERE id = " . $req_data['pet_id'];

            $stmt->bind_param('iii', $req_data['pet_id'], $req_data['shelter_id'], $request_id);
            if ($stmt->execute() && $conn->query($sql1)) {
                echo "Request Approved Successfully!";
            } else {
                die("Error executing statement: " . $stmt->error);
            }
        } else {
            die("Error updating adoption request: " . $stmt->error);
        }

    }else if($_POST['action'] == "reject"){
        $sql = "UPDATE adoption_requests SET status = 'rejected' WHERE id = " . $request_id;
        if($conn->query($sql)){
            echo "Request Rejected Successfully!";
        }
    }
}

?>