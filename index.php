<?php  
session_start(); 
include "includes/header.php"; 
include "config/db.php"; 

/* DATA POUR RECHERCHE */
$marques  = $conn->query("SELECT DISTINCT marque FROM voiture ORDER BY marque")->fetchAll();
$modeles  = $conn->query("SELECT DISTINCT modele FROM voiture ORDER BY modele")->fetchAll();
$energies = $conn->query("SELECT DISTINCT energie FROM voiture ORDER BY energie")->fetchAll();
$boites   = $conn->query("SELECT DISTINCT boite FROM voiture ORDER BY boite")->fetchAll();
?>
<style>
/* ===== GLOBAL ===== */
body {
    background: #0b0b0b;
    color: #ddd;
    font-family: 'Poppins', sans-serif;
}
.gold { color: #d4af37; }

/* ===== HERO ===== */
.hero-slider {
    position: relative;
    height: 85vh;
    overflow: hidden;
}
.hero-slider .slide {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    animation: slideFade 18s infinite;
}
.hero-slider .slide:nth-child(2) { animation-delay: 6s; }
.hero-slider .slide:nth-child(3) { animation-delay: 12s; }

@keyframes slideFade {
    0% { opacity: 0; }
    10% { opacity: 1; }
    30% { opacity: 1; }
    40% { opacity: 0; }
    100% { opacity: 0; }
}

.hero-content {
    position: relative;
    z-index: 2;
    top: 50%;
    transform: translateY(-50%);
}
.btn-hero {
    display: inline-block;
    margin: 8px;
    padding: 12px 22px;
    background: #d4af37;
    color: #000;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
}
.btn-hero:hover { background: #b8962e; }

/* ===== SEARCH ===== */
.search-section {
    margin-top: -60px;
    background: #111;
    padding: 30px;
    border-radius: 16px;
}
.search-box {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
}
.search-box select,
.search-box button {
    padding: 10px;
    border-radius: 8px;
    border: none;
    outline: none;
}
.search-box button {
    background: #d4af37;
    font-weight: bold;
}

/* ===== CAR CARD ===== */
.car-card {
    background: #141414;
    border-radius: 18px;
    overflow: hidden;
    transition: 0.3s;
    height: 100%;
    position: relative;
}
.car-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
}
.car-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}
.car-info { padding: 16px; }
.car-info h5 {
    font-weight: 600;
    margin-bottom: 6px;
}

/* ===== BADGE DISPONIBILITÉ ===== */
.badge-stock {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 10px;
    border-radius: 12px;
    font-weight: 600;
    color: #fff;
}
.badge-available { background-color: #28a745; } /* vert */
.badge-out { background-color: #dc3545; } /* rouge */

/* ===== BUTTONS ===== */
.btn-view {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 14px;
    border-radius: 6px;
    background: #222;
    color: #d4af37;
    text-decoration: none;
    border: 1px solid #d4af37;
}
.btn-view:hover {
    background: #d4af37;
    color: #000;
}

/* ===== ACTIONS ===== */
.car-actions {
    display: flex;
    gap: 10px;
    margin-top: 12px;
}
.btn-fav,
.btn-buy {
    flex: 1;
    padding: 8px;
    font-size: 14px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 500;
}
.btn-fav {
    background: transparent;
    color: #ff4d6d;
    border: 1px solid #ff4d6d;
}
.btn-fav:hover {
    background: #ff4d6d;
    color: #fff;
}
.btn-buy {
    background: #d4af37;
    color: #000;
}
.btn-buy:hover { background: #b8962e; }

/* ===== RESPONSIVE ===== */
@media(max-width: 768px) {
    .hero-slider { height: 65vh; }
    .car-img { height: 180px; }
}
</style>

<!-- HERO SLIDER -->
<div class="hero-slider">
    <div class="slide" style="background-image: url('img/hero1.jpg');"></div>
    <div class="slide" style="background-image: url('img/hero2.jpeg');"></div>
    <div class="slide" style="background-image: url('img/hero3.jpg');"></div>

    <div class="hero-content text-center">
        <h1>Bienvenue sur <span class="gold">MG AUTO</span></h1>
        <p>Découvrez les meilleurs modèles en Tunisie</p>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <!-- ADMIN: Commandes -->
            <a href="admin/commandes.php" class="btn-hero">
                Commandes des clients🔔
            </a>
            
            <a href="admin/dashboard.php" class="btn-hero">📊 Gestion des voitures</a>
            <a href="admin/add_car.php" class="btn-hero">Ajouter voiture 🚗</a>
            <a href="auth/logout.php" class="btn-hero">Déconnexion🔐</a>

        <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
            <a href="cars/list.php" class="btn-hero">Toutes les voitures 🚗</a>
            <a href="favoris.php" class="btn-hero">Mes Favoris ❤️</a>
            <a href="auth/logout.php" class="btn-hero">Déconnexion 🔐</a>

        <?php else: ?>
            <a href="cars/list.php" class="btn-hero">Voir voitures 🚗</a>
            <a href="auth/login.php" class="btn-hero">Connexion 🔐</a>
        <?php endif; ?>
    </div>
</div>

<!-- SEARCH -->
<section class="search-section container">
    <h2 class="gold">Trouvez votre voiture idéale 🚗</h2>
    <form method="GET" action="cars/list.php" class="search-box">
        <select name="marque">
            <option value="">Marque</option>
            <?php foreach ($marques as $m): ?>
                <option value="<?= $m['marque'] ?>"><?= $m['marque'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="modele">
            <option value="">Modèle</option>
            <?php foreach ($modeles as $mo): ?>
                <option value="<?= $mo['modele'] ?>"><?= $mo['modele'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="prix_max">
            <option value="">Prix max</option>
            <option value="20000">20 000 DT</option>
            <option value="50000">50 000 DT</option>
            <option value="100000">100 000 DT</option>
        </select>

        <select name="energie">
            <option value="">Énergie</option>
            <?php foreach ($energies as $e): ?>
                <option value="<?= $e['energie'] ?>"><?= $e['energie'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="boite">
            <option value="">Boîte</option>
            <?php foreach ($boites as $b): ?>
                <option value="<?= $b['boite'] ?>"><?= $b['boite'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">🔍 Rechercher</button>
    </form>
</section>

<!-- CARS -->
<section class="cars container">
    <h2 class="gold text-center mb-5">Nos modèles disponibles</h2>

    <div class="row g-4">
        <?php
        $stmt = $conn->prepare("SELECT * FROM voiture ORDER BY id DESC");
        $stmt->execute();
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cars as $v):
            $image = "assets/uploads/" . $v['image'];
            $stock_badge = $v['quantite'] > 0 ? 
                           '<div class="badge-stock badge-available">'.$v['quantite'].' dispo</div>' :
                           '<div class="badge-stock badge-out">Out of Stock</div>';
        ?>
        <div class="col-md-4">
            <div class="car-card">
                <?= $stock_badge ?>
                <img src="<?= $image ?>" class="car-img">

                <div class="car-info">
                    <h5><?= $v['marque'] ?> <?= $v['modele'] ?></h5>
                    <p class="gold"><?= number_format($v['prix']) ?> DT</p>

                    <a href="cars/details.php?id=<?= $v['id'] ?>" class="btn-view">
                        Voir plus
                    </a>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin/vente_agence.php?id=<?= $v['id'] ?>"
                           class="btn-view"
                           style="background:#d9534f;color:#fff;border:none;">
                            🏢 Vente agence
                        </a>
                        <a href="admin/edit_car.php?id=<?= $v['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="admin/delete_car.php?id=<?= $v['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette voiture ?');">Supprimer</a>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                        <div class="car-actions">
                            <a href="favoris.php?action=add&id=<?= $v['id'] ?>" class="btn-fav">
                                ❤️ Favoris
                            </a>

                            <form action="cars/buy.php" method="POST">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <button type="submit" class="btn-buy">
                                    🛒 Acheter
                                </button>
                            </form>
                        </div>

                    <?php elseif(!isset($_SESSION['role'])): ?>
                        <div class="text-muted small mt-2">
                            Connectez-vous pour acheter ou ajouter aux favoris
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>
