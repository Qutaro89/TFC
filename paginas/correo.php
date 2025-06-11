<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function enviarCorreo($usuario, $carrito, $total, $datos_partido)
{
    $mail = new PHPMailer(TRUE);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0; // Cambiar a 2 para depuración
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls"; // Para usar TLS
    $mail->Host = "smtp.gmail.com";    // Servidor SMTP de Gmail
    $mail->Port = 587;                // Puerto SMTP de Gmail para TLS
    $mail->Username = "daw.ventura2023@gmail.com";  // Usuario de Gmail
    $mail->Password = "ypxh vcue zcix ahdq";      // Contraseña de aplicación de Gmail
    $mail->SetFrom('daw.ventura2023@gmail.com', 'Rayo Vallecano de Madrid');
    $mail->Subject = 'Tus entradas - Rayo Vallecano de Madrid';

    // Formatear fecha y hora del partido
    $fechaHora = new DateTime($datos_partido['FECHA_HORA_PARTIDO']);
    $fecha_es = $fechaHora->format('d/m/Y');
    $hora_es = $fechaHora->format('H:i');

    // Generar el contenido del correo
    $contenido = "<h1>¡Gracias por tu compra en el Rayo Vallecano!</h1>";
    $contenido .= "<p>Aquí tienes el resumen de tus entradas:</p>";
    $contenido .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    $contenido .= "<thead>
                    <tr style='background-color: #f2f2f2;'>
                        <th>Partido</th>
                        <th>Fecha y Hora</th>
                        <th>Jornada</th>
                    </tr>
                   </thead>
                   <tbody>
                    <tr>
                        <td>" . htmlspecialchars($datos_partido['EQUIPO_LOCAL']) . " vs " . htmlspecialchars($datos_partido['EQUIPO_VISITANTE']) . "</td>
                        <td>" . $fecha_es . " - " . $hora_es . "</td>
                        <td>" . htmlspecialchars($datos_partido['ID_PARTIDO']) . "</td>
                    </tr>
                   </tbody>
                  </table>";

    $contenido .= "<h3>Detalles de las Butacas:</h3>";
    $contenido .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $contenido .= "<thead>
                    <tr style='background-color: #f2f2f2;'>
                        <th>ID Butaca</th>
                        <th>Zona</th>
                        <th>Puerta</th>
                        <th>Precio</th>
                    </tr>
                   </thead>
                   <tbody>";

    foreach ($carrito as $butaca_data) {
        $id_butaca = htmlspecialchars($butaca_data['id_butaca']);
        $zona = htmlspecialchars($butaca_data['zona']);
        $puerta = htmlspecialchars($butaca_data['puerta']);
        $precio_unitario = number_format((float)$butaca_data['precio_unitario'], 2);

        $contenido .= "<tr>
                            <td>{$id_butaca}</td>
                            <td>{$zona}</td>
                            <td>{$puerta}</td>
                            <td>{$precio_unitario}€</td>
                        </tr>";
    }

    $contenido .= "</tbody>";
    $contenido .= "<tfoot>
                    <tr style='background-color: #f2f2f2; font-weight: bold;'>
                        <td colspan='3' style='text-align: right;'>Total de la Compra:</td>
                        <td>" . number_format((float)$total, 2) . "€</td>
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