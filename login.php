<?php
session_start(); // Start a session to handle user data
include 'connection/index.php'; // Database connection

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

                // Redirect to the verification code page
                header('Location: verf_code.php');
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
        $error_message = "An error occurred. Please try again later.";
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
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
<header>
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="#" class="logo">
                <img src="images/logo2.png" alt="logo">
                <h2>CruiseMasters</h2>
            </a>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            
            <button class="signup-btn"><a href = "dashboard.php">Home</a></button>

        </nav>
    </header>
<div class="form-box">
            <!-- Login Form -->
            <div class="form-content login">
                <button class="close-btn">X</button>
                <h2>Login</h2>
                <form action="home.php" method="POST">
                    <div class="input-field">
                        <input type="text" id="username" name="username" required />
                        <label for="username">Username or Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" required />
                        <label for="password">Password</label>
                    </div>
                    <button type="submit">Login</button>
                    <p class="bottom-link"><a href="signup.php">Don't have an account? Sign up</a></p>
                </form>
            </div>
           
           
<footer>
<p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

    <script src="js/script.js"></script>

</body>
</html>

