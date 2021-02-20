<?php
   session_start();
   if(isset($_SESSION['loggedin']) && ($_SESSION['loggedin']) === true) {
       header("Location:welcome.php");
       exit;
   }
   $username = $password = "";
   $username_err = $password_err = "";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
       include "database.php";
       if(empty(trim($_POST["username"]))) {
           $username_err = "Please enter your username";
       }else {
           $username = htmlspecialchars(trim($_POST["username"]));
       }
       if(empty(trim($_POST["password"]))) {
           $password_err = "Please enter your password";
       }else {
           $password = htmlspecialchars(trim($_POST["password"]));
       }
       if(empty($username_err) && empty($password_err)) {
           $query = "SELECT id , username , password FROM users WHERE username = ?";
           if($stmt = mysqli_prepare($connect,$query)) {
               mysqli_stmt_bind_param($stmt,'s',$param_username);
               $param_username = $username;
               if(mysqli_stmt_execute($stmt)) {
                   mysqli_stmt_store_result($stmt);
                   if(mysqli_stmt_num_rows($stmt) > 0) {
                       mysqli_stmt_bind_result($stmt,$id,$username,$hashed_password);
                       if(mysqli_stmt_fetch($stmt)) {
                           if(password_verify($password,$hashed_password)) {
                               session_start();
                               $_SESSION["loggedin"] = true;
                               $_SESSION["id"] = $id;
                               $_SESSION["username"] = $username;
                               $_SESSION["password"] = $password;
                               header("location:welcome.php");
                           } else {
                               $password_err = "Invalid password";
                           }
                       }
                   } else {
                       $username_err = "No account exist with this username";
                   }
               } else {
                   echo "Ooop ,Something went wrong";
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
    <title>login</title>
</head>
<body>
    <div class = "login-div">
    <h3>Login</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
         <div class = "fields">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="username" class="username" placeholder = "username" >
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="password" class="password" placeholder = "Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="login" value="Submit">
            </div>
</div>
            <p>Dont have an account? <a href="register.php">Sign Up</a>.</p>
        </form>
    </div>
</body>
</html>