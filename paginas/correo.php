<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "c:/xampp/composer/vendor/autoload.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['usuario'], $_POST['carrito'], $_POST['total'])) {
        $usuario = $_SESSION['usuario']; // Correo del usuario
        $carrito = $_POST['carrito'];    // Array asociativo del carrito
        $total = $_POST['total'];        // Total de la compra

        enviarCorreo($usuario, $carrito, $total);
    } else {
        echo "Datos incompletos para enviar el correo.";
        exit();
    }
}

function enviarCorreo($usuario, $carrito, $total)
{
    $mail = new PHPMailer(TRUE);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0; // Cambiar a 2 para depuración
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls"; // Para usar TLS
    $mail->Host = "smtp.gmail.com";    // Servidor SMTP de Gmail
    $mail->Port = 587;                // Puerto SMTP de Gmail para TLS
    $mail->Username = "dawiesventura@gmail.com";  // Usuario de Gmail
    $mail->Password = "nnli oyoa game qmoc";      // Contraseña de aplicación de Gmail
    $mail->SetFrom('adrianbf035@gmail.com', 'ADRISPORTS');
    $mail->Subject = 'Pedido realizado - ADRISPORTS';

    // Generar el contenido del correo
    $contenido = "<h1>Gracias por realizar tu pedido en ADRISPORTS</h1>";
    $contenido .= "<p>A continuación, te mostramos los detalles de tu pedido:</p>";
    $contenido .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $contenido .= "<thead>
                    <tr>
                        <th>Producto</th>
                        <th>Unidades</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                   </thead>
                   <tbody>";

    foreach ($carrito as $producto) {
        $nombre = htmlspecialchars($producto['nombre']);
        $cantidad = (int)$producto['cantidad'];
        $precio = number_format((float)$producto['precio'], 2);
        $subtotal = number_format((float)$producto['subtotal'], 2);

        $contenido .= "<tr>
                            <td>{$nombre}</td>
                            <td>{$cantidad}</td>
                            <td>{$precio}€</td>
                            <td>{$subtotal}€</td>
                        </tr>";
    }

    $contenido .= "</tbody>";
    $contenido .= "<tfoot>
                    <tr>
                        <td colspan='3'><strong>Total:</strong></td>
                        <td><strong>" . number_format((float)$total, 2) . "€</strong></td>
                    </tr>
                   </tfoot>";
    $contenido .= "</table>";

    $mail->MsgHTML($contenido); // Establecer el contenido del correo en formato HTML
    $mail->AddAddress($usuario);

    email($mail);
}

// Enviar el correo
function email($mail){
    $resul = $mail->Send();
    if (!$resul) {
        echo "Error: " . $mail->ErrorInfo;  // Si hay un error, se muestra el mensaje de error
    } else {
        echo "Correo enviado correctamente al usuario.";
    }
}
?>