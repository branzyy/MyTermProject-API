<?php
require 'index.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    // Update password in the database
    $stmt = $conn->prepare("UPDATE users SET password = :password, verification_code = NULL WHERE email = :email");
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "Password has been reset successfully.";
        header("Location: login.php");
    } else {
        echo "Failed to reset password. Try again.";
    }
}
?>
