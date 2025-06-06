<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}
require_once("conexion.php");
$bd = conectar();


$correo = $_SESSION['correo'];
$usuario = $bd->prepare("SELECT NUM_USUARIO, NOMBRE_USUARIO, CORREO_USUARIO, DNI_USUARIO, TLFN_USUARIO, FECHA_ALTA_USUARIO FROM USUARIOS WHERE CORREO_USUARIO = :correo LIMIT 1");
$usuario->execute([':correo' => $correo]);
$datos_usuario = $usuario->fetch();

$num_abonado = $datos_usuario ? $datos_usuario['NUM_USUARIO'] : '';
$nombre_usuario = $datos_usuario ? $datos_usuario['NOMBRE_USUARIO'] : '';
$correo_usuario = $datos_usuario ? $datos_usuario['CORREO_USUARIO'] : '';
$dni_usuario = $datos_usuario ? $datos_usuario['DNI_USUARIO'] : '';
$tlfn_usuario = $datos_usuario ? $datos_usuario['TLFN_USUARIO'] : '';
$fecha_alta_usuario = $datos_usuario ? $datos_usuario['FECHA_ALTA_USUARIO'] : '';


$zona_butaca = '';
$puerta_butaca = '';
$id_butaca = '';
if ($num_abonado) {
    $abono = $bd->prepare("SELECT BUTACAS_ID_BUTACA FROM ABONOS WHERE USUARIOS_NUM_USUARIO = :num LIMIT 1");
    $abono->execute([':num' => $num_abonado]);
    $datos_abono = $abono->fetch();
    if ($datos_abono) {
        $id_butaca = $datos_abono['BUTACAS_ID_BUTACA'];
        $butaca = $bd->prepare("SELECT ZONA_BUTACA, PUERTA_BUTACA FROM BUTACAS WHERE ID_BUTACA = :id LIMIT 1");
        $butaca->execute([':id' => $id_butaca]);
        $datos_butaca = $butaca->fetch();
        if ($datos_butaca) {
            $zona_butaca = $datos_butaca['ZONA_BUTACA'];
            $puerta_butaca = $datos_butaca['PUERTA_BUTACA'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Personal | Rayo Vallecano</title>
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
        .flex-personal {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 1.2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .contenedor {
            margin: 0;
            padding: 2.2rem 2rem;
            min-width: 320px;
            max-width: 400px;
            width: 100%;
            background: #f5f5f5;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            font-size: 1.18rem;
        }
        h2 {
            color: #E21921;
            text-align: center;
            margin-top: 11%;
            margin-bottom: 2.5rem;
            font-size: 2.2rem;
        }
        .dato { margin-bottom: 1.2rem; }
        .contenedor a {
            display: inline-block;
            margin-top: 1rem;
            color: #fff;
            background: #E21921;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
        }
        .contenedor a:hover { background: #BC0A11; }
        .apartado {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ccc;
        }
        .apartado h3 {
            color: #E21921;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        .info-socio {
            font-size: 1.13em;
        }
        @media (max-width: 900px) {
            .flex-personal {
                flex-direction: column;
                align-items: center;
            }
            h2 {
                margin-top: 17%;
            }
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
    <h2>Área Personal</h2>
    <div class="flex-personal">
        <div class="contenedor">
            <div class="apartado">
                <h3>Información Personal</h3>
                <div class="info-socio">
                    <div class="dato"><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></div>
                    <div class="dato"><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($correo_usuario); ?></div>
                    <div class="dato"><strong>DNI:</strong> <?php echo htmlspecialchars($dni_usuario); ?></div>
                    <div class="dato"><strong>Telefono:</strong> <?php echo htmlspecialchars($tlfn_usuario); ?></div>
                    <div class="dato"><strong>Socio desde el </strong> <?php 
                        if ($fecha_alta_usuario) {
                            $fecha_socio = new DateTime($fecha_alta_usuario);
                            echo $fecha_socio->format('d/m/Y');
                        }
                    ?></div>
                </div>
            </div>
        </div>
        <div class="contenedor">
            <div class="apartado">
                <h3>Información Abono</h3>
                <div class="info-socio">
                    <div class="dato"><strong>Número de abonado:</strong> <?php echo htmlspecialchars($num_abonado); ?></div>
                    <div class="dato"><strong>Zona Butaca:</strong> <?php echo htmlspecialchars($zona_butaca); ?></div>
                    <div class="dato"><strong>Puerta Butaca:</strong> <?php echo htmlspecialchars($puerta_butaca); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 