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

if ($method === 'GET') {
  // Render a tiny test page so you can submit a real POST
  ?>
  <!doctype html>
  <meta charset="utf-8">
  <title>Contact test</title>
  <h2>Contact form (POST tester)</h2>
  <form method="POST" action="">
    <p><label>Name <input name="name" required></label></p>
    <p><label>Email <input type="email" name="email" required></label></p>
    <p><label>Message <textarea name="message" required></textarea></label></p>
    <button type="submit">Send (POST)</button>
  </form>
  <p style="opacity:.7">You reached this page with GET. Submit the form to send a POST.</p>
  <?php
  exit;
}

if ($method !== 'POST') {
  header('Allow: GET, POST');
  json_out(['status' => 'error', 'message' => 'Invalid request method. Please use POST.'], 405);
}

/* -------- Parse body: form-encoded or JSON ---------- */
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

/* ------------------ Validate ------------------------ */
if ($name === '' || $email === '' || $message === '') {
  json_out(['status'=>'error','message'=>'All fields are required.'], 400);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  json_out(['status'=>'error','message'=>'Invalid email format.'], 400);
}

/* ------------------ Save to DB ---------------------- */
try {
  // Ensure table exists (safe first run; remove if you manage schema elsewhere)
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS contact_messages (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      message TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ");

  $stmt = $pdo->prepare("
    INSERT INTO contact_messages (name, email, message)
    VALUES (:name, :email, :message)
  ");
  $stmt->execute([
    ':name'    => $name,
    ':email'   => $email,
    ':message' => $message,
  ]);

  json_out(['status'=>'success','message'=>"✅ Thank you, $name! Your message has been sent successfully."]);
} catch (Throwable $e) {
  error_log('DB error: '.$e->getMessage());
  json_out(['status'=>'error','message'=>'Sorry, there was an error saving your message. Please try again later.'], 500);
}
