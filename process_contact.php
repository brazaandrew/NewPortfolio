<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/s3-config.php'; // $s3, $s3Bucket, s3_put_json()

// Allow only POST
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  echo json_encode(['status'=>'error','message'=>'Invalid request method. Please use POST.']);
  exit;
}

// Accept form-encoded OR application/json
$ctype = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ctype, 'application/json') !== false) {
  $input = json_decode(file_get_contents('php://input'), true) ?: [];
  $name     = trim((string)($input['name'] ?? ''));
  $email    = trim((string)($input['email'] ?? ''));
  $message  = trim((string)($input['message'] ?? ''));
  $honeypot = (string)($input['company'] ?? '');
} else {
  $name     = trim((string)($_POST['name'] ?? ''));
  $email    = trim((string)($_POST['email'] ?? ''));
  $message  = trim((string)($_POST['message'] ?? ''));
  $honeypot = (string)($_POST['company'] ?? '');
}

// Bot check (honeypot)
if ($honeypot !== '') {
  // Pretend success to avoid tipping off bots
  echo json_encode(['status'=>'success','message'=>'Received.']);
  exit;
}

// Validation
if ($name === '' || $email === '' || $message === '') {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'All fields are required.']);
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'Invalid email format.']);
  exit;
}
if (mb_strlen($message) > 5000) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'Message too long.']);
  exit;
}

// Meta
$meta = [
  'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
  'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
  'received_at'=> gmdate('c'),
];

try {
  // Safety: ensure table exists (remove later if you manage schema elsewhere)
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS contact_messages (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      message TEXT NOT NULL,
      ip VARCHAR(45) NULL,
      user_agent VARCHAR(512) NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ");

  // Insert
  $stmt = $pdo->prepare('
    INSERT INTO contact_messages (name, email, message, ip, user_agent)
    VALUES (:name, :email, :message, :ip, :ua)
  ');
  $stmt->execute([
    ':name'    => $name,
    ':email'   => $email,
    ':message' => $message,
    ':ip'      => $meta['ip'],
    ':ua'      => mb_substr($meta['user_agent'], 0, 512),
  ]);

  // Optional S3 backup
  if (!empty($s3Bucket)) {
    $id  = (string)$pdo->lastInsertId();
    $dt  = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    $key = sprintf('contact-messages/%s/%s/%s/%s.json',
      $dt->format('Y'), $dt->format('m'), $dt->format('d'), $id
    );

    s3_put_json($s3, $s3Bucket, $key, [
      'id'       => (int)$id,
      'name'     => $name,
      'email'    => $email,
      'message'  => $message,
      'meta'     => $meta,
    ]);
  }

  echo json_encode(['status'=>'success','message'=>"✅ Thank you, {$name}! Your message has been sent successfully."]);
} catch (Throwable $e) {
  error_log('Contact form error: '.$e->getMessage());
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Sorry, there was an error saving your message. Please try again later.']);
}
