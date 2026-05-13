<?php

session_start();

require '../db.php';

if (!isset($_SESSION['user_email'])) {
    exit();
}

$email = $_SESSION['user_email'];

$stmt = $conn->prepare("
    UPDATE users
    SET dataset_generated = 1
    WHERE email = ?
");

$stmt->bind_param("s", $email);

$stmt->execute();