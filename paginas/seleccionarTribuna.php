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
        #AL{
            position: absolute;
            top:20%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 50%;
        }
        #LC{
            position: absolute;
            top:25%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 50%;
        }
        #LB{
            position: absolute;
            top:30%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 50%;
        }
        #FS{
            position: absolute;
            top:30%;
            left: 30%;
            background-color: lightblue;
            font-size:90%;
            height: 25%;
            width: 5%;
            border-top-left-radius: 100%;
            border-bottom-left-radius:100%;
        }
        #campo{
            background-color: green;
            height: 16%;
            width: 50%;
            position: absolute;
            top: 35%;
            left: 35%;
        }
        #GP{
            position: absolute;
            top:50%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 50%;
        }
        #TCa{
            position: absolute;
            top:55%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 20%;
        }
        #TCa{
            position: absolute;
            top:55%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 20%;
        }
        #PC{
            position: absolute;
            top:57%;
            left: 55%;
            background-color: lightblue;
            font-size:90%;
            height: 3%;
            width: 10%;
        }
        #PH{
            position: absolute;
            top:55%;
            left: 55%;
            background-color: lightblue;
            font-size:90%;
            height: 2.5%;
            width: 10%;
        }
        #TCb{
            position: absolute;
            top:55%;
            left: 65%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 20%;
        }
        #GAP{
            position: absolute;
            top:60%;
            left: 35%;
            background-color: lightblue;
            font-size:90%;
            height: 5%;
            width: 50%;
        }
        #AL:hover{background-color: #36f1f7;}#LC:hover{background-color: #36f1f7;}#LB:hover{background-color: #36f1f7;}#FS:hover{background-color: #36f1f7;}
        #GP:hover{background-color: #36f1f7;}#TCa:hover{background-color: #36f1f7;}#PC:hover{background-color: #36f1f7;}#PH:hover{background-color: #36f1f7;}
        #TCb:hover{background-color: #36f1f7;}#GAP:hover{background-color: #36f1f7;}
        #partido{visibility: hidden;}
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
    <form method="POST" id="fondo" action="seleccionarTribuna.php">
        <?php echo "<input name='id_partido' type='text' value='".$_POST["id_partido"]."'>"; ?>
        <input type="submit" name="TRIBUNA" value="TRIBUNA ALTA LATERAL" id="AL">
        <input type="submit" name="TRIBUNA" value="TRIBUNA LATERAL CUBIERTA" id="LC">
        <input type="submit" name="TRIBUNA" value="TRIBUNA LATERAL BAJA" id="LB">
        <input type="submit" name="TRIBUNA" value="FONDO SUR" id="FS">
        <div id="campo"></div>
        <input type="submit" name="TRIBUNA" value="GRADA DE PREFERENCIA" id="GP">
        <input type="submit" name="TRIBUNA" value="TRIBUNA CENTRAL" id="TCa">
        <input type="submit" name="TRIBUNA" value="PALCO CENTRAL" id="PC">
        <input type="submit" name="TRIBUNA" value="PALCO HONOR" id="PH">
        <input type="submit" name="TRIBUNA" value="TRIBUNA CENTRAL" id="TCb">
        <input type="submit" name="TRIBUNA" value="GRADA ALTA PREFERENCIA" id="GAP">
</body>
</html>