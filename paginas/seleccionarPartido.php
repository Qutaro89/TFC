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
        .partidos-container {
            margin: 10% auto 0 auto;
            max-width: 900px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 30px 20px;
            border: 2px solid #222;
        }
        .partido-card {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            padding: 20px 0;
        }
        .partido-card:last-child {
            border-bottom: none;
        }
        .escudos {
            display: flex;
            align-items: center;
            min-width: 120px;
            justify-content: center;
        }
        .escudos img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            margin: 0 8px;
        }
        .info-partido {
            flex: 1;
            padding-left: 20px;
        }
        .nombre-partido {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .detalle-partido {
            color: #666;
            font-size: 0.95em;
            margin-bottom: 2px;
        }
        .seleccionar-btn {
            background: #d32f2f;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 18px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .seleccionar-btn:hover {
            background: #b71c1c;
        }
        .radio-partido {
            margin-right: 10px;
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
    <div class="partidos-container">
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

            preg_match('/J-(\\d+)/', $partido['ID_PARTIDO'], $matches);
            $jornada = isset($matches[1]) ? $matches[1] : '';

            $fechaHora = new DateTime($partido['FECHA_HORA_PARTIDO']);
            $fecha_es = $fechaHora->format('d/m/Y');
            $hora_es = $fechaHora->format('H:i');

            echo "<div class='partido-card'>
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
                        <button class='seleccionar-btn' type='submit' name='id_partido' value='".$partido["ID_PARTIDO"]."'>Comprar entradas</button>
                    </div>
                </div>";
        }
    ?>
    </div>
    </form>
</body>
</html>
