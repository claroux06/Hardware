<?php
//config.php

session_start();

$pdo = new PDO(
<<<<<<< HEAD
    'mysql:host=localhost;dbname=ESP32-S3;',
    'root',
    'root',
=======
    'mysql:host=localhost;dbname=esp32;',
    'root',
    '1234',
>>>>>>> origin/main
    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

?>

