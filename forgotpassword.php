<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <h1>Forgot Password</h1>
    <form action="send_verification_code.php" method="POST">
        <label for="email">Enter your email address:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Send Verification Code</button>
    </form>
</body>
</html>
