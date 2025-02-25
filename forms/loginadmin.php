<?php
session_start();
include '../connection/index.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/vendor/autoload.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Fetch user details from `users` table and check if they exist in `admins` table
        $stmt = $conn->prepare("
            SELECT u.id, u.email, u.password, a.user_id AS is_admin 
            FROM users u 
            LEFT JOIN admins a ON u.id = a.user_id 
            WHERE u.email = :email 
            LIMIT 1
        ");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Check the fetched data
        if (!$user) {
            $error_message = "Invalid email or password.";
        } elseif (!password_verify($password, $user['password'])) {
            $error_message = "Invalid email or password.";
        } elseif (is_null($user['is_admin'])) { // User is not in the admins table
            $error_message = "You do not have admin access.";
        } else {
            // User is an admin, log them in
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: ../admin.php");
            exit();
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
    <title>Admin Login | CruiseMasters</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="images/logo2.png" alt="CruiseMasters Logo">
            <h2>CruiseMasters</h2>
        </a>
        <!--<ul class="links">
            <li><a href="../about.php">About Us</a></li>
            <li><a href="../contact.php">Contact Us</a></li>
        </ul>-->
        <button class="btn signup-btn"><a href="../dashboard.php">Home</a></button>
        
    </nav>
</header>

<main>
    <section class="login-section">
        <h1>Admin Login</h1>
        <p>Enter your admin credentials to access the dashboard.</p>

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

        <p class="bottom-link"><a href="forgotpassword.php">Forgot Password?</a></p>
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
