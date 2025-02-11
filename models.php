<?php
session_start(); // Start the session if needed

include 'connection/index.php'; 

// Fetch cars from the database using PDO
try {
    $stmt = $conn->prepare("SELECT * FROM cars ORDER BY carId ASC");
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cars: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Models</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 cars per row */
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .car {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s;
    }

    .car:hover {
        transform: translateY(-5px);
    }

    .car img {
        width: 100%;
        height: 200px; /* Fixed height */
        object-fit: cover; /* Ensures images fit without stretching */
    }

    .desc {
        padding: 15px;
        text-align: center;
    }

    .desc h3 {
        margin: 0 0 10px;
    }

    .desc p {
        margin: 5px 0;
        color: #555;
    }

    @media (max-width: 768px) {
        .gallery {
            grid-template-columns: repeat(2, 1fr); /* 2 cars per row on tablets */
        }
    }

    @media (max-width: 480px) {
        .gallery {
            grid-template-columns: 1fr; /* 1 car per row on phones */
        }
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
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <button class="signup-btn"><a href="home.php">Home</a></button>
    </nav>
</header>

<div class="container">
    <h2>Our Car Models</h2>
    <div class="gallery">
        <?php
        if ($cars && count($cars) > 0) {
            foreach ($cars as $car) {
                echo '<div class="car">';
                echo '<a href="details.php?car_id=' . urlencode($car["carId"]) . '">';
                echo '<img src="images/' . htmlspecialchars($car["image"]) . '" alt="' . htmlspecialchars($car["name"]) . '">';
                echo '<div class="desc">';
                echo '<h3>' . htmlspecialchars($car["name"]) . '</h3>';
                echo '<p>Year: ' . htmlspecialchars($car["year_of_make"]) . '</p>';
                echo '<p>Mileage: ' . htmlspecialchars($car["mileage"]) . '</p>';
                echo '<p>Price: ' . htmlspecialchars($car["price"]) . '</p>';
                echo '</div>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No cars available.</p>";
        }
        ?>
    </div>
</div>

<footer>
    <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

</body>
</html>
