<?php
session_start();
require 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $motdepasse = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($motdepasse, $user['motdepasse'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['notification'] = [
            'message' => "Welcome, " . htmlspecialchars($user['prenom']) . "! You have successfully logged in.",
            'type' => 'success'
        ];

        if ($user['role'] === 'admin') {
            header("Location: admin.php");
            exit();
        } elseif ($user['role'] === 'coach') {
            header("Location: coach.php");
            exit();
        } elseif ($user['role'] === 'client') {
            header("Location: home.php");
            exit();
        } else {
            $_SESSION['notification'] = [
                'message' => "Unknown user role.",
                'type' => 'warning'
            ];
            header("Location: home.php");
            exit();
        }
    } else {
        $_SESSION['notification'] = [
            'message' => "Invalid email or password.",
            'type' => 'error'
        ];
        header("Location: home.php");
        exit();
    }
}
