<?php
session_start();
require_once 'db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("UPDATE competition_registrations SET status = 'approved' WHERE id = ?");
$stmt->execute([$id]);

header("Location: admin.php");
