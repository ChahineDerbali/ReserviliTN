<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'reservili_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories for the dropdown
$categories = ['Technical Workshop', 'Music Festival', 'Art Exhibition', 'Networking Event', 'Sports Event', 'Charity Event', 'Food Festival'];

// Handle search and filtering
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

// Base SQL query
$sql = "SELECT id, name, description, date, image_url, category FROM events WHERE 1=1";

// Add search filter
if ($search !== '') {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

// Add category filter
if ($category_filter !== '' && in_array($category_filter, $categories)) {
    $sql .= " AND category = '" . $conn->real_escape_string($category_filter) . "'";
}

// Order and limit results
$sql .= " ORDER BY date ASC";

// Execute the query
$result = $conn->query($sql);

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Reservili</title>
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
        .hero {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(to right, #4CAF50, #66bb6a);
            color: white;
            animation: fadeIn 2s;
        }
        .hero h1 {
            font-size: 40px;
        }
        .hero p {
            font-size: 18px;
            margin-top: 10px;
        }
        .hero a {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: white;
            color: #4CAF50;
            border-radius: 25px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .hero a:hover {
            transform: scale(1.1);
            background-color: #f4f4f4;
        }
        .search-bar {
            margin: 30px auto;
            text-align: center;
        }
        .search-bar input, .search-bar select, .search-bar button {
            padding: 10px 15px;
            margin: 5px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-bar button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #45a049;
        }
        .events {
            padding: 40px 20px;
        }
        .events h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 40px;
            color: #333;
        }
        .events-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .event {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            width: 280px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }
        .event:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }
        .event:hover img {
            transform: scale(1.1);
        }
        .event-content {
            padding: 15px;
            text-align: center;
        }
        .event h3 {
            color: #4CAF50;
            margin: 10px 0;
        }
        .event p {
            color: #666;
            margin: 10px 0;
        }
        .event .date {
            font-weight: bold;
            color: #333;
        }
        .reserve-btn {
            display: block;
            margin: 15px auto 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .reserve-btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

<section class="hero">
    <h1>Welcome to Reservili</h1>
    <p>Your one-stop platform for managing and reserving events.</p>
    <a href="signup.php">Get Started</a>
</section>

<section class="search-bar">
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category; ?>" <?php echo $category_filter === $category ? 'selected' : ''; ?>>
                    <?php echo $category; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>
</section>

<section class="events">
    <h2>Featured Events</h2>
    <div class="events-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="event">';
                echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<div class="event-content">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<p class="date"><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>';
                echo '<p class="category"><strong>Category:</strong> ' . htmlspecialchars($row['category']) . '</p>';
                echo '<a class="reserve-btn" href="reserve.php?event_id=' . $row['id'] . '">Reserve Now</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No events found for your criteria.</p>';
        }
        ?>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2024 Reservili. All rights reserved. <a href="about.php">About Us</a><a href="Contact.php"> Contact Us</a></p>
</footer>
</body>
</html>