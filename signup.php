<?php
session_start();
include 'connection/index.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $error_message = "An account with that email already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();

                $_SESSION['first'] = $firstname;
                header('Location: loginform.php');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            $error_message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CruiseMasters</title>
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
                <li><a href="home.php">Home</a></li>
                <li><a href="models.php">Models</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <button class="btn signup-btn"><a href="loginform.php">Log In</a></button>
            <button class="hamburger-btn" onclick="toggleNavbar()">☰</button>
        </nav>
    </header>

    <main>
        <section class="signup-section">
            <h1>Sign Up</h1>
            <p>Join us today by creating an account. Fill out the form below to get started.</p>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" class="form-container">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn signup-btn">Sign Up</button>
            </form>

            <p class="bottom-link"><a href="loginform.php">Already have an account? Log in</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 CruiseMasters. All rights reserved.</p>
    </footer>

    <script>
        function toggleNavbar() {
            let navLinks = document.querySelector(".links");
            navLinks.classList.toggle("show");
        }
    </script>

</body>
</html>
