<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Assets/vendor/autoload.php';

$confirmacion_correo = false;

try {

    // Configuración SMTP
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'notificacionesrhgt@carsoca.com';
    $mail->Password = '!Tj6VOv3';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Remitente y destinatarios
    $mail->setFrom('notificacionesrhgt@carsoca.com', 'Notificaciones Recursos Humanos');
    $mail->addAddress($arrData['colaborador']['correo_empresarial'], $arrData['colaborador']['nombre_completo']); // Correo del solicitante
    // $mail->addAddress($arrData['colaborador']['correo_empresarial'], $arrData['colaborador']['nombre_completo']); // Poner Corre de RH

    // Contenido dinámico
    $mail->isHTML(true);
    $mail->Subject = 'Solicitud de Reversion de Vacacions ';

    // Construir filas de la tabla con los detalles de las fechas
    $detallesFechas = "";
    // Verificar si existen las claves y convertir a array
    $fechas = !empty($arrData['colaborador']['fechas_cancel']) ? explode('|', $arrData['colaborador']['fechas_cancel']) : [];
    $valores = !empty($arrData['colaborador']['valores_cancel']) ? explode('|', $arrData['colaborador']['valores_cancel']) : [];
    $dias = !empty($arrData['colaborador']['dias_cancel']) ? explode('|', $arrData['colaborador']['dias_cancel']) : [];

    if (!empty($fechas)) {
        foreach ($fechas as $key => $fecha) {
            $id = $key + 1;
            $valor = $valores[$key] ?? 'N/A';
            $dia = $dias[$key] ?? 'N/A';

            $detallesFechas .= "
                <tr>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold;'>$id</td>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>$fecha</td>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>$valor</td>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>$dia</td>
                </tr>";
        }
    }

    // Cuerpo del correo con tabla
    $mail->Body = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Reversion de Solicitud de Vacaciones</title>
        </head>
        <body style='font-family: Arial, sans-serif; padding: 20px;'>
            <div style='max-width: 600px; padding: 20px; margin: auto; border-radius: 10px; box-shadow: 0px 0px 10px #ccc;'>
                <h2 style='color: #333; text-align: center;'>Solicitud de Vacaciones</h2>
                <p>Estimado/a <strong>{$arrData['colaborador']['nombre_completo']}</strong>,</p>
                <p>Haz realizado una solicitud de reversion de vacaciones con los siguientes detalles:</p>

                <table style='width: 35%; border-collapse: collapse; margin-top: 10px;'>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Código de Empleado:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$arrData['colaborador']['codigo_empleado']}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Fecha de Solicitud:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>" . date('d/m/Y') . "</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Días Solicitados:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$arrData['colaborador']['dias_suma']}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Estado de la Solicitud:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px; color: black;'><strong>Pendiente de Renovacion</strong></td>
                    </tr>
                </table>

                <h3 style='color: #333; text-align: left; margin-top: 20px;'>Detalles de las Fechas</h3>
                <table style='width: 75%; border-collapse: collapse; margin-top: 10px;'>
                    <tr>
                        <th style='border: 1px solid #ddd; padding: 8px; background: yellow; color: black;'>#</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background: yellow; color: black;'>Fecha</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background: yellow; color: black;'>Valor</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background: yellow; color: black;'>Día</th>
                    </tr>
                    $detallesFechas
                </table>

                <p style='margin-top: 20px;'>Si tienes alguna consulta, no dudes en comunicarte con el departamento de recursos humanos.</p>

                <div style='text-align: center; margin-top: 20px;'>
                    <a href='https://carsoca.com/ccicsagt/rh/vacaciones/solicitud' style='background-color: black; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Ver Detalles</a>
                </div>

                <p style='text-align: center; font-size: 14px; color: #777;'>Este es un mensaje automático, por favor no responder a este correo.</p>
            </div>
        </body>
    </html> ";

    // Enviar y confirmar
    $mail->send();
    $confirmacion_correo = true;

} catch (Exception $e) {
    error_log("Error en SolicitudEmail: " . $e->getMessage());
    $confirmacion_correo = false;
}
?>