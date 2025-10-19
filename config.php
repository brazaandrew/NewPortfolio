<?php
$host = "portfoliodb.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username = "admin";  // or your RDS master username
$password = "Law_08199823";  // your RDS password
$dbname = "portfoliodb";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php
$servername = "portfoliodb.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Law_08199823";
$dbname = "portfolio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
