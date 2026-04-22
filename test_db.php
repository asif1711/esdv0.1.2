<?php
require 'db.php';

$result = $conn->query("SHOW TABLES");

if ($result) {
    echo "✅ DB Connected<br>";
    while ($row = $result->fetch_array()) {
        echo $row[0] . "<br>";
    }
} else {
    echo "❌ Query failed";
}