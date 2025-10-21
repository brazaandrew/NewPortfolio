<?php
$servername = "portfoliodbandrew.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username = "Andrew";
$password = "C1sc0123";
$dbname = "portfoliodbandrew";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>



