<?php

session_start();

require '../db.php';

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_email'])
) {

    header('Location: /esd/login.php');
    exit();
}

$email = $_SESSION['user_email'];

$stmt = $conn->prepare("
    SELECT role, trained, dataset_generated
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

    // Model trained
    if (
        (int)$user['dataset_generated'] === 1 &&
        (int)$user['trained'] === 1
    ) {
        header('Location: /esd/face_verify.php');
        exit();
    }

    // Dataset captured, waiting for training
    if (
        (int)$user['dataset_generated'] === 1 &&
        (int)$user['trained'] === 0
    ) {
        header('Location: /esd/face/face_training_pending.php');
        exit();
    }

    // No dataset yet
    header('Location: /esd/face/capture_face.php');
    exit();
}

// ===== EVENT MANAGER =====
if ($role === 'event_manager') {

    header('Location: /esd/events/eventM_Dashboard.php');
    exit();

}


// ===== ADMIN =====
if ($role === 'admin') {

    header('Location: /esd/admin/adminDashboard.php');
    exit();

}


// ===== INVALID ROLE =====
session_destroy();

header('Location: /esd/login.php');
exit();

?>