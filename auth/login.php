<?php
session_start();
include "../config/db.php";

$error = "";

// 🔴 ADMIN CONSTANT
define("ADMIN_EMAIL", "admin@gmail.com");
define("ADMIN_PASSWORD", "admin123");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // ===== ADMIN LOGIN =====
    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        $_SESSION['user'] = [
            'nom' => 'Admin',
            'email' => ADMIN_EMAIL
        ];
        $_SESSION['role'] = 'admin';
        header("Location: ../index.php");
        exit;
    }

    // ===== USER LOGIN (DB) =====
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user'] = $user;           // تخزين بيانات كاملة
        $_SESSION['role'] = 'user';
        $_SESSION['user_id'] = $user['id'];  // ⬅️ مهم لتعمل favoris

        header("Location: ../index.php");
        exit;
    }

    $error = "Email ou mot de passe incorrect";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="container">
    <div class="jelly-form">
        <div class="form-header">
            <h2>Welcome</h2>
        </div>

        <?php if ($error): ?>
            <div class="auth-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <button type="submit" name="login">Sign In</button>
        </form>

        <p class="auth-link">
            Pas de compte ? <a href="register.php">Créer un compte</a>
        </p>
        <p class="auth-link">
         ← <a href="../index.php">Retour à l'accueil</a>
        </p>
    </div>
</div>

</body>
</html>
