<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="recursos/img/RAYO.png">
    <title>Taquilla entradas | Rayo Vallecano</title>
    <style>
        a{
            outline: none;
            text-decoration: none;
            color: white;
        }#cabecera{
            position: fixed;
            background-color: red;
            color: white;
            border-collapse: collapse;
            height: 15%;
            width: 100%;
            top: 0%;
            left: 0%;
            font-size: 15px;
            z-index: 1000;
        }
        #cabecera td{
            width: 9%;
            text-align: center;
            font-size: 15px;
            padding: 5px;
        }
        #cabecera a {
            font-size: 15px;
            font-weight: normal;
        }
        #cabecera strong {
            font-size: 15px;
            font-weight: bold;
        }
        #cabecera img {
            height: 55px;
            width: 55px;
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
            height:1px;
            width: 1px;
        }
        #reservada{
            border:0.01px solid black;
            background-color:yellow;
            height:1px;
            width: 1px;
            
        }
        #libre{
            border:0.01px solid black;
            background-color:lightgreen;
            height:1px;
            width: 1px;
            
        }
        #transformar{
            transform: rotateY(180deg);
        }
        
        .submit-btn {
            background-color: #e31e24;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 20px;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #b3151b;
        }

        .leyenda {
            margin: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .leyenda-item {
            display: inline-block;
            margin-right: 20px;
            font-size: 14px;
        }

        .leyenda-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border: 1px solid black;
            vertical-align: middle;
        }

        .cesped-indicador {
            text-align: center;
            margin: 20px;
            font-weight: bold;
            color: #2e7d32;
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .cesped-flecha {
            font-size: 32px;
            color: #2e7d32;
            margin-bottom: 5px;
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

            // Determinar el valor de colspan según el tipo de tribuna
            $colspan_value = 1; // Valor por defecto
            if(($_POST['TRIBUNA']=='TRIBUNA ALTA LATERAL') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL CUBIERTA') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL BAJA') OR ($_POST['TRIBUNA']=='GRADA DE PREFERENCIA') OR ($_POST['TRIBUNA']=='TRIBUNA ALTA PREFERENCIA')){
                $colspan_value = 9;
            } else if(($_POST['TRIBUNA']=='FONDO SUR')){
                $colspan_value = 8;
            } else if(($_POST['TRIBUNA']=='TRIBUNA CENTRAL')){
                $colspan_value = 7;
            } else if(($_POST['TRIBUNA']=='PALCO DE HONOR') OR ($_POST['TRIBUNA']=='PALCO CENTRAL')){
                $colspan_value = 3;
            }

            $butacas=$bd->prepare("SELECT ID_BUTACA, ZONA_BUTACA, PUERTA_BUTACA, PRECIO_BUTACA FROM BUTACAS WHERE ZONA_BUTACA = :zona");
            $butacas->execute(array(':zona'=>$_POST['TRIBUNA']));
            $estado_butacas=$bd->prepare("SELECT ID_BUTACA, ESTADO_BUTACA, ID_PARTIDO FROM BUTACA_PARTIDO WHERE ID_PARTIDO = :partido");
            $estado_butacas->execute(array(':partido'=>$_POST['id_partido']));
            $limite=$butacas->rowCount();
            $ocupadas=$estado_butacas->fetchAll();
            $limite=$butacas->rowCount();
            $contador=0;
            $pedido=0;
            $estado=0;
            /*
                $estado==0 butaca disponible
                $estado==1 butaca reservada
                $estado==2 butaca ocupada
                */
            $filas=$butacas->fetchAll();
            //tribunas ALTA LATERAL LATERAL CUBIERTA LATERAL BAJA GRADA DE PREFERENCIA Y ALTA PREFERENCIA
    if(($_POST['TRIBUNA']=='TRIBUNA ALTA LATERAL') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL CUBIERTA') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL BAJA') OR ($_POST['TRIBUNA']=='GRADA DE PREFERENCIA') OR ($_POST['TRIBUNA']=='TRIBUNA ALTA PREFERENCIA')){
            echo "<td> <table id='A'>";
            for($i=0;$i<9;$i++){
            echo "<tr>";
                for($b=0;$b<12;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".$pedido."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    }foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='C'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    }foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='E'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    } foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='G'>";
            for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    } foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</tr>";
            }
            echo"</table></td><td><table id='I'>";
            for($i=0;$i<9;$i++){
            echo "<tr>";
                for($b=0;$b<12;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i==1){
                        echo "</tr><tr><td colspan=4></td>";
                        for($b=0;$b<7;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i==2){
                        echo "</tr><tr><td colspan='3'></td>";
                        for($b=0;$b<8;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i==3){
                        echo "</tr><tr><td colspan=2></td>";
                        for($b=0;$b<9;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i>=4 && $i <7){
                        echo "</tr><tr><td colspan=1></td>";
                        for($b=0;$b<10;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i>=7){
                        echo "</tr><tr><td></td>";
                        for($b=0;$b<10;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
        }
        echo"</table></td><td><table id='B'>";
        for($i=0;$i<11;$i++){
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
        }
        echo"</table></td><td><table id='C'>";
        for($i=0;$i<11;$i++){
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
        }
        echo"</table></td><td><table id='D'>";
        for($i=0;$i<11;$i++){
            if($i==10){
                echo "<tr><td></td>";
                for($b=0;$b<9;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "<td></td></tr>";
            }else{
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
            }
        }
        echo"</table></td><td><table id='D'>";
        for($i=0;$i<11;$i++){
            if($i==10){
                echo "<tr><td></td>";
                for($b=0;$b<9;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "<td></td></tr>";
            }else{
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
            }
        }
        echo"</table></td><td><table id='E'>";
        for($i=0;$i<11;$i++){
            echo "<tr>";
                for($b=0;$b<11;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='transformar'";
            for($i=0;$i<10;$i++){
                    if($i==0){
                        echo "<tr><td colspan=6></td>";
                        for($b=0;$b<5;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i==1){
                        echo "</tr><tr><td colspan=4></td>";
                        for($b=0;$b<7;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i==2){
                        echo "</tr><tr><td colspan='3'></td>";
                        for($b=0;$b<8;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i==3){
                        echo "</tr><tr><td colspan=2></td>";
                        for($b=0;$b<9;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i>=4 && $i <7){
                        echo "</tr><tr><td colspan=1></td>";
                        for($b=0;$b<10;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            }
                    if($i>=7){
                        echo "</tr><tr><td></td>";
                        for($b=0;$b<10;$b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    }foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    if($contador != ($limite-1)){
                    echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
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
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    }foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</tr>";
        }
        echo "</table></td><td><table id='F'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='B'>";
        for($i=0;$i<4;$i++){
            echo "<tr>";
                if($i==0){
                    echo"<td></td>";
                    for($b=0; $b<4; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo"<td></td>";
                for($b=0; $b<2; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo"<td></td>";
                for($b=0; $b<4; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }echo "</tr>";
                }
                if($i==1){
                    echo "<td></td>";
                    for($b=0; $b<3; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "<td colspan=6></td>";
                for($b=0; $b<3; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "<td></td></tr>";
                }
                if($i==2){
                    echo "<td></td>";
                    for($b=0; $b<3; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "<td colspan=6></td>";
                for($b=0; $b<3; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "<td></td></tr>";
                }
                if($i==3){
                echo "<td></td>";
                for($b=0; $b<12; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</td></tr>";
                }
        }
        echo "</table></td><td><table id='C'>";
        for($i=0;$i<6;$i++){
            echo "<tr>";
                for($b=0; $b<3; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
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
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
        }
        echo "</table></td><td><table id='B'>";
        for($i=0;$i<8;$i++){
            echo "<tr>";
                for($b=0; $b<12; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
                echo "</td></tr>";
                
        }
        echo "</table></td><td><table id='C'>";
        for($i=0;$i<8;$i++){
            echo "<tr>";
                for($b=0; $b<3; $b++){
                    foreach($ocupadas as $ocupada){
                        if($filas[$contador]['ID_BUTACA']==$ocupada['ID_BUTACA']){
                            
                            if($ocupada['ESTADO_BUTACA']=='RESERVADA'){$estado=1;}
                            if($ocupada['ESTADO_BUTACA']=='OCUPADA'){$estado=2;}
                        }
                    }
                    if($contador != ($limite-1)){
                        if($estado==0){
                        $pedido++;
                            echo"<td id='libre'><input  type='checkbox' name='butaca".($pedido+1)."' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                        $estado=0;
                        
                        }else if($estado==1){
                        $pedido++;
                            echo"<td id='reservada'></td>";
                        $estado=0;

                        }else if($estado==2){
                        $pedido++;
                            echo"<td id='ocupada'></td>";
                        $estado=0;

                        }
                    $contador++;}
                }
            echo "</tr>";
        }
        echo "</table></td>";
    }
        ?>
        <tr>
            <td colspan="<?php echo $colspan_value; ?>">
                <div class="cesped-indicador">
                    <div class="cesped-flecha">↓</div>
                    <div>Dirección del Campo</div>
                </div>
                <div class="leyenda">
                    <div class="leyenda-item">
                        <span class="leyenda-color" style="background-color: lightgreen;"></span>
                        Disponible
                    </div>
                    <div class="leyenda-item">
                        <span class="leyenda-color" style="background-color: yellow;"></span>
                        Reservada
                    </div>
                    <div class="leyenda-item">
                        <span class="leyenda-color" style="background-color: red;"></span>
                        Ocupada
                    </div>
                </div>
            </td>
        </tr>
        <tr><td colspan="<?php echo $colspan_value; ?>" style="text-align: center;"><input type='submit' class="submit-btn" value="Seleccionar Butacas"></td></tr>
    </table>
    </form>
</body>
</html>