<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Header styles */
        header {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        /* Container for content */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }

        /* CSS for positioning the form */
        .allocate-form {
            position: static;
            top: 160px;
            right: 20px;
            z-index: 9999; /* Ensure it's above other elements */
        }

        /* CSS styles for the button */
        .allocate-button {
            padding: 8px 15px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Styles for button hover and focus */
        .allocate-button:hover,
        .allocate-button:focus {
            background-color: #2980b9;
        }



    </style>
    
</head>

</body>
</html>
<?php
// Include your config.php and database connection here
include('includes/config.php');

// Function to fetch registration numbers
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


if (isset($_GET['floor'])) {
    $selectedFloor = $_GET['floor'];
    
    // Fetch and display rooms for the selected floor
    $sql = "SELECT rooms.room_id, rooms.room_no, rooms.seater, floors.floor_name
            FROM rooms
            INNER JOIN floors ON rooms.floor_id = floors.floor_id
            WHERE floors.floor_name = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $selectedFloor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Initialize variables to keep track of the current floor
        $currentFloor = null;

        while ($row = $result->fetch_object()) {
            // Check if the floor has changed
            if ($row->floor_name !== $currentFloor) {
                // Extract the floor number from the floor name, assuming the floor name is in the format "Floor X"
                preg_match('/Floor (\d+)/', $row->floor_name, $matches);
                $floorNumber = isset($matches[1]) ? $matches[1] : $row->floor_name;

                // Display the floor name as a header
                echo "<h3>Floor " . $floorNumber . "</h3>";
                // Update the current floor
                $currentFloor = $row->floor_name;
            }

            // Display room information
            echo "<h>Room No: " . $row->room_no . "<br>";
            echo "<p>Seater: " . $row->seater . "</p>";
            
            
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
        }
    } else {
        $sql = "SELECT floors.floor_name, GROUP_CONCAT(rooms.room_no) AS room_list
                FROM floors
                LEFT JOIN rooms ON floors.floor_id = rooms.floor_id
                GROUP BY floors.floor_name";
        $result = $mysqli->query($sql);

        while ($row = $result->fetch_object()) {
            echo "<h3>Floor " . $row->floor_name . "</h3>";
            if (!empty($row->room_list)) {
                $rooms = explode(',', $row->room_list);
                foreach ($rooms as $room) {
                    echo "<p>Room No: " . $room . "</p>";
                }
            } else {
                echo "No rooms found for this floor.";
            }
        }
    }
    $stmt->close();
} else {
    $sql = "SELECT DISTINCT floor_name FROM floors";
    $result = $mysqli->query($sql);

    while ($row = $result->fetch_object()) {
        echo '<a href="get_rooms_by_floor.php?floor=' . $row->floor_name . '">' . $row->floor_name . '</a><br>';
    }
}




?>


