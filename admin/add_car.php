<?php 
include "../auth/auth.php";
include "../config/db.php";
include "../includes/header.php";

// 🔹 Ajouter voiture
if (isset($_POST['ajouter'])) {

    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $prix = $_POST['prix'];
    $energie = $_POST['energie'];
    $garantie = $_POST['garantie'];
    $nombre_places = $_POST['nombre_places'];
    $nombre_portes = $_POST['nombre_portes'];
    $cylindres = $_POST['cylindres'];
    $boite = $_POST['boite'];
    $transmission = $_POST['transmission'];
    $puissance_fiscale = $_POST['puissance_fiscale'];
    $quantite = isset($_POST['quantite']) ? (int) $_POST['quantite'] : 1;
    $promo = isset($_POST['promo']) ? 1 : 0;
    $nouveaute = isset($_POST['nouveaute']) ? 1 : 0;

    // image
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../assets/uploads/" . $image);

    // Insert dans BDD
    $stmt = $conn->prepare("
        INSERT INTO voiture
        (marque, modele, prix, energie, garantie, nombre_places, nombre_portes,
         cylindres, boite, transmission, puissance_fiscale, image, promo, nouveaute, quantite)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $marque, $modele, $prix, $energie, $garantie,
        $nombre_places, $nombre_portes, $cylindres,
        $boite, $transmission, $puissance_fiscale,
        $image, $promo, $nouveaute, $quantite
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/add_car.css">

<div class="add-car-container">
    <h3>➕ Ajouter une voiture</h3>

    <form method="POST" enctype="multipart/form-data">

        <div class="grid">
            <input type="text" name="marque" placeholder="Marque" required>
            <input type="text" name="modele" placeholder="Modèle" required>
            <input type="number" name="prix" placeholder="Prix (DT)" required>

            <select name="energie" required>
                <option value="">Énergie</option>
                <option>Essence</option>
                <option>Diesel</option>
                <option>Hybride</option>
                <option>Électrique</option>
            </select>

            <input type="number" name="garantie" placeholder="Garantie (ans)">
            <input type="number" name="nombre_places" placeholder="Places">
            <input type="number" name="nombre_portes" placeholder="Portes">
            <input type="number" name="cylindres" placeholder="Cylindres">

            <select name="boite">
                <option value="">Boîte</option>
                <option>Manuelle</option>
                <option>Automatique</option>
            </select>

            <select name="transmission">
                <option value="">Transmission</option>
                <option>Avant</option>
                <option>Arrière</option>
                <option>4x4</option>
            </select>

            <input type="number" name="puissance_fiscale" placeholder="Puissance fiscale">

            <!-- Nouveau champ Quantité -->
            <input type="number" name="quantite" placeholder="Quantité" value="1" min="1" required>
        </div>

        <label class="file-label">
            📷 Image voiture
            <input type="file" name="image" required>
        </label>

        <div class="options">
            <label><input type="checkbox" name="promo"> Promo</label>
            <label><input type="checkbox" name="nouveaute"> Nouveauté</label>
        </div>

        <button name="ajouter">Ajouter la voiture</button>
    </form>

    <a href="dashboard.php" class="back">← Retour dashboard</a>
    <a href="../index.php" class="btn mt-3">← Retour to home page</a>

</div>

<?php include "../includes/footer.php"; ?>
