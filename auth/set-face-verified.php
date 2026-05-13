<?php

session_start();

header("Content-Type: application/json");

$_SESSION['face_verified'] = true;

echo json_encode([
    "status" => "success"
]);