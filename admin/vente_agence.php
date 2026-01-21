<?php
session_start();
include "../config/db.php";

/* 🔒 حماية الصفحة */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* 🆔 ID voiture */
if (!isset($_GET['id'])) {
    die("ID voiture manquant");
}
$id = (int) $_GET['id'];

/* 🚗 جلب السيارة */
$stmt = $conn->prepare("SELECT * FROM voiture WHERE id = ?");
$stmt->execute([$id]);
$voiture = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$voiture) {
    die("Voiture introuvable");
}

/* 🔹 جلب رقم الهاتف إذا مررناه في الرابط */
$user_tel = $_GET['tel'] ?? '';

/* 📝 Validation vente */
if (isset($_POST['valider'])) {

    $nom_client = trim($_POST['nom_client']);
    $tel_client = trim($_POST['tel_client']);
    $prix_vente = $_POST['prix_vente'];
    $remarque   = trim($_POST['remarque']);

    // اسم الـ admin
    $vendeur = $_SESSION['username'] ?? 'Admin';

    if ($nom_client === '' || $prix_vente === '') {
        $error = "Veuillez remplir les champs obligatoires";
    } else {

        /* 1️⃣ Insert vente agence */
        $stmt = $conn->prepare("
            INSERT INTO vente_agence
            (voiture_id, nom_client, tel_client, prix_vente, vendeur, date_vente, remarque)
            VALUES (?, ?, ?, ?, ?, NOW(), ?)
        ");
        $stmt->execute([
            $id,
            $nom_client,
            $tel_client,
            $prix_vente,
            $vendeur,
            $remarque
        ]);

        /* 2️⃣ تحديث كمية السيارة تلقائياً */
        $stmt2 = $conn->prepare("
            UPDATE voiture
            SET quantite = CASE 
                              WHEN quantite > 0 THEN quantite - 1
                              ELSE 0
                           END
            WHERE id = ?
        ");
        $stmt2->execute([$id]);

        /* 3️⃣ Redirect بعد النجاح */
        header("Location: ../index.php?vente=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vente agence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container mt-5">
    <div class="card bg-black text-light shadow">
        <div class="card-header bg-warning text-dark">
            <h4>🏢 Vente agence</h4>
        </div>

        <div class="card-body">

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <h5 class="mb-3">
                🚗 <?= htmlspecialchars($voiture['marque']) ?>
                <?= htmlspecialchars($voiture['modele']) ?>
            </h5>

            <p class="text-warning">
                Prix catalogue : <?= number_format($voiture['prix'],3,'.',' ') ?> DT
            </p>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nom du client *</label>
                    <input type="text" name="nom_client" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" name="tel_client" class="form-control"
                           value="<?= htmlspecialchars($user_tel) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Prix de vente *</label>
                    <input type="number" name="prix_vente"
                           class="form-control"
                           value="<?= $voiture['prix'] ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Remarque</label>
                    <textarea name="remarque" class="form-control"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="valider" class="btn btn-success">
                        ✅ Valider vente
                    </button>

                    <a href="../index.php" class="btn btn-secondary">
                        ↩ Retour
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
