<?php
session_start();
require 'connection/index.php'; // Database connection
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $name, 'email' => $email, 'message' => $message]);

            $_SESSION['success'] = "Your message has been sent successfully!";
            header("Location: contact.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Failed to send your message. Please try again.";
            header("Location: contact.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "All fields are required.";
        header("Location: contact.php");
        exit();
    }
} else {
    header("Location: contact.php");
    exit();
}
?>
