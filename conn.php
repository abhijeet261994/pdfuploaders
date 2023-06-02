<?php
    // Database connection configuration
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'fortest';

    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);
    $link = mysqli_connect($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>