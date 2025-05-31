<?php
require 'db.php'; // تأكد أن هذا الملف يحتوي على تعريف $pdo = new PDO(...)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $prenom = trim($_POST['prenom']);  // الاسم الأول
    $nom = trim($_POST['nom']);        // الاسم الأخير
    $email = $_POST['email'];
    $role = $_POST['role'];
    $sport = $_POST['sport'];

    // تنفيذ التحديث باستخدام PDO
    $stmt = $conn->prepare("UPDATE users SET prenom = ?, nom = ?, email = ?, role = ?, sport = ? WHERE id = ?");
    $stmt->execute([$prenom, $nom, $email, $role, $sport, $id]);
    

    header("Location: admin.php");
    exit;
}
?>
