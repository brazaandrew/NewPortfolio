<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "personalportfolio.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "C1sc012345";
$dbname = "personalportfolio";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ Connected successfully to RDS database!";
?>






