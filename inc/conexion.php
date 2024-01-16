<?php
$servidor = "mysql:dbname=bd_restaurante;host:localhost";
$dbuser="root";
$pass="";

try {
    $conn = new PDO($servidor,$dbuser,$pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

} catch (Exception $e){
    echo "Error en la conexión con la base de datos: " . $e->getMessage();
    die();
}