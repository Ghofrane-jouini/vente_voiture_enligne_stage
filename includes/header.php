<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nb_commandes = 0;

require_once $_SERVER['DOCUMENT_ROOT'] . "/stage/project/config/db.php";
// Si user connecté et role = user, compter le nb de commandes en attente
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    // COUNT(DISTINCT voiture_id) = nb de voitures différentes commandées en attente
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT voiture_id) 
        FROM commandes 
        WHERE user_id = ? AND statut = 'en attente'");
    $stmt->execute([$_SESSION['user_id']]);
    $nb_commandes = (int) $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MG AUTO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/stage/project/assets/css/global.css">
</head>

<body>

<nav class="mg-nav">
    <div class="mg-nav-inner">

        <!-- Logo -->
        <a class="mg-brand" href="/stage/project/index.php">
            <img src="/stage/project/assets/uploads/logoMGAUTO.jpg" alt="MG AUTO">
            <span>MG <strong>AUTO</strong></span>
        </a>

        <!-- Actions -->
        <div class="mg-nav-actions">

            <?php if (isset($_SESSION['user'])): ?>
                <span class="mg-greeting">
                    Bonjour, <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>
                </span>
                <a href="/stage/project/auth/logout.php" class="mg-btn mg-btn-ghost">
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="/stage/project/auth/login.php" class="mg-btn mg-btn-ghost">
                    Connexion
                </a>
                <a href="/stage/project/auth/register.php" class="mg-btn mg-btn-main">
                    Inscription
                </a>
            <?php endif; ?>

            <!-- Mes achats (user only) -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <a href="/stage/project/cars/mes_achats.php" class="mg-btn mg-btn-amber mg-achats">
                    🛒 Mes achats
                    <?php if ($nb_commandes > 0): ?>
                        <span class="mg-badge"><?= $nb_commandes ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<style>
/* ══════════════════════════════════════════
   NAVBAR — MG AUTO
══════════════════════════════════════════ */
.mg-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border-bottom: 1.5px solid rgba(168,85,247,.12);
    box-shadow: 0 2px 24px rgba(99,102,241,.08);
}

.mg-nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

/* ── Brand ── */
.mg-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex-shrink: 0;
}

.mg-brand img {
    height: 38px;
    width: 38px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(168,85,247,.25);
}

.mg-brand span {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--dark);
    letter-spacing: -.02em;
}

.mg-brand span strong {
    background: linear-gradient(135deg, var(--pink), var(--purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ── Actions ── */
.mg-nav-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.mg-greeting {
    font-family: 'Outfit', sans-serif;
    font-size: .88rem;
    color: var(--muted);
    margin-right: 4px;
}
.mg-greeting strong {
    color: var(--dark);
    font-weight: 700;
}

/* ── Nav Buttons ── */
.mg-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    border-radius: 50px;
    font-family: 'Outfit', sans-serif;
    font-size: .84rem;
    font-weight: 700;
    letter-spacing: .02em;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform .22s, box-shadow .22s;
    white-space: nowrap;
    position: relative;
}
.mg-btn:hover { transform: translateY(-2px); }

.mg-btn-main {
    background: linear-gradient(135deg, var(--pink), var(--rose), var(--purple));
    color: #fff;
    box-shadow: 0 4px 16px rgba(236,72,153,.3);
}
.mg-btn-main:hover { box-shadow: 0 8px 24px rgba(168,85,247,.45); color: #fff; }

.mg-btn-ghost {
    background: rgba(99,102,241,.07);
    border: 1.5px solid rgba(99,102,241,.2);
    color: var(--indigo);
}
.mg-btn-ghost:hover { background: rgba(99,102,241,.14); color: var(--indigo); }

.mg-btn-amber {
    background: linear-gradient(135deg, var(--amber), #f97316);
    color: #fff;
    box-shadow: 0 4px 16px rgba(245,158,11,.3);
}
.mg-btn-amber:hover { box-shadow: 0 8px 22px rgba(245,158,11,.45); color: #fff; }

/* ── Badge ── */
.mg-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    border-radius: 50px;
    background: linear-gradient(135deg, #ef4444, var(--pink));
    color: #fff;
    font-size: .68rem;
    font-weight: 800;
    box-shadow: 0 2px 8px rgba(239,68,68,.5);
    line-height: 1;
    margin-left: 2px;
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .mg-nav-inner { padding: 0 14px; height: 60px; }
    .mg-greeting { display: none; }
    .mg-btn { padding: 8px 14px; font-size: .8rem; }
    .mg-brand span { font-size: 1rem; }
}
</style>