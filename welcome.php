<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || ($_SESSION["loggedin"]) !== true) {
        header("location:login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Home</title>
</head>
<body>
    <div class = "rest-div">
    <div class = "fields">
      <h3>Hi <b><?php echo htmlspecialchars(trim($_SESSION["username"])); ?></b>,Welcome</h3>
    </div>
     <div class = "action">
        <p><a href="reset-password.php" class="input">Reset Your Password</a></p>
        <br>
        <p><a href="logout.php" class="input">Sign Out of Your Account</a><p>
</div>
    </div>
</body>
</html>