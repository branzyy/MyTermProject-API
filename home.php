<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CruiseMasters</title>
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
                <li><a href="home.php">Home</a></li>
                <li><a href="models.php">Models</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        <form action="logout.php" method="POST" style="display: inline;">
            <button class="btn signup-btn"><a href="dashboard.php">Log Out</a></button>
            <button class="hamburger-btn" onclick="toggleNavbar()">☰</button>
        </form>
        </nav>
    </header>

    <section class="welcome">
        <h1>Welcome Back to CruiseMasters</h1>
        <p>Explore the luxury cars available for rent or sale. Click below to see our models.</p>
        <a href="models.php" class="cta-btn">Explore Our Models</a>
    </section>

    <section class="services">
        <h2>Our Services</h2>
        <p>At CruiseMasters, we offer:</p>
        <ul>
            <li>Luxury car rentals for weddings, events, and business use</li>
            <li>Exotic car sales from the world’s top brands</li>
            <li>Long-term leasing options for individuals and companies</li>
        </ul>
    </section>

    <footer>
        <p>&copy; 2024 CruiseMasters. All Rights Reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
