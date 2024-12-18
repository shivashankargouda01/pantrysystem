<?php
$host = 'localhost'; // Your database host
$dbname = 'comm_pan'; // Your database name
$username = 'root'; // Your MySQL username
$password = 'Shivu@0425'; // Your MySQL password

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
