<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        
        </style>

</head>
<body>
<header>
    <nav class="navbar">
        <a href="admin.php" class="logo">
        <img src="images/logo2.png" alt="logo"><!-- Replace with actual logo -->
            <h2>Cruise Masters Dealership</h2>
        </a>
        <ul class="links">
            
            <li><a href="forms/loginform.php">Back</a></li>
        </ul>
    </nav>
</header>
    <h1>Forgot Password</h1>
    <form action="sendverifcode.php" method="POST">
        <label for="email">Enter your email address:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Send Verification Code</button>
    </form>
</body>
</html>
