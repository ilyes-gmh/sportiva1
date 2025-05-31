<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = $_POST['email'];
    $role = $_POST['role'];
    $sport = $_POST['sport'];

    // استخدم $conn بدل $pdo
    $stmt = $conn->prepare("UPDATE users SET prenom = ?, nom = ?, email = ?, role = ?, sport = ? WHERE id = ?");
    $stmt->execute([$prenom, $nom, $email, $role, $sport, $id]);

    header("Location: admin.php");
    exit;
}
?>
