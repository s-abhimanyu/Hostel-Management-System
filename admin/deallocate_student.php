<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// ... (Your previous code)

if (isset($_POST['deallocate'])) {
    $room_id = $_POST['room_no'];
    $regno = $_POST['reg_no']; // Assuming the form field is named 'student_regno'

    // Check the current occupancy of the room
    $checkOccupancyQuery = "SELECT COUNT(*) AS occupancy FROM allocation WHERE room_id = ?";
    $stmt = $mysqli->prepare($checkOccupancyQuery);

    if ($stmt) {
        $stmt->bind_param('i', $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $occupancy = $row['occupancy'];

        if ($occupancy === 0) {
            // No students in the room, display error message
            echo '<script>alert("No students available to deallocate.");</script>';
            echo '<script>window.location.href = "manage-rooms.php";</script>';
        } else {
            // Students found in the room, proceed with deallocation
            $deleteQuery = "DELETE FROM allocation WHERE room_id = ? AND regno = ?";
            $stmt = $mysqli->prepare($deleteQuery);

            if ($stmt) {
                $stmt->bind_param('is', $room_id, $regno);
                if ($stmt->execute()) {
                    // Deallocation was successful
                    echo '<script>alert("Deallocation Successful");</script>';
                    echo '<script>window.location.href = "manage-rooms.php";</script>';
                } else {
                    // Handle the case where the deletion failed
                    echo '<script>alert("Deallocation failed: ' . $stmt->error . '");</script>';
                }
                $stmt->close();
            } else {
                // Handle the case where the prepared statement creation failed
                echo '<script>alert("Prepare failed: ' . $mysqli->error . '");</script>';
            }
        }
    } else {
        // Handle the case where the prepared statement for checking occupancy failed
        echo '<script>alert("Occupancy check failed: ' . $mysqli->error . '");</script>';
    }
}

?>

