<?php
// Include your config.php and database connection here
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
if (isset($_GET['room_no'])) {
    $roomNumber = $_GET['room_no'];

    // Fetch rooms based on the entered room number
    $sql = "SELECT rooms.room_id, rooms.room_no, rooms.seater, floors.floor_name
            FROM rooms
            INNER JOIN floors ON rooms.floor_id = floors.floor_id
            WHERE rooms.room_no LIKE '%" . $roomNumber . "%'";

    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        // Display rooms that match the entered room number
        while ($row = $result->fetch_object()) {
            echo "<p>Room No: " . $row->room_no . "</p>";
            echo "<p>Seater: " . $row->seater . "</p>";
            // Add allocation and deallocation forms as needed
            // Add allocation form
            echo '<form method="post" action="allocate_student.php">';
            echo '<input type="hidden" name="room_no" value="' . $row->room_no . '">';
            echo '<input type="hidden" name="seater" value="' . $row->seater . '">';
            echo '<select name="reg_no">';
            // Populate the select options with students
            foreach ($regNos as $regNo) {
                echo '<option value="' . $regNo . '">' . $regNo . '</option>';
            }
            echo '</select>';
            echo '<input type="submit" name="allocate" value="Allocate Student">';
            echo '</form>';
            
            // Add deallocation form
            echo '<form method="post" action="deallocate_student.php">';
            echo '<input type="hidden" name="room_no" value="' . $row->room_no . '">';
            echo '<input type="hidden" name="seater" value="' . $row->seater . '">';
            echo '<select name="reg_no">';
            // Populate the select options with allocated students in this room
            foreach ($regNos as $regNo) {
                echo '<option value="' . $regNo . '">' . $regNo . '</option>';
            }
            echo '</select>';
            echo '<input type="submit" name="deallocate" value="Deallocate Student">';
            echo '</form>';
            echo "<p>&nbsp;</p>";
            echo '<hr>';
        }
    } else {
        echo "No rooms found matching the search criteria.";
    }
} else {
    echo "Room number parameter not provided.";
}
?>
