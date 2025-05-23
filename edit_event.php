<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'reservili_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in as an admin
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Redirect to login page if not an admin
if (!$is_logged_in || !$is_admin) {
    header('Location: login.php');
    exit();
}

// Fetch event ID from URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
} else {
    die("Event ID is required.");
}

// Fetch event details from the database
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found.");
}

// Handle form submission for updating event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $image_url = trim($_POST['image_url']);
    $category = trim($_POST['category']);

    // Basic validation
    if (empty($name) || empty($description) || empty($date) || empty($image_url) || empty($category)) {
        $error = "All fields are required.";
    } else {
        // Update event in the database
        $sql = "UPDATE events SET name = ?, description = ?, date = ?, image_url = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $name, $description, $date, $image_url, $category, $event_id);

        if ($stmt->execute()) {
            $success = "Event updated successfully!";
            // Refresh the event details after updating
            $event['name'] = $name;
            $event['description'] = $description;
            $event['date'] = $date;
            $event['image_url'] = $image_url;
            $event['category'] = $category;
        } else {
            $error = "Failed to update event. Please try again.";
        }
    }
}

// Categories for the dropdown
$categories = ['Technical Workshop', 'Music Festival', 'Art Exhibition', 'Networking Event', 'Sports Event', 'Charity Event', 'Food Festival'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | Reservili</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 600;
        }
        header nav a:hover {
            color: #f4f4f4;
        }
        .form-container {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container input,
        .form-container textarea,
        .form-container select,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        .success {
            color: green;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php"><h1>Reservili</h1></a>
    <nav>
        <a href="admin_dashboard.php">Admin Dashboard</a>
        <a href="my_reservations.php">My Reservations</a>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="form-container">
    <h2>Edit Event</h2>
    <?php if (isset($error)) { echo '<p class="error">' . $error . '</p>'; } ?>
    <?php if (isset($success)) { echo '<p class="success">' . $success . '</p>'; } ?>

    <form method="POST" action="edit_event.php?id=<?php echo $event_id; ?>">
        <label for="name">Event Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>

        <label for="description">Event Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>

        <label for="date">Event Date</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>

        <label for="image_url">Event Image URL</label>
        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($event['image_url']); ?>" required>

        <label for="category">Event Category</label>
        <select id="category" name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category; ?>" <?php echo $event['category'] === $category ? 'selected' : ''; ?>>
                    <?php echo $category; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Event</button>
    </form>
</div>

</body>
</html>
