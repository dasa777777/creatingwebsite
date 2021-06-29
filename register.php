<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $meno = $priezvisko ="";
$username_err = $password_err = $confirm_password_err = $meno_err = $priezvisko_err ="";
 
  
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

	// Validate meno
	if(empty(trim($_POST["meno"]))){
        $meno_err = "Please enter meno.";     
    } else{
        $meno = trim($_POST["meno"]);
    }

	// Validate priezvisko
	if(empty(trim($_POST["priezvisko"]))){
        $priezvisko_err = "Please enter priezvisko.";     
    } else{
        $priezvisko = trim($_POST["priezvisko"]);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)&& empty($meno_err)&& empty($priezvisko_err)){
        
        // Prepare an insert statement
        //este ti tu chybaju uvodzovky! takto si teraz pozries, ze co ti to realne v konzole vrati... proste skopirujes ten string a vidis vysledok
        $sql = "INSERT INTO users (username, password, meno, priezvisko) VALUES (?,?, '".$_POST['meno']."' , '".$_POST['priezvisko']. "')";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            // mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password,$param_meno,$param_priezvisko);
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // toto je miesto, kde sa jednosmerne hashuje heslo, cize vies ho iba zahasovat, ale nie potom uz rozhashovat
		    // $param_meno = $POST[`meno`];
		    // $param_priezvisko = $POST[`priezvisko`];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($meno_err)) ? 'has-error' : ''; ?>">
                <label>Meno</label>
                <input type="text" name="meno" class="form-control" value="<?php echo $meno; ?>">
                <span class="help-block"><?php echo $meno_err; ?></span>
            </div>    
			<div class="form-group <?php echo (!empty($priezvisko_err)) ? 'has-error' : ''; ?>">
                <label>Priezvisko</label>
                <input type="text" name="priezvisko" class="form-control" value="<?php echo $priezvisko; ?>">
                <span class="help-block"><?php echo $priezvisko_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>
