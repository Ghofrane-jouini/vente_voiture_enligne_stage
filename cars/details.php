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

<style>/* ===== GENERAL ===== */
body {
    background-color: #f8f9fa;
    font-family: 'Poppins', sans-serif;
    color: #111; /* Noir */
    
}

h1, h2, h5 {
    color: #111; /* Noir */
}

a {
    text-decoration: none;
}

/* ===== SECTION DETAILS ===== */
.car-details {
    padding: 60px 0;
}

/* ===== IMAGE BOX ===== */
.image-box {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.image-box img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.3s ease;
}

.image-box:hover img {
    transform: scale(1.05);
}

/* Badges */
.badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 5px 10px;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 5px;
    color: #fff;
}

.badge.promo {
    background-color: #dc3545; /* rouge promo */
}

.badge.new {
    background-color: #28a745; /* vert nouveauté */
    top: 10px;
    right: 10px;
    left: auto;
}

/* ===== PRICE ===== */
.price {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 15px 0;
    color: #111; /* Noir */
}

/* ===== INFO CARD ===== */
.info-card {
    background-color: #5d3434a8;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    
}

.info-card h5 {
    font-weight: 600;
    margin-bottom: 15px;
}

.info-card ul {
    list-style: none;
    padding: 0;
}

.info-card ul li {
    padding: 5px 0;
    font-size: 0.95rem;
}

/* ===== BUTTONS ===== */
.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 15px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Outline Buttons */
.btn-outline-warning {
    color: #ffc107;
    border-color: #ffc107;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    color: #111;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: #fff;
}

.btn-outline-dark {
    color: #111;
    border-color: #111;
}

.btn-outline-dark:hover {
    background-color: #111;
    color: #fff;
}

/* Admin Buttons */
.btn-warning, .btn-danger {
    width: 100%;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .car-details .row {
        flex-direction: column;
    }

    .image-box, .col-md-6 {
        width: 100%;
    }
}
</style>
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
                        <li>⛽ Énergie : <strong><?= $car['energie'] ?></strong></li>
                        <li>⚙️ Boîte : <strong><?= $car['boite'] ?></strong></li>
                        <li>🛞 Transmission : <strong><?= $car['transmission'] ?></strong></li>
                        <li>🧮 Puissance fiscale : <strong><?= $car['puissance_fiscale'] ?> CV</strong></li>
                        <li>🔧 Cylindres : <strong><?= $car['cylindres'] ?></strong></li>
                    </ul>
                </div>

                <!-- CONFORT -->
                <div class="info-card">
                    <h5>🪑 Confort</h5>
                    <ul>
                        <li>Nombre de places : <strong><?= $car['nombre_places'] ?></strong></li>
                        <li>Nombre de portes : <strong><?= $car['nombre_portes'] ?></strong></li>
                    </ul>
                </div>

                <!-- GARANTIE -->
                <div class="info-card">
                    <h5>🛡 Garantie</h5>
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
