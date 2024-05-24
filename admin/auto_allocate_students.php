<?php
session_start();
include('includes/config.php');

function fetchRegNosFromDatabase($mysqli) {
    $regNos = array();
    $sql = "SELECT regno FROM registration"; // Adjust your SQL query as needed
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $regNos[] = $row['regno'];
        }
    }

    return $regNos;
}

$regNos = fetchRegNosFromDatabase($mysqli);

// Auto Allocate Students Button
if (isset($_POST['auto_allocate'])) {
    autoAllocateStudentsRandomly($mysqli, $regNos);
}

// Auto Allocate Students Function
function autoAllocateStudentsRandomly($mysqli, $regNos) {
    // Fetch unallocated students
    $unallocatedStudents = array();
    $sqlStudents = "SELECT regno FROM registration WHERE regno NOT IN (SELECT regno FROM allocation)";
    $resultStudents = $mysqli->query($sqlStudents);

    if ($resultStudents->num_rows > 0) {
        while ($row = $resultStudents->fetch_assoc()) {
            $unallocatedStudents[] = $row['regno'];
        }
    }

    // Fetch rooms with seater capacity and not allocated
    $sqlRooms = "SELECT room_no, seater FROM rooms WHERE room_no NOT IN (SELECT room_id FROM allocation)";
    $resultRooms = $mysqli->query($sqlRooms);

    $rooms = array();
    if ($resultRooms->num_rows > 0) {
        while ($row = $resultRooms->fetch_assoc()) {
            $rooms[$row['room_no']] = $row['seater'];
        }
    }

    // Allocate students randomly to rooms
    foreach ($rooms as $roomNo => $seater) {
        for ($i = 0; $i < $seater && count($unallocatedStudents) > 0; $i++) {
            $randomStudentIndex = array_rand($unallocatedStudents);
            $randomStudent = $unallocatedStudents[$randomStudentIndex];

            // Insert allocation record for the student into the 'allocation' table with seater value
            $insertQuery = "INSERT INTO allocation (room_id, regno, allocation_date, seater) VALUES (?, ?, NOW(), ?)";
            $stmt = $mysqli->prepare($insertQuery);

            if ($stmt) {
                $stmt->bind_param('ssi', $roomNo, $randomStudent, $seater);
                $stmt->execute();
                $stmt->close();

                // Remove the allocated student from the unallocated students list
                unset($unallocatedStudents[$randomStudentIndex]);
            }
        }
    }

    // Provide a success message and redirect
    echo '<script>';
    echo 'alert("Allocation successful!");';
    echo 'window.location.href = "manage-students.php";'; // Replace 'your_redirect_page.php' with your desired page
    echo '</script>';
}
?>

