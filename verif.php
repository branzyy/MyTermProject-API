<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';
//include 'processes/auth.php';

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usermail=$_POST['email'];
    $username=$_POST['user-name'];
    
    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'brandonnthiwa@gmail.com';                     //SMTP username
        $mail->Password   = 'utggmrzihminerwi';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS
    
        //Recipients
        $mail->setFrom('exempt@gmail.com', 'PASSWORD RESET');
        $mail->addAddress($usermail, $username); 
        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'PASSWORD RESET';
        $mail->Body    = 'We have received a request to change the password. Kindly input the verification code below
        or tap on the link below to reset your password <br> <br>'.
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        echo 'Message has been sent';
        header("location:verify_code.php");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>

<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['temp_user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $user_email = $_SESSION['temp_user']['email'];

    try {
        // Prepare the SQL query to fetch the user's OTP and expiry details
        $sql = "SELECT id, otp, otp_expiry FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Check if the entered OTP matches the stored OTP
            if ($data['otp'] === $user_otp) {
                // Check if the OTP is not expired
                $otp_expiry = strtotime($data['otp_expiry']);
                if ($otp_expiry >= time()) {
                    // OTP is valid and not expired
                    $_SESSION['user_id'] = $data['id'];
                    unset($_SESSION['temp_user']);  // Clear temporary session data
                    
                    // Clear OTP from the database to prevent reuse
                    $clearOtpSql = "UPDATE users verification_code SET  = NULL, otp_expiry = NULL WHERE email = :email";
                    $clearStmt = $conn->prepare($clearOtpSql);
                    $clearStmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                    $clearStmt->execute();

                    header("Location: home.php");  // Redirect to the dashboard
                    exit();
                } else {
                    // OTP has expired
                    echo "<script>
                            alert('OTP has expired. Please request a new one.');
                            window.location.href = 'index.php';
                          </script>";
                }
            } else {
                // Invalid OTP entered
                echo "<script>
                        alert('Invalid OTP. Please try again.');
                        window.location.href = 'index.php';
                      </script>";
            }
        } else {
            // User data not found
            echo "<script>
                    alert('Error: Unable to fetch user details. Please try again.');
                    window.location.href = 'index.php';
                  </script>";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>
                alert('Database error: " . $e->getMessage() . "');
                window.location.href = 'index.php';
              </script>";
    }
}
?>
