<?php
    include "database.php";
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty(trim($_POST["username"]))) {
            $username_err = "Please enter a username";
        } else {
            $query = "SELECT id FROM users WHERE username = ?";
            if($stmt = mysqli_prepare($connect,$query)) {
                mysqli_stmt_bind_param($stmt,'s',$param_username);
                $param_username = htmlspecialchars(trim($_POST['username']));
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) > 0) {
                        $username_err = "This username is already taken. Try another one.";
                    } else {
                        $username = htmlspecialchars(trim($_POST['username']));
                    }
                } else {
                    echo "Opps ,something went wrong";
                }
                mysqli_stmt_close($stmt);
            } 
        } 
        if(empty(trim($_POST['password']))) {
            $password_err = "Please enter a password";
        } else if (strlen(trim($_POST['password'])) < 6) {
            $password_err = "Password must not be less that six characters";
        } else {
            $password = htmlspecialchars(trim($_POST['password']));
        }
        if(empty(trim($_POST['confirm_password']))) {
            $confirm_password_err = "Please confirm your password";
        } else {
            $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
            if(empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Passwords did not match.";
            }
        }
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $query = "INSERT INTO users (username,password)  VALUES (?,?)";
            if($stmt = mysqli_prepare($connect,$query)) {
                mysqli_stmt_bind_param($stmt,'ss',$param_username,$param_password);
                $param_username = $username;
                $param_password = password_hash($password,PASSWORD_DEFAULT);
                if(mysqli_stmt_execute($stmt)) {
                    header("Location:login.php");
                } else {
                    echo "Something went wrong.Try again";
                }
                mysqli_stmt_close($stmt);
            }
        }
    mysqli_close($connect);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
    <title>Sign up </title>
</head>
<body>
<div class="login-div">
        <h3>Sign Up</h3>
        <form action="register.php" method="post">
         <div class = "fields">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="username" class="username" placeholder = "username" >
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="password" class="password" placeholder = "Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="confirm_password" class="password" placeholder = "Confirm password" >
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="login" value="Submit">
            </div>
</div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div> 
    </div>
</body>
</html>