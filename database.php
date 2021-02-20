<?php 
    $server_name = "localhost";
    $user =  "root";
    $password = "nakoroma2";
    $database = "crud";

    $connect = mysqli_connect($server_name,$user,$password,$database);

    if(mysqli_connect_error($connect)) {
        echo "error in connection " .mysqli_error();
    }
?>