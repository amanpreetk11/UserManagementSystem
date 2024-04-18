<?php
    $db_host="localhost";
    $db_username="root";
    $db_password="";
    $database="user_management_system";
    $connection=mysqli_connect($db_host,$db_username,$db_password,$database);
    
    if (!$connection)
    {
        die("Could not connect to the database <br/>".mysqli_error($connection));
    }
?>