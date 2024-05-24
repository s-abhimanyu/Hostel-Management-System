<?php
session_start();
include('includes/config.php');
if (isset($_POST['submit'])) {
    $regno = $_POST['regno'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $contactno = $_POST['contact'];
    $emailid = $_POST['email'];
    $password = $_POST['password']; // Include the password field
    $query = "INSERT INTO userRegistration(regNo, firstName, middleName, lastName, gender, contactNo, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssiss', $regno, $fname, $mname, $lname, $gender, $contactno, $emailid, $password); // Bind the password field
    if ($stmt->execute()) {
        // Registration successful
        echo "<script>alert('Student Successfully registered');</script>";
        // Redirect to the main page after successful registration
        header("Location: index.php"); // Replace 'main-page.php' with your main page URL
        exit(); // Ensure that code execution stops after redirection
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <!---<title> Responsive Registration Form | CodingLab </title>--->
    <link rel="stylesheet" href="regis.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        function valid()
        {
            if(document.registration.password.value!= document.registration.cpassword.value)
            {
                alert("Password and Confirm Password Field do not match  !!");
                document.registration.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="container">
    <div class="title">Registration</div>
    <div class="content">
        <form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">

            <div class="user-details">
                <div class="input-box">
                <span class="details">Registration no. / Phone no.</span>
                    <input type="text" name="regno" id="regno"  class="form-control" required="required" placeholder="Enter your Registration No." >
                </div>
                <div class="input-box">
                    <span class="details">First Name</span>
                    <input type="text" name="fname" id="fname"  class="form-control" required="required" placeholder="Enter your First Name" >
                </div>
                <div class="input-box">
                    <span class="details">Middle Name </span>
                    <input  type="text" name="mname" id="mname"  class="form-control" placeholder="Enter your Middle Name">
                </div>
                <div class="input-box">
                    <span class="details">Last Name </span>
                    <input type="text" name="lname" id="lname"  class="form-control" required="required" placeholder="Enter your Last Name">
                </div>
                <div class="input-box">
                    <span class="details">Gender</span>
                    <select name="gender" class="form-control" required="required">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="email" name="email" id="email"  class="form-control" onBlur="checkAvailability()" required="required" placeholder="Enter your Email Id">
                    <span id="user-availability-status" style="font-size:12px;"></span>
                </div>
                <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="text" name="contact" id="contact" class="form-control" required="required" placeholder="Enter your number" onblur="checkMobileNumberAvailability()">
                    <i class="fas fa-eye-slash show_hide"></i>
                    <span id="user-availability-status-mobile" style="font-size:12px;"></span>
                    <!-- <div id="loaderIconMobile" style="display:none;"><img src="loader.gif" /></div> -->
                </div>
                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="password" name="password" id="password" class="form-control" onBlur="PasswordStrength()" onKeyUp="PasswordStrength()" required="required" placeholder="Enter your password" >
                    <i class="fas fa-eye-slash show_hide"></i>
                    <span id="password-strength-indicator" class="indicator"></span>
                    <span class="text">Password strength indicator</span>
                </div>
                <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <i class="fas fa-eye-slash show_hide"></i>
                    <input type="password" name="cpassword" id="cpassword" class="form-control" required="required" placeholder="Confirm your password" >
                </div>
            </div>
            <div class="button">
                <input type="submit" name="submit" Value="Register" class="btn btn-primary">
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
</body>

<script>
    function checkAvailability() {

        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'emailid='+$("#email").val(),
            type: "POST",
            success:function(data){
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function ()
            {
                event.preventDefault();
                alert('error');
            }
        });
    }
</script>

<script>
    function checkMobileNumberAvailability() {
        $("#loaderIconMobile").show();
        const mobileNumber = $("#contact").val();
        const mobileNumberPattern = /^[6789]\d{9}$/; // Indian mobile numbers start with 7, 8, or 9 and are 10 digits long

        if (!mobileNumberPattern.test(mobileNumber)) {
            $("#user-availability-status-mobile").text('Please enter a valid Indian mobile number.');
            $("#loaderIconMobile").hide();
        } else {
            // Mobile number is valid
            $("#user-availability-status-mobile").text('Mobile number is valid.');
            $("#loaderIconMobile").hide();
        }
    }
</script>

<script>
    function PasswordStrength() {
        const alphabet = /[a-zA-Z]/; // Letters a to z and A to Z
        const numbers = /[0-9]/; // Numbers 0 to 9
        const specialCharacters = /[!@#$%^&*?_()-+=~]/; // Special characters

        const input = document.getElementById("password");
        const indicator = document.getElementById("password-strength-indicator");
        const text = document.querySelector(".text");

        const val = input.value;
        indicator.classList.add("active");
        indicator.style.display = "block";

        if (val.match(alphabet) || val.match(numbers) || val.match(specialCharacters)) {
            text.textContent = "Password is Weak";
            indicator.style.backgroundColor = "#FF6333";
        }

        if (val.match(alphabet) && val.match(numbers) && val.length >= 6) {
            text.textContent = "Password is Medium";
            indicator.style.backgroundColor = "#cc8500";
        }

        if (val.match(alphabet) && val.match(numbers) && val.match(specialCharacters) && val.length >= 8) {
            text.textContent = "Password is Strong";
            indicator.style.backgroundColor = "#22C32A";
        }

        if (val === "") {
            indicator.classList.remove("active");
            indicator.style.display = "none";
        }
    }
</script>








