<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservili_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("You must be logged in to reserve."); window.location.href = "login.php";</script>';
    exit;
}

// Validate event_id
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    echo '<script>alert("Invalid event ID."); window.location.href = "index.php";</script>';
    exit;
}

$event_id = intval($_GET['event_id']);
$user_id = $_SESSION['user_id'];

// Check if the event exists
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script>alert("Event not found."); window.location.href = "index.php";</script>';
    exit;
}

$event = $result->fetch_assoc();

// Check if the user already reserved this event
$checkStmt = $conn->prepare("SELECT * FROM reservations WHERE event_id = ? AND user_id = ?");
$checkStmt->bind_param("ii", $event_id, $user_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo '<script>alert("You have already reserved this event."); window.location.href = "index.php";</script>';
    exit;
}

// Reserve the event
$insertStmt = $conn->prepare("INSERT INTO reservations (event_id, user_id) VALUES (?, ?)");
$insertStmt->bind_param("ii", $event_id, $user_id);

if ($insertStmt->execute()) {
    echo '<script>alert("Reservation successful! You are now registered for the event: ' . htmlspecialchars($event['name']) . '"); window.location.href = "index.php";</script>';
} else {
    echo '<script>alert("An error occurred while reserving the event. Please try again."); window.location.href = "index.php";</script>';
}

$insertStmt->close();
$stmt->close();
$checkStmt->close();
$conn->close();
?>
