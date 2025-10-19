<?php
// Database configuration for AWS RDS
define('DB_HOST', 'portfoliodb.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com'); // replace with your RDS endpoint
define('DB_USER', 'admin'); // your RDS master username
define('DB_PASS', 'Law_08199823'); // your RDS master password
define('DB_NAME', 'portfoliodb'); // your RDS database name

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to AWS RDS!";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
