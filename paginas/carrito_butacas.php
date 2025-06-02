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
            width:75%;
            left:1%;
            border-collapse:collapse;
        }
        #contenedor td{
            height:0.1%;
            width: auto;
            text-align:center;
            border:1px solid black;
        }
        td table{
            border-collapse:collapse;
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
        <tr>
            <td>partido</td>
            <td>fecha hora</td>
            <td>butaca</td>
            <td>tribuna</td>
            <td>puerta</td>
            <td>precio</td>
        </tr>
        <?php
            $total=0;
            $entrada=1;
            error_reporting (E_ALL);
            require("conexion.php");
            $bd=conectar();
            $partidos=$bd->prepare("SELECT * FROM PARTIDOS WHERE ID_PARTIDO = :id");
            $partidos->execute(array(':id'=>$_POST['id_partido']));
            $butacas=$bd->prepare("SELECT ID_BUTACA, ZONA_BUTACA, PUERTA_BUTACA, PRECIO_BUTACA FROM BUTACAS WHERE ZONA_BUTACA = :zona");
            $butacas->execute(array(':zona'=>$_POST['TRIBUNA']));
            $insertar=$bd->prepare("SELECT ID_BUTACA,ID_PARTIDO, ESTADO_BUTACA FROM BUTACA_PARTIDO");
            foreach ($partidos as $partido){
            foreach ($butacas as $butaca){
                if(empty($_POST["butaca".$entrada.""])){
                    $entrada++;
                }else{
                    if($butaca["ID_BUTACA"]==$_POST['butaca'.$entrada.'']){
                    $entrada++;
                    }else{
                        echo"<tr>";
                        echo"<td>".$partido['ID_PARTIDO']."</td>";
                        echo"<td>".$partido['FECHA_HORA_PARTIDO']."</td>";
                        echo"<td>".$butaca['ID_BUTACA']."</td>";
                        echo"<td>".$butaca['ZONA_BUTACA']."</td>";
                        echo"<td>".$butaca['PUERTA_BUTACA']."</td>";
                        echo"<td>".$butaca['PRECIO_BUTACA']."€</td></tr>";
                        $total=$total+$butaca['PRECIO_BUTACA'];
                        $sql="INSERT INTO `tfc`.`BUTACA_PARTIDO`(`ID_BUTACA`, `ID_PARTIDO`, `ESTADO_BUTACA`) VALUES('".$butaca['ID_BUTACA']."','".$partido['ID_PARTIDO']."','RESERVADA')";
                        $insertar=$bd->query($sql);
                        $entrada++;
                    }
                }}}
                echo"<tr><td colspan=5>TOTAL:</td><td>".$total."€</td></tr>";
