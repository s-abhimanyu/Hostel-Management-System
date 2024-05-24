<?php
// Include your config.php and database connection here
include('includes/config.php');

if (isset($_POST['auto_allocate'])) {
    // Check if a specific student registration number is provided
    if (isset($_POST['regno'])) {
        $specificRegNo = $_POST['regno'];

        // Check if the specific student is unallocated
        $sqlCheckAllocation = "SELECT regno FROM registration WHERE regno = ? AND regno NOT IN (SELECT regno FROM allocation)";
        $stmtCheckAllocation = $mysqli->prepare($sqlCheckAllocation);

        if ($stmtCheckAllocation) {
            $stmtCheckAllocation->bind_param('s', $specificRegNo);
            $stmtCheckAllocation->execute();
            $stmtCheckAllocation->store_result();

            if ($stmtCheckAllocation->num_rows > 0) {
                // Fetch an available room (Modify this query according to your room allocation logic)
                $sqlGetRoom = "SELECT room_no, seater FROM rooms WHERE room_no NOT IN (SELECT room_id FROM allocation)";
                $resultRoom = $mysqli->query($sqlGetRoom);

                if ($resultRoom && $resultRoom->num_rows > 0) {
                    $row = $resultRoom->fetch_assoc();
                    $roomNo = $row['room_no'];
                    $seater = $row['seater'];

                    // Insert allocation record for the specific student into the 'allocation' table with seater value
                    $insertQuery = "INSERT INTO allocation (room_id, regno, allocation_date, seater) VALUES (?, ?, NOW(), ?)";
                    $stmtInsert = $mysqli->prepare($insertQuery);

                    if ($stmtInsert) {
                        $stmtInsert->bind_param('ssi', $roomNo, $specificRegNo, $seater);
                        $stmtInsert->execute();
                        $stmtInsert->close();

                        echo '<script>';
                        echo 'alert("Allocation successful!");';
                        echo 'window.location.href = "manage-students.php";';
                        echo '</script>';
                    } else {
                        echo "Failed to prepare allocation statement.";
                    }
                } else {
                    echo "No available rooms for allocation.";
                }
            } else {
                echo "The specified student is already allocated or does not exist.";
            }
            $stmtCheckAllocation->close();
        } else {
            echo "Failed to prepare check allocation statement.";
        }
    } else {
        echo "Please provide a registration number.";
    }
} else {
    echo "Allocation form was not submitted.";
}
?>
