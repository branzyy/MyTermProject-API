<?php
session_start(); // Start a session to handle user data
include 'connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//$_SESSION['first']=$firstname;

//Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';
//include 'processes/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error_message = '';

    try {
        // Prepare the SQL statement to fetch user data by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if a user with the provided email exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the entered password with the hashed password in the database
            if (password_verify($password, $user['password'])) {
                $verif_code= rand(100000,999999);
                $sql = $conn->prepare("UPDATE users SET verification_code=$verif_code  WHERE email ='$email' ");
        //$stmt->bindParam(':email', $email);
        $stmt->execute();
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'brandonnthiwa@gmail.com';                     //SMTP username
                    $mail->Password   = 'utggmrzihminerwi';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS
                
                    //Recipients
                    $mail->setFrom('exempt@gmail.com', 'PASSWORD RESET');
                    $mail->addAddress($email, $firstname); 
                    // $mail->addAddress('ellen@example.com');               //Name is optional
                    // $mail->addReplyTo('info@example.com', 'Information');
                    // $mail->addCC('cc@example.com');
                    // $mail->addBCC('bcc@example.com');
                
                    //Attachments
                    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'ACCOUNT VERIFICATION';
                    $mail->Body    = 'We have received a request to verify your account. Kindly input the verification code below
                    <br> <br>'.$verif_code;
                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                
                    $mail->send();
                    echo 'Message has been sent';
                    header("location:verif.html");
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                // Redirect to the verification code page
               // header('Location: verif.html');
                exit();
            } else {
                // Password is incorrect
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            // No user found with the provided email
            $error_message = "No account found with that email.";
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        //$error_message = "An error occurred. Please try again later.";
    }
}
?>