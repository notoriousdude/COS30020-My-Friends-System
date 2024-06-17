<?php
    $host = "feenix-mariadb.swin.edu.au";
    $username = "s104180485";
    $password = "220104";
    $dbname = "s104180485_db";

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>