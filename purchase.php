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

// Handle purchase submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiclename = $car['name'];
    $purchasedate = date("Y-m-d");

    try {
        // Insert purchase into the database
        $stmt = $conn->prepare("INSERT INTO purchases (vehiclename, purchasedate) VALUES (:vehiclename, :purchasedate)");
        $stmt->bindParam(':vehiclename', $vehiclename);
        $stmt->bindParam(':purchasedate', $purchasedate);
        $stmt->execute();

        // Get the last inserted purchase ID
        $purchaseID = $conn->lastInsertId();

        // Create a session for the purchase
        $_SESSION['purchase'] = [
            'purchaseID' => $purchaseID,
            'vehiclename' => $vehiclename,
            'purchasedate' => $purchasedate
        ];

        // Send confirmation email
        $subject = "Purchase Confirmation - CruiseMasters";
        $message = "Dear Customer,\n\nYou have successfully purchased the $vehiclename on $purchasedate.\n\nThank you for choosing CruiseMasters!\n\nBest regards,\nCruiseMasters Team";
        $headers = "From: no-reply@cruisemasters.com";
        mail($userEmail, $subject, $message, $headers);

        // Redirect to confirmation page
        header("Location: purchase_confirmation.php");
        exit;

    } catch (PDOException $e) {
        die("Error processing purchase: " . $e->getMessage());
    }
}
?>
