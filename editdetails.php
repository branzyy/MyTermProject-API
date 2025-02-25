<?php
session_start();
require_once "connection/index.php"; // Ensure config connects using PDO

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No user ID provided.");
}

$id = $_GET['id'];
$user = null;

// Fetch the existing user details (Using PDO)
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];

    $update_sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email WHERE id=:id";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindValue(":firstname", $firstname, PDO::PARAM_STR);
    $stmt->bindValue(":lastname", $lastname, PDO::PARAM_STR);
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION["success"] = "User details updated successfully.";
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating user details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Details</title>
    <link rel="stylesheet" href="adminstyles.css"> <!-- Ensure CSS file is correctly linked -->
<style>
    /* General Page Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: black;
    padding: 10px 20px;
}

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: white;
    font-size: 22px;
    font-weight: bold;
}

.logo img {
    height: 50px;
    margin-right: 10px;
}

.links {
    list-style: none;
    display: flex;
}

.links li {
    margin-right: 15px;
}

.links a {
    text-decoration: none;
    color: white;
    font-weight: bold;
}

.links a:hover {
    color: #ffcc00;
}

/* Main Content Container */
.admin-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

label {
    margin: 10px 0 5px;
    font-weight: bold;
}

input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
}

.submit-btn {
    margin-top: 20px;
    padding: 10px;
    background-color: black;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
}

.submit-btn:hover {
    background-color: #ffcc00;
    color: black;
}

/* Footer */
footer {
    text-align: center;
    padding: 15px;
    background-color: black;
    color: white;
    position: relative;
    margin-top: 50px;
}

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
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container">
    <h2>Edit User Details</h2>

    <form action="" method="POST">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <button type="submit" class="submit-btn">Update</button>
    </form>
</div>

<footer>
    <p>&copy; 2025 Cruise Masters Dealership. All Rights Reserved.</p>
</footer>

</body>
</html>
