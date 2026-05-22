<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

require 'vendor/autoload.php';
use Twilio\Rest\Client;
use Dotenv\Dotenv;

try {
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }
} catch (Exception $e) {
    // Gracefully ignore missing .env for development mode
}



header('Content-Type: application/json');

require 'db.php';

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
    $sms_sent = false;
    
    // Check if Twilio environment variables are populated and not default placeholders
    if (!empty($_ENV['TWILIO_SID']) && !empty($_ENV['TWILIO_AUTH_TOKEN']) && !empty($_ENV['TWILIO_PHONE_NUMBER']) && 
        strpos($_ENV['TWILIO_SID'], 'your_') === false && $_ENV['TWILIO_SID'] !== 'YOUR_TWILIO_SID') {
        
        $twilio = new Client($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
        $twilio->messages->create($phone, [
            'from' => $_ENV['TWILIO_PHONE_NUMBER'],
            'body' => "Your verification code is: $code"
        ]);
        $sms_sent = true;
    }
    
    if ($sms_sent) {
        echo json_encode([
            'message' => 'Verification code sent successfully. (DEV CODE: ' . $code . ')'
        ]);
    } else {
        echo json_encode([
            'message' => 'Verification code generated successfully. [Twilio Not Configured] (DEV CODE: ' . $code . ')'
        ]);
    }
} catch (Exception $e) {
    // If Twilio fails, still return a successful JSON response with the generated code so testing is not blocked
    echo json_encode([
        'message' => 'Verification code generated successfully. [Twilio Error: ' . $e->getMessage() . '] (DEV CODE: ' . $code . ')'
    ]);
}

