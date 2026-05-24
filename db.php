<?php
// Database configuration
// Using local XAMPP MariaDB/MySQL server as primary fallback since remote Railway went away
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "railway";
$port = 3307;

// Try to connect to local MySQL server
$conn = @new mysqli($host, $user, $pass, "", $port);

if ($conn->connect_error) {
    // If local connection fails, fallback to Railway just in case
    $host_rw = "shinkansen.proxy.rlwy.net";
    $user_rw = "root";
    $pass_rw = "ipoymoDuzsXhfSfetdFBRVCYhMMcxPww";
    $db_rw   = "railway";
    $port_rw = 17922;
    
    $conn = new mysqli($host_rw, $user_rw, $pass_rw, $db_rw, $port_rw);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} else {
    // We connected to local MySQL! Let's ensure the 'railway' database exists
    $conn->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($db);

    // Automatically initialize 'users' table if it doesn't exist
    $conn->query("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `email` VARCHAR(255) NOT NULL UNIQUE,
            `name` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Automatically initialize 'verifications' table if it doesn't exist
    $conn->query("
        CREATE TABLE IF NOT EXISTS `verifications` (
            `phone` VARCHAR(20) PRIMARY KEY,
            `code_hash` VARCHAR(255) NOT NULL,
            `expires_at` DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
}
?>