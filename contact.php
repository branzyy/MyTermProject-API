<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CruiseMasters</title>
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
                
                <li><a href="home.php">Home</a></li>
                <li><a href="models.php">Models</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
                <li><a href="profile.php">Profile</a></li>
                
            </ul>
            <form action="logout.php" method="POST" style="display: inline;">
            <button class="btn signup-btn">Log Out</a></button>
            <button class="hamburger-btn" onclick="toggleNavbar()"></button>
        </form>
        </nav>
    </header>

    <main>
    <main>
    <?php
    session_start();
    if (isset($_SESSION['success'])) {
        echo '<div class="message success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']); // Remove message after displaying
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="message error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']); // Remove message after displaying
    }
    ?>
    
    <section class="contact-section">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you! Please fill out the form below and we'll get back to you as soon as possible.</p>

        <form action="sendmessage.php" method="POST" class="contact-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit" class="submit-btn">Send Message</button>
        </form>

        <div class="contact-info">
            <h2>Our Contact Information</h2>
            <p>Email: <a href="mailto:support@cruisemasters.com">support@cruisemasters.com</a></p>
            <p>Phone: +254 654 7890</p>
            <p>Address: Nairobi, Kenya</p>
        </div>
    </section>
</main>

    </main>

    <footer>
        <p>&copy; 2024 CruiseMasters. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
