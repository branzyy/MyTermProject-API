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

// Handle purchase submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiclename = $car['name'];
    $purchasedate = date("Y-m-d");

    try {
        // Insert purchase into the database
        $stmt = $conn->prepare("INSERT INTO purchases (vehiclename, purchasedate, email) VALUES (:vehiclename, :purchasedate, :email)");
        $stmt->bindParam(':vehiclename', $vehiclename);
        $stmt->bindParam(':purchasedate', $purchasedate);
        $stmt->bindParam(':email',$userEmail);
        
        $stmt->execute();

        // Get the last inserted purchase ID
        $purchaseID = $conn->lastInsertId();

        // Store purchase details in session
        $_SESSION['purchase'] = [
            'purchaseID' => $purchaseID,
            'vehiclename' => $vehiclename,
            'purchasedate' => $purchasedate,
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
    $mail->Subject = 'Purchase Confirmation';
    $mail->Body = 'Thank you for purchasing <strong>' . htmlspecialchars($vehiclename) . '</strong> on ' . $purchasedate . '. We will contact you with further details shortly.';

    $mail->send();

    // Redirect to confirmation page
    header("Location: purchaseconfirmation.php");
    exit;

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


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
    <title>Confirm Purchase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Confirm Your Purchase</h2>
    <p>Are you sure you want to purchase <strong><?php echo htmlspecialchars($car['name']); ?></strong>?</p>
    <form method="POST" action="">
        <button type="submit" class="btn">Confirm Purchase</button>
    </form>
    <a href="models.php" class="btn">Cancel</a>
</div>
</body>
</html>
