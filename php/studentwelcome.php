<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
</head>
<body style="background-image: url('../images/back2.png');background-size:cover; ">
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Your <b><?php echo htmlspecialchars($_SESSION["usertype"]); ?></b> Control Center.</h1>
    </div>
    <div style="position: relative;top: -60px;left:75%;width: 25%;">
         <p>

            <a href="reset-password.php"  class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p> 
    </div>
    <div style=" position:relative;left: 50%;width: 50%;top: -5%">

    <div class="btn " >
        <button class="login100-form-btn" class="btn btn-primary" onclick="addAdminBtn()">Make your profile</button>
    </div>
    <div id="addAdminForm"  style="display: none;">
        <?php
// Include config file
        require_once "config.php";

// Define variables and initialize with empty values
        $fullname  = $Email= $userType=$Description=$Address=$contact=$University="";
        $fullname_err = $confirm_password_err =$email_err=$Descri_err=$Address_err= "";

// Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
            if(empty(trim($_POST["fullname"]))){
                $fullname_err = "Please enter your fullname.";
            } else{
        // Prepare a select statement
                $sql = "SELECT id FROM users WHERE username = ?";

                if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
                    $param_fullname = trim($_POST["fullname"]);

            // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        /* store result */
                        mysqli_stmt_store_result($stmt);

                        if(mysqli_stmt_num_rows($stmt) == 1){
                            $fullname_err = "This fullname is already taken.";
                        } else{
                            $fullname = trim($_POST["fullname"]);
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }

        // Close statement
                mysqli_stmt_close($stmt);
            }



    // Validate email
            
            $userType = "Admin";
    // Check input errors before inserting in database
            if(empty($username_err) && empty($password_err) && empty($confirm_password_err)&& empty($email_err)){

        // Prepare an insert statement
                $sql = "INSERT INTO users (username,usertype,email) VALUES (?, ?,?,?)";

                if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password,$param_userType,$param_email);

            // Set parameters
                    $param_fullname = $fullname;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_userType = $userType;
            $param_email = $Email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: adminwelcome.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }

    
    // Close connection
    mysqli_close($link);
}
?>
<div class="limiter" >
    <div class="container-addadmin" >
        <div class="wrapper" style="background-color: white;border-radius: 25px; width: 750px ;" >
            <br><br>
            <h2 style="text-align:center;font-family: Poppins-Bold;font-size:39px ;"><?php echo htmlspecialchars($_SESSION["username"]); ?></h2><br>
            <p style="text-align:center">Please fill this form to make your profile.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"><br>
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Full name 
                    </p>
                    <input type="text" name="fullname" class="form-control" value="<?php echo $fullname; ?>"style="border-radius: 10px; width: 80% ;position: relative;left: 15px">
                    <span class="help-block"><?php echo $fullname_err; ?></span>
                    
                </div>

                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </p>
                    <input type="Email" name="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION["email"]); ?>"style="border-radius: 10px;width: 500px;position: relative;left: 15px">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($Descri_err)) ? 'has-error' : ''; ?>">
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Description
                    </p>
                    <input type="text" name="Description" class="form-control" value="<?php echo $Description; ?>"style="border-radius: 10px;width: 80%;position: relative;left: 15px;height: 50px">
                    <span class="help-block"><?php echo $Descri_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($Address_err)) ? 'has-error' : ''; ?>">
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Address
                    </p>
                    <input type="text" name="Address" class="form-control" value="<?php echo $Address; ?>"style="border-radius: 10px;width: 80%;position: relative;left: 15px ">
                    <span class="help-block"><?php echo $Address_err; ?></span>
                </div>
                <div >
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;contact number
                    </p>
                    <input type="contact" name="contact" class="form-control" value="<?php echo $contact; ?>"style="border-radius: 10px;width: 80%;position: relative;left: 15px ">
                </div>
                <div >
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;University
                    </p>
                    <select name="University" style="position: relative;left: -30px;border-radius: 10px;width: 80%;height: 30px;">
                        <option value="UOM">University of Moratuwa</option>
                        <option value="Uop">University of Peradeniya</option>
                        <option value="UOR">University of Ruhuna</option>
                        <option value="UOC">University of Colombo</option>
                    </select>
                </div>
                <div >
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Department
                    </p>
                    <select name="University" style="position: relative;left: -30px;border-radius: 10px;width: 80%;height: 30px;font-size: 13px">
                        <option value="CSE" >Computer Science and Engineering</option>
                        <option value="BME">Biomedical Engineering</option>
                        <option value="ENTC">Electronic and Telecommunication Engineering</option>
                        <option value="ME">Mechanical Engineering</option>
                        <option value="EE">Electrica; Engineering</option>
                        <option value="MSE">Material Science Engineering</option>
                        <option value="ERE">Earth Resources Engineering</option>
                        <option value="TM">Textile Engineering</option>

                    </select>
                </div>
                <div>
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Interested Fields
                    </p>
                    <input type="checkbox" name="vehicle1" value="1"> Java programing<br>
                    <input type="checkbox" name="vehicle1" value="2"> App developping<br>
                    <input type="checkbox" name="vehicle1" value="3"> Circuit Designing<br>
                    <input type="checkbox" name="vehicle1" value="4"> Digital Electronics<br>
                    <input type="checkbox" name="vehicle1" value="5"> Analog<br>
                </div>
                <div>
                    <p style="text-align: left;">
                     &nbsp;&nbsp;&nbsp;Upload CV
                    </p>
                    <input type="file" name="CV" class="login100-form-btn">
                    
                </div>


                <div class="form-group"style="align">
                    <br><br>
                    <input type="submit"  class="login100-form-btn"   value="Submit" style="width: 45% ; position: relative;left: 5%">
                    <input type="reset" class="login100-form-btn" value="Reset" style="width: 45%;position:relative; top:-50px;left:50%"  >
                </div>
                
            </form>
        </div>
    </div>
</div>
</div>
</div>

</body>

<script >
    function addAdminBtn() {
      var x = document.getElementById("addAdminForm");
      if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>
</html>