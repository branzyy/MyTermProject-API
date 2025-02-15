<?php
session_start(); // Start session for user authentication
include 'connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'PHPMailer/vendor/autoload.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare statement to fetch user details
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Store session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // Generate verification code
                $verif_code = rand(100000, 999999);
                $sql = $conn->prepare("UPDATE users SET verification_code = :verif_code WHERE email = :email");
                $sql->bindParam(':verif_code', $verif_code);
                $sql->bindParam(':email', $email);
                $sql->execute();

                // Send verification email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'brandonnthiwa@gmail.com';
                    $mail->Password = 'utggmrzihminerwi'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->setFrom('exempt@gmail.com', 'CruiseMasters Verification');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Account Verification Code';
                    $mail->Body = 'Your verification code is: <b>' . $verif_code . '</b>';

                    $mail->send();
                    header("Location: verify_code.php");
                    exit();
                } catch (Exception $e) {
                    $error_message = "Email could not be sent. Error: " . $mail->ErrorInfo;
                }
            } else {
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            $error_message = "No account found with that email.";
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        $error_message = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CruiseMasters</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="images/logo2.png" alt="CruiseMasters Logo">
            <h2>CruiseMasters</h2>
        </a>
        <ul class="links">
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <button class="btn signup-btn"><a href="dashboard.php">Home</a></button>
        <button class="hamburger-btn" onclick="toggleNavbar()">☰</button>
    </nav>
</header>

<main>
    <section class="login-section">
        <h1>Login</h1>
        <p>Welcome back! Enter your credentials to access your account.</p>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-container">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
            </div>

            <button type="submit" class="btn login-btn">Login</button>
        </form>

        <p><a href="forgotpassword.php">Forgot Password?</a></p>
        <p class="bottom-link"><a href="signup.php">Don't have an account? Sign up</a></p>
    </section>
</main>

<footer>
    <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

<script>
    function togglePassword() {
        let passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }

    function toggleNavbar() {
        let navLinks = document.querySelector(".links");
        navLinks.classList.toggle("show");
    }
</script>

</body>
</html>
