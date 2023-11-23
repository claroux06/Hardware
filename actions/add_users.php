<?php 
require_once 'cfg.php';
$sql ='INSERT INTO users(UID,Access) VALUES(:UID,:Access)';

$pre = $pdo->prepare($sql);
$pre->bindParam('UID',htmlspecialchars($_POST['UID']));
$pre->bindParam('Access',htmlspecialchars($_POST['Access']));
$pre->execute();

header('Location: ../index.php');
?>