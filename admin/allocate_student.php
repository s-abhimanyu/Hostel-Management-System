<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// ... (Your previous code)

if (isset($_POST['allocate'])) {
    $room_id = $_POST['room_no'];
    $seater = $_POST['seater'];
    $regno = $_POST['reg_no']; 
    // You should perform validation and error handling here

    // Check if the student is already allocated to the selected room
    $checkAllocationQuery = "SELECT COUNT(*) AS count FROM allocation WHERE room_id = ? AND regno = ?";
    $stmt = $mysqli->prepare($checkAllocationQuery);

    if ($stmt) {
        $stmt->bind_param('is', $room_id, $regno);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $allocationCount = $row['count'];

        if ($allocationCount > 0) {
            // Student is already allocated to the room
            echo '<script>alert("This student is already allocated to a room.");</script>';
            echo '<script>window.location.href = "manage-rooms.php";</script>';
        } else {
            // Proceed with the allocation
            $insertQuery = "INSERT INTO allocation (room_id, regno, allocation_date, seater) VALUES (?, ?, NOW(), ?)";
            $stmt = $mysqli->prepare($insertQuery);

            if ($stmt) {
                $stmt->bind_param('iis', $room_id, $regno, $seater);
                if ($stmt->execute()) {
                    // Allocation was successful
                    echo '<script>alert("Allocation Successful");</script>';
                    echo '<script>window.location.href = "manage-rooms.php";</script>';
                } else {
                    // Handle the case where the insertion failed
                    echo '<script>alert("Allocation failed: ' . $stmt->error . '");</script>';
                }
                $stmt->close();
            } else {
                // Handle the case where the prepared statement creation failed
                echo '<script>alert("Prepare failed: ' . $mysqli->error . '");</script>';
            }
        }
    } else {
        // Handle the case where the prepared statement for checking allocation failed
        echo '<script>alert("Allocation check failed: ' . $mysqli->error . '");</script>';
    }
}


?>
