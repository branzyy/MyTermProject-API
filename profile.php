<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CruiseMasters</title>
        <!-- Google Fonts Link For Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
        <link rel="stylesheet" href="css/style.css">
        
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
                <li><a href="home.php">Home</a></li>
                <li><a href="models.php">Models</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
            </ul>
            <form action="logout.php" method="POST" style="display: inline;">
            <button class="logout-btn">Log Out</button>
        </form>
        </nav>
    </header>
<style>
    body {
        text-align: center;
    }
</style>

<script src="js/script.js"></script>

</body>
</html>