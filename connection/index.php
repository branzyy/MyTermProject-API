<?php
$servername = "localhost:3306";
$username = "root";
$password = "1234";

try {
    $conn = new PDO("mysql:host=$servername;dbname=api_project", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    //print '<br>';

    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>