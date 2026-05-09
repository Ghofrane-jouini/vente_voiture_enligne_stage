<?php
session_start();
include "../config/db.php";

// Vérification de l'authentification et du rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


if (!isset($_GET['id'])) {
    die("ID voiture manquant");
}
$id = (int) $_GET['id'];
// Récupérer les infos de la voiture
$stmt = $conn->prepare("SELECT * FROM voiture WHERE id = ?");
$stmt->execute([$id]);
$voiture = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voiture) {
    die("Voiture introuvable");
}

$user_tel = $_GET['tel'] ?? '';
// Traitement du formulaire de vente
if (isset($_POST['valider'])) {

    $nom_client = trim($_POST['nom_client']);
    $tel_client = trim($_POST['tel_client']);
    $prix_vente = trim($_POST['prix_vente']);
    $remarque   = trim($_POST['remarque']);

    $vendeur_form = trim($_POST['vendeur'] ?? '');
    if ($vendeur_form !== '') {
        $vendeur = $vendeur_form;
    } elseif (!empty($_SESSION['username'])) {
        $vendeur = $_SESSION['username'];
    } else {
        $vendeur = 'Admin';
    }

    if ($nom_client === '' || $prix_vente === '') {
        $error = "Veuillez remplir les champs obligatoires.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO vente_agence
            (marque, modele, nom_client, tel_client, prix_vente, vendeur, date_vente, remarque)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
        ");
        $stmt->execute([
            $voiture['marque'],
            $voiture['modele'],
            $nom_client,
            $tel_client,
            $prix_vente,
            $vendeur,
            $remarque
        ]);

        $stmt2 = $conn->prepare("DELETE FROM voiture WHERE id = ?");
        $stmt2->execute([$id]);

        header("Location: ../index.php?vente=success");
        exit;
    }
}

// Valeurs par défaut pour le formulaire (pour éviter les "undefined index")
$val_nom_client = htmlspecialchars($_POST['nom_client'] ?? '');
$val_tel_client = htmlspecialchars($_POST['tel_client'] ?? $user_tel);
$val_vendeur    = htmlspecialchars($_POST['vendeur']    ?? '');
$val_prix_vente = htmlspecialchars($_POST['prix_vente'] ?? $voiture['prix']);
$val_remarque   = htmlspecialchars($_POST['remarque']   ?? '');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vente agence — <?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body class="vente-page">

<style>
/* ══════════════════════════════════════════
   VENTE AGENCE PAGE
══════════════════════════════════════════ */
.vente-page {
    min-height: 100vh;
    background: #f0f4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    font-family: 'Outfit', sans-serif;
}

.vente-page::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 60% at 20% 20%, rgba(139,92,246,.10) 0%, transparent 55%),
        radial-gradient(ellipse 60% 50% at 80% 80%, rgba(236,72,153,.08) 0%, transparent 55%);
    pointer-events: none;
    z-index: 0;
}

.vente-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 620px;
    background: #fff;
    border-radius: 24px;
    border: 1.5px solid rgba(168,85,247,.12);
    box-shadow: 0 20px 60px rgba(99,102,241,.12);
    overflow: hidden;
    animation: fadeUp .6s both;
}

@keyframes fadeUp {
    from { opacity:0; transform:translateY(24px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Header ── */
.vente-card-header {
    padding: 22px 32px;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.vente-card-header h4 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.15rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: -.02em;
}

.vente-card-header .header-badge {
    padding: 4px 12px;
    border-radius: 50px;
    background: rgba(255,255,255,.25);
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}

/* ── Car info band ── */
.vente-car-band {
    padding: 18px 32px;
    background: rgba(240,244,255,.7);
    border-bottom: 1.5px solid rgba(168,85,247,.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.vente-car-band .car-name {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: #1e1b4b;
    letter-spacing: -.02em;
}

.vente-car-band .car-price {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1rem;
    font-weight: 800;
    background: linear-gradient(135deg, #f59e0b, #f43f5e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ── Form body ── */
.vente-card-body {
    padding: 28px 32px 32px;
}

.vente-field {
    margin-bottom: 18px;
}

.vente-field label {
    display: block;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: rgba(30,27,75,.5);
    margin-bottom: 7px;
}

.vente-field label .req {
    color: #f43f5e;
    margin-left: 2px;
}

.vente-field input,
.vente-field textarea {
    width: 100%;
    padding: 13px 16px;
    border-radius: 12px;
    border: 1.5px solid rgba(168,85,247,.18);
    background: rgba(240,244,255,.6);
    color: #1e1b4b;
    font-family: 'Outfit', sans-serif;
    font-size: .92rem;
    outline: none;
    transition: border-color .22s, box-shadow .22s, background .22s;
}

.vente-field input:focus,
.vente-field textarea:focus {
    border-color: #a855f7;
    box-shadow: 0 0 0 3px rgba(168,85,247,.14);
    background: #fff;
}

.vente-field textarea {
    resize: vertical;
    min-height: 90px;
}

/* Grid 2 colonnes */
.vente-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* Error */
.vente-error {
    padding: 12px 16px;
    border-radius: 12px;
    background: rgba(244,63,94,.07);
    border: 1.5px solid rgba(244,63,94,.2);
    color: #f43f5e;
    font-size: .88rem;
    font-weight: 600;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Buttons */
.vente-actions {
    display: flex;
    gap: 10px;
    margin-top: 24px;
}

.vente-btn-submit {
    flex: 1;
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg, #10b981, #06b6d4);
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .92rem;
    border: none;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(16,185,129,.3);
    transition: transform .22s, box-shadow .22s;
}

.vente-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(16,185,129,.45);
}

.vente-btn-back {
    padding: 14px 22px;
    border-radius: 12px;
    background: rgba(99,102,241,.08);
    border: 1.5px solid rgba(99,102,241,.2);
    color: #6366f1;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .92rem;
    text-decoration: none;
    transition: .22s;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.vente-btn-back:hover {
    background: rgba(99,102,241,.15);
    color: #4f46e5;
    transform: translateY(-2px);
}

@media (max-width: 520px) {
    .vente-card-header, .vente-car-band, .vente-card-body { padding-left: 20px; padding-right: 20px; }
    .vente-grid-2 { grid-template-columns: 1fr; }
    .vente-actions { flex-direction: column; }
}
</style>

<div class="vente-card">

    <!-- Header -->
    <div class="vente-card-header">
        <h4> Vente agence</h4>
        <span class="header-badge">Admin</span>
    </div>

    <!-- Car info -->
    <div class="vente-car-band">
        <span class="car-name">
            <?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?>
        </span>
        <span class="car-price">
            Prix catalogue : <?= number_format($voiture['prix'], 3, '.', ' ') ?> DT
        </span>
    </div>

    <!-- Form -->
    <div class="vente-card-body">

        <?php if (isset($error)): ?>
            <div class="vente-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="vente-grid-2">
                <div class="vente-field">
                    <label>Nom du client <span class="req">*</span></label>
                    <input type="text" name="nom_client"
                           value="<?= $val_nom_client ?>"
                           placeholder="Ex: Mohamed Ben Ali"
                           required>
                </div>

                <div class="vente-field">
                    <label>Téléphone</label>
                    <input type="tel" name="tel_client"
                           value="<?= $val_tel_client ?>"
                           placeholder="Ex: 55 123 456">
                </div>
            </div>

            <div class="vente-grid-2">
                <div class="vente-field">
                    <label>Nom du vendeur</label>
                    <input type="text" name="vendeur"
                           value="<?= $val_vendeur ?>"
                           placeholder="Optionnel">
                </div>

                <div class="vente-field">
                    <label>Prix de vente <span class="req">*</span></label>
                    <input type="number" step="0.001" name="prix_vente"
                           value="<?= $val_prix_vente ?>"
                           required>
                </div>
            </div>

            <div class="vente-field">
                <label>Remarque</label>
                <textarea name="remarque" placeholder="Notes supplémentaires..."><?= $val_remarque ?></textarea>
            </div>

            <div class="vente-actions">
                <button type="submit" name="valider" class="vente-btn-submit">
                     Valider la vente
                </button>
                <a href="../index.php" class="vente-btn-back">
                    ↩ Retour
                </a>
            </div>

        </form>
    </div>
</div>

</body>
</html>