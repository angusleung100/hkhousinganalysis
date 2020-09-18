<?php
    //Create database connection
    $host = "localhost";
    $database = "hkhousinganalysis";
    $username = "root";
    $password = "";


    $connection = mysqli_connect($host, $username, $password, $database) or die("Could not connect to database");


?>