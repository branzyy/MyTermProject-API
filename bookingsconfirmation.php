<?php
session_start();

// Check if booking session exists
if (!isset($_SESSION['booking'])) {
    header("Location: models.php"); // Redirect to models if no booking session
    exit;
}

// Retrieve booking details from session
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
    <p>Your booking has been confirmed with the following details:</p>
    <ul>
        <li><strong>Vehicle:</strong> <?php echo htmlspecialchars($booking['vehiclename']); ?></li>
        <li><strong>Pick-Up Date:</strong> <?php echo htmlspecialchars($booking['pickupdate']); ?></li>
        <li><strong>Return Date:</strong> <?php echo htmlspecialchars($booking['returndate']); ?></li>
        <li><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['bookingID']); ?></li>
    </ul>
    <p>A confirmation email has been sent to your registered email address.</p>
    <a href="models.php" class="btn">Back to Models</a>
</div>
</body>
</html>
