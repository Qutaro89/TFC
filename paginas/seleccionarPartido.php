<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de partidos | Rayo Vallecano</title>
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
        .contenedor-partidos {
            margin: 10% auto 0 auto;
            max-width: 1000px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px 30px 30px 30px;
            border: 1.5px solid #222;
            position: relative;
        }
        .titulo-partidos {
            text-align: center;
            font-size: 2.8em;
            font-family: 'Arial Black', Arial, sans-serif;
            font-weight: bold;
            color: #111;
            margin-bottom: 0.2em;
            margin-top: 0.2em;
            letter-spacing: 1px;
            position: relative;
        }
        .subrayado-rojo {
            display: block;
            width: 85%;
            height: 6px;
            background: #d32f2f;
            margin: 0 auto 2.5em auto;
            border-radius: 3px;
        }
        .tarjeta-partido {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            padding: 25px 0 25px 0;
            background: transparent;
        }
        .tarjeta-partido:last-child {
            border-bottom: none;
        }
        .escudos {
            display: flex;
            align-items: center;
            min-width: 120px;
            justify-content: center;
            gap: 18px;
        }
        .escudos img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .info-partido {
            flex: 1;
            padding-left: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .nombre-partido {
            font-size: 1.25em;
            font-family: 'Arial Black', Arial, sans-serif;
            font-weight: bold;
            margin-bottom: 5px;
            color: #111;
        }
        .detalle-partido {
            color: #222;
            font-size: 1em;
            margin-bottom: 2px;
        }
        .boton-seleccionar {
            background: #d32f2f;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
            font-weight: 500;
            margin-left: 30px;
        }
        .boton-seleccionar:hover {
            background: #b71c1c;
        }
        @media (max-width: 700px) {
            .contenedor-partidos { padding: 10px; }
            .tarjeta-partido { flex-direction: column; align-items: flex-start; }
            .boton-seleccionar { margin-left: 0; margin-top: 10px; width: 100%; }
            .escudos { margin-bottom: 10px; }
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
    <form method="POST" action="seleccionarTribuna.php">
    <div class="contenedor-partidos">
        <div class="titulo-partidos">PARTIDOS</div>
        <span class="subrayado-rojo"></span>
    <?php
        error_reporting (E_ALL);
        require("conexion.php");
        $bd=conectar();
        $partidos=$bd->prepare("SELECT * FROM PARTIDOS");
        $partidos->execute(array());
        foreach($partidos as $partido){
            $escudo_local = "recursos/img/RAYO.png";
            $escudo_visitante1 = "recursos/img/GETAFE.png";
            $escudo_visitante2 = "recursos/img/BETIS.png";
            $escudo_visitante3 = "recursos/img/MALLORCA.png";

            if ($partido['EQUIPO_VISITANTE'] == "GETAFE") {
                $escudo_visitante = $escudo_visitante1;
            } elseif ($partido['EQUIPO_VISITANTE'] == "BETIS") {
                $escudo_visitante = $escudo_visitante2;
            } else {
                $escudo_visitante = $escudo_visitante3;
            }

            preg_match('/J-(\\d+)/', $partido['ID_PARTIDO'], $coincidencias);
            $jornada = isset($coincidencias[1]) ? $coincidencias[1] : '';

            $fechaHora = new DateTime($partido['FECHA_HORA_PARTIDO']);
            $fecha_es = $fechaHora->format('d/m/Y');
            $hora_es = $fechaHora->format('H:i');

            echo "<div class='tarjeta-partido'>
                    <div class='escudos'>
                        <img src='".$escudo_local."' alt='".$partido['EQUIPO_LOCAL']."'>
                        <img src='".$escudo_visitante."' alt='".$partido['EQUIPO_VISITANTE']."'>
                    </div>
                    <div class='info-partido'>
                        <div class='nombre-partido'>".$partido['EQUIPO_LOCAL']." - ".$partido['EQUIPO_VISITANTE']."</div>
                        <div class='detalle-partido'>Jornada ".$jornada." - La Liga</div>
                        <div class='detalle-partido'>".$fecha_es." - ".$hora_es."</div>
                    </div>
                    <div>
                        <button class='boton-seleccionar' type='submit' name='id_partido' value='".$partido["ID_PARTIDO"]."'>Comprar entradas</button>
                    </div>
                </div>";
        }
    ?>
    </div>
    </form>
</body>
</html>
