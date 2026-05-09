<?php  
include "../auth/auth.php";
include __DIR__ . "/../config/db.php"; 
include __DIR__ . "/../includes/header.php";

$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($car_id <= 0) {
    header("Location: ../index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM voiture WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    header("Location: ../index.php");
    exit;
}
$isFavorite = false;

if (isset($_SESSION['user_id'])) {
    $checkFav = $conn->prepare("
        SELECT id FROM favoris 
        WHERE user_id = ? AND voiture_id = ?
    ");
    $checkFav->execute([$_SESSION['user_id'], $car['id']]);
    $isFavorite = $checkFav->fetch() ? true : false;
}

?>
<link rel="stylesheet" href="../assets/css/global.css">
<section class="car-details py-5">
    <div class="container">
        <div class="row g-4">

            <!-- IMAGE -->
            <div class="col-md-6">
                <div class="image-box">
                    <img src="../assets/uploads/<?= htmlspecialchars($car['image']) ?>" alt="">
                    
                    <?php if ($car['promo']): ?>
                        <span class="badge promo">PROMO</span>
                    <?php endif; ?>
                    <?php if ($car['nouveaute']): ?>
                        <span class="badge new">NOUVEAU</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- INFOS -->
            <div class="col-md-6">
                <h1 class="fw-bold">
                    <?= htmlspecialchars($car['marque'].' '.$car['modele']) ?>
                </h1>

                <h2 class="price">
                    <?= number_format($car['prix'], 3, '.', ' ') ?> DT
                </h2>
                
                <!-- CARACTERISTIQUES -->
                <div class="info-card">
                    <h5>🧾 Caractéristiques</h5>
                    <ul>
                        <li> Énergie : <strong><?= $car['energie'] ?></strong></li>
                        <li> Boîte : <strong><?= $car['boite'] ?></strong></li>
                        <li> Transmission : <strong><?= $car['transmission'] ?></strong></li>
                        <li> Puissance fiscale : <strong><?= $car['puissance_fiscale'] ?> CV</strong></li>
                        <li> Cylindres : <strong><?= $car['cylindres'] ?></strong></li>
                    </ul>
                </div>

                <!-- CONFORT -->
                <div class="info-card">
                    <h5> Confort</h5>
                    <ul>
                        <li>Nombre de places : <strong><?= $car['nombre_places'] ?></strong></li>
                        <li>Nombre de portes : <strong><?= $car['nombre_portes'] ?></strong></li>
                    </ul>
                </div>

                <!-- GARANTIE -->
                <div class="info-card">
                    <h5> Garantie</h5>
                    <p><strong><?= $car['garantie'] ?></strong></p>
                </div>

                <!-- ADMIN -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="mt-3">
                        <a href="../admin/edit_car.php?id=<?= $car['id'] ?>" class="btn btn-warning">✏️ Modifier</a>
                        <a href="../admin/delete_car.php?id=<?= $car['id'] ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Supprimer cette voiture ?');">
                           🗑️ Supprimer
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <?php if ($isFavorite): ?>
                    <button class="btn btn-outline-danger" disabled>
                        ❤️ Déjà en favoris
                    </button>
                <?php else: ?>
                    <a href="../favoris.php?action=add&id=<?= $car['id'] ?>"
                    class="btn btn-outline-warning">
                        ⭐ Ajouter aux favoris
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="../auth/login.php" class="btn btn-outline-secondary">
                    🔒 Connectez-vous pour ajouter aux favoris
                </a>
            <?php endif; ?>

                <a href="../index.php" class="btn btn-outline-dark mt-4">← Retour</a>
            </div>

        </div>
    </div>
</section>


<?php include __DIR__ . "/../includes/footer.php"; ?>
