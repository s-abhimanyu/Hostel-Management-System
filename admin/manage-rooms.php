<!DOCTYPE html>
<html>
<head>
    <style>

        /* CSS for positioning the form */
        .allocate-form {
            position: static;
            top: 20px;
            left: 20px;
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

        /* Styles for room display */
        h3 {
            font-size: 24px;
            margin-top: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        /* Styles for room information */
        .room-info {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        /* Styles for form labels */
        label {
            display: block;
            margin-bottom: 5px;
        }

        /* Styles for form select and buttons */
        select, input[type="submit"] {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 4px;
        }

        #floorFilterContainer {
        position: fixed;
        top: 250px; /* Adjust the top position as needed */
        right: 50px; /* Adjust the right position to position the dropdown on the right */
        z-index: 9999; /* Ensure it's above other elements */
    }
    </style>
</head>
</body>
</html>

<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Include the function to fetch registration numbers
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

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $adn = "DELETE FROM rooms WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Data Deleted');</script>";
}
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12"><br>
                        <br><h2 class="page-title">Allocation Panel</h2>
                        </div>
                        <div class="panel panel-default">
                        <div class="panel-heading">All Room Details</div>
                         </div>
                           
							<div class="panel-body">
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <div>
                                    
<body>
    
    <div id="floorFilterContainer">
        <label for="floorFilter">Filter by Floor:</label><br>
        <select id="floorFilter">
            <option value="">All Floors</option>
            <?php
            // Fetch a list of distinct floors from your database
            $sql = "SELECT DISTINCT floor_name FROM floors";
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_object()) {
                echo '<option value="' . $row->floor_name . '">' . $row->floor_name . '</option>';
            }
            ?>
        </select>
    </div>
        <!-- search by room -->
        <label for="roomSearch">Search by Room Number:</label><br>
                            <input type="text" id="roomSearch" placeholder="Enter Room Number">
                            <button onclick="searchRooms()">Search</button>
    

                        <div class="panel-body" id="filteredRooms">
                                <?php
                                // Fetch and populate the $rooms variable
                        
                                $rooms = array(); // Initialize an empty array to hold room data

                                // Fetch room data from your database and store it in the $rooms array
                                $sql = "SELECT rooms.room_id, rooms.room_no, rooms.seater, floors.floor_name
                                        FROM rooms
                                        INNER JOIN floors ON rooms.floor_id = floors.floor_id
                                        ORDER BY floors.floor_name, rooms.room_no";


                                $result = $mysqli->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_object()) {
                                        $rooms[] = $row; // Add each room to the $rooms array
                                    }

                                    // Initialize variables to keep track of the current floor
                                    $currentFloor = null;

                                    foreach ($rooms as $room) {
                                        // Check if the floor has changed
                                        if ($room->floor_name !== $currentFloor) {
                                            // Extract the floor number from the floor name, assuming the floor name is in the format "Floor X"
                                            preg_match('/Floor (\d+)/', $room->floor_name, $matches);
                                            $floorNumber = isset($matches[1]) ? $matches[1] : $room->floor_name;

                                            // Display the floor name as a header
                                            echo "<h3>Floor " . $floorNumber . "</h3>";
                                            // Update the current floor
                                            $currentFloor = $room->floor_name;
                                        }

                                        // Display room information
                                        echo "<p>Room No: " . $room->room_no . "</p>";
                                        echo "<p>Seater: " . $room->seater . "</p>";

                                        // Add allocation and deallocation forms as needed

                                        // You can add allocation and deallocation forms here as in your existing code

                                        echo '<hr>';
                                    }
                                } else {
                                    echo "No rooms found.";
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('floorFilter').addEventListener('change', function () {
            var selectedFloor = document.getElementById('floorFilter').value;
            var filteredRooms = document.getElementById('filteredRooms');
            filteredRooms.innerHTML = 'Loading...';

            // Use AJAX to fetch and display rooms based on the selected floor
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    filteredRooms.innerHTML = xhr.responseText;
                }
            };

            // Send a GET request to your PHP script to retrieve rooms for the selected floor
            xhr.open('GET', 'get_rooms_by_floor.php?floor=' + selectedFloor, true);
            xhr.send();
        });
    </script>


<script>
        function searchRooms() {
            var roomNumber = document.getElementById('roomSearch').value;
            var filteredRooms = document.getElementById('filteredRooms');
            filteredRooms.innerHTML = 'Loading...';

            // Use AJAX to fetch and display rooms based on the entered room number
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    filteredRooms.innerHTML = xhr.responseText;
                }
            };

            // Send a GET request to your PHP script to retrieve rooms for the entered room number
            xhr.open('GET', 'get_rooms_by_room_number.php?room_no=' + roomNumber, true);
            xhr.send();
        }
    </script>
</body>
</html>
