<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$user_name = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["user_name"]))){
        $username_err = "Please enter username.";
    } else{
        $user_name = trim($_POST["user_name"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_id, user_name, password FROM User WHERE user_name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $user_name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $user_id, $user_name, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["user_name"] = $user_name;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
        <title>Rebbit - Frogs 24/7</title>
    </head>

<style>

.form_rebbit{
    background-color: #4f676c;
    padding: 20px;
    overflow: auto;
    color: #e9f5ef;
    border-radius: 25px;
    max-width: 600px;
}

.submit_btn{
    background-color: darkseagreen;
    border-color: #9eb9ab;
}

.submit_btn:hover{
    background-color: #9eb9ab;
    border-color: darkseagreen;
}

.rebbit_link{
    color: darkseagreen;
}

.rebbit_link:hover{
    color: #86e4aa;
}

</style>

<body style="margin: 0.25em; background-color: #f1f0f0;">    <!-- NAVIGATION BAR-->
    <nav style="background-color: #f1f0f0" class="navbar navbar-expand-lg navbar-light " >
        <a class="navbar-brand" href="index.html">
            <img src="images/graphics/rebbit.png" width="115" height="30" alt="rebbit">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <!-- Get username -->
                    <a class="nav-link" href="#user/username">@froglover97<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">about rebbit</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        explore
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#following">your knots</a>
                        <a class="dropdown-item" href="#popular">popular knots</a>
                    </div>
                </li>
            </ul>
            <!-- Search bar-->
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="hop on" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">search</button>
            </form>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="wrapper form_rebbit">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <div class="col-md-6">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $user_name; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <input type="submit" class="btn btn-primary submit_btn" value="Login">
            </div>
            <p>Don't have an account? <a class="rebbit_link" href="register.php">Sign up</a></p>
        </form>
    </div>    
</body>
</html>