<?php
require 'vendor/autoload.php';
use Twilio\Rest\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


header('Content-Type: application/json');

$host = 'localhost';
$db = 'vips';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Only POST requests allowed']);
    exit();
}

$phone = trim($_POST['phone'] ?? '');
if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid phone number format']);
    exit();
}

$code = rand(100000, 999999);
$code_hash = password_hash($code, PASSWORD_BCRYPT);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

$stmt = $conn->prepare("REPLACE INTO verifications (phone, code_hash, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $phone, $code_hash, $expires_at);
$stmt->execute();
$stmt->close();

try {
    $twilio = new Client($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
    $twilio->messages->create($phone, [
        'from' => $_ENV['TWILIO_PHONE_NUMBER'],
        'body' => "Your verification code is: $code"
    ]);
    echo json_encode(['message' => 'Verification code sent successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to send SMS: ' . $e->getMessage()]);
}
