<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    $car_id  = (int)$_POST['id'];
    $user_id = $_SESSION['user_id'];
    // Vérifier si une commande en attente existe déjà pour cette voiture et cet utilisateur
    $check = $conn->prepare("
        SELECT id, quantite 
        FROM commandes 
        WHERE user_id=? AND voiture_id=? AND statut='en attente'
    ");
    $check->execute([$user_id, $car_id]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Si oui, on met à jour la quantité
        $update = $conn->prepare("
            UPDATE commandes 
            SET quantite = quantite + 1 
            WHERE id=?
        ");
        $update->execute([$existing['id']]);
    } else {
        // Sinon, on crée une nouvelle commande
        $stmt = $conn->prepare("
            INSERT INTO commandes (user_id, voiture_id, date_commande, statut, quantite)
            VALUES (?, ?, NOW(), 'en attente', 1)
        ");
        $stmt->execute([$user_id, $car_id]);
    }

    header("Location: mes_achats.php");
    exit;
}
