<?php 
include "../auth/auth.php";
include "../config/db.php";
include "../includes/header.php";

$id = $_GET['id'];
// Récupérer les infos de la voiture
$stmt = $conn->prepare("SELECT * FROM voiture WHERE id=?");
$stmt->execute([$id]);
$v = $stmt->fetch();

if (!$v) {
    die("Voiture introuvable");
}

if (isset($_POST['modifier'])) {

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

    // Gérer l'upload de l'image
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $image);
    } else {
        $image = $v['image'];
    }

    // Mettre à jour la voiture dans la base de données
    $stmt2 = $conn->prepare("
        UPDATE voiture SET
        marque=?, modele=?, prix=?, energie=?, garantie=?,
        nombre_places=?, nombre_portes=?, cylindres=?,
        boite=?, transmission=?, puissance_fiscale=?,
        image=?, promo=?, nouveaute=?, quantite=?
        WHERE id=?
    ");

    $stmt2->execute([
        $marque, $modele, $prix, $energie, $garantie,
        $nombre_places, $nombre_portes, $cylindres,
        $boite, $transmission, $puissance_fiscale,
        $image, $promo, $nouveaute, $quantite, $id
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/global.css">
<div class="add-car-container">
    <h3> Modifier la voiture</h3>

    <form method="POST" enctype="multipart/form-data">

        <div class="grid">
            <input name="marque" value="<?= $v['marque'] ?>" required>
            <input name="modele" value="<?= $v['modele'] ?>" required>
            <input type="number" name="prix" value="<?= $v['prix'] ?>" required>

            <select name="energie">
                <option><?= $v['energie'] ?></option>
                <option>Essence</option>
                <option>Diesel</option>
                <option>Hybride</option>
                <option>Électrique</option>
            </select>

            <input type="number" name="garantie" value="<?= $v['garantie'] ?>" placeholder="Garantie">
            <input type="number" name="nombre_places" value="<?= $v['nombre_places'] ?>">
            <input type="number" name="nombre_portes" value="<?= $v['nombre_portes'] ?>">
            <input type="number" name="cylindres" value="<?= $v['cylindres'] ?>">

            <select name="boite">
                <option><?= $v['boite'] ?></option>
                <option>Manuelle</option>
                <option>Automatique</option>
            </select>

            <select name="transmission">
                <option><?= $v['transmission'] ?></option>
                <option>Avant</option>
                <option>Arrière</option>
                <option>4x4</option>
            </select>

            <input type="number" name="puissance_fiscale" value="<?= $v['puissance_fiscale'] ?>">

            <input type="number" name="quantite" value="<?= $v['quantite'] ?>" min="1" required>
        </div>

        <p style="margin-top:15px; color:#aaa;">Image actuelle :</p>
        <img src="../assets/uploads/<?= $v['image'] ?>" width="120" style="border-radius:10px">

        <label class="file-label">
             Changer l’image
            <input type="file" name="image">
        </label>

        <div class="options">
            <label>
                <input type="checkbox" name="promo" <?= $v['promo'] ? 'checked' : '' ?>> Promo
            </label>
            <label>
                <input type="checkbox" name="nouveaute" <?= $v['nouveaute'] ? 'checked' : '' ?>> Nouveauté
            </label>
        </div>

        <button name="modifier">Enregistrer les modifications</button>
    </form>

    <a href="dashboard.php" class="back">← Retour dashboard</a>
    <a href="../index.php" class="btn mt-3">← Retour to home page</a>
</div>

<?php include "../includes/footer.php"; ?>
