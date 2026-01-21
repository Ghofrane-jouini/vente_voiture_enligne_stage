<?php
include "../auth/auth.php";
include "../config/db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id > 0){
    $stmt = $conn->prepare("DELETE FROM voiture WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: ../index.php");
exit;
?>
