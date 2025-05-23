<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'reservili_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and is an admin
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_logged_in || !$is_admin) {
    die("Access denied. Only admins can create events.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $image_url = trim($_POST['image_url']);

    // Validate inputs
    if (empty($name) || empty($description) || empty($date) || empty($image_url)) {
        echo "All fields are required.";
    } else {
        // Insert event into the database
        $stmt = $conn->prepare("INSERT INTO events (name, description, date, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $description, $date, $image_url);

        if ($stmt->execute()) {
            echo "Event created successfully.";
        } else {
            echo "Error creating event: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right, #4CAF50, #66bb6a);
        }
        .form-container form {
            background: #fff;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        .form-container form h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        input, textarea, button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s ease;
        }
        button:hover {
            background-color: #45a049;
            transform: translateY(-3px);
        }
        .back-button {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: #333;
            border-radius: 5px;
            transition: background 0.3s ease-in-out, transform 0.2s ease;
            display: inline-block;
        }
        .back-button:hover {
            background: #4CAF50;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<header>
    <a href="index.php"><h1>Reservili</h1></a>
    <nav>
        <?php if ($is_admin): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
        <?php endif; ?>
        <?php if ($is_logged_in): ?>
            <a href="my_reservations.php">My Reservations</a>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>

<div class="form-container">
    <form action="create_event.php" method="POST">
        <h1>Create New Event</h1>
        <label for="name">Event Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter event name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Enter event description" required></textarea>

        <label for="date">Event Date:</label>
        <input type="date" name="date" id="date" required>

        <label for="image_url">Event Image URL:</label>
        <input type="text" name="image_url" id="image_url" placeholder="Enter image URL" required>

        <button type="submit">Create Event</button>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <a href="admin_dashboard.php" class="back-button">Back to Admin Dashboard</a>
        </div>
    </form>
</div>

</body>
</html>
