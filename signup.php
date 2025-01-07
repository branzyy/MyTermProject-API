<?php
include 'connection/index.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullnames = $_POST['fullnames'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Generate a verification code
        $verification_code = rand(100000, 999999);

        // Hash the password using the bcrypt algorithm
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (fullnames, email, password, verification_code) VALUES (:fullnames, :email, :password, :verification_code)");
        $stmt->bindParam(':fullnames', $fullnames);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':verification_code', $verification_code);

        // Execute the statement
        $stmt->execute();

        // Send the verification email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'brandonnthiwa@gmail.com';
            $mail->Password   = 'qtzlorsqwjhkctun'; // Use app-specific password for Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('cruisemasters@gmail.com', 'IT Team');
            $mail->addAddress($email, $fullnames);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Here is your Verification Code';
            $mail->Body    = 'Your secret code is: <b>' . $verification_code . '</b>';

            $mail->send();
            echo 'Verification email has been sent.';
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
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
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body.blurred {
            filter: blur(5px);
        }

        .form-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 300px;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-field {
            margin-bottom: 15px;
            position: relative;
        }

        .input-field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .input-field label {
            position: absolute;
            top: 10px;
            left: 10px;
            pointer-events: none;
            font-size: 12px;
            color: #666;
            transition: all 0.3s;
        }

        .input-field input:focus + label,
        .input-field input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 10px;
            color: #333;
        }

        button[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .bottom-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .bottom-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .bottom-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Sign Up</h2>
    <form action="" method="POST">
        <div class="input-field">
            <input type="text" id="fullnames" name="fullnames" placeholder=" " required>
            <label for="fullnames">Full Names</label>
        </div>
        <div class="input-field">
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">Email</label>
        </div>
        <div class="input-field">
            <input type="password" id="password" name="password" placeholder=" " required>
            <label for="password">Password</label>
        </div>
        <button type="submit">Sign Up</button>
        <p class="bottom-link"><a href="login.php">Already have an account? Login</a></p>
    </form>
</div>
<script>
    // Blurs the background when the form is displayed
    document.body.classList.add('blurred');
</script>
</body>
</html>
