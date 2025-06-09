<?php
session_start();
error_reporting(E_ALL);
require("conexion.php");

if (!isset($_POST['total']) || !isset($_POST['id_partido'])) {
    header('Location: carrito_butacas.php');
    exit();
}

$total = $_POST['total'];
$id_partido = $_POST['id_partido'];
$tribuna = $_POST['TRIBUNA'];

// Reservar las butacas al llegar a la página de pago
$bd = conectar();
foreach ($_POST as $clave => $valor) {
    if (strpos($clave, 'butaca') === 0) {
        // Verificar si la butaca ya está reservada
        $verificar = $bd->prepare("SELECT COUNT(*) FROM BUTACA_PARTIDO
                                 WHERE ID_BUTACA = :butaca
                                 AND ID_PARTIDO = :partido
                                 AND ESTADO_BUTACA = 'RESERVADA'");
        $verificar->execute(array(
            ':butaca' => $valor,
            ':partido' => $id_partido
        ));
        
        // Solo reservar si no está ya reservada
        if ($verificar->fetchColumn() == 0) {
            $sql = "INSERT INTO `tfc`.`BUTACA_PARTIDO`(`ID_BUTACA`, `ID_PARTIDO`, `ESTADO_BUTACA`) 
                   VALUES('".$valor."','".$id_partido."','RESERVADA')";
            $bd->query($sql);
        }
    }
}

// Obtener información del partido
$bd = conectar();
$partidos = $bd->prepare("SELECT * FROM PARTIDOS WHERE ID_PARTIDO = :id");
$partidos->execute(array(':id' => $id_partido));
$partido = $partidos->fetch();

$fechaHora = new DateTime($partido['FECHA_HORA_PARTIDO']);
$fecha_es = $fechaHora->format('d/m/Y');
$hora_es = $fechaHora->format('H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de Entradas</title>
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
            padding-top: 90px;
        }
        .contenedor-pago {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .resumen {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .formulario-pago {
            display: grid;
            gap: 20px;
        }
        .campo {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        label {
            color: #2c3e50;
            font-weight: bold;
        }
        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .fecha-cvv {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        button {
            background-color: #E21921;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #c41820;
        }
        .volver {
            background-color: #2c3e50;
            margin-top: 10px;
        }
        .volver:hover {
            background-color: #1a252f;
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

    <div class="contenedor-pago">
        <h1 style="color: #2c3e50; margin-bottom: 20px;">Pago de Entradas</h1>
        
        <div class="resumen">
            <h3 style="margin-top: 0;">Resumen del Pedido</h3>
            <p><strong>Partido:</strong> <?php echo $partido['EQUIPO_LOCAL']." VS ".$partido['EQUIPO_VISITANTE']; ?></p>
            <p><strong>Fecha:</strong> <?php echo $fecha_es." - ".$hora_es; ?></p>
            <p><strong>Tribuna:</strong> <?php echo $tribuna; ?></p>
            <p><strong>Total a Pagar:</strong> <?php echo $total; ?>€</p>
        </div>

        <form action="procesar_pago.php" method="POST" class="formulario-pago">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <input type="hidden" name="id_partido" value="<?php echo $id_partido; ?>">
            <?php
            // Pasar todas las butacas seleccionadas
            foreach ($_POST as $clave => $valor) {
                if (strpos($clave, 'butaca') === 0) {
                    echo '<input type="hidden" name="'.$clave.'" value="'.$valor.'">';
                }
            }
            ?>

            <div class="campo">
                <label for="numero_tarjeta">Número de Tarjeta</label>
                <input type="text" id="numero_tarjeta" name="numero_tarjeta" 
                       pattern="[0-9]{16}" maxlength="16" required 
                       placeholder="1234 5678 9012 3456">
            </div>

            <div class="fecha-cvv">
                <div class="campo">
                    <label for="fecha_expiracion">Fecha de Expiración</label>
                    <input type="text" id="fecha_expiracion" name="fecha_expiracion" 
                           pattern="(0[1-9]|1[0-2])\/([0-9]{2})" 
                           placeholder="MM/AA" maxlength="5" required>
                </div>
                <div class="campo">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" 
                           pattern="[0-9]{3}" maxlength="3" required 
                           placeholder="123">
                    <small style="color: #666; font-size: 12px;">3 dígitos de seguridad en el reverso de tu tarjeta</small>
                </div>
            </div>

            <div class="campo">
                <label for="nombre_tarjeta">Nombre en la Tarjeta</label>
                <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" 
                       required placeholder="Como aparece en la tarjeta">
            </div>

            <button type="submit">Pagar <?php echo $total; ?>€</button>
        </form>

        <form action="carrito_butacas.php" method="POST">
            <input type="hidden" name="id_partido" value="<?php echo $id_partido; ?>">
            <input type="hidden" name="TRIBUNA" value="<?php echo $tribuna; ?>">
            <?php
            // Liberar las reservas antes de volver al carrito
            foreach ($_POST as $clave => $valor) {
                if (strpos($clave, 'butaca') === 0) {
                    echo '<input type="hidden" name="'.$clave.'" value="'.$valor.'">';
                    // Eliminar la reserva
                    $sql = "DELETE FROM BUTACA_PARTIDO 
                           WHERE ID_BUTACA = '".$valor."' 
                           AND ID_PARTIDO = '".$id_partido."' 
                           AND ESTADO_BUTACA = 'RESERVADA'";
                    $bd->query($sql);
                }
            }
            ?>
            <button type="submit" class="volver">Volver al Resumen</button>
        </form>
    </div>

    <script>
        // Formatear automáticamente la fecha de expiración
        document.getElementById('fecha_expiracion').addEventListener('input', function(e) {
            let valor = e.target.value.replace(/\D/g, '');
            if (valor.length >= 2) {
                valor = valor.slice(0,2) + '/' + valor.slice(2);
            }
            e.target.value = valor;
        });

        // Validar número de tarjeta
        document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Validar CVV
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
</body>
</html> 