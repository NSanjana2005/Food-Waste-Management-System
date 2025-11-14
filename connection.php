<?php
// Database configuration
$servername = "localhost";
$username = "root"; 
$password = "";     
$database = "demo"; 

// Create a connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Error: Database connection failed. " . $connection->connect_error);
} 
date_default_timezone_set('Asia/Kolkata'); // Adjust based on your region


$connection->set_charset("utf8"); 
?>
