<?php
session_start();
include 'connection/index.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: loginform.php");
    exit();
}

// Fetch all users
$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all cars
$cars = $conn->query("SELECT * FROM cars")->fetchAll(PDO::FETCH_ASSOC);

// Fetch purchases
$purchases = $conn->query("SELECT * FROM purchases")->fetchAll(PDO::FETCH_ASSOC);

// Fetch bookings
$bookings = $conn->query("SELECT * FROM bookings")->fetchAll(PDO::FETCH_ASSOC);


// Initialize search results
$user = null;
$search_purchases = [];
$search_bookings = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search_email'])) {
    $search_email = trim($_POST['search_email']);

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$search_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Fetch purchases for searched user
        $stmt = $conn->prepare("SELECT * FROM purchases WHERE email = ?");
        $stmt->execute([$search_email]);
        $search_purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch bookings for searched user
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE email = ?");
        $stmt->execute([$search_email]);
        $search_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CruiseMasters</title>
    <style>
       /* General Page Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: black;
    padding: 10px 20px;
}

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: white;
    font-size: 22px;
    font-weight: bold;
}

.logo img {
    height: 50px;
    margin-right: 10px;
}

.links {
    list-style: none;
    display: flex;
}

.links li {
    margin-right: 15px;
}

.links a {
    text-decoration: none;
    color: white;
    font-weight: bold;
}

.links a:hover {
    color: #ffcc00;
}

/* Main Admin Panel Styling */
.admin-container {
    max-width: 1000px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Headings */
h1 {
    text-align: center;
    color: black;
    margin-bottom: 20px;
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background: black;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e6e6e6;
}

/* Buttons and Links */
button, .btn {
    padding: 10px;
    background-color: black;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    margin-top: 10px;
}

button:hover, .btn:hover {
    background-color: #ffcc00;
    color: black;
}

a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* Forms */
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
}

/* Footer */
footer {
    text-align: center;
    padding: 15px;
    background-color: black;
    color: white;
    margin-top: 50px;
}
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="images/logo2.png" alt="logo">
            <h2>CruiseMasters</h2>
        </a>
      
        <button class="btn signup-btn"><a href="adminsearch.php">Search Records</a></button>
        <button class="btn signup-btn"><a href="dashboard.php">Log Out</a></button>
        
    </nav>

    <h1>Admin Dashboard</h1>

    

    <h1>Users</h1>
    <table border="1">
        <tr>
            <th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['firstname']) ?></td>
                <td><?= htmlspecialchars($user['lastname']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <a href="editdetails.php?id=<?= $user['id'] ?>">Edit</a> |
                    <a href="deletedetails.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <h1>Add New Car Model</h1>
    <form action="add_car.php" method="POST">
        <input type="text" name="model_name" placeholder="Car Model" required>
        <input type="text" name="price" placeholder="Price" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <button type="submit">Add Car</button>
    </form>
    
    <h1>Purchases</h1>
    <table border="1">
        <tr>
            <th>ID</th><th>Vehicle</th><th>Purchase Date</th><th>Email</th><th>Status</th><th>Actions</th>
        </tr>
        <?php foreach ($purchases as $purchase): ?>
            <tr>
                <td><?= htmlspecialchars($purchase['purchaseID']) ?></td>
                <td><?= htmlspecialchars($purchase['vehiclename']) ?></td>
                <td><?= htmlspecialchars($purchase['purchasedate']) ?></td>
                <td><?= htmlspecialchars($purchase['email']) ?></td>
                <td><?= htmlspecialchars($purchase['status']) ?></td>
                <td>
                    <a href="updatestatus.php?id=<?= $purchase['purchaseID'] ?>&type=purchase">Mark as Shipped</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <h1>Bookings</h1>
    <table border="1">
        <tr>
            <th>ID</th><th>Vehicle</th><th>Pickup Date</th><th>Return Date</th><th>Email</th><th>Status</th><th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= htmlspecialchars($booking['bookingsID']) ?></td>
                <td><?= htmlspecialchars($booking['vehiclename']) ?></td>
                <td><?= htmlspecialchars($booking['pickupdate']) ?></td>
                <td><?= htmlspecialchars($booking['returndate']) ?></td>
                <td><?= htmlspecialchars($booking['email']) ?></td>
                <td><?= htmlspecialchars($booking['status']) ?></td>
                <td>
                    <a href="updatestatus.php?id=<?= $booking['bookingsID'] ?>&type=booking">Mark as Picked Up</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

