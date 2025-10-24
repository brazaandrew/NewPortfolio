<?php
$servername = "personalportfolio.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "C1sc012345";
$dbname = "personalportfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to RDS!";
?>





