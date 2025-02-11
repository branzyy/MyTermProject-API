<?php
session_start();
include 'connection/index.php'; // Database connection

// Check if 'car' parameter is set in the URL
if (isset($_GET['car'])) {
    $carName = $_GET['car'];

    try {
        // Fetch car details based on the name
        $carId = $_GET['car_id'];
        $stmt = $conn->prepare("SELECT * FROM cars WHERE id = :id");
        $stmt->bindParam(':id', $carID);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        // If car not found, show an error message
        if (!$car) {
            die("Car details not found.");
        }
    } catch (PDOException $e) {
        die("Error fetching car details: " . $e->getMessage());
    }
} else {
    die("No car specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['name']); ?> - Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="images/logo2.png" alt="logo">
            <h2>CruiseMasters</h2>
        </a>
        <ul class="links">
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <button class="signup-btn"><a href="models.php">Back to Models</a></button>
    </nav>
</header>

<div class="container">
    <h2><?php echo htmlspecialchars($car['name']); ?></h2>
    <div class="car-details">
        <img src="images/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
        <div class="details">
            <p><strong>Year of Make:</strong> <?php echo htmlspecialchars($car['year_of_make']); ?></p>
            <p><strong>Mileage:</strong> <?php echo htmlspecialchars($car['mileage']); ?></p>
            <p><strong>Price:</strong> <?php echo htmlspecialchars($car['price']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

</body>
</html>
