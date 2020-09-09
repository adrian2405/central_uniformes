<?php
$host='localhost';
$user = 'root';
$password='';
$db='central_uniformes';

$conection =@mysqli_connect($host,$user,$password,$db);

if(!$conection){
    echo "error de conexion";
}
