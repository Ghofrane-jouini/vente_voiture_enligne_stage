<?php
include "auth/auth.php";
include "config/db.php";
include "includes/header.php";

// 🔹 التأكد من أن المستخدم متصل
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['user']['id'])) {
    echo "<div class='container py-5 text-center alert alert-warning'>
            Veuillez vous connecter pour utiliser la comparaison de vos favoris.
          </div>";
    include "includes/footer.php";
    exit;
}

$user_id = $_SESSION['user']['id'];

// 🔹 جلب السيارات المفضلة للمستخدم
$stmt = $conn->prepare("
    SELECT v.*
    FROM favoris f
    JOIN voiture v ON v.id = f.voiture_id
    WHERE f.user_id = ?
");
$stmt->execute([$user_id]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 دالة لحساب score لكل سيارة
function calculateScore($car) {
    $score = 0;
    $score += 100000 / max(1, $car['prix']);      // السعر الأرخص أفضل
    $score += $car['puissance_fiscale'] * 2;      // القوة
    if (strtolower($car['boite']) == "automatique") $score += 5;
    if (strtolower($car['energie']) == "electrique") $score += 10;
    if (!empty($car['garantie'])) $score += 3;
    return $score;
}

// 🔹 حساب score لكل سيارة
foreach ($favoris as &$car) {
    $car['score'] = calculateScore($car);
}

// 🔹 ترتيب السيارات حسب score تنازلي
usort($favoris, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

// 🔹 السيارة الأفضل
$best_car = $favoris[0] ?? null;
?>

<!-- ربط CSS الخاص بالمقارنة -->
<link rel="stylesheet" href="assets/css/comparaison.css">

<div class="container py-5">
    <h2 class="mb-4">🔍 Comparaison de mes favoris voitures</h2>

    <?php if (empty($favoris)): ?>
        <p>Aucune voiture dans vos favoris pour comparer.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Marque & Modèle</th>
                    <th>Prix (DT)</th>
                    <th>Énergie</th>
                    <th>Boîte</th>
                    <th>Puissance fiscale</th>
                    <th>Garantie</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($favoris as $car): ?>
                    <tr <?= $car['id'] === $best_car['id'] ? 'class="table-success"' : '' ?>>
                        <td data-label="Marque & Modèle"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></td>
                        <td data-label="Prix (DT)"><?= number_format($car['prix'],3,'.',' ') ?></td>
                        <td data-label="Énergie"><?= htmlspecialchars($car['energie']) ?></td>
                        <td data-label="Boîte"><?= htmlspecialchars($car['boite']) ?></td>
                        <td data-label="Puissance fiscale"><?= $car['puissance_fiscale'] ?> CV</td>
                        <td data-label="Garantie"><?= htmlspecialchars($car['garantie']) ?></td>
                        <td data-label="Score"><?= round($car['score'],2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($best_car): ?>
            <div class="alert alert-success mt-4">
                ✅ <strong>La meilleure voiture parmi vos favoris est :</strong>
                <?= htmlspecialchars($best_car['marque'].' '.$best_car['modele']) ?>
                avec un score de <?= round($best_car['score'],2) ?>.
            </div>
        <?php endif; ?>
    <?php endif; ?>
        <a href="favoris.php" class="back">← Retour</a>

</div>

<?php include "includes/footer.php"; ?>
