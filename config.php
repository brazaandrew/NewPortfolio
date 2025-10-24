<?php
// config.php — RDS connection (mysqli)
$servername = "personalportfolio.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username   = "admin";
$password   = "C1sc012345";
$dbname     = "personalportfolio";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed.");
}
// IMPORTANT: do NOT echo anything here (keeps JSON/headers clean)
$conn->set_charset('utf8mb4');
