<?php
session_start();
require 'connection/index.php'; // Include your database connection

// Debugging: Check if the database connection is established
if (!isset($conn)) {
    die("Database connection failed."); // Will show this message if $conn is not defined
}

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            // Insert the message into the database using $conn
            $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $conn->prepare($sql);  // Use $conn instead of $pdo
            $stmt->execute(['name' => $name, 'email' => $email, 'message' => $message]);

            // Set success message in session and redirect back to the contact page
            $_SESSION['success'] = "Your message has been sent successfully!";
            header("Location: contact.php");
            exit();
        } catch (PDOException $e) {
            // Handle database insertion error
            $_SESSION['error'] = "Failed to send your message. Please try again later.";
            header("Location: contact.php");
            exit();
        }
    } else {
        // Handle empty form fields
        $_SESSION['error'] = "All fields are required.";
        header("Location: contact.php");
        exit();
    }
} else {
    // Redirect if accessed without POST request
    header("Location: contact.php");
    exit();
}
?>
