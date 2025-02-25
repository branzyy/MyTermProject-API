<?php
session_start();
include 'connection/index.php'; // Database connection

// Fetch purchases
$purchasesQuery = "SELECT * FROM purchases";
$purchasesResult = $conn->query($purchasesQuery);

// Fetch bookings
$bookingsQuery = "SELECT * FROM bookings";
$bookingsResult = $conn->query($bookingsQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'], $_POST['status'], $_POST['type'])) {
        $id = intval($_POST['id']);
        $status = $_POST['status'];
        $type = $_POST['type'];

        $allowed_status = ['Pending', 'Confirmed', 'Cancelled'];
        if (!in_array($status, $allowed_status)) {
            die("Invalid status selected.");
        }

        if ($type === "purchase") {
            $sql = "UPDATE purchases SET status = :status WHERE purchaseID = :id";
        } elseif ($type === "booking") {
            $sql = "UPDATE bookings SET status = :status WHERE bookingsID = :id";
        } else {
            die("Invalid request type.");
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Status updated successfully.</p>";
        } else {
            echo "<p style='color:red;'>Error updating status.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status - CruiseMasters</title>
    <link rel="stylesheet" href="css/adminstyles.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="images/logo2.png" alt="logo">
            <h2>CruiseMasters</h2>
        </a>
        <ul class="links">
            <li><a href="admin.php">Home</a></li>
            
        </ul>
        <button class="btn signup-btn"><a href="dashboard.php">Log Out</a></button>
        
    </nav>
</header>

<main class="profile-container">
    <h2>Update Purchase Status</h2>
    <table>
        <tr>
            <th>Purchase ID</th>
            <th>Vehicle Name</th>
            <th>Email</th>
            <th>Purchase Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $purchasesResult->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['purchaseID']); ?></td>
                <td><?= htmlspecialchars($row['vehiclename']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['purchasedate']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['purchaseID']); ?>">
                        <input type="hidden" name="type" value="purchase">
                        <select name="status">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Update Booking Status</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Vehicle Name</th>
            <th>Email</th>
            <th>Pick-up Date</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $bookingsResult->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['bookingsID']); ?></td>
                <td><?= htmlspecialchars($row['vehiclename']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['pickupdate']); ?></td>
                <td><?= htmlspecialchars($row['returndate']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['bookingsID']); ?>">
                        <input type="hidden" name="type" value="booking">
                        <select name="status">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</main>

<footer>
    <p>&copy; 2024 CruiseMasters. All rights reserved.</p>
</footer>

</body>
</html>
