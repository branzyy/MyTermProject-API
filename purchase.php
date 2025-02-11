<?php
session_start();
include 'connection/index.php'; // Database connection

if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

    // Fetch car details
    $stmt = $conn->prepare("SELECT * FROM cars WHERE carId = :carId");
    $stmt->bindParam(':carId', $carId, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Car not found.");
    }
} else {
    die("No car specified.");
}

// Handle purchase submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiclename = $car['name'];
    $purchasedate = date("Y-m-d");

    try {
        $stmt = $conn->prepare("INSERT INTO purchases (vehiclename, purchasedate) VALUES (:vehiclename, :purchasedate)");
        $stmt->bindParam(':vehiclename', $vehiclename);
        $stmt->bindParam(':purchasedate', $purchasedate);
        $stmt->execute();

        echo "<h2>Purchase Successful!</h2>";
        echo "<p>You have purchased <strong>" . htmlspecialchars($vehiclename) . "</strong> on " . htmlspecialchars($purchasedate) . ".</p>";
        echo '<a href="models.php">Back to Models</a>';
        exit;
    } catch (PDOException $e) {
        die("Error processing purchase: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase <?php echo htmlspecialchars($car['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Confirm Purchase</h2>
    <p>Are you sure you want to purchase <strong><?php echo htmlspecialchars($car['name']); ?></strong>?</p>
    <form method="POST" action="">
        <button type="submit" class="btn purchase-btn">Confirm Purchase</button>
    </form>
    <a href="details.php?car_id=<?php echo urlencode($carId); ?>" class="btn cancel-btn">Cancel</a>
</div>
</body>
</html>
