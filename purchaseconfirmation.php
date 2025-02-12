<?php
session_start();

// Check if the purchase session is set
if (!isset($_SESSION['purchase'])) {
    die("Purchase not found.");
}

// Retrieve purchase details from the session
$purchase = $_SESSION['purchase'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Purchase Successful!</h2>
    <p>Purchase ID: <strong><?php echo htmlspecialchars($purchase['purchaseID']); ?></strong></p>
    <p>Vehicle Name: <strong><?php echo htmlspecialchars($purchase['vehiclename']); ?></strong></p>
    <p>Purchase Date: <strong><?php echo htmlspecialchars($purchase['purchasedate']); ?></strong></p>
    <p>A confirmation email has been sent to your email address.</p>
    <a href="models.php" class="btn">Back to Models</a>
</div>
</body>
</html>
