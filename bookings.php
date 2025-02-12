<?php
session_start();
include 'connection/index.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginform.php"); // Redirect to login page if not logged in
    exit;
}

// Fetch user details
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT email FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$userEmail = $user['email'];

// Fetch car details
if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

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
        // Insert booking into the database
        $stmt = $conn->prepare("INSERT INTO bookings (vehiclename, pickupdate, returndate) VALUES (:vehiclename, :pickupdate, :returndate)");
        $stmt->bindParam(':vehiclename', $vehiclename);
        $stmt->bindParam(':pickupdate', $pickupdate);
        $stmt->bindParam(':returndate', $returndate);
        $stmt->execute();

        // Send confirmation email
        $subject = "Booking Confirmation - CruiseMasters";
        $message = "Dear Customer,\n\nYou have successfully booked the $vehiclename from $pickupdate to $returndate.\n\nThank you for choosing CruiseMasters!\n\nBest regards,\nCruiseMasters Team";
        $headers = "From: no-reply@cruisemasters.com";
        mail($userEmail, $subject, $message, $headers);

        echo "<h2>Booking Successful!</h2>";
        echo "<p>A confirmation email has been sent to <strong>" . htmlspecialchars($userEmail) . "</strong>.</p>";
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
    <p>Logged in as: <strong><?php echo htmlspecialchars($userEmail); ?></strong></p>
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
