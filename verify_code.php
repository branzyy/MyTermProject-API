<?php
session_start();
include 'connection/index.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['verification_code'];
    $email = $_SESSION['email'];  // Get the email from session

    try {
        // Fetch verification code for the logged-in user
        $stmt = $conn->prepare("SELECT verification_code FROM users WHERE email = :email AND verification_code = :code");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // If the code matches, set session as verified
            $_SESSION['verified'] = true;
            header("Location: home.php");
            exit();
        } else {
            echo "Invalid verification code. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
            <ul class="links">
                
                
                <li><a href="forms/loginform.php">Back</a></li>
            </ul>
            
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