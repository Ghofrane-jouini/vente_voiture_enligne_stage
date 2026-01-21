<?php

include "config/db.php";
include "auth/auth.php";
include "includes/header.php";

/* 🔐 Vérification utilisateur */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ➕ Ajouter aux favoris */
if (
    isset($_GET['action'], $_GET['id']) &&
    $_GET['action'] === 'add'
) {
    $voiture_id = (int) $_GET['id'];

    if ($voiture_id > 0) {
        $stmt = $conn->prepare("
            INSERT INTO favoris (user_id, voiture_id)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE date_ajout = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$user_id, $voiture_id]);
    }

    header("Location: favoris.php");
    exit;
}

/* ❌ Supprimer des favoris */
if (
    isset($_GET['action'], $_GET['id']) &&
    $_GET['action'] === 'remove'
) {
    $voiture_id = (int) $_GET['id'];

    $stmt = $conn->prepare("
        DELETE FROM favoris
        WHERE user_id = ? AND voiture_id = ?
    ");
    $stmt->execute([$user_id, $voiture_id]);

    header("Location: favoris.php");
    exit;
}

/* 📥 Récupération des favoris */
$stmt = $conn->prepare("
    SELECT v.*
    FROM favoris f
    INNER JOIN voiture v ON v.id = f.voiture_id
    WHERE f.user_id = ?
    ORDER BY f.date_ajout DESC
");
$stmt->execute([$user_id]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <h2 class="mb-4">❤️ Mes voitures favorites</h2>

    <?php if (empty($favoris)): ?>
        <div class="alert alert-info">
            Aucune voiture ajoutée aux favoris pour le moment.
        </div>
        <a href="index.php" class="btn btn-dark">← Découvrir les voitures</a>

    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($favoris as $car): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img
                            src="assets/uploads/<?= htmlspecialchars($car['image']) ?>"
                            class="card-img-top"
                            style="height:200px;object-fit:cover"
                            alt="voiture">

                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?>
                            </h5>

                            <p class="text-danger fw-bold">
                                <?= number_format($car['prix'], 3, '.', ' ') ?> DT
                            </p>

                            <div class="d-flex justify-content-between">
                                <a href="cars/details.php?id=<?= $car['id'] ?>"
                                   class="btn btn-outline-dark btn-sm">
                                   Détails
                                </a>

                                <a href="favoris.php?action=remove&id=<?= $car['id'] ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Retirer cette voiture des favoris ?');">
                                   ❌ Retirer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <a href="comparaison.php" class="btn btn-warning">
                🔍 Comparer mes favoris
            </a>
            <a href="index.php" class="btn btn-outline-dark ms-2">
                ← Retour
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
