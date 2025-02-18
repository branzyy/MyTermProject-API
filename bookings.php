<?php
session_start();
include 'connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginform.php");
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

$errorMessage = ""; // Initialize error message

// Handle booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiclename = $car['name'];
    $pickupdate = $_POST['pickupdate'];
    $returndate = $_POST['returndate'];

    try {
        // Check for overlapping bookings
        $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings 
                                WHERE vehiclename = :vehiclename 
                                AND NOT (returndate <= :pickupdate OR pickupdate >= :returndate)");
        $stmt->bindParam(':vehiclename', $vehiclename);
        $stmt->bindParam(':pickupdate', $pickupdate);
        $stmt->bindParam(':returndate', $returndate);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errorMessage = "This car is already booked for the selected dates. Please choose different dates.";
        } else {
            // Insert booking into the database
            $stmt = $conn->prepare("INSERT INTO bookings (vehiclename, pickupdate, returndate, email) VALUES (:vehiclename, :pickupdate, :returndate, :email)");
            $stmt->bindParam(':vehiclename', $vehiclename);
            $stmt->bindParam(':pickupdate', $pickupdate);
            $stmt->bindParam(':returndate', $returndate);
            $stmt->bindParam(':email', $userEmail);
            $stmt->execute();

            // Get the last inserted booking ID
            $bookingID = $conn->lastInsertId();

            // Create a session for the booking
            $_SESSION['booking'] = [
                'bookingID' => $bookingID,
                'vehiclename' => $vehiclename,
                'pickupdate' => $pickupdate,
                'returndate' => $returndate,
                'email' => $userEmail
            ];

            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'brandonnthiwa@gmail.com';
                $mail->Password = 'utggmrzihminerwi'; // Consider using environment variables instead of hardcoding
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('exempt@gmail.com', 'CruiseMasters Dealership');
                $mail->addAddress($userEmail);  

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Booking Confirmation';
                $mail->Body = 'Thank you for booking <strong>' . htmlspecialchars($vehiclename) . '</strong> from ' . $pickupdate . ' to ' . $returndate . '. We will contact you with further details shortly.';

                $mail->send();

                // Redirect to confirmation page
                header("Location: bookingsconfirmation.php");
                exit;
            } catch (Exception $e) {
                $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } catch (PDOException $e) {
        $errorMessage = "Error processing booking: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Car</title>
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
                <li><a href="home.php">Home</a></li>
                <li><a href="models.php">Models</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <button class="btn signup-btn"><a href="models.php">Back to Models</a></button>
        <button class="hamburger-btn" onclick="toggleNavbar()">☰</button>
    </nav>
</header>
<div class="form-container">
    <h2>Book <?php echo htmlspecialchars($car['name']); ?></h2>
    <form method="post">
        <label for="pickupdate">Pick-up Date:</label>
        <input type="date" id="pickupdate" name="pickupdate" required>

        <label for="returndate">Return Date:</label>
        <input type="date" id="returndate" name="returndate" required>

        
        <button type="submit">Confirm Booking</button>
        
        <!-- Display the error message if booking dates overlap -->
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message" style="color: red; margin-top: 10px;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </form>
    
</div>
</body>
</html>
