<?php
include 'connection/index.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'store/PHPMailer/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullnames = $_POST['fullnames'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Generate a verification code
        $verfication_code = rand(100000, 999999);

        // Hash the password using the bcrypt algorithm
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (full names, email, password, verfication_code) VALUES (:full names, :email, :password, :verfication_code)");
        $stmt->bindParam(':full names', $fullnames);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':verfication_code', $verfication_code);

        // Execute the statement
        $stmt->execute();
        echo "Record inserted successfully!";

        // Send the verification email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'brandonnthiwa@gmail.com';                     //SMTP username
            $mail->Password   = 'qtzlorsqwjhkctun';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

            //Recipients
            $mail->setFrom('Cruisemasters@gmail.com', 'IT Team');
            $mail->addAddress($email, $fullnames . ' ' );     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Here is your Verification Code';
            $mail->Body    = 'Your secret code is: <b>' . $verfication_code . '</b>';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect to login page
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css.css">
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sign-Up Form -->
    <div class="form-content signup">
                <button class="close-btn">X</button>
                <h2>Sign Up</h2>
                <form action="home.php" method="POST">
                    <div class="input-field">
                        <input type="text" id="name" name="name" required />
                        <label for="name">Full Name</label>
                    </div>
                    <div class="input-field">
                        <input type="email" id="email" name="email" required />
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" required />
                        <label for="password">Password</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="confirm-password" name="confirm-password" required />
                        <label for="confirm-password">Confirm Password</label>
                    </div>
                    <button type="submit">Sign Up</button>
                    <p class="bottom-link"><a href="login.php">Already have an account? Login</a></p>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
