<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CruiseMasters</title>
        <!-- Google Fonts Link For Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
        <link rel="stylesheet" href="css/style.css">
        
    </head>

<body>
    <header>
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="#" class="logo">
                <img src="images/logo2.png" alt="logo">
                <h2>CruiseMasters</h2>
            </a>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="home.php">Home</a></li>
                <li><a href="models.php">Models</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
            </ul>
            <form action="logout.php" method="POST" style="display: inline;">
            <button class="logout-btn">Log Out</button>
        </form>
        </nav>
    </header>
<style>
    body {
        text-align: center;
    }
</style>
<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change if different
$password = "";      // Change if different
$dbname = "your_database_name";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cars from the database
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Models Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);  /* 3 cars per row */
            gap: 20px;
            justify-items: center;
        }

        .car {
            width: 300px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.3s ease;
            text-align: center;
        }

        .car:hover {
            transform: translateY(-5px);
        }

        .car img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .desc {
            padding: 15px;
        }

        .desc h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .desc p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>

<h1>Cruise Masters Dealership - Car Models</h1>

<div class="gallery">
    <?php
    if ($result->num_rows > 0) {
        while($car = $result->fetch_assoc()) {
            echo '<div class="car">';
            echo '<a href="details.php?car=' . urlencode($car["name"]) . '">';
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
    $conn->close();
    ?>
</div>

</body>
</html>


<script src="js/script.js"></script>

</body>
</html>