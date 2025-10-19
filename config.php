<?php
$servername = "portfoliodb.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Law_08199823";
$dbname = "portfoliodb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>
