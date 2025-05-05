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
        }
        #contenedor td{
            height:1%;
            width: auto;
        }
        td table{
            border-collapse:collapse;
        }
        td table td{
            border:0.01px solid black;
            background-color:lightblue;
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
                    echo"<td><a href='login.php'>Iniciar sesión</a></td>";
                }else{
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
    <form method="POST" id="fondo" action="butacas.php">
        <?php
        error_reporting (E_ALL);
        require("conexion.php");
        $bd=conectar();
        echo "<input id='partido' name='id_partido' type='text' value='".$_POST["id_partido"]."'>";
        echo "<input id='tribuna' name='TRIBUNA' type='text' value='".$_POST["TRIBUNA"]."'>";
        
        $butacas=$bd->prepare("SELECT ID_BUTACA, ZONA_BUTACA, PUERTA_BUTACA, PRECIO_BUTACA FROM BUTACAS WHERE ZONA_BUTACA = :zona");
        $butacas->execute(array(':zona'=>$_POST['TRIBUNA']));
        $limite=$butacas->rowCount();
        $contador=0;
        $filas=$butacas->fetchAll();
        if(($_POST['TRIBUNA']=='TRIBUNA ALTA LATERAL') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL CUBIERTA') OR ($_POST['TRIBUNA']=='TRIBUNA LATERAL BAJA') OR ($_POST['TRIBUNA']=='GRADA DE PREFERENCIA') OR
        ($_POST['TRIBUNA']=='TRIBUNA ALTA PREFERENCIA')){
        echo "<td> <table id='A'>";
        for($i=0;$i<9;$i++){
            echo "<tr>";
                for($b=0;$b<12;$b++){
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
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
                    }
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
                }
                echo "</tr>";
        }
        echo"</table></td><td><table id='C'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
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
                    }
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
                }
                echo "</tr>";
        }
        echo"</table></td><td><table id='E'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
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
                    }
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
                }
                echo "</tr>";
        }
        echo"</table></td><td><table id='G'>";
        for($i=0;$i<9;$i++){
            echo"<tr>";
                for($b=0; $b<26; $b++){
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
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
                    }
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
                }
                echo "</tr>";
        }
        echo"</table></td><td><table id='I'>";
        for($i=0;$i<9;$i++){
            echo "<tr>";
                for($b=0;$b<12;$b++){
                    echo"<td><input type='checkbox' value='".$filas[$contador]['ID_BUTACA']."'></td>";
                    $contador++;
                }
            echo "</tr>";
        }
        echo "</table> </td>";
    }

        
        ?>
    </table>
</body>
</html>