<?php
# delete_user.php

require_once 'cfg.php';

// Check if user ID is provided in the request
if (isset($_POST['ID'])) {
    $userId = $_POST['ID'];

    // Debugging statement to check the received user ID
    echo "User ID to delete: $userId";

    // Prepare and execute the DELETE query
    $stmt = $pdo->prepare("DELETE FROM users WHERE ID = :ID");
    $stmt->bindParam(':ID', $userId, PDO::PARAM_INT);

    try {
        $pdo->beginTransaction();

        // Debugging statement to check the SQL query
        echo "SQL Query: " . $stmt->queryString;

        $stmt->execute();

        $pdo->commit();
        echo "User with UID $userId deleted successfully.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
        // Add additional debugging information if needed, like $stmt->debugDumpParams();
    }
} else {
    echo "User ID not provided in the request.";
}

header('Location:../index.php')
?>
