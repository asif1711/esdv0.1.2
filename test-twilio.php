<?php
require 'vendor/autoload.php';
use Twilio\Rest\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $twilio = new Client($_ENV['TWILIO_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
    $number = $_ENV['TWILIO_PHONE_NUMBER'];
    echo "✅ Twilio credentials are working. Sender: " . $number;
} catch (Exception $e) {
    echo "❌ Twilio auth failed: " . $e->getMessage();
}
