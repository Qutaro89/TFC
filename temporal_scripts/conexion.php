<?php
function error(){
    echo "Error al leer el fichero";
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
    echo "<p> se ha creado con exito la conexion con ".$datos_conexion."<br>Administrador ".$user." |pw ".$ps."</p>";
    return $conexion;
}