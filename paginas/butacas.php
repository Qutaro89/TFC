<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taquilla entradas</title>
    <style>
        a{
            outline: none;
            text-decoration: none;
            color: white;
        }
        #cabecera{
            position: fixed;
            background-color: red;
            color: white;
            border-collapse: collapse;
            height: 15%;
            width: 100%;
            top: 0%;
            left: 0%;
            font-size: 15px;
        }
        #cabecera td{
            width: 9%;
            text-align: center;
            font-size: 85%;
        }
        #partido{visibility: hidden;}
        #tribuna{visibility: hidden;}
        #contenedor{
            position: absolute;
            top:20%;
            height:20%;
            width: 100%;
            left:-5px;
        }
        #contenedor td{
            height:0.1%;
            width: auto;
        }
        td table{
            border-collapse:collapse;
        }
        #ocupada{
            border:0.01px solid black;
            background-color:red;
            height:0.1%;
            width: auto;
        }
        #reservada{
            border:0.01px solid black;
            background-color:yellow;
            height:0.1%;
            width: auto;
        }
        #libre{
            border:0.01px solid black;
            background-color:lightgreen;
            height:0.1%;
            width: auto;
        }
        #transformar{
            transform: rotateY(180deg);
        }
        
    </style>
</head>
<body>
    <header>
        <table id="cabecera">
            <tr>
                <td style="background-color: black;"></td>
                <td>Tienda Online</td>
                <td><a href="https://www.rayovallecano.es/tienda-oficial"> Tienda Oficial</a></td>
                <td><a href="https://www.digimobil.es/fibra-movil?fibra=1320&movil=1326&utm_source=">Contrata Digi</a></td>
                <td><a href="https://www.ffluzon.org/colabora/">Colabora con la Fundación Luzón</a></td>
                <td colspan="3"></td>
                <?php
                if(!isset($_SESSION['login'])){
                    header('Location: login.php');
                    exit();
                }else{
                    echo "<td><a href='area_personal.php'>Área personal</a></td>";
                    echo "<td><a href='cerrarsesion.php'>Cerrar sesión</a></td>";
                }
                ?>
                <td style="background-color: black;"></td>
            </tr>
            <tr>
                <td><a href="inicio.php"><img src="recursos/img/main-logo.png" height="55px" width="55px"></a></td>
                <td><strong>Actualidad</strong></td>
                <td><strong>Club</strong></td>
                <td><strong>Primer equipo</strong></td>
                <td><strong>Agenda</strong></td>
                <td><strong>Afición</strong></td>
                <td><strong>Fútbol Base</strong></td>
                <td><strong>Femenino</strong></td>
                <td><strong>Fundación</strong></td>
                <td><a href="https://www.rayovallecano.es/noticias/empieza-la-inscripcion-para-las-prueba-de-cantera"><strong>Pruebas cantera 25/26</strong></a></td>
            </tr>
        </table>
    </header>
    <hr>
    <table id='contenedor'>
    <form method="POST" id="fondo" action="carrito_butacas.php">
            <?php
            error_reporting (E_ALL);
            require("conexion.php");
            $bd=conectar();
            echo "<input id='partido' name='id_partido' type='text' value='".$_POST["id_partido"]."'>";
            echo "<input id='tribuna' name='TRIBUNA' type='text' value='".$_POST["TRIBUNA"]."'>";
            $butacas=$bd->prepare("SELECT ID_BUTACA, ZONA_BUTACA, PUERTA_BUTACA, PRECIO_BUTACA FROM BUTACAS WHERE ZONA_BUTACA = :zona");
            $butacas->execute(array(':zona'=>$_POST['TRIBUNA']));
            $estado_butacas=$bd->prepare("SELECT ID_BUTACA, ESTADO_BUTACA, ID_PARTIDO FROM BUTACA_PARTIDO WHERE ID_PARTIDO = :partido");
            $estado_butacas->execute(array(':partido'=>$_POST['id_partido']));
            $limite=$butacas->rowCount();
            $contador=0;
            $pedido=0;
            $reserva=false;
            $ocupada=false;
            $filas=$butacas->fetchAll();
            //tribunas ALTA LATERAL LATERAL CUBIERTA LATERAL BAJA GRADA DE PREFERENCIA Y ALTA PREFERENCIA
    if(($_POST['TRIBUNA']=='TRIBUNA ALTA LATERAL') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL CUBIERTA') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL BAJA') OR ($_POST['TRIBUNA']=='GRADA DE PREFERENCIA') OR ($_POST['TRIBUNA']=='TRIBUNA ALTA PREFERENCIA')){
            echo "<td> <table id='A'>";
            for($i=0;$i<9;$i++){
            echo "<tr>";
                for($b=0;$b<12;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        $contador++;
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $pedido++;
                    }else{echo"</tr>";}
                }
            echo "</tr>";
            }
            echo "</table></td><td><table id='B'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0;$b<23;$b++){
                    if($i>3){
                        if($b==6){
                            echo"<td colspan=11 style=' background-color:black;'></td>";
                            $b=17;
                        }
                    }foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='C'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
            echo "</table></td><td><table id='D'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0;$b<23;$b++){
                    if($i>3){
                        if($b==6){
                            echo"<td colspan=11 style=' background-color:black;'></td>";
                            $b=17;
                        }
                    }foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='E'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
            echo "</table></td><td><table id='F'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0;$b<23;$b++){
                    if($i>3){
                        if($b==6){
                            echo"<td colspan=11 style=' background-color:black;'></td>";
                            $b=17;
                        }
                    } foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='G'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
            echo "</table></td><td><table id='H'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0;$b<23;$b++){
                    if($i>3){
                        if($b==6){
                            echo"<td colspan=11 style=' background-color:black;'></td>";
                            $b=17;
                        }
                    } foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='I'>";
            for($i=0;$i<9;$i++){
            echo "<tr>";
                for($b=0;$b<12;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
            echo "</table></td>";
            }
    // TRIBUNA FONDO SUR
    if(($_POST['TRIBUNA']=='FONDO SUR')){
        echo "<td><table id='A'>";
            for($i=0;$i<10;$i++){
                    if($i==0){
                        echo "<tr><td colspan=6></td>";
                        for($b=0;$b<5;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i==1){
                        echo "</tr><tr><td colspan=4></td>";
                        for($b=0;$b<7;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i==2){
                        echo "</tr><tr><td colspan='3'></td>";
                        for($b=0;$b<8;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i==3){
                        echo "</tr><tr><td colspan=2></td>";
                        for($b=0;$b<9;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i>=4 && $i <7){
                        echo "</tr><tr><td colspan=1></td>";
                        for($b=0;$b<10;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i>=7){
                        echo "</tr><tr><td></td>";
                        for($b=0;$b<10;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
        }
        echo"</table></td><td><table id='B'>";
        for($i=0;$i<11;$i++){
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo"</table></td><td><table id='C'>";
        for($i=0;$i<11;$i++){
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo"</table></td><td><table id='D'>";
        for($i=0;$i<11;$i++){
            if($i==10){
                echo "<tr><td></td>";
                for($b=0;$b<9;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "<td></td></tr>";
            }else{
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
        }
        echo"</table></td><td><table id='D'>";
        for($i=0;$i<11;$i++){
            if($i==10){
                echo "<tr><td></td>";
                for($b=0;$b<9;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "<td></td></tr>";
            }else{
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
        }
        echo"</table></td><td><table id='D'>";
        for($i=0;$i<11;$i++){
            if($i==10){
                echo "<tr><td></td>";
                for($b=0;$b<9;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "<td></td></tr>";
            }else{
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
            }
        }
        echo"</table></td><td><table id='E'>";
        for($i=0;$i<11;$i++){
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='transformar'";
            for($i=0;$i<10;$i++){
                    if($i==0){
                        echo "<tr><td colspan=6></td>";
                        for($b=0;$b<5;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i==1){
                        echo "</tr><tr><td colspan=4></td>";
                        for($b=0;$b<7;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i==2){
                        echo "</tr><tr><td colspan='3'></td>";
                        for($b=0;$b<8;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i==3){
                        echo "</tr><tr><td colspan=2></td>";
                        for($b=0;$b<9;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i>=4 && $i <7){
                        echo "</tr><tr><td colspan=1></td>";
                        for($b=0;$b<10;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
                    if($i>=7){
                        echo "</tr><tr><td></td>";
                        for($b=0;$b<10;$b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            }
        }
        echo "</table></td>";
        }
    //TRIBUNA CENTRAL
    if(($_POST['TRIBUNA']=='TRIBUNA CENTRAL')){
        echo"<td><table id='A'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='B'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0;$b<23;$b++){
                    if($i>3){
                        if($b==6){
                            echo"<td colspan=11 style=' background-color:black;'></td>";
                            $b=17;
                        }
                    }foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</tr>";
        }
        echo "</table></td><td><table id='C'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                                $reserva = true;

                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                                    $b++;
                            }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='separador'>";
        for($i=0;$i<9;$i++){
            echo "<tr>";
            for($b=0;$b<26;$b++){
                echo"<td style='background-color:gray;'></td>";
            }
        }
        echo "</table></td><td><table id='D'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='E'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0;$b<23;$b++){
                    if($i>3){
                        if($b==6){
                            echo"<td colspan=11 style=' background-color:black;'></td>";
                            $b=17;
                        }
                    }foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</tr>";
        }
        echo "</table></td><td><table id='F'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td>";
    }
    // TRIBUNA PALCO DE HONOR
    if(($_POST['TRIBUNA']=='PALCO DE HONOR')){
        echo "<td><table id='A'>";
        for($i=0;$i<6;$i++){
            echo "<tr>";
                for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='B'>";
        for($i=0;$i<4;$i++){
            echo "<tr>";
                if($i==0){
                    echo"<td></td>";
                    for($b=0; $b<4; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo"<td></td>";
                for($b=0; $b<2; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo"<td></td>";
                for($b=0; $b<4; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }echo "</tr>";
                }
                if($i==1){
                    echo "<td></td>";
                    for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "<td colspan=6></td>";
                for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "<td></td></tr>";
                }
                if($i==2){
                    echo "<td></td>";
                    for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "<td colspan=6></td>";
                for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "<td></td></tr>";
                }
                if($i==3){
                echo "<td></td>";
                for($b=0; $b<12; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</td></tr>";
                }
        }
        echo "</table></td><td><table id='C'>";
        for($i=0;$i<6;$i++){
            echo "<tr>";
                for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td>";
    }
    //TRIBUNA PALCO CENTRAL
    if(($_POST['TRIBUNA']=='PALCO CENTRAL')){
        echo "<td><table id='A'>";
        for($i=0;$i<8;$i++){
            echo "<tr>";
                for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='B'>";
        for($i=0;$i<8;$i++){
            echo "<tr>";
                for($b=0; $b<12; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
                echo "</td></tr>";
                
        }
        echo "</table></td><td><table id='C'>";
        for($i=0;$i<8;$i++){
            echo "<tr>";
                for($b=0; $b<3; $b++){
                    foreach($estado_butacas as $butac4){
                        if($filas[$contador]['ID_BUTACA']== $butac4['ID_BUTACA']){
                            if($butac4['ESTADO_BUTACA']=='RESERVADA'){
                            $reserva = true;
                            }
                            if($butac4['ESTADO_BUTACA']=='OCUPADA'){
                            $ocupada = true;
                        }
                    if($reserva == true){
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        echo "<td id='reservada'></td>";
                        $reserva=false;
                    }
                    if($ocupada == true){
                        echo "<td id='ocupada'></td>";
                        $contador=$contador+1;
                        $pedido++;
                        $b++;
                        $ocupada=false;
                    }
                    }
                    }
                    if($contador != $limite){
                        echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $pedido++;
                    $contador=$contador+1;
                    }
                }
            echo "</tr>";
        }
        echo "</table></td>";
    }
        ?>
        <tr><td><input type='submit'></td></tr>
    </table>
    </form>
</body>
</html>