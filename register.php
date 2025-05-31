<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $sport = $_POST['sport'];

    try {
        $stmt = $conn->prepare("INSERT INTO users (role, nom, prenom, email, motdepasse, sport) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$role, $nom, $prenom, $email, $motdepasse, $sport]);

        $user_id = $conn->lastInsertId();

        if ($role === 'client') {
            $age = $_POST['age'] ?? null;
            $niveau = $_POST['niveau'] ?? null;

            $stmtClient = $conn->prepare("INSERT INTO clients (user_id, age, niveau) 
                                         VALUES (?, ?, ?)");
            $stmtClient->execute([$user_id, $age, $niveau]);

            $_SESSION['notification'] = [
                'message' => "✅ Inscription réussie en tant que client.",
                'type' => 'success'
            ];

            header("Location: home.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['notification'] = [
            'message' => "❌ Erreur lors de l'inscription : " . $e->getMessage()
           
        ];


        header("Location: home.php");
        exit;
    }
}
