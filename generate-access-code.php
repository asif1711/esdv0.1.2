<?php

session_start();

header('Content-Type: application/json');

require 'vendor/autoload.php';

use Twilio\Rest\Client;
use Dotenv\Dotenv;

require 'db.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// REQUIRE LOGIN SESSION
if (!isset($_SESSION['user_id'])) {

    http_response_code(401);

    echo json_encode([
        'status'  => 'error',
        'message' => 'Unauthorized access'
    ]);

    exit();
}


// ONLY POST REQUESTS
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'status'  => 'error',
        'message' => 'Only POST requests allowed'
    ]);

    exit();
}


// GET PHONE
$phone = trim($_POST['phone'] ?? '');


// VALIDATE PHONE
if (!preg_match('/^\+?\d{10,15}$/', $phone)) {

    http_response_code(400);

    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid phone number format'
    ]);

    exit();
}


// CHECK AUTHORIZED USER
$check = $conn->prepare("
    SELECT id
    FROM users
    WHERE phone = ?
");

$check->bind_param("s", $phone);

$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {

    http_response_code(403);

    echo json_encode([
        'status'  => 'error',
        'message' => 'This number is not authorized'
    ]);

    exit();
}


// GENERATE OTP
$code = rand(100000, 999999);

$code_hash = password_hash(
    $code,
    PASSWORD_BCRYPT
);

$expires_at = date(
    'Y-m-d H:i:s',
    strtotime('+5 minutes')
);


// STORE OTP
$stmt = $conn->prepare("
    REPLACE INTO verifications
    (phone, code_hash, expires_at)
    VALUES (?, ?, ?)
");

$stmt->bind_param(
    "sss",
    $phone,
    $code_hash,
    $expires_at
);

$stmt->execute();

$stmt->close();


// SEND SMS
try {

    $twilio = new Client(
        $_ENV['TWILIO_SID'],
        $_ENV['TWILIO_AUTH_TOKEN']
    );

    $twilio->messages->create($phone, [

        'from' => $_ENV['TWILIO_PHONE_NUMBER'],

        'body' => "Your verification code is: $code"

    ]);


    echo json_encode([

        'status'  => 'success',

        'message' =>
            'Access code sent successfully'

    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([

        'status'  => 'error',

        'message' =>
            'Failed to send access code'

    ]);
}


$conn->close();

?>