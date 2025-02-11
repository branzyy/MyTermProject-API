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

// Handle booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiclename = $car['name'];
    $pickupdate = $_POST['pickupdate'];
    $returndate = $_POST['returndate'];

    try {
        $stmt = $conn->prepare("INSERT INTO bookings (vehiclename, pickupdate, returndate) VALUES (:vehiclename, :pickupdate, :returndate)");
        $stmt->bindParam(':vehiclename', $vehiclename);
        $stmt->bindParam(':pickupdate', $pickupdate);
        $stmt->bindParam(':returndate', $returndate);
        $stmt->execute();

        echo "<h2>Booking Successful!</h2>";
        echo "<p>You have booked <strong>" . htmlspecialchars($vehiclename) . "</strong> from " . htmlspecialchars($pickupdate) . " to " . htmlspecialchars($returndate) . ".</p>";
        echo '<a href="models.php">Back to Models</a>';
        exit;
    } catch (PDOException $e) {
        die("Error processing booking: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo htmlspecialchars($car['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Book <?php echo htmlspecialchars($car['name']); ?></h2>
    <form method="POST" action="">
        <label for="pickupdate">Pick-Up Date:</label>
        <input type="date" name="pickupdate" required>

        <label for="returndate">Return Date:</label>
        <input type="date" name="returndate" required>

        <button type="submit" class="btn book-btn">Confirm Booking</button>
    </form>
    <a href="details.php?car_id=<?php echo urlencode($carId); ?>" class="btn cancel-btn">Cancel</a>
</div>
</body>
</html>
