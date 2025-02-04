<?php
require 'connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php';

function generateVerificationCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, $length);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT firstname FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $verification_code = generateVerificationCode();
        $expires_at = date("Y-m-d H:i:s", strtotime("+3 minutes")); // Set expiry time

        // Update the verification_code and expiry time in the database
        $updateStmt = $conn->prepare("UPDATE users SET verification_code = :verification_code, verification_expires_at = :expires_at WHERE email = :email");
        $updateStmt->bindParam(':verification_code', $verification_code);
        $updateStmt->bindParam(':expires_at', $expires_at);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();

        // Send email with verification code
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'brandonnthiwa@gmail.com';   // Use environment variable in production
            $mail->Password   = 'utggmrzihminerwi';          // Store this securely
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'CruiseMasters Support');
            $mail->addAddress($email, $user['firstname']); // Use fetched name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Code';
            $mail->Body = "
                <h2>Password Reset Request</h2>
                <p>Hello <b>{$user['firstname']}</b>,</p>
                <p>Your password reset code is: <b style='font-size: 18px;'>$verification_code</b></p>
                <p>This code will expire in <b>3 minutes</b>. Please do not share it with anyone.</p>
                <p>If you did not request this, please ignore this email.</p>
                <br>
                <p>Best regards,</p>
                <p><b>CruiseMasters Support Team</b></p>
            ";

            $mail->send();
            echo "Verification code has been sent to your email.";
            header("Location: verifyresetcode.php?email=$email");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found in our database.";
    }
}
?>
