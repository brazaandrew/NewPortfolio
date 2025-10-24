<?php
// Include database configuration
require_once 'config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data safely
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required.'
        ]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid email format.'
        ]);
        exit;
    }

    try {
        // Prepare and execute SQL insert
        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, message)
            VALUES (:name, :email, :message)
        ");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => "✅ Thank you, $name! Your message has been sent successfully."
        ]);

    } catch (PDOException $e) {
        // Log or display database errors
        error_log("Database Error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Sorry, there was an error saving your message. Please try again later.'
        ]);
    }

} else {
    // Handle direct GET access
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Please use POST.'
    ]);
}
?>
