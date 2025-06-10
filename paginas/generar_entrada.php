<?php
// Incluir el autoloader de Composer para cargar las clases necesarias
require_once __DIR__ . '/vendor/autoload.php';

// Iniciar sesión para acceder a los datos del partido (si se mantienen ahí)
session_start();

// Activar reporte de errores para depuración (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si los IDs de partido y butaca están en la URL
if (!isset($_GET['id_partido']) || !isset($_GET['id_butaca'])) {
    // Si no hay IDs, redirigir a la página de inicio o a un error
    header('Location: inicio.php');
    exit();
}

// Recuperar los IDs de la URL
$id_partido_url = $_GET['id_partido'];
$id_butaca_url = $_GET['id_butaca'];

require("conexion.php"); // Asegúrate de que tu archivo de conexión esté en el mismo directorio o ajusta la ruta
$bd = conectar();

// Obtener información del partido
$partido_q = $bd->prepare("SELECT * FROM PARTIDOS WHERE ID_PARTIDO = :id_partido");
$partido_q->execute(array(':id_partido' => $id_partido_url));
$datos_partido = $partido_q->fetch();

// Obtener información de la butaca específica
$butaca_q = $bd->prepare("SELECT ID_BUTACA, ZONA_BUTACA, PUERTA_BUTACA, PRECIO_BUTACA FROM BUTACAS WHERE ID_BUTACA = :id_butaca");
$butaca_q->execute(array(':id_butaca' => $id_butaca_url));
$butaca_unica = $butaca_q->fetch();

// Si no se encuentra el partido o la butaca, redirigir
if (!$datos_partido || !$butaca_unica) {
    header('Location: inicio.php');
    exit();
}

// Formatear fecha y hora del partido
$fechaHora = new DateTime($datos_partido['FECHA_HORA_PARTIDO']);
$fecha_es_larga = $fechaHora->format('d/m/Y');
$hora_es_corta = $fechaHora->format('H:i');

// Suponemos un número de socio ficticio y nombre del comprador para el ejemplo
$numero_socio = '1672'; // Esto podrías obtenerlo de la sesión del usuario si lo tuvieras
// Si el nombre del comprador no está disponible en la sesión, usa un placeholder
$nombre_comprador = isset($_SESSION['nombre_tarjeta']) ? $_SESSION['nombre_tarjeta'] : 'COMPRADOR ANÓNIMO'; 

// Referenciar la clase Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Crear una instancia de Dompdf
$dompdf = new Dompdf();

// Opcional: Configurar opciones para Dompdf (por ejemplo, para permitir CSS externo o imágenes)
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf->setOptions($options);

// 2. Definir el contenido HTML que se convertirá en PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Entrada Rayo Vallecano - Butaca ' . $butaca_unica['ID_BUTACA'] . '</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 9pt; /* Aumentar el tamaño base de la fuente */
            color: #333;
        }
        .ticket-container {
            width: 190mm;
            margin: 5mm auto; /* Reducir margen superior/inferior */
            box-sizing: border-box;
            padding: 0;
            background-color: white;
            position: relative;
            overflow: hidden;
            border: 1px solid #eee;
        }
        .header-top {
            background-color: #E21921; /* Rojo Rayo */
            height: 45px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10mm;
            box-sizing: border-box;
        }
        .header-top h1 {
            color: white;
            font-size: 1.8em; /* Aumentado para compensar la falta del logo */
            margin: 0;
            vertical-align: middle;
        }
        .header-main {
            background-color: #1a1a1a; /* Negro para el Rayo */
            color: white;
            padding: 8px 10mm;
            text-align: center;
            font-size: 1.1em;
            font-weight: bold;
        }
        .ticket-content {
            margin-top: 15px; /* Reducir margen superior */
            text-align: center;
            padding: 0 10mm;
            width: 170mm;
            margin: 0 auto;
        }
        .match-title {
            font-size: 2.2em;
            font-weight: bold;
            color: #E21921;
            margin-bottom: 5px;
            word-wrap: break-word;
        }
        .match-subtitle {
            font-size: 1.3em;
            color: #555;
            margin-bottom: 20px;
        }
        .info-box-container {
            margin-top: 15px; /* Reducir margen superior */
        }
        .info-box {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px; /* Simplificar padding interno */
            background-color: #f9f9f9;
            display: inline-block; /* Elementos en línea con ancho fijo */
            margin: 5mm; /* Margen entre las cajas */
            width: 50mm; /* Ancho fijo para cada caja */
            box-sizing: border-box;
            vertical-align: top; /* Alinear arriba si hay diferentes alturas */
            min-width: 90px; /* Asegurar que no sean demasiado estrechos */
            margin-bottom: 5mm; /* Margen inferior para separar filas */
        }
        .info-box.full-width {
            width: 170mm; /* Ocupa todo el ancho del contenedor */
            font-size: 1.1em;
            padding: 15px;
            color: #E21921;
            border-color: #E21921;
            display: block; /* Para que ocupe todo el ancho y se centre */
            margin: 5mm auto; /* Centrar la caja full-width */
        }
        .info-box .label {
            font-size: 0.75em;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .info-box .value {
            font-size: 1.6em;
            font-weight: bold;
            color: #333;
            line-height: 1.2;
        }
        .info-box.full-width .value {
            font-size: 2em;
            color: #E21921;
        }
        .info-box.price .value {
            font-size: 1.6em;
            color: #E21921;
        }
        .buyer-info {
            margin-top: 15px; /* Reducir margen superior */
            text-align: left;
            padding: 0 10mm; /* Padding horizontal para el comprador */
        }
        .buyer-info span {
            font-weight: bold;
            color: #E21921;
            margin-right: 8px;
        }
        .club-address {
            margin-top: 15px; /* Reducir margen superior */
            text-align: center;
            font-size: 0.85em;
            color: #666;
            padding: 0 10mm; /* Padding horizontal */
        }
        .disclaimer {
            font-size: 8pt; /* Reducir tamaño de fuente */
            color: #999;
            margin-top: 15px; /* Reducir margen superior */
            text-align: justify;
            line-height: 1.3; /* Reducir line-height */
            padding: 0 10mm;
        }
        .disclaimer h3 {
            font-size: 10pt; /* Reducir tamaño de fuente */
            color: #666;
            margin-bottom: 4px; /* Reducir margen inferior */
        }
        .disclaimer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .disclaimer li {
            margin-bottom: 2mm; /* Reducir margen entre elementos */
            font-size: 8pt; /* Asegurar tamaño de fuente consistente */
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header-top">
            <h1>RAYO VALLECANO</h1>
        </div>
        <div class="header-main">
            <span>ESTADIO DE VALLECAS</span> <!-- Ubicación del estadio -->
        </div>

        <div class="ticket-content">
            <div class="match-title">
                ' . $datos_partido['EQUIPO_LOCAL'] . ' vs ' . $datos_partido['EQUIPO_VISITANTE'] . '
            </div>
            <div class="match-subtitle">
                ' . $fecha_es_larga . ' ' . $hora_es_corta . ' | JORNADA ' . $datos_partido['ID_PARTIDO'] . ' 
            </div>

            <div class="info-box-container">
                <div class="info-box full-width">
                    <div class="label">ZONA/ZONE</div>
                    <div class="value">' . $butaca_unica['ZONA_BUTACA'] . '</div>
                </div>
                <div class="info-box">
                    <div class="label">PUERTA/GATE</div>
                    <div class="value">' . $butaca_unica['PUERTA_BUTACA'] . '</div>
                </div>
                <div class="info-box price">
                    <div class="label">PRECIO/PRICE</div>
                    <div class="value">' . number_format($butaca_unica['PRECIO_BUTACA'], 2) . '€ <br/> IVA/IGIC Incluido</div>
                </div>
                <div class="info-box">
                    <div class="label">SECTOR/BLOCK</div>
                    <div class="value">' . $butaca_unica['ZONA_BUTACA'] . '</div>
                </div>
                <div class="info-box">
                    <div class="label">FILA/ROW</div>
                    <div class="value">' . substr($butaca_unica['ID_BUTACA'], 0, 1) . '</div>
                </div>
                <div class="info-box">
                    <div class="label">ASIENTO/SEAT</div>
                    <div class="value">' . substr($butaca_unica['ID_BUTACA'], 1) . '</div>
                </div>
            </div>
        </div>

        <div class="buyer-info">
            <span>ID: ' . $butaca_unica['ID_BUTACA'] . '</span>
            ' . $nombre_comprador . '
        </div>

        <div class="club-address">
            Club Rayo Vallecano de Madrid S.A.D. - C/ Payaso Fofó, s/n, 28018 Madrid
        </div>

        <div class="disclaimer">
            <h3>CONDICIONES DE USO:</h3>
            <ul>
                <li>No se aceptan cambios ni devoluciones. Entradas personales e intransferibles.</li>
                <li>Reservado el derecho de admisión. Prohibida la entrada de objetos peligrosos.</li>
                <li>Acude con antelación al estadio. Más información en web oficial del Rayo Vallecano.</li>
            </ul>
        </div>

    </div>
</body>
</html>
';

$dompdf->loadHtml($html);

// 3. (Opcional) Configurar el tamaño y la orientación del papel
$dompdf->setPaper('A4', 'portrait');

// 4. Renderizar el HTML como PDF
$dompdf->render();

// 5. Enviar el PDF al navegador
$dompdf->stream("entrada_rayo_vallecano_" . $butaca_unica['ID_BUTACA'] . ".pdf", array("Attachment" => false));

?> 