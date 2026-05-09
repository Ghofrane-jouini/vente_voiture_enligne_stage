<?php
session_start();
include "config/db.php";
// Vérification de l'authentification
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
// Récupérer l'ID de l'utilisateur connecté
$user_id = (int) $_SESSION['user_id']; 

// Favoris du user
$stmt = $conn->prepare("
    SELECT v.*
    FROM favoris f
    JOIN voiture v ON v.id = f.voiture_id
    WHERE f.user_id = ?
");
$stmt->execute([$user_id]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Score par voiture
function calculateScore($car) {
    $score = 0;
    $score += 100000 / max(1, $car['prix']);
    $score += $car['puissance_fiscale'] * 2;
    
    if (strtolower($car['boite'])   == "automatique") $score += 5;
    if (strtolower($car['energie']) == "electrique")  $score += 10;
    if (!empty($car['garantie']))                     $score += 3;
    return $score;
}
// Calcul du score pour chaque voiture
foreach ($favoris as &$car) {
    $car['score'] = calculateScore($car);
}
// Trier les favoris par score décroissant
unset($car);
usort($favoris, fn($a, $b) => $b['score'] <=> $a['score']);
// Meilleure voiture (la première après tri)
$best_car = $favoris[0] ?? null;
include "includes/header.php";
?>

<style>
/* ══════════════════════════════════════════
   COMPARAISON PAGE
══════════════════════════════════════════ */
.comp-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 60px 20px 100px;
}

/* ── Page header ── */
.comp-header {
    margin-bottom: 40px;
}

.comp-header .comp-sub {
    font-family: 'Space Grotesk', sans-serif;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #a855f7;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.comp-header .comp-sub::before {
    content: '';
    display: inline-block;
    width: 24px; height: 2px;
    background: linear-gradient(90deg, #f43f5e, #a855f7);
    border-radius: 2px;
}

.comp-header h2 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #1e1b4b;
    letter-spacing: -.025em;
    margin: 0;
}

.comp-header h2 span {
    background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ── Table wrapper ── */
.comp-table-wrap {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid rgba(168,85,247,.12);
    box-shadow: 0 8px 32px rgba(99,102,241,.10);
    overflow: hidden;
    overflow-x: auto;
    margin-bottom: 28px;
}

.comp-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Outfit', sans-serif;
    font-size: .9rem;
}

/* Header row */
.comp-table thead tr {
    background: linear-gradient(135deg, rgba(99,102,241,.07), rgba(168,85,247,.05));
}

.comp-table th {
    font-family: 'Space Grotesk', sans-serif;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(30,27,75,.5);
    padding: 16px 18px;
    border-bottom: 1.5px solid rgba(168,85,247,.1);
    text-align: left;
    white-space: nowrap;
}

/* Body rows */
.comp-table td {
    padding: 14px 18px;
    border-bottom: 1px solid rgba(168,85,247,.07);
    color: #1e1b4b;
    vertical-align: middle;
}

.comp-table tbody tr {
    transition: background .2s;
}

.comp-table tbody tr:hover td {
    background: rgba(240,244,255,.8);
}

.comp-table tbody tr:last-child td {
    border-bottom: none;
}

/* Best car row */
.comp-table tbody tr.best-row td {
    background: rgba(16,185,129,.05);
}

.comp-table tbody tr.best-row:hover td {
    background: rgba(16,185,129,.09);
}

/* Score badge */
.score-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 50px;
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 800;
    font-size: .82rem;
    background: linear-gradient(135deg, #6366f1, #a855f7);
    color: #fff;
    box-shadow: 0 3px 10px rgba(99,102,241,.3);
}

.best-row .score-badge {
    background: linear-gradient(135deg, #10b981, #06b6d4);
    box-shadow: 0 3px 10px rgba(16,185,129,.35);
}

/* Best pill on name */
.best-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 9px;
    border-radius: 50px;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    background: linear-gradient(135deg, #10b981, #06b6d4);
    color: #fff;
    margin-left: 8px;
    vertical-align: middle;
    box-shadow: 0 2px 8px rgba(16,185,129,.35);
}

/* Energie / boite pills */
.info-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: .75rem;
    font-weight: 600;
    background: rgba(99,102,241,.09);
    color: #6366f1;
    border: 1px solid rgba(99,102,241,.15);
}

/* ── Best car alert ── */
.best-alert {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: rgba(16,185,129,.07);
    border: 1.5px solid rgba(16,185,129,.2);
    border-radius: 16px;
    margin-bottom: 28px;
    animation: fadeUp .5s both;
}

.best-alert-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.best-alert-text strong {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1rem;
    font-weight: 800;
    color: #047857;
    display: block;
    margin-bottom: 3px;
}

.best-alert-text span {
    font-size: .88rem;
    color: rgba(4,120,87,.75);
}

/* ── Empty state ── */
.comp-empty {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid rgba(168,85,247,.12);
}

.comp-empty p {
    font-size: 1rem;
    color: rgba(30,27,75,.45);
    margin-bottom: 20px;
}

/* ── Back link ── */
.comp-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 11px 22px;
    border-radius: 50px;
    background: rgba(99,102,241,.08);
    border: 1.5px solid rgba(99,102,241,.2);
    color: #6366f1;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .88rem;
    text-decoration: none;
    transition: .22s;
}

.comp-back:hover {
    background: rgba(99,102,241,.15);
    color: #4f46e5;
    transform: translateY(-2px);
}

@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<div class="comp-page">

    <!-- Header -->
    <div class="comp-header">
        <p class="comp-sub">Analyse intelligente</p>
        <h2>Comparaison de mes <span>favoris</span></h2>
    </div>

    <?php if (empty($favoris)): ?>
        <div class="comp-empty">
            <p>Aucune voiture dans vos favoris pour comparer.</p>
            <a href="index.php" class="comp-back">← Découvrir les voitures</a>
        </div>

    <?php else: ?>

        <!-- Best car alert -->
        <?php if ($best_car): ?>
            <div class="best-alert">
                <div class="best-alert-icon">🏆</div>
                <div class="best-alert-text">
                    <strong>Meilleur choix : <?= htmlspecialchars($best_car['marque'].' '.$best_car['modele']) ?></strong>
                    <span>Score de <?= round($best_car['score'], 2) ?> — meilleur rapport qualité/prix parmi vos favoris</span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="comp-table-wrap">
            <table class="comp-table">
                <thead>
                    <tr>
                        <th>Marque & Modèle</th>
                        <th>Prix (DT)</th>
                        <th>Énergie</th>
                        <th>Boîte</th>
                        <th>Puissance</th>
                        <th>Garantie</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($favoris as $car): 
                        $isBest = $best_car && $car['id'] === $best_car['id'];
                    ?>
                        <tr <?= $isBest ? 'class="best-row"' : '' ?>>
                            <td>
                                <?= htmlspecialchars($car['marque'].' '.$car['modele']) ?>
                                <?php if ($isBest): ?>
                                    <span class="best-pill">⭐ Meilleur</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= number_format($car['prix'], 3, '.', ' ') ?></strong></td>
                            <td><span class="info-pill"><?= htmlspecialchars($car['energie']) ?></span></td>
                            <td><span class="info-pill"><?= htmlspecialchars($car['boite']) ?></span></td>
                            <td><?= $car['puissance_fiscale'] ?> CV</td>
                            <td><?= htmlspecialchars($car['garantie']) ?> ans</td>
                            <td><span class="score-badge"><?= round($car['score'], 2) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="favoris.php" class="comp-back">← Retour aux favoris</a>

    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>