<?php
session_start();
include('includes/config.php');
if(isset($_POST['login']))
{
$email=$_POST['email'];
$password=$_POST['password'];
$stmt=$mysqli->prepare("SELECT email,password,id FROM userregistration WHERE email=? and password=? ");
$stmt->bind_param('ss',$email,$password);
$stmt->execute();
$stmt -> bind_result($email,$password,$id);
$rs=$stmt->fetch();
$stmt->close();
$_SESSION['id']=$id;
$_SESSION['login']=$email;
$uip=$_SERVER['REMOTE_ADDR'];
$ldate=date('d/m/Y h:i:s', time());
if($rs)
{
$uid=$_SESSION['id'];
$uemail=$_SESSION['login'];
$ip=$_SERVER['REMOTE_ADDR'];
$geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
$addrDetailsArr = unserialize(file_get_contents($geopluginURL));
$city = $addrDetailsArr['geoplugin_city'];
$country = $addrDetailsArr['geoplugin_countryName'];
$log="insert into userLog(userId,userEmail,userIp,city,country) values('$uid','$uemail','$ip','$city','$country')";
$mysqli->query($log);
if($log)
{
header("location:dashboard.php");
}
}
else
{
echo "<script>alert('Invalid Username/Email or password');</script>";
}
}
?>
<?php
/*session_start();
check_login();*/
//code for update email id
if($_POST['update'])
{
    $email=$_POST['emailid'];
    $aid=$_SESSION['id'];
    $udate=date('Y-m-d');
    $query="update admin set email=?,updation_date=? where id=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssi',$email,$udate,$aid);
    $stmt->execute();
    echo"<script>alert('Email id has been successfully updated');</script>";
}
// code for change password
if(isset($_POST['changepwd']))
{
    $op=$_POST['oldpassword'];
    $np=$_POST['newpassword'];
    $ai=$_SESSION['id'];
    $udate=date('Y-m-d');
    $sql="SELECT password FROM admin where password=?";
    $chngpwd = $mysqli->prepare($sql);
    $chngpwd->bind_param('s',$op);
    $chngpwd->execute();
    $chngpwd->store_result();
    $row_cnt=$chngpwd->num_rows;;
    if($row_cnt>0)
    {
        $con="update admin set password=?,updation_date=?  where id=?";
        $chngpwd1 = $mysqli->prepare($con);
        $chngpwd1->bind_param('ssi',$np,$udate,$ai);
        $chngpwd1->execute();
        $_SESSION['msg']="Password Changed Successfully !!";
    }
    else
    {
        $_SESSION['msg']="Old Password not match !!";
    }


}
?>
//------------------------------------------------
/*----------------------------------------------------------------------------------------------*/
<!DOCTYPE html>
<html lang="en">
<head>
  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Student%20Section/css/style.css">
    <link rel="stylesheet" href="footer.css">
    <!---------------------------Js--------------------------------------------->

    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        function valid()
        {
            if(document.registration.password.value!= document.registration.cpassword.value)
            {
                alert("Password and Re-Type Password Field do not match  !!");
                document.registration.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
    <!---------------------------Js--------------------------------------------->
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
        }

        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
    <style>
    #emailLoader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7);
  z-index: 9999;
  display: none;
}

#emailLoader .loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 2s linear infinite;
}
</style>


</head>
<body>
<section id="home">
    <header>
    <!DOCTYPE html>
<html>
<head>
<style>
  .highlighted-text {
    font-variant: small-caps;
    /* background-color: #FFD700; Set the background color to a shade of yellow */
    color: #FFFFFF; /* Set the text color to red */
    padding: 10px; /* Add padding to make it more prominent */
    display: inline-block; /* Make the background color cover the text width */
    font-weight: bold; /* Make the text bold */
  }
  #myImage {
  filter: brightness(0) invert(1) sepia(1) saturate(0) hue-rotate(180deg);
}

</style>
</head>
<body>
  <div class="highlighted-text">
  <img id="myImage" src="image/iiitg.png" style="width: 45px; height: 40px">

  <!-- <p style="font-variant:small-caps;"> <h3>Hostel Management System</h3><a style="width:400px;height: 120px" alt=""></a></p><br> -->
</div>
</body>
</html>

        <!--  <a href="#"><img src="Fury" alt=""></a>-->
        <!-- <a href="#"><img src="image/logo-removebg-preview.png" style="width:400px;height: 80px" alt=""></a> -->

        <ul id="menu">
            <li><a href="#home">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                        <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
                    </svg>
                    Home</a></li>
            <li><a href="#about">About us</a></li>
            <li><a href="#galary">Gallery</a></li>
            <li><a href="#contact">Contact</a></li>
            <!---    <li><a href="#faq">FAQ</a></li>---->
        </ul>
    </header>
    <div class="overlay" id="loader">
        <div class="loader"></div>
        <div class="progress-bar" id="progressBar"></div>
    </div>
  </div>
  
    <!---   <img src="image/r.jpeg" alt="" width="150%">----->
    <div class="container">
    <form action="" class="mt" method="post">
        <img src="image/logo-removebg-preview.png" style="width: 360px; height: 100px">
        <label  class="text-uppercase text-sm">Email</label>
        <input type="text" placeholder="E-mail" name="email" class="" style="border-radius: 20px; text-transform: none;">
        
        <!-- Password input field -->
        <div class="password-wrapper">
            <label  class="text-uppercase text-sm">Passwr</label>
            <input type="password" placeholder="Enter Password" name="password" class="" id="password-input" style="text-transform: none;">
            <i class="fas fa-eye-slash show_hide"></i>
            <!-- Eye icon button to toggle password visibility -->
        </div>

        <button type="submit" name="login" value="login"><span></span>login</button><br>
        <a href="forgot-password.php"><div class="sss">forget password?</div></a><br>
        <a href="registration.php"><div class="sss">create account</div></a>
    </form>
</div>

<script>
    const passwordInput = document.querySelector('input[name="password"]'),
        showHide = document.querySelector(".show_hide");

    showHide.addEventListener("click", () => {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            showHide.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            passwordInput.type = "password";
            showHide.classList.replace("fa-eye", "fa-eye-slash");
        }
    });
</script>

</section>
<!DOCTYPE html>
<html>
<head>
<style>
  #about p {
    font-family: "Comic Sans MS", sans-serif; /* Change the font to Comic Sans MS */
  }
  
  #about p {
    text-align: center; /* Center-align the text */
    opacity: 0.7; /* Reduce the opacity to make it less highlighted */
  }
</style>
</head>
<body>
<section id="about">
    <table>
        <tr>
            <th>
                <img id="borderimg1" src="image/ezgif.com-gif-maker.gif" alt="Computer man" style="width: 450px; height: 350px;">
            </th>
            <th>
                <p>
                    Hostel management system
                    is designed<br> to manage
                    all hostel activities like<br>
                    hostel admissions room, & generates<br> related
                    reports regarding hostel issues.
                </p>
            </th>
        </tr>
    </table>
</section>
</body>
</html>


<section id="galary">
    <!--  <TABLE>
          <TR>
              <TD>--->
    <div class="aaa">
        <marquee width="100%" direction="right" height="100%" scrollamount="15" behavior="alternate">
            <IMG src="image/hs2.jpg"  style="width: 500px; height:400px;">
            <IMG src="image/hs.jpeg"  style="width: 500px; height:400px;">
            <IMG src="image/g3.jpeg"  style="width: 500px; height:400px;">
            <IMG src="image/hs1.jpeg"  style="width: 500px; height:400px;">
            <IMG src="image/hs3.jpg"  style="width: 500px; height:400px;">
        </marquee>
    </div>

</section>
<section id="contact">
    <!-- <div><h1>Contact us</h1></div> -->
    <div class="container2">
        <div class="content2">
            <div class="left-side">
                <div class="address details">
                    <i class="fas fa-map-marker-alt"></i>
                    <div <!DOCTYPE html>
<html>
<head>
<style>
<style>
  .highlighted-text {
    font-variant: small-caps;
    /* background-color: #FFD700; Set the background color to a shade of yellow */
    color: #FFFFFF; /* Set the text color to red */
    padding: 50px; /* Add padding to make it more prominent */
    display: inline-block; /* Make the background color cover the text width */
    font-weight: bold; /* Make the text bold */
  }
</style>
</head>
<body>
  <div class="highlighted-text">Address</div>
</body>
</html>
</div>
                    <div class="text-one">IIIT-Guwahati</div>
                </div>
                <div class="phone details">
                    <i class="fas fa-phone-alt"></i><br>
                    <div <!DOCTYPE html>
<html>
<head>
<style>
<style>
  .highlighted-text {
    font-variant: small-caps;
    /* background-color: #FFD700; Set the background color to a shade of yellow */
    color: #FFFFFF; /* Set the text color to red */
    padding: 50px; /* Add padding to make it more prominent */
    display: inline-block; /* Make the background color cover the text width */
    font-weight: bold; /* Make the text bold */
  }
</style>
</head>
<body>
  <div class="highlighted-text">Phone</div>
</body>
</html></div>
                    <div class="text-one">+91 6305765508</div>
                    <div class="text-two">+91 7739888046</div>
                </div>
                <div class="email details">
                    <i class="fas fa-envelope"></i>
                    <div <!DOCTYPE html>
<html>
<head>
<style>
<style>
  .highlighted-text {
    font-variant: small-caps;
    /* background-color: #FFD700; Set the background color to a shade of yellow */
    color: #FFFFFF; /* Set the text color to red */
    padding: 50px; /* Add padding to make it more prominent */
    display: inline-block; /* Make the background color cover the text width */
    font-weight: bold; /* Make the text bold */
  }
</style>
</head>
<body>
  <div class="highlighted-text">Email</div>
</body>
</html></div>
                    <div class="text-one">s.abhimanyu@iiitg.ac.in</div>
                    <div class="text-two">navin.kishor@iiitg.ac.in</div>
                </div>
            </div>
            <div class="right-side">
                <p>Contact</p><br>
                <br>
                <p>If you have any work from me or any types of quries related<br>to our system, you can send me message from here.<br> It's my pleasure to help you.</p>
                <!DOCTYPE html>
<html>
<head>
  <!-- Include the Email.js library -->
  <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
</head>
<body>
  <script>
    (function() {
      emailjs.init("z5Eh_qaLiSwpmbuwR");
    })();
  </script>

  <form id="emailForm">
    <div class="input-box">
      <input type="text" id="name" placeholder="ENTER YOUR NAME" style="text-transform: none;">
    </div>
    <div class="input-box">
      <input type="email" id="email" placeholder="ENTER YOUR EMAIL" style="text-transform: none;">
    </div>
    <div class="input-box message-box">
      <textarea id="message" placeholder="ENTER YOUR MESSAGE" style="text-transform: none;"></textarea>
    </div>
    <div class="overlay" id="emailLoader">
    <div class="loader"></div>
</div>

    <div class="button">
      <input type="button" value="Send Now" onclick="validateAndSendEmail()">
    </div>
  </form>
  <script>
function validateAndSendEmail() {
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const message = document.getElementById('message').value;

  if (!isValidEmail(email)) {
    alert('Please enter a valid email address.');
    return;
  }

  // Show loading bar before sending the email
  showEmailLoader();

  emailjs.send("service_8e6r2f5", "template_oiijrql", {
    from_name: name,
    from_email: email,
    message: message,
  }).then(
    function(response) {
      // Hide loading bar after successful email sending
      hideEmailLoader();

      alert('Email sent successfully!');
      document.getElementById('emailForm').reset(); // Clear the form
    },
    function(error) {
      // Hide loading bar on error
      hideEmailLoader();

      console.error('Email sending failed:', error);
      alert('Email sending failed. Please try again later.');
    }
  );
}

function showEmailLoader() {
  document.getElementById('emailLoader').style.display = 'block';
}

function hideEmailLoader() {
  document.getElementById('emailLoader').style.display = 'none';
}


    function isValidEmail(email) {
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
      return emailPattern.test(email);
    }
  </script>
</body>
</html>



            </div>
        </div>
    </div>
</section>
<!------------------------------------------------------->
<!--<section id="faq">
</section>---->
<!-------------------foooter------------------------->

<footer>
    <div class="content">
        <div class="top">
            <div class="logo-details">
                <i class="fab fa-slack"></i>
                <span class="logo_name">Icons</span>
            </div>
            <div class="media-icons">
                <a href="https://www.facebook.com/iiitghy/"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/iiitghy?lang=en"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/iiit.guwahati/?hl=en"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/school/indian-institute-of-information-technology/?originalSubdomain=in"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.youtube.com/hashtag/iiitg"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        <div class="link-boxes">
            <ul class="box">
                <li class="link_name">System</li>
                <li><a href="#home">Home</a></li>
                <li><a href="#contact">Contact us</a></li>
                <li><a href="#about">About us</a></li>
                <li><a href="#galary">Gallery</a></li>
            </ul>
            <!-- <ul class="box">
                <li class="link_name">Services</li>
                <li><a href="#">GymKhana</a></li>
                <li><a href="#">Mess Facility</a></li>
                <li><a href="#">Sports</a></li>
                <li><a href="#">Cultural Fest</a></li>
            </ul> -->
            <ul class="box">
                <li class="link_name">Account</li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">My account</a></li>
                <li><a href="#">Prefrences</a></li>
            </ul>
            <!-- <ul class="box">
                <li class="link_name">Department</li>
                <li><a href="#">Mess</a></li>
                <li><a href="#">Sanitation</a></li>
                <li><a href="#">Accounting</a></li>
                <li><a href="#">Management</a></li>
            </ul> -->
            <ul class="box input-box">
                <a href="admin/index.php"><li class="link_name">Admin Login</li></a>
            <!--  <li><input type="text" placeholder="Enter your email"></li>
                <li><input type="password" placeholder="Enter your  Password"></li>
                <li><input type="submit" value="Login"></li>--->
            </ul>
        </div>
    </div>
</footer>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/Chart.min.js"></script>
<script src="js/fileinput.js"></script>
<script src="js/chartData.js"></script>
<script src="js/main.js"></script>
<script>
  // Function to show the loader
  function showLoader() {
    document.getElementById('loader').style.display = 'block';
  }

  // Function to hide the loader
  function hideLoader() {
    document.getElementById('loader').style.display = 'none';
  }

  // Event listener for form submission
  document.querySelector('form').addEventListener('submit', function() {
    showLoader(); // Show loader when the form is submitted

  });
</script>
</body>
</html>

