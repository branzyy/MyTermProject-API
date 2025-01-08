<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';
//include 'processes/auth.php';

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usermail=$_POST['email'];
    $username=$_POST['user-name'];
    
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
        $mail->addAddress($usermail, $username); 
        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'PASSWORD RESET';
        $mail->Body    = 'We have received a request to change the password. Kindly input the verification code below
        or tap on the link below to reset your password <br> <br>'.
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        echo 'Message has been sent';
        header("location:verify_code.php");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="home.html" class="logo">
                <img src="images/logo2.png" alt="logo">
                <h2>CruiseMasters</h2>
            </a>
            
        </nav>
    </header>
    <form action="verify_code.php" method="POST">
        <div class="input-field">
            <input type="number" id="email" name="verification_code" placeholder="Enter the code " required />
            <label for="email">Verification Code</label>
        </div>
        
        <button type="verify">Verify</button>
        
    </form>
</body>
</html>