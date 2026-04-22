<?php
session_start();

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if (empty($email) || empty($name) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href = 'signup.php';</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href = 'signup.php';</script>";
        exit();
    }

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email is already registered.'); window.location.href = 'signup.php';</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful. Please log in.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: ' . $conn->error); window.location.href = 'signup.php';</script>";
    }
}

$conn->close();
?>
