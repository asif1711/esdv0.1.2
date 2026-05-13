<?php

session_start();

header('Content-Type: application/json');


// DB CONNECTION
require 'db.php';


// REQUIRE LOGIN
if (!isset($_SESSION['user_id'])) {

    http_response_code(401);

    echo json_encode([
        'status'  => 'error',
        'message' => 'Unauthorized access'
    ]);

    exit();
}


// ONLY POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'status'  => 'error',
        'message' => 'Only POST requests are allowed'
    ]);

    exit();
}


// GET VALUES
$phone = trim($_POST['phone'] ?? '');

$code = trim($_POST['code'] ?? '');


// VALIDATE INPUTS
if (empty($phone) || empty($code)) {

    http_response_code(400);

    echo json_encode([
        'status'  => 'error',
        'message' => 'Phone and code are required'
    ]);

    exit();
}


// FETCH HASHED OTP
$stmt = $conn->prepare("
    SELECT code_hash, expires_at
    FROM verifications
    WHERE phone = ?
");

$stmt->bind_param("s", $phone);

$stmt->execute();

$stmt->store_result();


// NO OTP FOUND
if ($stmt->num_rows === 0) {

    echo json_encode([
        'status'  => 'error',
        'message' =>
            'No verification code found for this number'
    ]);

    exit();
}


// GET DB VALUES
$stmt->bind_result(
    $code_hash,
    $expires_at
);

$stmt->fetch();


// CHECK EXPIRY
if (strtotime($expires_at) < time()) {

    echo json_encode([
        'status'  => 'error',
        'message' =>
            'Verification code has expired'
    ]);

    exit();
}


// VERIFY OTP
if (password_verify($code, $code_hash)) {

    // MFA SESSION FLAG
    $_SESSION['otp_verified'] = true;


    // DELETE USED OTP
    $del = $conn->prepare("
        DELETE FROM verifications
        WHERE phone = ?
    ");

    $del->bind_param("s", $phone);

    $del->execute();


    echo json_encode([

        'status'  => 'success',

        'message' =>
            'Verification successful'

    ]);

} else {

    echo json_encode([

        'status'  => 'error',

        'message' =>
            'Incorrect verification code'

    ]);
}


// CLEANUP
$stmt->close();

$conn->close();

?>