<?php
session_start();
include 'connection/index.php'; // Adjust the path if needed

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Prepare the delete statement
    $sql = "DELETE FROM users WHERE id = :id"; // Adjust table name if needed
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    // Execute the statement and handle the result
    if ($stmt->execute()) {
        header("Location: admin.php?message=User deleted successfully");
        exit();
    } else {
        echo "Error deleting record.";
    }
} else {
    echo "Invalid request.";
}
?>
