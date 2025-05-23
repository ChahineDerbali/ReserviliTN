<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'reservili_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Only admins can delete events.");
}

// Get the event ID from the URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate event ID
if ($event_id <= 0) {
    die("Invalid event ID.");
}

// Check if the event exists
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found.");
}

// Delete the event from the database
$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    // Show success message and redirect back to the dashboard
    echo "<script>
            alert('Event successfully deleted.');
            window.location.href = 'admin_dashboard.php';
          </script>";
} else {
    // Show error message if deletion fails
    echo "<script>
            alert('Error deleting event: " . $conn->error . "');
            window.location.href = 'admin_dashboard.php';
          </script>";
}

// Close the connection
$stmt->close();
$conn->close();
?>
