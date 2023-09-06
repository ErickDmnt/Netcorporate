
<?php
$host="localhost";/**Datos para la conexion al localhost PHPMyadmin */
$bd="netcorporatetest";
$usuario="root";
$contrasenia="";

try{
    $conexion= new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);/**Conexion al localhost */
    
}catch(Exception $ex){
    echo $ex->getMessage();
}
?>