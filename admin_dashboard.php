<?php
session_start();

// Verify admin role
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'reservili_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Categories for filtering
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

// Order results
$sql .= " ORDER BY date ASC";

// Fetch events
$result = $conn->query($sql);

// Get the admin's username
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Event Management</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        header {
            background-color: #1E3A8A;
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
        header nav {
            display: flex;
            align-items: center;
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
            background: linear-gradient(to right, #1E3A8A, #3B82F6);
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
            color: #1E3A8A;
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
            background-color: #1E3A8A;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3B82F6;
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
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1E3A8A;
            color: white;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .btn {
            background-color: #1E3A8A;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #3B82F6;
        }
        .btn-danger {
            background-color: #ef4444;
        }
        .btn-danger:hover {
            background-color: #f87171;
        }
        .footer {
            background-color: #1E3A8A;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        .footer a {
            color: #3B82F6;
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
        <a href="admin_dashboard.php" class="btn">Admin Dashboard</a>
        <a href="my_reservations.php" class="btn">Reservations</a>
        <span style="color: white; margin-right: 20px;">Welcome, <?php echo htmlspecialchars($username); ?></span>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<section class="hero">
    <h1>Admin Dashboard</h1>
    <p>Manage events efficiently and keep everything in check!</p>
    <a href="create_event.php">Create New Event</a>
</section>

<section class="search-bar">
    <form method="GET" action="admin_dashboard.php">
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
    <h2>Manage Events</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                            <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No events found for your criteria.</p>
    <?php endif; ?>
</section>

<footer class="footer">
    <p>&copy; 2024 Reservili. All rights reserved. <br> <a href="contact.php">Contact Us</a></p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
