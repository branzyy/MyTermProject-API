<?php
session_start();
include 'connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

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
    $mail->Password = 'utggmrzihminerwi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom('exempt@gmail.com', 'CruiseMasters Dealership');
    $mail->addAddress($userEmail);  // Corrected variable

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Booking Confirmation';
    $mail->Body = 'Thank you for booking <strong>' . htmlspecialchars($vehiclename) . '</strong> from ' . $pickupdate .  '</strong> to ' . $returndate . '. We will contact you with further details shortly.';


    $mail->send();

    // Redirect to confirmation page
    header("Location: bookingsconfirmation.php");
    exit;

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


        
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
    <title>Book Car</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Book <?php echo htmlspecialchars($car['name']); ?></h2>
    <form method="post">
        <label for="pickupdate">Pick-up Date:</label>
        <input type="date" id="pickupdate" name="pickupdate" required>

        <label for="returndate">Return Date:</label>
        <input type="date" id="returndate" name="returndate" required>

        <button type="submit">Confirm Booking</button>
    </form>
    <a href="models.php" class="btn">Back to Models</a>
</div>
</body>
</html>
