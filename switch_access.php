<?php
require_once "cfg.php";

// Vérifiez si les clés existent dans $_POST
if (isset($_POST['UID'])) {
    // Récupérez la valeur actuelle de Access depuis la base de données
    $stmt = $pdo->prepare("SELECT Access FROM users WHERE UID = :UID");
    $stmt->bindParam(":UID", $_POST['UID']);
    $stmt->execute();
    $currentAccess = $stmt->fetchColumn();

    // Basculement entre 0 et 1
    $newAccess = ($currentAccess == 0) ? 1 : 0;

    // Mettez à jour la base de données avec la nouvelle valeur
    $sql = "UPDATE users SET Access = :Access WHERE UID = :UID";
    $pre = $pdo->prepare($sql);
    $pre->bindParam(":UID", $_POST['UID']);
    $pre->bindParam(":Access", $newAccess);
    $pre->execute();
}

header("Location:../index.php");
exit();
?>
