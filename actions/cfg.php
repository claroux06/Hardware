<?php
#config.php

session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'ESP32-S3';

$pdo = new PDO(
  'mysql:host=localhost;dbname=ESP32-S3;',
  'root',
  'root',
  array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
);

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

?>