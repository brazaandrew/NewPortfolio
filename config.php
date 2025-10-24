<?php
declare(strict_types=1);

/**
 * RDS settings come from environment:
 *   RDS_HOSTNAME, RDS_DB_NAME, RDS_USERNAME, RDS_PASSWORD
 * Optional:
 *   RDS_CA_BUNDLE (default: /etc/ssl/certs/rds-combined-ca-bundle.pem)
 *   APP_ENV=dev|prod (dev shows errors)
 */

$env = getenv('APP_ENV') ?: 'prod';
if ($env === 'dev') {
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
} else {
  error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', '0');
}

$dbHost = getenv('RDS_HOSTNAME') ?: 'personalportfolio.cpkc6sc8if8f.ap-southeast-2.rds.amazonaws.com';
$dbName = getenv('RDS_DB_NAME') ?: 'portfolioandrew';
$dbUser = getenv('RDS_USERNAME') ?: 'admin';
$dbPass = getenv('RDS_PASSWORD') ?: 'C1sc012345';
$charset = 'utf8mb4';

$dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$charset}";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

// SSL for RDS if CA bundle exists
$ca = getenv('RDS_CA_BUNDLE') ?: '/etc/ssl/certs/rds-combined-ca-bundle.pem';
if (is_readable($ca)) {
  $options[PDO::MYSQL_ATTR_SSL_CA] = $ca;
}

$pdo = new PDO($dsn, $dbUser, $dbPass, $options);

/** Helpers */
function h(?string $s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function is_ajax(): bool {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
         strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}







