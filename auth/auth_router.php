<?php

session_start();

require '../db.php';

if (!isset($_SESSION['user_id'])) {

    header('Location: /esd/login.php');
    exit();

}

$email = $_SESSION['user_email'];

$stmt = $conn->prepare("
    SELECT role, trained
    FROM users
    WHERE email = ?
");

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    session_destroy();

    header('Location: /esd/login.php');
    exit();

}

$user = $result->fetch_assoc();

$role = $user['role'];


// ===== STANDARD USER =====
if ($role === 'user') {

    // User already has dataset
    if ((int)$user['face_dataset_exists'] === 1) {

        header('Location: /esd/face_verify.php');
        exit();

    }

    // First-time face registration
    header('Location: /esd/face/capture_face.php');
    exit();

}


// ===== EVENT MANAGER =====
if ($role === 'event_manager') {

    header('Location: /esd/events/event_request.php');
    exit();

}


// ===== ADMIN =====
if ($role === 'admin') {

    header('Location: /esd/admin/admin_sms_verify.php');
    exit();

}


// ===== INVALID ROLE =====
session_destroy();

header('Location: /esd/login.php');
exit();

?>