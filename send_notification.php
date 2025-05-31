<?php
session_start();
require_once 'db.php'; // your DB connection

$coach_id = $_SESSION['user_id'] ?? 0;
if (!$coach_id) exit("Accès non autorisé");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
    $target = $_POST['target'];
    $message = trim($_POST['message']);
    $email = trim($_POST['email'] ?? '');

    if (empty($message)) {
        exit("Le message ne peut pas être vide.");
    }

    // Get coach's sport
    $stmt = $conn->prepare("SELECT sport FROM users WHERE id = ?");
    $stmt->execute([$coach_id]);
    $coach_sport = $stmt->fetchColumn();

    if (!$coach_sport) exit("Sport introuvable pour le coach.");

    $users = [];

    if ($target === 'sport') {
        // Get all users with same sport
        $stmt = $conn->prepare("SELECT id, email FROM users WHERE sport = ? AND id != ?");
        $stmt->execute([$coach_sport, $coach_id]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($target === 'email' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Get specific user by email
        $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) $users[] = $user;
    } else {
        exit("Cible invalide.");
    }

    // Insert notifications
    $stmt = $conn->prepare("INSERT INTO notifications (sender_id, receiver_id, message, email) VALUES (?, ?, ?, ?)");

    foreach ($users as $user) {
        $stmt->execute([$coach_id, $user['id'], $message, $user['email']]);
    }

    echo "Notification envoyée à " . count($users) . " utilisateur(s).";
}
