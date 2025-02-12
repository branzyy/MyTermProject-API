<?php
session_start();

// Check if booking session exists
if (!isset($_SESSION['booking'])) {
    die("No booking found.");
}

$booking = $_SESSION['booking'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Booking Successful!</h2>
    <p>Booking ID: <strong><?php echo htmlspecialchars($booking['bookingID']); ?></strong></p>
    <p>Vehicle Name: <strong><?php echo htmlspecialchars($booking['vehiclename']); ?></strong></p>
    <p>Pick-up Date: <strong><?php echo htmlspecialchars($booking['pickupdate']); ?></strong></p>
    <p>Return Date: <strong><?php echo htmlspecialchars($booking['returndate']); ?></strong></p>
    <p>A confirmation email has been sent to your email address.</p>
    <a href="models.php" class="btn">Back to Models</a>
</div>
</body>
</html>
