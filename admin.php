<?php
session_start();
include 'connection/index.php'; // Database connection

// Check if admin is logged in (you may modify this based on your auth system)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");

// Fetch all cars
$cars = $conn->query("SELECT * FROM cars");

// Fetch purchases
$purchases = $conn->query("SELECT * FROM purchases");

// Fetch bookings
$bookings = $conn->query("SELECT * FROM bookings");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['firstname'] ?></td>
                <td><?= $user['lastname'] ?></td>
                <td><?= $user['email'] ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> |
                    <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    
    <h2>Add New Car Model</h2>
    <form action="add_car.php" method="POST">
        <input type="text" name="model_name" placeholder="Car Model" required>
        <input type="text" name="price" placeholder="Price" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <button type="submit">Add Car</button>
    </form>
    
    <h2>Purchases</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>Vehicle</th><th>Purchase Date</th><th>Status</th><th>Actions</th>
        </tr>
        <?php while ($purchase = $purchases->fetch_assoc()): ?>
            <tr>
                <td><?= $purchase['purchaseID'] ?></td>
                <td><?= $purchase['vehiclename'] ?></td>
                <td><?= $purchase['purchasedate'] ?></td>
                <td><?= $purchase['status'] ?></td>
                <td>
                    <a href="update_status.php?id=<?= $purchase['purchaseID'] ?>&type=purchase">Mark as Shipped</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    
    <h2>Bookings</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>Vehicle</th><th>Pickup Date</th><th>Return Date</th><th>Status</th><th>Actions</th>
        </tr>
        <?php while ($booking = $bookings->fetch_assoc()): ?>
            <tr>
                <td><?= $booking['bookingsID'] ?></td>
                <td><?= $booking['vehiclename'] ?></td>
                <td><?= $booking['pickupdate'] ?></td>
                <td><?= $booking['returndate'] ?></td>
                <td><?= $booking['status'] ?></td>
                <td>
                    <a href="update_status.php?id=<?= $booking['bookingsID'] ?>&type=booking">Mark as Picked Up</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
