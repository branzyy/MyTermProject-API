<?php
include 'connection/index.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['verification_code'];
    

    try {
        // Prepare the SQL statement to fetch user data by email
        $stmt = $conn->prepare("SELECT verification_code FROM users WHERE verification_code = :code");
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        // Check if a user with the provided email exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $correct_code=$user['verification_code'];
        $ver_code=str_replace(',','', $correct_code);
   if($ver_code){
   header("location:home.php");
   }
   else{
    echo "Invalid code";
   }
        
    }catch (PDOException $e) {
        echo $stmt. $e->getMessage();
        //$error_message = "An error occurred. Please try again later.";
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
            
        </nav>
    </header>
    <form action="verify_code.php" method="POST">
        <div class="input-field">
            <input type="text" id="email" name="verification_code" placeholder="Enter the code " required />
            <label for="email">Verification Code</label>
        </div>
        
        <button type="verify">Verify</button>
        
    </form>
</body>
</html>