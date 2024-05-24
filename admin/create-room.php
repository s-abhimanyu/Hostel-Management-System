<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    $seater = $_POST['seater'];
    $roomNumbers = explode(',', $_POST['rmno']);
    $floorName = trim($_POST['floor']);

    // Fetch the floor ID for the provided floor name
    $floorQuery = "SELECT floor_id FROM floors WHERE floor_name = ?";
    $stmt1 = $mysqli->prepare($floorQuery);
    $stmt1->bind_param('s', $floorName);
    $stmt1->execute();
    $stmt1->store_result();
    $stmt1->bind_result($floorId);
    $stmt1->fetch();
    $row_cnt = $stmt1->num_rows;

    if ($row_cnt == 0 ) {
              // Insert the new floor
              $floorInsertQuery = "INSERT INTO floors (floor_name) VALUES (?)";
              $stmt2 = $mysqli->prepare($floorInsertQuery);
              $stmt2->bind_param('s', $floorName);
              $stmt2->execute();
      
              // Fetch the newly inserted floor's ID
              $floorId = $stmt2->insert_id;
          } else {
              // Fetch the existing floor's ID
              $stmt1->bind_result($floorId);
              $stmt1->fetch();
          } // Flag to check if any room already exists

        foreach ($roomNumbers as $roomno) {
            $roomno = trim($roomno);

            // Check if the room number already exists on the selected floor
            $roomQuery = "SELECT room_no FROM rooms WHERE room_no = ? AND floor_id = ?";
            $stmt2 = $mysqli->prepare($roomQuery);
            $stmt2->bind_param('si', $roomno, $floorId);
            $stmt2->execute();
            $stmt2->store_result();
            $row_cnt = $stmt2->num_rows;

            if ($row_cnt > 0) {
                $roomsExist = true;
            } else {
                // Insert the room for the specified floor
                $roomInsertQuery = "INSERT INTO rooms (seater, room_no, floor_id) VALUES (?, ?, ?)";
                $stmt3 = $mysqli->prepare($roomInsertQuery);
                $stmt3->bind_param('iis', $seater, $roomno, $floorId);
                $stmt3->execute();
            }
        }

        if ($roomsExist) {
            echo "<script>alert('Some rooms already exist on the selected floor');</script>";
        } else {
            echo "<script>alert('Rooms have been added successfully');</script>";
        }
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
	<title>Create Room</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Add a Room</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Add a Room</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
                                            <div class="hr-dashed"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Select Seater</label>
                                                <div class="col-sm-8">
                                                    <select name="seater" class="form-control" required>
                                                        <option value="">Select Seater</option>
                                                        <option value="1">Single Seater</option>
                                                        <option value="2">Two Seater</option>
                                                    </select>
                                                </div>
                                            </div>
                                    <!-- Room No. and Select Floor input fields -->
<div class="form-group">
    <label class="col-sm-2 control-label">Room No.</label>
    <div class="col-sm-8">
        <textarea class="form-control" name="rmno" id="rmno" rows="3" required="required" placeholder="Enter Room Numbers (comma-separated)"></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Select Floor</label>
    <div class="col-sm-8">
        <textarea class="form-control" name="floor" id="floor" rows="3" required="required" placeholder="Enter Floor "></textarea>
    </div>
</div>

                                            </div>
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <input class="btn btn-primary" type="submit" name="submit" value="Create Room">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
</script>
</body>
</html>
