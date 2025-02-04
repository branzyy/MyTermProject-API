<?php
require 'connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php';

function generateVerificationCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $verification_code = generateVerificationCode();

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
           //Server settings
           $mail->SMTPDebug = 0;                      //Enable verbose debug output
           $mail->isSMTP();                                            //Send using SMTP
           $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
           $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
           $mail->Username   = 'brandonnthiwa@gmail.com';                  //SMTP username
           $mail->Password   = 'utggmrzihminerwi';                               //SMTP password
           $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
           $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS
       
           //Recipients
           $mail->setFrom('exempt@gmail.com', 'PASSWORD RESET');
           $mail->addAddress($email, $firstname); 
             //Recipients
                $mail->setFrom('exempt@gmail.com', 'PASSWORD RESET');
                $mail->addAddress($email, $firstname); 

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Code';
            $mail->Body = "Your password reset code is <b>$verification_code</b>";

            $mail->send();
            echo "Verification code has been sent to your email.";
            header("Location: verifyresetcode.php?email=$email");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found in our database.";
    }
}
?>
