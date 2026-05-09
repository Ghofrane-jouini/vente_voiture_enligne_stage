<?php
session_start();
include "../config/db.php";

// Vérification de l'authentification
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// actions (annuler, supprimer)
if (isset($_GET['action'], $_GET['id'])) {
    $commande_id = (int)$_GET['id'];

    if ($_GET['action'] === 'cancel') {
        $stmt = $conn->prepare("UPDATE commandes SET statut='annulée' WHERE id=? AND user_id=? AND statut='en attente'");
        $stmt->execute([$commande_id, $user_id]);
    }

    if ($_GET['action'] === 'delete') {
        $stmt = $conn->prepare("DELETE FROM commandes WHERE id=? AND user_id=?");
        $stmt->execute([$commande_id, $user_id]);
    }

    header("Location: mes_achats.php");
    exit;
}

// Récupération des commandes
$stmt = $conn->prepare("
    SELECT 
        commandes.id AS commande_id,
        commandes.date_commande,
        commandes.statut,
        commandes.quantite,
        voiture.id AS voiture_id,
        voiture.marque,
        voiture.modele,
        voiture.prix,
        voiture.image
    FROM commandes
    JOIN voiture ON commandes.voiture_id = voiture.id
    WHERE commandes.user_id = ?
    ORDER BY commandes.date_commande DESC
");
$stmt->execute([$user_id]);
$achats = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<div class="container my-5">
    <h2 class="gold text-center mb-4">Mes achats 🚗</h2>

    <?php if(empty($achats)): ?>
        <div class="alert alert-info text-center">
            Vous n'avez encore effectué aucun achat.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($achats as $a): ?>
                <div class="col-md-4">
                    <div class="car-card">
                        <img src="../assets/uploads/<?= htmlspecialchars($a['image']) ?>" class="car-img" alt="<?= htmlspecialchars($a['marque'] . ' ' . $a['modele']) ?>">

                        <div class="car-info">
                            <h5><?= htmlspecialchars($a['marque'] . ' ' . $a['modele']) ?></h5>
                            <p><strong>Quantité :</strong> <?= $a['quantite'] ?></p>
                            <p><strong>Prix total :</strong> <?= number_format($a['prix'] * $a['quantite'], 0, ',', ' ') ?> DT</p>
                            <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($a['date_commande'])) ?></p>

                            <?php if($a['statut'] == 'en attente'): ?>
                                <span class="badge bg-warning text-dark">En attente</span>
                            <?php elseif($a['statut'] == 'confirmée'): ?>
                                <span class="badge bg-success">Confirmée</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Annulée</span>
                            <?php endif; ?>

                            <div class="mt-3 d-flex flex-column gap-2">
                                <a href="details.php?id=<?= $a['voiture_id'] ?>" class="btn-view">
                                    Voir la voiture
                                </a>

                                <?php if($a['statut'] == 'en attente'): ?>
                                    <a href="?action=cancel&id=<?= $a['commande_id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Annuler cette commande ?')">
                                        ❌ Annuler
                                    </a>
                                <?php endif; ?>

                                <a href="?action=delete&id=<?= $a['commande_id'] ?>"
                                   class="btn btn-dark btn-sm"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette commande ?')">
                                    🗑️ Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="../index.php" class="btn btn-outline-dark mt-4">← Retour</a>
</div>

<?php include "../includes/footer.php"; ?>