<?php
include "../config/db.php";

if (isset($_POST['register'])) {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 1️⃣ Vérification email déjà utilisé
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        echo "<script>alert('❌ Email déjà utilisé !');</script>";
    }
    // 2️⃣ Validation téléphone tunisien (8 chiffres, option +216)
    elseif (!preg_match('/^(?:\+216)?[0-9]{8}$/', $telephone)) {
        echo "<script>alert('❌ Numéro de téléphone invalide !');</script>";
    }
    // 3️⃣ Insert user
    else {
        $stmt = $conn->prepare("
            INSERT INTO users (nom, email, telephone, password)
            VALUES (:nom, :email, :telephone, :password)
        ");

        $stmt->execute([
            ":nom" => $nom,
            ":email" => $email,
            ":telephone" => $telephone,
            ":password" => $password
        ]);

        echo "<script>alert('✅ Inscription réussie !'); window.location.href='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="container">
        <div class="jelly-form">
            <div class="form-header">
                <h2>Create Account</h2>
            </div>

            <form method="POST">
                <div class="input-group">
                    <input type="text" name="nom" required>
                    <label>Full Name</label>
                    <div class="input-highlight"></div>
                </div>
                <div class="input-group">
                    <input type="email" name="email" required>
                    <label>Email</label>
                    <div class="input-highlight"></div>
                </div>
                <div class="input-group">
                    <input type="text" name="telephone" class="form-control"  required>
                    <label>Phone Number</label>
                    <div class="input-highlight"></div>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label>Password</label>
                    <div class="input-highlight"></div>
                </div>
                <button type="submit" name="register">
                    <span>Sign Up</span>
                    <div class="btn-highlight"></div>
                </button>
            </form>

            <p class="auth-link">
                Déjà inscrit ? <a href="login.php">Connexion</a>
            </p>
            <p class="auth-link">
                ← <a href="../index.php">Retour à l'accueil</a>
            </p>
        </div>

        <!-- Floating blobs -->
        <div class="floating-blob blob-1"></div>
        <div class="floating-blob blob-2"></div>
    </div>
</body>
</html>
