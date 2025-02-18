<?php
session_start();  // Start the session

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    echo "Logged in as: " . $_SESSION['email'];
} else {
    echo "Not logged in!";
}

include 'connection/index.php'; // Database connection

// Check if 'car_id' parameter is set in the URL
if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

    try {
        // Fetch car details based on carId
        $stmt = $conn->prepare("SELECT * FROM cars WHERE carId = :carId");
        $stmt->bindParam(':carId', $carId, PDO::PARAM_INT);
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
        <button class="btn signup-btn"><a href="models.php">Back To Models</a></button>
        <button class="hamburger-btn" onclick="toggleNavbar()">☰</button>
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
           
        </div>
    </div>

    <!-- Purchase and Book Buttons -->
    <div class="action-buttons">
        <a href="purchase.php?car_id=<?php echo urlencode($car['carId']); ?>" class="btn purchase-btn">Purchase</a>
        <a href="bookings.php?car_id=<?php echo urlencode($car['carId']); ?>" class="btn book-btn">Book</a>
    </div>
</div>

<footer>
    <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

</body>
</html>
