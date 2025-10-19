<?php
// Show all errors temporarily for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration for AWS RDS
define('DB_HOST', 'portfoliodb.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com');
define('DB_USER', 'admin');
define('DB_PASS', 'Law_08199823');
define('DB_NAME', 'portfoliodb');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected successfully to AWS RDS!";
} catch(PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
