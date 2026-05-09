<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    UPDATE commandes 
    SET statut='annulée' 
    WHERE id=? AND user_id=? AND statut='en attente'");
$stmt->execute([$id, $user_id]);

header("Location: mes_achats.php");
