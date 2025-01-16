<?php
require 'connection/index.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];

    // Check if the verification code matches
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND verification_code = :verification_code");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: resetpassword.php?email=$email");
    } else {
        echo "Invalid verification code.";
    }
}
?>
