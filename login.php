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
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('images/background one.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .form-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-content h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .input-field label {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 14px;
            color: #999;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-field input:focus + label,
        .input-field input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #333;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .bottom-link {
            text-align: center;
            margin-top: 10px;
        }

        .bottom-link a {
            color: #007bff;
            text-decoration: none;
        }

        .bottom-link a:hover {
            text-decoration: underline;
        }

        .form-content {
            text-align: center;
        }
    </style>
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
    <div class="form-content login">
        <h2>Login</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"> <?php echo $error_message; ?> </p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="input-field">
                <input type="email" id="email" name="email" placeholder=" " required />
                <label for="email">Email</label>
            </div>
            <div class="input-field">
                <input type="password" id="password" name="password" placeholder=" " required />
                <label for="password">Password</label>
            </div>
            <button type="submit">Login</button>
            <p class="bottom-link"><a href="signup.php">Don't have an account? Sign up</a></p>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
</footer>

</body>
</html>
