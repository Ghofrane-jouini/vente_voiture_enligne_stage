<?php
session_start();
include "../config/db.php";

$error = "";
define("ADMIN_EMAIL", "admin@gmail.com");
define("ADMIN_PASSWORD", "admin123");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        $_SESSION['user'] = ['nom' => 'Admin', 'email' => ADMIN_EMAIL];
        $_SESSION['role'] = 'admin';
        header("Location: ../index.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = 'user';
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit;
    }

    $error = "Email ou mot de passe incorrect";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body class="auth-page">

    <div class="jelly-form">
        <div class="form-header">
            <h2>Connexion</h2>
            <p style="color:var(--muted);font-size:.88rem;margin-top:4px;">Bienvenue sur MGAUTO l' AutoLux </p>
        </div>

        <?php if ($error): ?>
            <div class="auth-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder=" " required>
                <label>Email</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder=" " required>
                <label>Mot de passe</label>
            </div>

            <button type="submit" name="login">Se connecter</button>
        </form>

        <p class="auth-link">
            Pas de compte ? <a href="register.php">Créer un compte</a>
        </p>
        <p class="auth-link">
            <a href="../index.php">← Retour à l'accueil</a>
        </p>
    </div>

</body>
</html>