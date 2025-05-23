<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Update if your MySQL has a password
$database = 'reservili_db'; // Your database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>