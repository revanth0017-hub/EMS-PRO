<?php
$host = 'localhost';
$dbname = 'your_database'; // your database name
$username = 'root'; // MySQL username
$password = ''; // MySQL password (empty by default in XAMPP)

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
