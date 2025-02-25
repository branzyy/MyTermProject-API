<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

header("Location: forms/loginform.php"); // Redirect to login page
exit;
?>