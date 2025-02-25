<?php
session_start();
require 'connection/index.php'; // Ensure this file establishes a PDO connection in $conn

// Check if user is logged in and email is set
if (!isset($_SESSION['email'])) {
    die("Error: User not logged in.");
}

$email = $_SESSION['email'];

try {
    // Fetch user details
    $queryUser = "SELECT firstname, lastname, email FROM users WHERE email = ?";
    $stmtUser = $conn->prepare($queryUser);
    $stmtUser->execute([$email]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "No user found!";
        $user = ['firstname' => '', 'lastname' => '', 'email' => '']; // Avoid null errors
    }

    // Fetch bookings
    $queryBookings = "SELECT vehiclename, pickupdate, returndate FROM bookings WHERE email = ?";
    $stmtBookings = $conn->prepare($queryBookings);
    $stmtBookings->execute([$email]);
    $bookings = $stmtBookings->fetchAll(PDO::FETCH_ASSOC);

    if (!$bookings) {
        $bookings = []; // Ensure an empty array instead of null
    }

    // Fetch purchases
    $queryPurchases = "SELECT vehiclename, purchasedate FROM purchases WHERE email = ?";
    $stmtPurchases = $conn->prepare($queryPurchases);
    $stmtPurchases->execute([$email]);
    $purchases = $stmtPurchases->fetchAll(PDO::FETCH_ASSOC);

    if (!$purchases) {
        $purchases = [];
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - CruiseMasters</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
/* General Styling */
h1, h2 {
    font-family: 'Arial', sans-serif;
    color: #333;
    text-align: center;
}

/* Styling for h1 */
h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Styling for h2 */
h2 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 15px;
    border-bottom: 3px solid #ff9800;
    display: inline-block;
    padding-bottom: 5px;
}

/* Styling for paragraphs */
p {
    font-family: 'Arial', sans-serif;
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
    margin-bottom: 10px;
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
        <ul class="links">
            <li><a href="home.php">Home</a></li>
            <li><a href="models.php">Models</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <button class="btn signup-btn"><a href="Home.php">Log Out</a></button>
        <button class="hamburger-btn" onclick="toggleNavbar()">☰</button>
    </nav>
</header>

<main class="profile-container">
    <section class="box">
        <h2>User Profile</h2>
        <p><strong>First Name:</strong> <?= htmlspecialchars($user['firstname']); ?></p>
        <p><strong>Last Name:</strong> <?= htmlspecialchars($user['lastname']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
    </section>

    <section class="box">
        <h3>Bookings</h3>
        <?php if (count($bookings) > 0): ?>
            <table>
                <tr>
                    <th>Vehicle Name</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                </tr>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['vehiclename']); ?></td>
                        <td><?= htmlspecialchars($booking['pickupdate']); ?></td>
                        <td><?= htmlspecialchars($booking['returndate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </section>

    <section class="box">
        <h3>Purchases</h3>
        <?php if (count($purchases) > 0): ?>
            <table>
                <tr>
                    <th>Vehicle Name</th>
                    <th>Purchase Date</th>
                </tr>
                <?php foreach ($purchases as $purchase): ?>
                    <tr>
                        <td><?= htmlspecialchars($purchase['vehiclename']); ?></td>
                        <td><?= htmlspecialchars($purchase['purchasedate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No purchases found.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; 2024 CruiseMasters. All rights reserved.</p>
</footer>

</body>
</html>
