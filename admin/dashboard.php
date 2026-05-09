<?php  
include "../auth/auth.php";
include "../config/db.php";
include "../includes/header.php";

//  Nombre total de voitures
$stmt = $conn->query("SELECT COUNT(*) as total_voitures FROM voiture");
$total_voitures = $stmt->fetch(PDO::FETCH_ASSOC)['total_voitures'];

//  Voiture la plus chère
$stmt = $conn->query("SELECT marque, modele, prix FROM voiture ORDER BY prix DESC LIMIT 1");
$most_expensive = $stmt->fetch(PDO::FETCH_ASSOC);
//  Voiture la moins chère
$stmt = $conn->query("SELECT marque, modele, prix FROM voiture ORDER BY prix ASC LIMIT 1");
$least_expensive_car = $stmt->fetch(PDO::FETCH_ASSOC);

//  Voiture la plus ajoutée en favoris
$stmt = $conn->query("
    SELECT v.marque, v.modele, COUNT(f.voiture_id) AS total
    FROM favoris f
    JOIN voiture v ON v.id = f.voiture_id
    GROUP BY f.voiture_id
    ORDER BY total DESC
    LIMIT 1
");
$most_favorite = $stmt->fetch(PDO::FETCH_ASSOC);

//  Distribution selon l'énergie
$stmt = $conn->query("SELECT energie, COUNT(*) as total FROM voiture GROUP BY energie");
$energie_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

//  Distribution selon la boîte
$stmt = $conn->query("SELECT boite, COUNT(*) as total FROM voiture GROUP BY boite");
$boite_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

//  Dernières voitures ajoutées
$stmt = $conn->query("SELECT * FROM voiture ORDER BY date_ajout DESC LIMIT 5");
$latest_cars = $stmt->fetchAll();

//  Toutes les voitures pour le tableau complet
$stmt = $conn->query("SELECT * FROM voiture");
$voitures = $stmt->fetchAll();

//  Statistiques utilisateurs
$stmt = $conn->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $conn->query("SELECT COUNT(*) as recent_users FROM users WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$recent_users = $stmt->fetch(PDO::FETCH_ASSOC)['recent_users'];

//  Statistiques ventes agence
$stmt = $conn->query("SELECT COUNT(*) AS total_ventes, SUM(prix_vente) AS ca_agence FROM vente_agence");
$sales_stats = $stmt->fetch(PDO::FETCH_ASSOC);
$total_ventes = $sales_stats['total_ventes'] ?? 0;
$ca_agence = $sales_stats['ca_agence'] ?? 0;

//  Voitures restantes (selon quantite)
$stmt = $conn->query("SELECT SUM(quantite) AS voitures_restantes FROM voiture");
$voitures_restantes = $stmt->fetch(PDO::FETCH_ASSOC)['voitures_restantes'] ?? 0;

//  Voiture la plus chère vendue
$stmt = $conn->query("SELECT marque, modele, prix_vente FROM vente_agence ORDER BY prix_vente DESC LIMIT 1");
$most_expensive_sold = $stmt->fetch(PDO::FETCH_ASSOC);

//  Voiture la moins chère vendue
$stmt = $conn->query("SELECT marque, modele, prix_vente FROM vente_agence ORDER BY prix_vente ASC LIMIT 1");
$least_expensive_sold = $stmt->fetch(PDO::FETCH_ASSOC);

//  Dernières ventes
$stmt = $conn->query("SELECT * FROM vente_agence ORDER BY date_vente DESC LIMIT 5");
$latest_sales = $stmt->fetchAll();

?>

<link rel="stylesheet" href="../assets/css/global.css"><a href="../index.php" class="btn mt-3">← Retour</a>

<div class="container py-5">
    <h3 class="mb-4"> Gestion des voitures</h3>

    <!-- Cards statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <h4>Total Voitures</h4>
                <p class="stat-number"><?= $total_voitures ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <h4>Voiture la plus chère</h4>
                <p><?= $most_expensive['marque'].' '.$most_expensive['modele'] ?></p>
                <p class="stat-number"><?= number_format($most_expensive['prix'],3,'.',' ') ?> DT</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <h4>Voiture la moins chère</h4>
                <?php if($least_expensive_car): ?>
                    <p><?= $least_expensive_car['marque'].' '.$least_expensive_car['modele'] ?></p>
                    <p class="stat-number"><?= number_format($least_expensive_car['prix'],3,'.',' ') ?> DT</p>
                <?php else: ?>
                    <p>Aucune voiture</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <h4>Plus favorite</h4>
                <?php if($most_favorite): ?>
                    <p><?= $most_favorite['marque'].' '.$most_favorite['modele'] ?></p>
                    <p class="stat-number"><?= $most_favorite['total'] ?> </p>
                <?php else: ?>
                    <p>Aucune</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistiques utilisateurs -->
        <div class="col-md-3">
            <div class="card stat-card">
                <h4>Utilisateurs</h4>
                <p>Total: <?= $total_users ?></p>
                <p>Inscrits cette semaine: <?= $recent_users ?></p>
            </div>
        </div>
    </div>

    <!--  Nouvelles cards: ventes agence -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <h4>Total ventes agence </h4>
                <p class="stat-number"><?= $total_ventes ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <h4>Chiffre d’affaires </h4>
                <p class="stat-number"><?= number_format($ca_agence,3,'.',' ') ?> DT</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <h4>Quantite totale des Voitures </h4>
                <p class="stat-number"><?= $voitures_restantes ?></p>
            </div>
        </div>
    </div>

    <!--  Cars vendues : plus chère et moins chère -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card stat-card">
                <h4>Voiture la plus chère vendue </h4>
                <?php if($most_expensive_sold): ?>
                    <p><?= $most_expensive_sold['marque'].' '.$most_expensive_sold['modele'] ?></p>
                    <p class="stat-number"><?= number_format($most_expensive_sold['prix_vente'],3,'.',' ') ?> DT</p>
                <?php else: ?>
                    <p>Aucune vente</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card">
                <h4>Voiture la moins chère vendue </h4>
                <?php if($least_expensive_sold): ?>
                    <p><?= $least_expensive_sold['marque'].' '.$least_expensive_sold['modele'] ?></p>
                    <p class="stat-number"><?= number_format($least_expensive_sold['prix_vente'],3,'.',' ') ?> DT</p>
                <?php else: ?>
                    <p>Aucune vente</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Dernières ventes -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card p-3">
                <h4>Dernières ventes</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Prix Vente</th>
                        <th>Client</th>
                        <th>Vendeur</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach($latest_sales as $sale): ?>
                        <tr>
                            <td><?= $sale['marque'] ?></td>
                            <td><?= $sale['modele'] ?></td>
                            <td><?= number_format($sale['prix_vente'],3,'.',' ') ?> DT</td>
                            <td><?= htmlspecialchars($sale['nom_client']) ?></td>
                            <td><?= htmlspecialchars($sale['vendeur']) ?></td>
                            <td><?= $sale['date_vente'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Dernières voitures -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Dernières voitures ajoutées</h4>
                <?php foreach($latest_cars as $lc): ?>
                    <p><?= $lc['marque'].' '.$lc['modele'] ?> - <?= number_format($lc['prix'],3,'.',' ') ?> DT</p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Tableau des voitures -->
    <h4 class="mb-3">Gestion des Voitures</h4>

    <a href="add_car.php" class="btn btn-success mb-3">Ajouter Voiture</a>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Modèle</th>
            <th>Prix</th>
            <th>Énergie</th>
            <th>Boîte</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>

        <?php foreach($voitures as $v): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= $v['marque'] ?> <?= $v['modele'] ?></td>
                <td><?= number_format($v['prix'],3,'.',' ') ?> DT</td>
                <td><?= $v['energie'] ?></td>
                <td><?= $v['boite'] ?></td>
                <td><img src="../assets/uploads/<?= $v['image'] ?>" width="80"></td>
                <td>
                    <a href="edit_car.php?id=<?= $v['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="delete_car.php?id=<?= $v['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette voiture ?');">Supprimer</a>
                    <a href="vente_agence.php?id=<?= $v['id'] ?>" class="btn-view" style="background:purple;color:white;border:none;"> Vente agence </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="../index.php" class="btn mt-3">← Retour</a>
</div>
