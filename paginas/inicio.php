<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Web Oficial | Rayo Vallecano</title>
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
        #carrusel{
            display: flex;
            margin-top: 10%;
            height: 600px;
            width: 900px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            justify-self: center;
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
                <td style="background-color: blueviolet;"><a href="seleccionarPartido.php"> Compra tus entradas</a></td>
                <td colspan="2"></td>
                <?php
                session_start();
                if(!isset($_SESSION['login'])){
                    echo"<td><a href='login.php'>Iniciar sesión</a></td>";
                }
                if(isset($_SESSION['login'])){
                    echo "<td><a href='area_personal.php'>Área personal</a></td>";
                    echo "<td><a href='cerrarsesion.php'>Cerrar sesión</a></td>";
                }
                ?>
                <td style="background-color: black;"></td>
            </tr>
            <tr>
                <td><img src="recursos/img/main-logo.png" height="55px" width="55px"></td>
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
    <div id="carrusel"></div>
</body>
<script src="recursos/carrusel.js"></script>
</html>