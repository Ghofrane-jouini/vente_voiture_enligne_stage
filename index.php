<?php 
session_start(); 
include "includes/header.php"; 
include "config/db.php"; 
// Récupérer les valeurs uniques pour les filtres de recherche
$marques  = $conn->query("SELECT DISTINCT marque FROM voiture ORDER BY marque")->fetchAll();
$modeles  = $conn->query("SELECT DISTINCT modele FROM voiture ORDER BY modele")->fetchAll();
$energies = $conn->query("SELECT DISTINCT energie FROM voiture ORDER BY energie")->fetchAll();
$boites   = $conn->query("SELECT DISTINCT boite FROM voiture ORDER BY boite")->fetchAll();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Space+Grotesk:wght@500;700;800&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: #f0f4ff;
    color: #1e1b4b;
    font-family: 'Outfit', sans-serif;
    overflow-x: hidden;
}

/* ══════════════════════════════════════════
   HERO
══════════════════════════════════════════ */
.hero {
    position: relative;
    min-height: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 5px 75px;
    overflow: hidden;
    text-align: center;
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 30%, rgba(139,92,246,.28) 0%, transparent 55%),
        radial-gradient(ellipse 70% 50% at 80% 70%, rgba(236,72,153,.2)  0%, transparent 55%),
        radial-gradient(ellipse 60% 70% at 50% 10%, rgba(59,130,246,.18) 0%, transparent 55%),
        radial-gradient(ellipse 50% 40% at 10% 80%, rgba(6,182,212,.15)  0%, transparent 50%);
    animation: meshMove 12s ease-in-out infinite alternate;
    z-index: 0;
}

.hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(99,102,241,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99,102,241,.05) 1px, transparent 1px);
    background-size: 60px 60px;
    z-index: 0;
}

@keyframes meshMove {
    0%   { opacity: .85; transform: scale(1)   rotate(0deg); }
    50%  { opacity: 1;   transform: scale(1.08) rotate(1.5deg); }
    100% { opacity: .9;  transform: scale(1.04) rotate(-1deg); }
}

.hero-inner {
    position: relative;
    z-index: 2;
    max-width: 860px;
    width: 100%;
}

.hero-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: clamp(2.2rem, 6vw, 4.5rem);
    font-weight: 700;
    line-height: 1.0;
    letter-spacing: -.03em;
    color: #1e1b4b;
    margin-bottom: 10px;
    animation: fadeUp .8s .1s both;
}

.hero-title .brand {
    display: block;
    background: linear-gradient(135deg, #f59e0b 0%, #f43f5e 40%, #a855f7 80%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200%;
    animation: fadeUp .8s .1s both, gradShift 4s ease infinite alternate;
}

@keyframes gradShift {
    from { background-position: 0% 50%; }
    to   { background-position: 100% 50%; }
}

.hero-sub {
    font-size: clamp(1rem, 2.5vw, 1.25rem);
    color: rgba(30,27,75,.5);
    margin-bottom: 44px;
    animation: fadeUp .8s .2s both;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 44px;
    animation: fadeUp .8s .3s both;
    flex-wrap: wrap;
}

.stat-item { text-align: center; }

.stat-num {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #f59e0b, #f43f5e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}

.stat-label {
    font-size: .75rem;
    color: rgba(30,27,75,.4);
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-top: 3px;
}

.hero-btns {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    animation: fadeUp .8s .4s both;
}

.hbtn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 30px;
    border-radius: 50px;
    font-family: 'Outfit', sans-serif;
    font-size: .9rem;
    font-weight: 700;
    letter-spacing: .03em;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform .25s, box-shadow .25s;
    white-space: nowrap;
}
.hbtn:hover { transform: translateY(-3px); }

.hbtn-main {
    background: linear-gradient(135deg, #f43f5e, #ec4899, #a855f7);
    color: #fff;
    box-shadow: 0 8px 30px rgba(236,72,153,.45);
}
.hbtn-main:hover { box-shadow: 0 14px 40px rgba(168,85,247,.6); color: #fff; }

.hbtn-blue {
    background: linear-gradient(135deg, #6366f1, #3b82f6);
    color: #fff;
    box-shadow: 0 8px 28px rgba(99,102,241,.4);
}
.hbtn-blue:hover { box-shadow: 0 14px 36px rgba(59,130,246,.55); color: #fff; }

.hbtn-green {
    background: linear-gradient(135deg, #10b981, #06b6d4);
    color: #fff;
    box-shadow: 0 8px 26px rgba(16,185,129,.38);
}
.hbtn-green:hover { color: #fff; }

.hbtn-ghost {
    background: rgba(99,102,241,.08);
    border: 1.5px solid rgba(99,102,241,.25);
    color: #6366f1;
    backdrop-filter: blur(10px);
}
.hbtn-ghost:hover { background: rgba(99,102,241,.15); color: #4f46e5; }

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ══════════════════════════════════════════
   SEARCH
══════════════════════════════════════════ */
.search-section {
    position: relative;
    z-index: 10;
    padding: 0 16px;
    margin-top: -72px;
}

.search-card {
    max-width: 1100px;
    margin: 0 auto;
    background: #fff;
    border: 1.5px solid rgba(168,85,247,.12);
    border-radius: 24px;
    padding: 32px 36px;
    box-shadow: 0 20px 60px rgba(99,102,241,.12);
}

.search-label {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 700;
    color: rgba(30,27,75,.6);
    margin-bottom: 16px;
    letter-spacing: .06em;
    text-transform: uppercase;
    font-size: .78rem;
}

.search-grid {
    display: grid;
    grid-template-columns: repeat(5,1fr) auto;
    gap: 12px;
    align-items: center;
}

.search-grid select {
    width: 100%;
    padding: 13px 36px 13px 14px;
    border-radius: 12px;
    border: 1px solid rgba(168,85,247,.2);
    background: #f8f5ff;
    color: #1e1b4b;
    font-family: 'Outfit', sans-serif;
    font-size: .88rem;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23a855f7' stroke-width='1.6' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 13px center;
    transition: .22s;
}
.search-grid select option { background: #fff; color: #1e1b4b; }
.search-grid select:focus {
    border-color: #a855f7;
    box-shadow: 0 0 0 3px rgba(168,85,247,.18);
    background-color: rgba(168,85,247,.06);
}

.search-btn {
    padding: 13px 28px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f43f5e, #ec4899, #a855f7);
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    transition: .25s;
    box-shadow: 0 6px 24px rgba(236,72,153,.38);
}
.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(168,85,247,.55);
}

/* ══════════════════════════════════════════
   CARS SECTION
══════════════════════════════════════════ */
.cars-section {
    padding: 80px 16px 100px;
    max-width: 1200px;
    margin: 0 auto;
}

.cars-section-inner {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}

/* ── Titre vertical à gauche ── */
.section-side-title {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
    width: 48px;
}

.section-side-title .section-heading {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: -.02em;
    writing-mode: vertical-lr;
    transform: rotate(180deg);
    white-space: nowrap;
    line-height: 1;
    margin: 0;
}

.section-side-title .section-heading span {
    background: linear-gradient(180deg, #a855f7 0%, #6366f1 50%, #1e1b4b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-side-title .section-sub {
    font-family: 'Space Grotesk', sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #a855f7;
    writing-mode: vertical-lr;
    transform: rotate(180deg);
    white-space: nowrap;
    opacity: .7;
}

.section-side-title::before {
    content: '';
    width: 2px;
    height: 40px;
    background: linear-gradient(180deg, transparent, #a855f7);
    border-radius: 2px;
}

.section-side-title::after {
    content: '';
    width: 2px;
    height: 40px;
    background: linear-gradient(180deg, #a855f7, transparent);
    border-radius: 2px;
}

.cars-grid-wrap {
    flex: 1;
    min-width: 0;
}

/* ══════════════════════════════════════════
   CAR CARDS
══════════════════════════════════════════ */
.cars-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 24px;
}

.car-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    border: 1.5px solid rgba(168,85,247,.1);
    box-shadow: 0 4px 22px rgba(99,102,241,.08);
    transition: transform .28s, box-shadow .28s, border-color .28s;
    position: relative;
    display: flex;
    flex-direction: column;
}

.car-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, #f43f5e, #ec4899, #a855f7, #6366f1, #06b6d4);
    opacity: 0;
    transition: .28s;
}

.car-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 18px 50px rgba(99,102,241,.16);
    border-color: rgba(168,85,247,.3);
}
.car-card:hover::before { opacity: 1; }

.car-thumb {
    position: relative;
    height: 210px;
    overflow: hidden;
    flex-shrink: 0;
}
.car-thumb img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s;
}
.car-card:hover .car-thumb img { transform: scale(1.07); }

.car-thumb::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(8,9,15,.6) 0%, transparent 50%);
    pointer-events: none;
}

.stock-pill {
    position: absolute;
    top: 12px; left: 12px;
    z-index: 2;
    padding: 5px 13px;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #fff;
    backdrop-filter: blur(8px);
}
.stock-yes { background: linear-gradient(135deg,#10b981,#06b6d4); box-shadow: 0 3px 14px rgba(16,185,129,.45); }
.stock-no  { background: linear-gradient(135deg,#ef4444,#f43f5e); box-shadow: 0 3px 14px rgba(239,68,68,.45); }

.car-body {
    padding: 20px 20px 22px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.car-name {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e1b4b;
    margin-bottom: 5px;
    line-height: 1.3;
}

.car-price {
    font-weight: 800;
    font-size: 1.15rem;
    background: linear-gradient(135deg,#f59e0b,#f43f5e,#ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 14px;
}

.cbtn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .82rem;
    letter-spacing: .03em;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform .22s, box-shadow .22s;
    margin-bottom: 7px;
}
.cbtn:last-child { margin-bottom: 0; }
.cbtn:hover { transform: translateY(-2px); }

.cbtn-blue {
    background: linear-gradient(135deg,#6366f1,#3b82f6,#06b6d4);
    color: #fff;
    box-shadow: 0 4px 16px rgba(99,102,241,.3);
}
.cbtn-blue:hover { box-shadow: 0 8px 26px rgba(99,102,241,.5); color: #fff; }

.cbtn-pink {
    background: linear-gradient(135deg,#f43f5e,#ec4899);
    color: #fff;
    box-shadow: 0 4px 16px rgba(236,72,153,.3);
}
.cbtn-pink:hover { box-shadow: 0 8px 26px rgba(236,72,153,.5); color: #fff; }

.cbtn-orange {
    background: linear-gradient(135deg,#f59e0b,#ef4444);
    color: #fff;
    box-shadow: 0 4px 14px rgba(245,158,11,.3);
}
.cbtn-orange:hover { color: #fff; }

.cbtn-red {
    background: linear-gradient(135deg,#ef4444,#f43f5e);
    color: #fff;
    box-shadow: 0 4px 14px rgba(239,68,68,.3);
}
.cbtn-red:hover { color: #fff; }

.admin-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 7px;
}
.admin-row .cbtn { margin-bottom: 0; }

.card-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 2px;
}

.btn-fav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 8px;
    border-radius: 10px;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: .82rem;
    text-decoration: none;
    background: rgba(236,72,153,.1);
    color: #ec4899;
    border: 1.5px solid rgba(236,72,153,.25);
    transition: .22s;
}
.btn-fav:hover {
    background: rgba(236,72,153,.2);
    border-color: #ec4899;
    transform: translateY(-1px);
    color: #ec4899;
}

.btn-buy {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 8px;
    border-radius: 10px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .82rem;
    background: linear-gradient(135deg,#f43f5e,#ec4899,#a855f7);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: .22s;
    box-shadow: 0 4px 14px rgba(236,72,153,.3);
    width: 100%;
}
.btn-buy:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(168,85,247,.5); }

.login-hint {
    margin-top: 8px;
    padding: 10px 12px;
    background: #f8f5ff;
    border-radius: 10px;
    border: 1px dashed rgba(168,85,247,.2);
    color: #9ca3af;
    font-size: .78rem;
    text-align: center;
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 1024px) {
    .cars-grid { grid-template-columns: repeat(2,1fr); }
    .search-grid { grid-template-columns: repeat(3,1fr); }
    .search-btn { grid-column: 1 / -1; }
}

@media (max-width: 768px) {
    .hero { padding: 40px 20px 80px; min-height: 360px; }
    .hero-stats { gap: 28px; }
    .stat-num { font-size: 1.6rem; }
    .search-section { margin-top: -48px; }
    .search-card { padding: 24px 20px 28px; border-radius: 18px; }
    .search-grid { grid-template-columns: repeat(2,1fr); }
    .cars-section { padding: 60px 12px 72px; }
}

@media (max-width: 576px) {
    .hero-title { font-size: clamp(2.4rem,11vw,3.4rem); }
    .hero-stats { display: none; }
    .cars-grid { grid-template-columns: 1fr; }
    .search-grid { grid-template-columns: 1fr; }
    .hbtn { padding: 12px 22px; font-size: .85rem; }
}
</style>

<!-- ══ HERO ══ -->
<section class="hero">
    <div class="hero-inner">
        <h1 class="hero-title">
            Votre prochaine voiture<br>
            <span class="brand">commence ici</span>
        </h1>

        <p class="hero-sub">Découvrez les meilleurs modèles disponibles en Tunisie — neufs, garantis, livrés.</p>

        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-num">100+</div>
                <div class="stat-label">Véhicules</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">15+</div>
                <div class="stat-label">Marques</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">5★</div>
                <div class="stat-label">Service</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24h</div>
                <div class="stat-label">Livraison</div>
            </div>
        </div>

        <div class="hero-btns">
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin/commandes.php"  class="hbtn hbtn-main">Commandes</a>
                <a href="admin/dashboard.php"  class="hbtn hbtn-blue">Dashboard</a>
                <a href="admin/add_car.php"    class="hbtn hbtn-green">Ajouter</a>
                <a href="auth/logout.php"      class="hbtn hbtn-ghost">Déconnexion</a>

            <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <a href="favoris.php"     class="hbtn hbtn-blue">Mes Favoris</a>
                <a href="auth/logout.php" class="hbtn hbtn-ghost">Déconnexion</a>

            <?php else: ?>
                <a href="auth/login.php" class="hbtn hbtn-blue">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ══ SEARCH ══ -->
<div class="search-section">
    <div class="search-card">
        <p class="search-label">Trouvez votre voiture idéale</p>
        <form method="GET" action="cars/list.php" class="search-grid">
            <select name="marque">
                <option value="">Marque</option>
                <?php foreach($marques as $m): ?>
                    <option value="<?= $m['marque'] ?>"><?= $m['marque'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="modele">
                <option value="">Modèle</option>
                <?php foreach($modeles as $mo): ?>
                    <option value="<?= $mo['modele'] ?>"><?= $mo['modele'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="prix_max">
                <option value="">Prix max</option>
                <option value="50000">50 000 DT</option>
                <option value="80000">80 000 DT</option>
                <option value="100000">100 000 DT</option>
            </select>

            <select name="energie">
                <option value="">Énergie</option>
                <?php foreach($energies as $e): ?>
                    <option value="<?= $e['energie'] ?>"><?= $e['energie'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="boite">
                <option value="">Boîte</option>
                <?php foreach($boites as $b): ?>
                    <option value="<?= $b['boite'] ?>"><?= $b['boite'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="search-btn">Rechercher</button>
        </form>
    </div>
</div>

<!-- ══ CARS ══ -->
<div class="cars-section">
    <div class="cars-section-inner">

        <!-- Titre vertical à gauche -->
        <div class="section-side-title">
            <h2 class="section-heading"><span>Nos modèles disponibles</span></h2>
            <p class="section-sub">Sélectionnez votre véhicule parmi notre collection exclusive</p>
        </div>

        <!-- Grille voitures -->
        <div class="cars-grid-wrap">
            <div class="cars-grid">
        <?php
        $stmt = $conn->prepare("SELECT * FROM voiture ORDER BY id DESC");
        $stmt->execute();
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($cars as $v):
            $image = "assets/uploads/" . $v['image'];
            $pill  = $v['quantite'] > 0
                ? '<span class="stock-pill stock-yes">✓ '.$v['quantite'].' dispo</span>'
                : '<span class="stock-pill stock-no">✗ Indisponible</span>';
        ?>
        <div class="car-card">
            <div class="car-thumb">
                <?= $pill ?>
                <img src="<?= $image ?>" alt="<?= htmlspecialchars($v['marque'].' '.$v['modele']) ?>">
            </div>

            <div class="car-body">
                <p class="car-name"><?= htmlspecialchars($v['marque'].' '.$v['modele']) ?></p>
                <p class="car-price"><?= number_format($v['prix'],0,',',' ') ?> DT</p>

                <a href="cars/details.php?id=<?= $v['id'] ?>" class="cbtn cbtn-blue">
                    Voir les détails
                </a>

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/vente_agence.php?id=<?= $v['id'] ?>" class="cbtn cbtn-pink">
                        Vente agence
                    </a>
                    <div class="admin-row">
                        <a href="admin/edit_car.php?id=<?= $v['id'] ?>" class="cbtn cbtn-orange">Modifier</a>
                        <a href="admin/delete_car.php?id=<?= $v['id'] ?>" class="cbtn cbtn-red"
                           onclick="return confirm('Supprimer cette voiture ?')">Supprimer</a>
                    </div>

                <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                    <div class="card-actions">
                        <a href="favoris.php?action=add&id=<?= $v['id'] ?>" class="btn-fav">❤️ Favoris</a>
                        <form action="cars/buy.php" method="POST" style="display:contents">
                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                            <button type="submit" class="btn-buy">🛒 Acheter</button>
                        </form>
                    </div>

                <?php elseif(!isset($_SESSION['role'])): ?>
                    <div class="login-hint">🔒 Connectez-vous pour acheter</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>