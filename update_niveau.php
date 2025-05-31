<?php
require 'db.php';
session_start();

$coach_id  = $_SESSION['user_id'] ?? null;
$client_id = $_POST['client_id'] ?? null;
$niveau    = trim($_POST['niveau'] ?? '');

if (!$coach_id || !$client_id || $niveau === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes.']);
    exit;
}

// Check if client belongs to coach by matching sport
$check = $conn->prepare("
    SELECT 1
    FROM users u
    JOIN users coach ON coach.id = ? AND coach.role = 'coach'
    WHERE u.id = ? AND u.role = 'client' AND u.sport = coach.sport
");
$check->execute([$coach_id, $client_id]);
if (!$check->fetchColumn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Client non associé.']);
    exit;
}

// Update niveau
$stmt = $conn->prepare("UPDATE clients SET niveau = ? WHERE user_id = ?");
$success = $stmt->execute([$niveau, $client_id]);

header('Content-Type: application/json');
if ($success) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['error' => 'Failed to update niveau']);
}
