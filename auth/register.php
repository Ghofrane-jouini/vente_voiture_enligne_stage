<?php
include "../config/db.php";

if (isset($_POST['register'])) {

    $nom       = trim($_POST['nom']);
    $email     = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        $error = "Email déjà utilisé !";
    } elseif (!preg_match('/^(?:\+216)?[0-9]{8}$/', $telephone)) {
        $error = "Numéro de téléphone invalide !";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO users (nom, email, telephone, password)
            VALUES (:nom, :email, :telephone, :password)
        ");
        $stmt->execute([
            ":nom"       => $nom,
            ":email"     => $email,
            ":telephone" => $telephone,
            ":password"  => $password
        ]);
        header("Location: login.php?registered=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body class="auth-page">

    <!-- Blobs décoratifs -->
    <div class="floating-blob blob-1"></div>
    <div class="floating-blob blob-2"></div>

    <div class="jelly-form">
        <div class="form-header">
            <h2>Créer un compte</h2>
            <p style="color:var(--muted);font-size:.88rem;margin-top:4px;">Rejoignez AutoLux Tunisia</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <input type="text" name="nom" placeholder=" " required>
                <label>Nom complet</label>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder=" " required>
                <label>Email</label>
            </div>

            <div class="input-group">
                <input type="text" name="telephone" placeholder=" " required>
                <label>Numéro de téléphone</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder=" " required>
                <label>Mot de passe</label>
            </div>

            <button type="submit" name="register">Créer mon compte</button>
        </form>

        <p class="auth-link">
            Déjà inscrit ? <a href="login.php">Se connecter</a>
        </p>
        <p class="auth-link">
            <a href="../index.php">← Retour à l'accueil</a>
        </p>
    </div>

</body>
</html>