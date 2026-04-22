<?php
$host = "shinkansen.proxy.rlwy.net";
$user = "root";
$pass = "ipoymoDuzsXhfSfetdFBRVCYhMMcxPww"; // from Railway
$db   = "railway";
$port = 17922;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>