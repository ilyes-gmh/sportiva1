<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour ajouter une séance.");
}

$coach_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_session'])) {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $sport_type = trim($_POST['sport_type'] ?? '');

    if ($date && $time && $location && $sport_type) {
        $stmt = $conn->prepare("INSERT INTO sessions (coach_id, date, time, location, sport_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$coach_id, $date, $time, $location, $sport_type]);

        $_SESSION['notification'] = ['type' => 'success', 'message' => '✅ Séance ajoutée avec succès.'];
    } else {
        $_SESSION['notification'] = ['type' => 'error', 'message' => '❌ Tous les champs sont requis.'];
    }

    header('Location: coach.php');  // redirect back to the form page
    exit;
}
