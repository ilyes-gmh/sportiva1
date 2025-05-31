<?php
require 'db.php';
session_start();

$coach_id  = $_SESSION['user_id'] ?? null;
$client_id = $_POST['client_id']   ?? null;
$notes     = '';                         // keep empty for quick-add

if (!$coach_id || !$client_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing IDs']);
    exit;
}

/* Optional but recommended: verify that
   this client really belongs to this coach */
$check = $conn->prepare("
   SELECT 1
   FROM users u
   WHERE u.id = ? 
     AND u.role = 'client' 
     AND u.sport = (
         SELECT sport 
         FROM users 
         WHERE id = ? AND role = 'coach'
     )
");
$check->execute([$client_id, $coach_id]);
if (!$check->fetchColumn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Not your client']);
    exit;
}

/* Insert session */
$stmt = $conn->prepare(
    "INSERT INTO client_sessions (client_id, coach_id, notes)
     VALUES (?, ?, ?)"
);
$stmt->execute([$client_id, $coach_id, $notes]);

header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
