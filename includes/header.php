<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===== Badge commandes (USER ONLY) ===== */
$nb_commandes = 0;

require_once $_SERVER['DOCUMENT_ROOT'] . "/stage/project/config/db.php";

if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    $stmt = $conn->prepare("
        SELECT SUM(quantite) as total 
        FROM commandes 
        WHERE user_id = ? AND statut = 'en attente'
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $nb_commandes = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MG AUTO</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/stage/project/assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/stage/project/index.php">
            <img src="/stage/project/assets/uploads/logoMGAUTO.jpg"  style="height:40px; margin-right:10px;">
            MG AUTO
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">

            <?php if (isset($_SESSION['user'])): ?>
                <span class="text-light">
                    Bonjour <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                </span>

                <a href="/stage/project/auth/logout.php"
                   class="btn btn-outline-warning btn-sm">
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="/stage/project/auth/login.php"
                   class="btn btn-outline-light btn-sm">
                    Connexion
                </a>

                <a href="/stage/project/auth/register.php"
                   class="btn btn-warning btn-sm">
                    Inscription
                </a>
            <?php endif; ?>

            <!-- USER: Mes achats avec Badge -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <a href="/stage/project/cars/mes_achats.php" class="btn btn-warning position-relative me-2">
                    Mes achats 🔔
                    <?php if($nb_commandes > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $nb_commandes ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>


        </div>
    </div>
</nav>
