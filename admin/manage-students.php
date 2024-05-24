<!DOCTYPE html>
<html>
<head>
    <style>
/* Style for the form acting as a button */
.allocate-form {
    display: inline; /* Display the form as inline */
    margin: 0; /* Adjust margin as needed */
}

/* Style for the button appearance */
.allocate-button {
    background-color: #3498db; /* Button background color */
    color: white; /* Text color */
    border: none; /* Remove border */
    padding: 8px 16px; /* Adjust padding */
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    cursor: pointer;
    border-radius: 4px; /* Add some border radius for rounded corners */
}

/* Hover effect */
.allocate-button:hover {
    background-color: #2980b9; /* Change background color on hover */
}

</style>
<style>

/* CSS for positioning the form */
.allocate-form2 {
    position: static;
    top: 85px;
    right: 20px;
    z-index: 9999; /* Ensure it's above other elements */
}

/* CSS styles for the button */
.allocate-button2 {
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
top: 195px; /* Adjust the top position as needed */
right: 50px; /* Adjust the right position to position the dropdown on the right */
z-index: 9999; /* Ensure it's above other elements */
}
</style>
</head>


<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');

function allocation_count($roomId, $mysqli)
{
    $count = 0;

    // Prepare and execute a query to count the number of rooms with the given room ID
    $query = "SELECT COUNT(*) as count FROM allocation WHERE room_id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $roomId); // Assuming room_id is a string; change the type ('s') if it's different
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $count = $row['count'];
            }
            $result->free_result();
        }
        $stmt->close();
    }

    return $count;
}

if (isset($_GET['del'])) {
    $id_to_delete = $_GET['del'];
    // Perform deletion query using prepared statement to avoid SQL injection
	$deallocation_query ="DELETE FROM allocation WHERE regno=?";
	$stmt = $mysqli->prepare($deallocation_query);
    $stmt->bind_param("i", $id_to_delete); // Assuming 'id' is an integer; change the type ('i') if it's different
    $stmt->execute();

    $delete_query = "DELETE FROM registration WHERE regno = ?";
    
    $stmt = $mysqli->prepare($delete_query);
    $stmt->bind_param("i", $id_to_delete); // Assuming 'id' is an integer; change the type ('i') if it's different
    $stmt->execute();
    
    // Check if deletion was successful
    if ($stmt->affected_rows > 0) {
        // Deletion successful, redirect or display success message
        header("Location: manage-students.php"); // Redirect to the same page or any other page
        exit();
    } else {
        // Deletion failed
        echo "Failed to delete record. Please try again.";
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
	<title>Manage Rooms</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<script language="javascript" type="text/javascript">
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
 if(popUpWin)
{
if(!popUpWin.closed) popUpWin.close();
}
popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+510+',height='+430+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}


</script>

</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
			<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12"><br>
						<br><h2 class="page-title">Manage Students</h2>
                        <form class="allocate-form2" method="post" action="auto_allocate_students.php">
        <input class="allocate-button2" type="submit" name="auto_allocate" value="Auto Allocate Students">
    </form>
						<div class="panel panel-default">
							<div class="panel-heading">All Room Details </div>
							<div class="panel-body">
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Sno.</th>
											<th>Student Name</th>
											<th>Reg no</th>
											<th>Contact no </th>
											<th>room no  </th>
											<th>Seater </th>
                                            <th>Course</th>
											<!-- <th>Staying From </th> --> 
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Sno.</th>
											<th>Student Name</th>
											<th>Reg no</th>
											<th>Contact no </th>
											<th>Room no  </th>
											<th>Seater </th> 
                                            <th>Course</th>
											<!-- <th>Staying From </th>  -->
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>
<?php	
$aid = $_SESSION['id'];
$ret = "SELECT registration.id ,registration.firstName, registration.middleName, registration.lastName, registration.regno, registration.contactno, allocation.room_id, 
        registration.stayfrom , registration.emailid, registration.gender, registration.course, registration.egycontactno, registration.guardianName ,registration.guardianRelation ,
        registration.guardianContactno ,registration.corresAddress ,registration.corresCIty,registration.corresPincode ,registration.corresState ,registration.pmntAddress,
        registration.pmntCity ,registration.pmntPincode, registration.pmnatetState, allocation.seater, registration.course
        FROM registration 
        LEFT JOIN allocation ON registration.regno = allocation.regno";

$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
$cnt = 1;

while ($row = $res->fetch_object()) {
    ?>
    <tr>
        <td><?php echo $cnt; ?></td>
        <td><?php echo $row->firstName . " " . $row->middleName . " " . $row->lastName; ?></td>
        <td><?php echo $row->regno; ?></td>
        <td><?php echo $row->contactno; ?></td>
        <td>
            <?php
            if ($row->room_id) {
                echo $row->room_id;
                if ($row->seater == 2) {
                    $roomAllocationCount = allocation_count($row->room_id,$mysqli);
					 
                    if ($roomAllocationCount == 1) {
                        echo ' { Partially Allocated }';
                    }
                }
            } else {
                echo 'Not Allocated';
            }
            ?>
        </td>
        <td><?php echo $row->seater; ?></td>
        <td><?php echo $row->course; ?></td>
        <td>
			<!-- Button for Auto Allocate -->
            <form class="allocate-form" method="post" action="auto_allocate_students_manage.php">
    <input type="hidden" name="regno" value="<?php echo $row->regno; ?>">
    <input class="allocate-button" type="submit" name="auto_allocate" value=" Allocate ">&nbsp;&nbsp; 
</form>

		<a href="javascript:void(0);" onClick="popUpWindow('full-profile.php?id=<?php echo $row->id; ?>');" title="View Full Details"><i class="fa fa-desktop"></i></a>&nbsp;&nbsp; 
		<a href="manage-students.php?del=<?php echo $row->regno; ?>" title="Delete Record" onclick="return confirm('Do you want to delete?');"><i class="fa fa-close"></i></a>
        </td>
    </tr>
    <?php
    $cnt = $cnt + 1;
}
?>

											
										
									</tbody>
								</table>

								
							</div>
						</div>

					
					</div>
				</div>

			

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>
