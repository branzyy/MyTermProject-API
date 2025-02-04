require 'connection/index.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];

    // Check if the verification code matches and is still valid
    $stmt = $conn->prepare("SELECT verification_expires_at FROM users WHERE email = :email AND verification_code = :verification_code");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $expires_at = strtotime($user['verification_expires_at']);
        $current_time = time();

        if ($current_time > $expires_at) {
            echo "The verification code has expired. Please request a new one.";
        } else {
            header("Location: resetpassword.php?email=$email");
            exit();
        }
    } else {
        echo "Invalid verification code.";
    }
}
