<?php
session_start();
error_reporting(E_ALL);
require("conexion.php");
require_once __DIR__ . "/vendor/autoload.php";
require_once("correo.php");

if (!isset($_POST['total']) || !isset($_POST['id_partido'])) {
    header('Location: carrito_butacas.php');
    exit();
}

$total = $_POST['total'];
$id_partido = $_POST['id_partido'];

// Obtener información del partido
$bd = conectar();
$partido = $bd->prepare("SELECT * FROM PARTIDOS WHERE ID_PARTIDO = :id");
$partido->execute(array(':id' => $id_partido));
$datos_partido = $partido->fetch();

// Obtener las butacas seleccionadas
$butacas_seleccionadas = array();
foreach ($_POST as $key => $value) {
    if (strpos($key, 'butaca') === 0) {
        $butaca = $bd->prepare("SELECT ID_BUTACA, ZONA_BUTACA, PUERTA_BUTACA, PRECIO_BUTACA FROM BUTACAS WHERE ID_BUTACA = :id");
        $butaca->execute(array(':id' => $value));
        $butacas_seleccionadas[] = $butaca->fetch();
    }
}

// Guardar datos relevantes en la sesión para la generación del PDF
$_SESSION['compra_datos_partido'] = $datos_partido;
$_SESSION['compra_total'] = $total;
$_SESSION['nombre_tarjeta'] = $_POST['nombre_tarjeta'];

// Formatear fecha y hora del partido
$fechaHora = new DateTime($datos_partido['FECHA_HORA_PARTIDO']);
$fecha_es = $fechaHora->format('d/m/Y');
$hora_es = $fechaHora->format('H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="recursos/img/RAYO.png">
    <title>Confirmación de Compra | Rayo Vallecano</title>
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            padding-top: 120px;
        }
        .confirmacion {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .icono-exito {
            font-size: 48px;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }
        .titulo {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .resumen {
            background-color: #f8f8f8;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .seccion {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .seccion:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .seccion .subtitulo {
            color: #E21921;
            font-size: 1.3em;
            margin-bottom: 15px;
        }
        .detalle {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            color: #666;
        }
        .detalle .negrita {
            color: #333;
        }
        .butacas {
            margin-top: 15px;
        }
        .butaca {
            background-color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .total {
            font-size: 1.2em;
            color: #E21921;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }
        .botones {
            text-align: center;
            margin-top: 30px;
        }
        .boton {
            background-color: #E21921;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin: 0 10px;
        }
        .boton:hover {
            background-color: #c41820;
        }
        .mensaje {
            text-align: center;
            color: #666;
            margin: 20px 0;
            font-style: italic;
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
                
                <td colspan="2"></td>
                <?php
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
    <div class="confirmacion">
        <div class="icono-exito">✓</div>
        <h1 class="titulo">¡Compra Realizada con Éxito!</h1>
        
        <div class="resumen">
            <div class="seccion">
                <h2 class="subtitulo">Información del Partido</h2>
                <div class="detalle">
                    <span>Partido:</span>
                    <strong class="negrita"><?php echo $datos_partido['EQUIPO_LOCAL'] . ' vs ' . $datos_partido['EQUIPO_VISITANTE']; ?></strong>
                </div>
                <div class="detalle">
                    <span>Fecha:</span>
                    <strong class="negrita"><?php echo $fecha_es; ?></strong>
                </div>
                <div class="detalle">
                    <span>Hora:</span>
                    <strong class="negrita"><?php echo $hora_es; ?></strong>
                </div>
            </div>

            <div class="seccion">
                <h2 class="subtitulo">Butacas Seleccionadas</h2>
                <div class="butacas">
                    <?php foreach ($butacas_seleccionadas as $butaca): ?>
                    <div class="butaca">
                        <div class="detalle">
                            <span>Butaca:</span>
                            <strong class="negrita"><?php echo $butaca['ID_BUTACA']; ?></strong>
                        </div>
                        <div class="detalle">
                            <span>Zona:</span>
                            <strong class="negrita"><?php echo $butaca['ZONA_BUTACA']; ?></strong>
                        </div>
                        <div class="detalle">
                            <span>Puerta:</span>
                            <strong class="negrita"><?php echo $butaca['PUERTA_BUTACA']; ?></strong>
                        </div>
                        <div class="detalle">
                            <span>Precio:</span>
                            <strong class="negrita"><?php echo number_format($butaca['PRECIO_BUTACA'], 2); ?>€</strong>
                        </div>
                    </div>
                    <?php
                    $sql="DELETE FROM `butaca_partido` WHERE `butaca_partido`.`ID_BUTACA` = '".$butaca['ID_BUTACA']."' AND `butaca_partido`.`ID_PARTIDO` = '".$id_partido."'";
                    $insertar=$bd->query($sql);
                    $sql="INSERT INTO `tfc`.`BUTACA_PARTIDO`(`ID_BUTACA`, `ID_PARTIDO`, `ESTADO_BUTACA`) VALUES('".$butaca['ID_BUTACA']."','".$id_partido."','OCUPADA')";
                    $insertar=$bd->query($sql);
                    ?>
                    <?php endforeach; ?>
                </div>
                <div class="total">
                    Total: <?php echo number_format($total, 2); ?>€
                </div>
            </div>
        </div>

        <?php
            $usuario_correo = $_SESSION['correo'];
            $carrito_para_email = [];
            foreach ($butacas_seleccionadas as $butaca) {
                $carrito_para_email[] = [
                    'id_butaca' => $butaca['ID_BUTACA'],
                    'zona' => $butaca['ZONA_BUTACA'],
                    'puerta' => $butaca['PUERTA_BUTACA'],
                    'precio_unitario' => $butaca['PRECIO_BUTACA']
                ];
            }
            enviarCorreo($usuario_correo, $carrito_para_email, $total, $datos_partido);
        ?>

        <p class="mensaje">Recibirás un correo electrónico con los detalles de la compra de entradas.</p>
        
        <div class="botones">
            <?php foreach ($butacas_seleccionadas as $butaca): ?>
                <a href="generar_entrada.php?id_partido=<?php echo $datos_partido['ID_PARTIDO']; ?>&id_butaca=<?php echo $butaca['ID_BUTACA']; ?>" class="boton" style="margin-bottom: 10px;">Descargar Entrada Butaca <?php echo $butaca['ID_BUTACA']; ?></a>
            <?php endforeach; ?>
            <a href="inicio.php" class="boton">Volver al inicio</a>
        </div>
    </div>
</body>
</html>