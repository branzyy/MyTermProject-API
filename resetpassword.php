<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <h1>Reset Password</h1>
    <form action="reset_password_process.php" method="POST">
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" />
        <label for="password">Enter new password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
