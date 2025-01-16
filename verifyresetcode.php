<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
</head>
<body>
    <h1>Verify Code</h1>
    <form action="verifyresetcodeprocess.php" method="POST">
        <label for="verification_code">Enter your verification code:</label><br>
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" />
        <input type="text" name="verification_code" required><br><br>
        <button type="submit">Verify Code</button>
    </form>
</body>
</html>
