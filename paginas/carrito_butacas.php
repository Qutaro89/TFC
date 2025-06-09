<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de entradas</title>
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
        #partido{visibility: hidden;}
        #tribuna{visibility: hidden;}
        #contenedor{
            position: absolute;
            top: 20%;
            width: 90%;
            left: 5%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        #contenedor tr:first-child {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 1.1em;
        }
        #contenedor td{
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #contenedor tr:last-child td {
            border-bottom: none;
            font-weight: bold;
            background-color: #f8f8f8;
        }
        #contenedor tr:not(:first-child):hover {
            background-color: #f5f5f5;
        }
        td table{
            border-collapse: collapse;
        }
        .total-row {
            font-size: 1.2em;
            color: #E21921;
        }
        .botones-navegacion {
            position: absolute;
            top: 85%;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
            padding: 20px;
            background-color: white;
        }
        .boton {
            padding: 15px 30px;
            margin: 0 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .boton-atras {
            background-color: #666;
            color: white;
        }
        .boton-atras:hover {
            background-color: #555;
        }
        .boton-pago {
            background-color: #E21921;
            color: white;
        }
        .boton-pago:hover {
            background-color: #c41820;
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
            <td>Partido</td>
            <td>Fecha y Hora</td>
            <td>Butaca</td>
            <td>Tribuna</td>
            <td>Puerta</td>
            <td>Precio</td>
        </tr>
        <?php
            $total=0;
            $entrada=0;
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
                        $fechaHora = new DateTime($partido['FECHA_HORA_PARTIDO']);
                        $fecha_es = $fechaHora->format('d/m/Y');
                        $hora_es = $fechaHora->format('H:i');
                        echo"<tr>";
                        echo"<td>".$partido['EQUIPO_LOCAL']." VS ".$partido['EQUIPO_VISITANTE']."</td>";
                        echo"<td>".$fecha_es." - ".$hora_es."</td>";
                        echo"<td>".$_POST['butaca'.$entrada.'']."</td>";
                        echo"<td>".$butaca['ZONA_BUTACA']."</td>";
                        echo"<td>".$butaca['PUERTA_BUTACA']."</td>";
                        echo"<td>".$butaca['PRECIO_BUTACA']."€</td></tr>";
                        $total=$total+$butaca['PRECIO_BUTACA'];
                        $sql="INSERT INTO `tfc`.`BUTACA_PARTIDO`(`ID_BUTACA`, `ID_PARTIDO`, `ESTADO_BUTACA`) VALUES('".$_POST['butaca'.$entrada.'']."','".$partido['ID_PARTIDO']."','RESERVADA')";
                        $insertar=$bd->query($sql);
                        $entrada++;
                    }
                }}}
                echo"<tr class='total-row'><td colspan=5>TOTAL:</td><td>".$total."€</td></tr>";
                ?>
        </table>
    <div class="botones-navegacion">
        <form method="POST" action="butacas.php" style="display: inline;">
            <input type="hidden" name="id_partido" value="<?php echo $_POST['id_partido']; ?>">
            <input type="hidden" name="TRIBUNA" value="<?php echo $_POST['TRIBUNA']; ?>">
            <?php
            // Mantener las butacas seleccionadas
            foreach($_POST as $key => $value) {
                if(strpos($key, 'butaca') === 0) {
                    echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                }
            }
            ?>
            <button type="submit" class="boton boton-atras">← Volver a selección de butacas</button>
        </form>
        <form method="POST" action="pago.php" style="display: inline;">
            <input type="hidden" name="id_partido" value="<?php echo $_POST['id_partido']; ?>">
            <input type="hidden" name="TRIBUNA" value="<?php echo $_POST['TRIBUNA']; ?>">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <?php
            // Mantener las butacas seleccionadas
            foreach($_POST as $key => $value) {
                if(strpos($key, 'butaca') === 0) {
                    echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                }
            }
            ?>
            <button type="submit" class="boton boton-pago">Proceder al pago →</button>
        </form>
    </div>