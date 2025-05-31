<?php
$host = '127.0.0.1'; // or 'localhost'
$dbname = 'sportiva';
$username = 'root';
$password = ''; // default for XAMPP is an empty password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
