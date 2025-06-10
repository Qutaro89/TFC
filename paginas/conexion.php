<?php
function error(){
    console.log("Error al leer el fichero");
}
function conectar(){
    $datos = simplexml_load_file("config.xml");
    if($datos == false){
        error();
    }
    $host=$datos->xpath('//host')[0];
    $bdname=$datos->xpath('//nombrebase')[0];
    $user=$datos->xpath('//user')[0];
    $ps=$datos->xpath('//pw')[0];
    $datos_conexion = 'mysql:dbname='.$bdname.';host='.$host;
    $conexion=new PDO($datos_conexion,$user,$ps);
    return $conexion;
}