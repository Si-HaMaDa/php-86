<?php

session_start();

$db_servername = "127.0.0.1";
$db_username   = "valet";
$db_password   = "root";
$db_name       = "php_86";

try {
    // PDO connection
    $conn = new PDO("mysql:host=$db_servername;dbname=$db_name;charset=utf8mb4", $db_username, $db_password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die;
}

const SITE_URL = 'http://php-86.test';

const ADMIN_URL = 'http://php-86.test/admin';
