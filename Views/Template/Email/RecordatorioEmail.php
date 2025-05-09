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

    $estimado = "";

    // Verificación de datos de destinatarios y selección del destinatario
    if (!empty($arrData['solicitud']['responsable_aprobacion']) && $arrData['jefes']['estado_aprobador'] != 'Baja') {
        $mail->addAddress($arrData['jefes']['correo_responsable'], $arrData['jefes']['responsable_aprobador']);
        $estimado = "<p>Estimado {$arrData['jefes']['responsable_aprobador']},</p>";
    } 

    if ($arrData['solicitud']['estado'] == 'Pendiente Aprob. 1' && $arrData['jefes']['estado_aprobador1'] != 'Baja' ) {
        $mail->addAddress($arrData['jefes']['correo_aprobador_1'], $arrData['jefes']['nombre_aprobador_1']);
        $estimado = "<p>Estimado {$arrData['jefes']['nombre_aprobador_1']},</p>";
    } elseif ($arrData['solicitud']['estado'] == 'Pendiente Aprob. 2'&& $arrData['jefes']['estado_aprobador2'] != 'Baja' ) {
        $mail->addAddress($arrData['jefes']['correo_aprobador_2'], $arrData['jefes']['nombre_aprobador_2']);
        $estimado = "<p>Estimado {$arrData['jefes']['nombre_aprobador_2']},</p>";
    } elseif ($arrData['solicitud']['estado'] == 'Pendiente Aprob. 3' && $arrData['jefes']['estado_aprobador3'] != 'Baja' ) {
        $mail->addAddress($arrData['jefes']['correo_aprobador_3'], $arrData['jefes']['nombre_aprobador_3']);
        $estimado = "<p>Estimado {$arrData['jefes']['nombre_aprobador_3']},</p>";
    } 

    // Contenido dinámico
    $mail->isHTML(true);
    $mail->Subject = 'Recordatorio de Solicitudes Pendientes';

    // Cuerpo del correo con información dinámica (personaliza esto con la solicitud real)
    $mail->Body = "
        <h3>Recordatorio de Solicitud Pendiente</h3>
        $estimado
        <p>Hay una solicitud pendiente de revisión. A continuación se detallan los datos:</p>
        <table>
            <tr><td>ID Solicitud:</td><td>{$arrData['solicitud']['id_solicitud']}</td></tr>
            <tr><td>Estado:</td><td>{$arrData['solicitud']['estado']}</td></tr>
            <tr><td>Fecha Solicitud:</td><td>{$arrData['solicitud']['fecha_solicitud']}</td></tr>
            <!-- Añadir más detalles si es necesario -->
        </table>
        <p>Por favor, revisa la solicitud lo antes posible.</p>
    ";

    // Enviar y confirmar
    $mail->CharSet = 'UTF-8';
    $mail->send();
    $confirmacion_correo = true;

} catch (Exception $e) {
    error_log("Error en SolicitudEmail: " . $e->getMessage());
    $confirmacion_correo = false;
    echo json_encode(["status" => false, "message" => "Error al enviar correo: " . $e->getMessage()]);
}
?>