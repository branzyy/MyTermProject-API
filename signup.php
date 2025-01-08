<?php
session_start(); // Start a session to handle user data
include 'connection/index.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
   // $error_message = '';

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        try {
            // Check if the email already exists in the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $error_message = "An account with that email already exists.";
            } else {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user into the database
                $stmt = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";
                $conn->exec($stmt);
            
               /* $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);*/
                //$stmt->execute();

                // Redirect to login page after successful registration
                header('Location: login.php');
                exit();
            }
        } catch (PDOException $e) {
        echo $stmt. $e->getMessage();
            //$error_message = "An error occurred. Please try again later.";
        }
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
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="images/logo2.png" alt="logo">
            <h2>CruiseMasters</h2>
        </a>
        <ul class="links">
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <button class="signup-btn"><a href="dashboard.php">Home</a></button>
    </nav>
</header>

<div class="form-box">
    <div class="form-content signup">
        <h2>Sign Up</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"> <?php echo $error_message; ?> </p>
        <?php endif; ?>
        <form method="POST" id="signup-form">
    <div class="input-field">
        <input type="text" id="firstname" name="firstname" placeholder=" " required />
        <label for="firstname">First Name</label>
    </div>
    <div class="input-field">
        <input type="text" id="lastname" name="lastname" placeholder=" " required />
        <label for="lastname">Last Name</label>
    </div>
    <div class="input-field">
        <input type="email" id="email" name="email" placeholder=" " required />
        <label for="email">Email</label>
    </div>
    <div class="input-field">
        <input type="password" id="password" name="password" placeholder=" " required />
        <label for="password">Password</label>
    </div>
    <div class="input-field">
        <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required />
        <label for="confirm_password">Confirm Password</label>
    </div>
    <button type="submit">Sign Up</button>
    <p class="bottom-link"><a href="login.php">Already have an account? Log in</a></p>
</form>
    </div>
</div>

<footer>
    <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

</body>
</html>
