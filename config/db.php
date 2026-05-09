<?php
$host = "127.0.0.1";
$dbname = "db_voitures";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>