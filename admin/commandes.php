<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
// update statut pour chaque commande
if (isset($_GET['action'], $_GET['commande_id'])) {
    $commande_id = (int) $_GET['commande_id'];

    if ($_GET['action'] === 'confirm') {
        $stmt = $conn->prepare("UPDATE commandes SET statut='confirmée' WHERE id=?");
        $stmt->execute([$commande_id]);
    }

    if ($_GET['action'] === 'cancel') {
        $stmt = $conn->prepare("UPDATE commandes SET statut='annulée' WHERE id=?");
        $stmt->execute([$commande_id]);
    }
}

// récupérer toutes les commandes triées par client et voiture
$stmt = $conn->query("
    SELECT commandes.*, users.nom, users.email, users.telephone, voiture.marque, voiture.modele
    FROM commandes
    JOIN users ON commandes.user_id = users.id
    JOIN voiture ON commandes.voiture_id = voiture.id
    ORDER BY users.nom, commandes.date_commande DESC
");
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// organiser par client
$clients = [];
foreach ($commandes as $c) {
    $clients[$c['user_id']]['info'] = [
        'nom' => $c['nom'],
        'email' => $c['email'],
        'telephone' => $c['telephone']
    ];
    $clients[$c['user_id']]['commandes'][] = $c;
}
?>

<link rel="stylesheet" href="../assets/css/global.css">

<div class="container my-5">
    <h2>Gestion des commandes</h2>

    <?php foreach($clients as $client_id => $data): ?>
        <div class="client-card">
            <h4>
                <?= htmlspecialchars($data['info']['nom']) ?> 
                <small style="color:#ccc">
                    (<?= $data['info']['email'] ?>, <?= $data['info']['telephone'] ?>)
                </small>
            </h4>

            <?php foreach($data['commandes'] as $c): ?>
                <div class="car-card">
                    <div class="car-info">
                        <h5><?= htmlspecialchars($c['marque'].' '.$c['modele']) ?></h5>
                        <p>Quantité: <?= $c['quantite'] ?></p>
                        <p>Date: <?= date('d/m/Y', strtotime($c['date_commande'])) ?></p>
                        <span class="badge <?= $c['statut']=='confirmée'?'bg-success':($c['statut']=='annulée'?'bg-danger':'bg-warning text-dark') ?>">
                            <?= ucfirst($c['statut']) ?>
                        </span>
                    </div>
                    <div>
                        <a href="?action=confirm&commande_id=<?= $c['id'] ?>" class="btn btn-success btn-sm" title="Confirmer">✔</a>
                        <a href="?action=cancel&commande_id=<?= $c['id'] ?>" class="btn btn-danger btn-sm" title="Annuler">✖</a>
                        <a href="vente_agence.php?id=<?= $c['voiture_id'] ?>&tel=<?= urlencode($data['info']['telephone']) ?>" 
                        class="btn-view" style="background:purple;color:white;border:none;">
                         Vente agence
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<a href="../index.php" class="btn btn-outline-dark mt-4"><-- Retour</a>

<?php include "../includes/footer.php"; ?>
