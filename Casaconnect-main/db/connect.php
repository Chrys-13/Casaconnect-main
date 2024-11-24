<?php
// Database connection file
$servername = "localhost";
$username = "root";       
$password = "";      
$database = "casaconnect"; 

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connection To Database Successful.";
}
?>
