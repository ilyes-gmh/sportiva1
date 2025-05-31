<?php
session_start();
require_once 'db.php';

// Check if the user is authorized (optional, add your auth check here)

// Check if ID is provided and is a valid integer
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die('Invalid coach ID.');
}

$coachId = (int) $_GET['id'];

try {
    // Prepare DELETE statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id AND role = 'coach'");
    $stmt->bindParam(':id', $coachId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Success - coach deleted
        header("Location: admin.php?msg=Coach+deleted+successfully");
        exit();
    } else {
        // No coach found with that ID or user is not a coach
        die('Coach not found or already deleted.');
    }
} catch (PDOException $e) {
    die("Error deleting coach: " . $e->getMessage());
}
?>
