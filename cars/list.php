<?php 
include "../auth/auth.php";
include "../config/db.php";
include "../includes/header.php";
$sql = "SELECT * FROM voiture WHERE 1=1";
$params = [];
/* MARQUE */
if (!empty($_GET['marque'])) {
    $sql .= " AND marque = ?";
    $params[] = $_GET['marque'];
}

/* MODELE */
if (!empty($_GET['modele'])) {
    $sql .= " AND modele = ?";
    $params[] = $_GET['modele'];
}

/* PRIX MAX */
if (!empty($_GET['prix_max'])) {
    $sql .= " AND prix <= ?";
    $params[] = $_GET['prix_max'];
}

/* ENERGIE */
if (!empty($_GET['energie'])) {
    $sql .= " AND energie = ?";
    $params[] = $_GET['energie'];
}

/* BOITE */
if (!empty($_GET['boite'])) {
    $sql .= " AND boite = ?";
    $params[] = $_GET['boite'];
}

/* TRI */
$sql .= " ORDER BY prix ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$voitures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../assets/css/global.css">
<div class="list-container">
    <h3> Liste des voitures</h3>

    <?php if (count($voitures) === 0): ?>
        <p style="text-align:center; color:#aaa; margin-top:20px;">
            ! Aucune voiture ne correspond à votre recherche.
        </p>
    <?php endif; ?>

    <div class="row g-4 justify-content-center">
        <?php foreach ($voitures as $v): 
            $image = "../assets/uploads/" . $v['image'];
            $nom = htmlspecialchars($v['marque'].' '.$v['modele']);
        ?>
            <div class="col-md-4">
                <div class="car-card">
                    <?php if ($v['promo']): ?>
                        <span class="badge promo">PROMO</span>
                    <?php endif; ?>
                    <?php if ($v['nouveaute']): ?>
                        <span class="badge new">NEW</span>
                    <?php endif; ?>

                    <img src="<?= $image ?>" class="car-img" alt="<?= $nom ?>">

                    <div class="car-info">
                        <h5><?= $nom ?></h5>
                        <p class="energie"><?= $v['energie'] ?> • <?= $v['boite'] ?></p>
                        <p class="gold"><?= number_format($v['prix'], 0, '.', ' ') ?> DT</p>
                        <a href="details.php?id=<?= $v['id'] ?>" class="btn-view btn-buy">
                            Voir plus
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="../index.php" class="back">← Retour</a>
</div>

<?php include "../includes/footer.php"; ?>
