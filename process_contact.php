<?php
declare(strict_types=1);
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/config.php';

function json_out(array $data, int $code = 200): void {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// If opened directly, show a tiny test form so you can POST
if ($method === 'GET') {
  ?>
  <!doctype html><meta charset="utf-8">
  <title>Contact form test</title>
  <h2>Contact (POST tester)</h2>
  <form method="POST" action="">
    <p><label>Name <input name="name" required></label></p>
    <p><label>Email <input type="email" name="email" required></label></p>
    <p><label>Message <textarea name="message" required></textarea></label></p>
    <button type="submit">Send</button>
  </form>
  <p style="opacity:.7">You reached this page with GET. Submit the form to send a POST.</p>
  <?php
  exit;
}

if ($method !== 'POST') {
  header('Allow: GET, POST');
  json_out(['status'=>'error','message'=>'Invalid request method. Please use POST.'], 405);
}

/* ---- Read body (form-encoded or JSON) ---- */
$ctype = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ctype, 'application/json') !== false) {
  $body = json_decode(file_get_contents('php://input'), true) ?: [];
  $name    = trim((string)($body['name'] ?? ''));
  $email   = trim((string)($body['email'] ?? ''));
  $message = trim((string)($body['message'] ?? ''));
} else {
  $name    = trim((string)($_POST['name'] ?? ''));
  $email   = trim((string)($_POST['email'] ?? ''));
  $message = trim((string)($_POST['message'] ?? ''));
}

/* ---- Validate ---- */
if ($name === '' || $email === '' || $message === '') {
  json_out(['status'=>'error','message'=>'All fields are required.'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  json_out(['status'=>'error','message'=>'Invalid email format.'], 400);
}

/* ---- Ensure table exists (first-run safety) ---- */
$conn->query("
  CREATE TABLE IF NOT EXISTS contact_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

/* ---- Insert (mysqli prepared) ---- */
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
if (!$stmt) {
  json_out(['status'=>'error','message'=>'DB prepare failed.'], 500);
}
$stmt->bind_param('sss', $name, $email, $message);

if (!$stmt->execute()) {
  json_out(['status'=>'error','message'=>'DB insert failed.'], 500);
}

json_out(['status'=>'success','message'=>"✅ Thank you, {$name}! Your message has been sent successfully."]);
