<?php
require 'admin/Database.php';

$serveur          = "localhost";
$utilisateur      = "root";
$motDePasse       = "";
$base             = "computer04"; 
$dir_backup       = __DIR__ . DIRECTORY_SEPARATOR . "DB_Backup";
$table            = 'brand, cardex, items';
if(!is_dir($dir_backup)){
    mkdir($dir_backup, 0777, true);
}

  exec("mysqldump --host={$serveur} --user={$utilisateur} --password={$motDePasse} {$base} --result-file={$dir_backup} > ".$dir_backup."-".$base."-".date("d-m-Y-H\hi").".sql");


header('Location: index.php');