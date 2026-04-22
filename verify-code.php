<?php
header('Content-Type: application/json');

// Database connection
require 'db.php';

// Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Only POST requests are allowed']);
    exit();
}

// Get values
$phone = trim($_POST['phone'] ?? '');
$code = trim($_POST['code'] ?? '');

if (empty($phone) || empty($code)) {
    http_response_code(400);
    echo json_encode(['message' => 'Phone and code are required']);
    exit();
}

// Fetch hashed code and expiry
$stmt = $conn->prepare("SELECT code_hash, expires_at FROM verifications WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['message' => 'No verification code found for this number']);
    exit();
}

$stmt->bind_result($code_hash, $expires_at);
$stmt->fetch();

// Check expiry
if (strtotime($expires_at) < time()) {
    echo json_encode(['message' => 'Verification code has expired']);
    exit();
}

// Verify code
if (password_verify($code, $code_hash)) {
    // Optionally delete after success
    $del = $conn->prepare("DELETE FROM verifications WHERE phone = ?");
    $del->bind_param("s", $phone);
    $del->execute();

    echo json_encode(['message' => 'Verification successful']);
} else {
    echo json_encode(['message' => 'Incorrect verification code']);
}

$stmt->close();
$conn->close();
