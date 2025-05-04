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
        #partidos{
            margin:10%;
            border-collapse:collapse;
        }
        #partidos td{
            border:1px solid black;
            align-items: center;
            justify-content: center;
            text-align: center;
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
    <form method="POST" action="seleccionarTribuna.php">
    <table id="partidos">
        <tr>
            <td>partido</td>
            <td>fecha y hora del partido</td>
            <td></td>
    <?php
        error_reporting (E_ALL);
        require("conexion.php");
        $bd=conectar();
        $partidos=$bd->prepare("SELECT * FROM PARTIDOS");
        $partidos->execute(array());
        foreach($partidos as $partido){
            echo"<tr>
                    <td>".$partido['EQUIPO_LOCAL']."-".$partido['EQUIPO_VISITANTE']."</td>
                    <td>".$partido['FECHA_HORA_PARTIDO']."</td>
                    <td><input type='radio' id='id_partido' name='id_partido' value='".$partido["ID_PARTIDO"]."'></td>
                </tr>";
        }
    ?>
    <tr>
        <td colspan=3><input name="partido" type="submit" id="partido" value="Seleccionar tribuna"></td>
    </table >
    
    
</body>
</html>