<?php

if (!isset($_GET['email']) || empty($_GET['email'])) {
    header("Location: forgotpassword.php");
    exit();
}
$email = $_GET['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
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
    <h1>Verify Code</h1>
    <form action="verifyresetcodeprocess.php" method="POST">
        <label for="verification_code">Enter your verification code:</label><br>
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" />
        <input type="text" name="verification_code" required><br><br>
        <button type="submit">Verify Code</button>
    </form>
</body>
</html>


