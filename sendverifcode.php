<?php
require 'conn.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $verification_code = rand(100000, 999999); // Generate 6-digit code

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Update the verification_code in the database
        $updateStmt = $conn->prepare("UPDATE users SET verification_code = :verification_code WHERE email = :email");
        $updateStmt->bindParam(':verification_code', $verification_code);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();

        // Send email with verification code
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('no-reply@example.com', 'Password Reset');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Code';
            $mail->Body = "Your password reset code is <b>$verification_code</b>";

            $mail->send();
            echo "Verification code has been sent to your email.";
            header("Location: verify_code.php?email=$email");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found in our database.";
    }
}
?>
