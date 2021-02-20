<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || ($_SESSION["loggedin"]) !== true) {
        header("location:login.php");
        exit;
    }
    $old_password = $new_password = $confirm_new_password = "";
    $old_password_err = $new_password_err = $confirm_new_password_err = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        include "database.php";
        if(empty(trim($_POST["old_password"]))) {
            $old_password_err = "Please enter your old password";
        } else {
            $old_password = htmlspecialchars(trim($_POST["old_password"]));
            if(empty($old_password_err) && ($old_password != $_SESSION["password"])) {
                $old_password_err = "Wrong old password";
            }
        }
        if(empty(trim($_POST["new_password"]))) {
            $new_password_err = "Please enter your new password";
        } elseif(strlen(trim($_POST["new_password"])) < 6){
            $new_password_err = "Password must have atleast 6 characters.";
        } else  {
            $new_password = htmlspecialchars(trim($_POST["new_password"]));
        }
        if(empty(trim($_POST["confirm_new_password"]))){
            $confirm_new_password_err = "Please confirm the password.";
        } else{
            $confirm_new_password = htmlspecialchars(trim($_POST["confirm_new_password"]));
            if(empty($new_password_err) && ($new_password !== $confirm_new_password)){
                $confirm_new_password_err = "Password did not match.";
            }
        }
        if(empty($old_password_err) && empty($new_password_err) && empty($confirm_new_password_err)) {
            $query = "UPDATE  users SET password = ? WHERE id = ?";
            if($stmt = mysqli_prepare($connect,$query)) {
                mysqli_stmt_bind_param($stmt,"si",$param_password,$param_id);
                $param_password = password_hash($new_password,PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                if(mysqli_stmt_execute($stmt)) {
                    session_destroy();
                    header("location:login.php");
                    exit;
                } else {
                    echo "Ooops,Something went wrong";
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
    <link rel="stylesheet" href="css/changepass.css">
    <title>Change password</title>
</head>
<body>
<div class="rest-div">
        <h3>Reset Password</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <div class = "fields">
            <div class="form-group <?php echo (!empty($old_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="old_password" placeholder = "Old password" class="password" value="<?php echo $old_password; ?>">
                <span class="help-block"><?php echo $old_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="new_password" placeholder = "New password" class="password" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_new_password_err)) ? 'has-error' : ''; ?>">
               <input type="password" name="confirm_new_password" placeholder = "Confirm new password" class="password">
                <span class="help-block"><?php echo $confirm_new_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="welcome.php">Cancel</a>
            </div>
            </div>
        </form>
    </div> 
</body>
</html>