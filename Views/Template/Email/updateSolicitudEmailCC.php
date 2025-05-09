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
    $mail->addAddress($arrData['correo']['correo_empresarial']); // Correo del solicitante

    // $mail->addCC($arrData['colaborador']['correo_jefe'], $arrData['colaborador']['nombre_jefe']); //C.C.


    // Construir filas de la tabla con los detalles de las fechas
    $detallesFechas = "";
    // Verificar si existen las claves y convertir a array
    $fechas = !empty($arrData['solicitud']['fechas_cancel']) ? explode('|', $arrData['solicitud']['fechas_cancel']) : [];
    $valores = !empty($arrData['solicitud']['valores_cancel']) ? explode('|', $arrData['solicitud']['valores_cancel']) : [];
    $dias = !empty($arrData['solicitud']['dias_cancel']) ? explode('|', $arrData['solicitud']['dias_cancel']) : [];

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
    $mail->isHTML(true);
    $mail->Subject = 'Actualización de Solicitud de Vacaciones';
    $mail->Body = "
    <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Actualización de Solicitud</title>
        </head>
        <body style='font-family: Arial, sans-serif;  padding: 20px;'>
            <div style='max-width: 600px; padding: 20px; margin: auto; border-radius: 10px; box-shadow: 0px 0px 10px #ccc;'>
                <h2 style='color: #333; text-align: center;'>Actualización de Solicitud de Vacaciones</h2>
                <p>Estimado/a <strong>{$arrData['correo']['nombre_completo']}</strong>,</p>
                <p>El usuario <strong>{$arrData['solicitud']['nombre_completo']}</strong> actualizado su solicitud de vacaciones a los siguientes detalles:</p>

                <table style='width: 35%; border-collapse: collapse; margin-top: 10px;'>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Código de Empleado:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$arrData['solicitud']['codigo_empleado']}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>No. de Solicitud:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$arrData['solicitud']['id_solicitud']}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Días Solicitados:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$arrData['dias_solicitados']}</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Estado de la Solicitud:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px; color: black;'><strong>Pendiente</strong></td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'><strong>Responsable de Aprobación:</strong></td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$arrData['correo']['nombre_completo']}</td>
                    </tr>
                </table>

                <h3 style='color: #333; text-align: left; margin-top: 20px;'>Detalles de las Fechas</h3>
                <table style='width: 75%; border-collapse: collapse; margin-top: 10px;'>
                    <tr>
                        <th style='border: 1px solid #ddd; padding: 8px; background:rgb(0, 0, 0); color: white;'>#</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background:rgb(0, 0, 0); color: white;'>Fecha</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background:rgb(0, 0, 0); color: white;'>Valor</th>
                        <th style='border: 1px solid #ddd; padding: 8px; background:rgb(0, 0, 0); color: white;'>Día</th>
                    </tr>
                    $detallesFechas
                </table>
                <h3 style='color: #333; text-align: left; margin-top: 20px;'>Comentario:</h3>
                <div style='border: 1px solid #ddd; padding: 10px; border-radius: 5px;'>
                    <p style='margin: 0;'>{$arrData['comentario_solicitud']}</p>
                </div>

                <p style='margin-top: 20px;'>Si tienes alguna consulta, no dudes en comunicarte con el departamento de recursos humanos.</p>
            <div style='text-align: center; margin-top: 20px;'>
                <a href='https://carsoca.com/ccicsagt/rh/Login' style='background-color: black; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Ver Detalles</a>
            </div>
                <p style='text-align: center; font-size: 14px; color: #777;'>Este es un mensaje automático, por favor no responder a este correo.</p>
            </div>
        </body>
        </html>
        ";

    // Enviar y confirmar
    $mail->send();
    $confirmacion_correo = true;

} catch (Exception $e) {
    error_log("Error en SolicitudEmail: " . $e->getMessage());
    $confirmacion_correo = false;
}
?>