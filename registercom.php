<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé. Connectez-vous.");
}

$user_id = $_SESSION['user_id'];
$competition_id = intval($_GET['id'] ?? 0);

if ($competition_id <= 0) {
    die("Compétition invalide.");
}

// Get minimum niveau required for the competition
$stmt = $conn->prepare("SELECT min_niveau_required FROM competitions WHERE id = ?");
$stmt->execute([$competition_id]);
$min_niveau = $stmt->fetchColumn();

if ($min_niveau === false) {
    die("Compétition introuvable.");
}

// Get user's niveau
$stmt = $conn->prepare("SELECT niveau FROM clients WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_niveau = $stmt->fetchColumn();

if ($user_niveau === false) {
    die("Profil client introuvable.");
}

// Helper function to extract numeric part from niveau strings like "niveau3"
function niveauToNumber($niveau)
{
    return (int) filter_var($niveau, FILTER_SANITIZE_NUMBER_INT);
}

$min_niveau_num = niveauToNumber($min_niveau);
$user_niveau_num = niveauToNumber($user_niveau);

// Check if user's niveau meets the minimum required niveau
if ($user_niveau_num < $min_niveau_num) {
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => "❌ Vous devez avoir un niveau au moins $min_niveau pour vous inscrire. Votre niveau actuel est $user_niveau."
    ];
    header("Location: compétition.php");
    exit;
}

// Check if already registered
$stmt = $conn->prepare("SELECT COUNT(*) FROM competition_registrations WHERE user_id = ? AND competition_id = ?");
$stmt->execute([$user_id, $competition_id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['notification'] = ['type' => 'info', 'message' => 'ℹ️ Vous êtes déjà inscrit à cette compétition.'];
    header("Location: compétition.php");
    exit;
}

// Insert registration with status 'pending'
$stmt = $conn->prepare("INSERT INTO competition_registrations (user_id, competition_id, status) VALUES (?, ?, 'pending')");
$stmt->execute([$user_id, $competition_id]);

$_SESSION['notification'] = ['type' => 'success', 'message' => '🕒 Votre demande d\'inscription a été envoyée. En attente de validation.'];

header("Location: compétition.php");
exit;
