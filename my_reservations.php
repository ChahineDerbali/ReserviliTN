<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'reservili_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current user's ID and role (assuming it's stored in session)
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; // Assuming role is stored in session (admin or user)

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $event_id = $_POST['event_id'];

    // Delete the reservation
    $delete_sql = "DELETE FROM reservations WHERE user_id = $user_id AND event_id = $event_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Reservation deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting reservation.');</script>";
    }
}

// Fetch the user's reservations
$sql = "SELECT e.id, e.name, e.date, e.description, e.image_url 
        FROM events e 
        JOIN reservations r ON e.id = r.event_id 
        WHERE r.user_id = $user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }
        header {
            background-color: #4CAF50; /* Green header */
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
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
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50; /* Green header for table */
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .delete-button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover {
            background: #c0392b;
        }
        .back-button {
            text-align: center;
            display: block;
            margin: 20px auto;
            text-decoration: none;
            color: white;
            padding: 12px 25px;
            background: #333;
            border-radius: 25px;
            transition: background 0.3s ease-in-out, transform 0.2s ease;
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
        <?php if ($user_role === 'admin'): ?>
            <a href="admin_dashboard.php" class="btn">Admin Dashboard</a>
        <?php endif; ?>
        <a href="my_reservations.php" class="btn">My Reservations</a>
        <span style="color: white; margin-right: 20px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h1>My Reservations</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="event_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete" class="delete-button">Cancel Reservation</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no reservations at the moment.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
