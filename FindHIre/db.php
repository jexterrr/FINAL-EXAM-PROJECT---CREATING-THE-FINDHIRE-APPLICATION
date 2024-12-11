<?php
// Database configuration
$host = "localhost"; // Change to your database host if not localhost
$username = "root";  // Replace with your MySQL username
$password = "cicto2024"; // Your database password
$database = "jexterschoolpurposes"; // The database name

try {
    // Create a connection using PDO
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Handle the exception and display an error message
    die("Connection failed: " . $e->getMessage());
}
?>
