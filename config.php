<?php
$host = "personalportfolio.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$dbname = "portfolioandrew";  // ✅ use your actual RDS database name
$username = "admin";
$password = "C1sc012345";

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional success check
    // echo "✅ Connected successfully!";
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>





