<?php
    // $host = "feenix-mariadb.swin.edu.au";
    // $username = "";
    // $password = "";
    // $dbname = "";

    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
