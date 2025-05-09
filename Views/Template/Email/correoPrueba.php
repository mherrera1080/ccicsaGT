<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Assets/vendor/autoload.php';

$confirmacion_correo = false;

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'notificacionesrhgt@carsoca.com';
    $mail->Password = '!Tj6VOv3';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('notificacionesrhgt@carsoca.com', 'Notificaciones RH');
    $mail->addAddress('jh30716@gmail.com'); // Reemplázalo con un correo de prueba
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de correo';
    $mail->Body = 'Este es un correo de prueba.';

    if ($mail->send()) {
        echo "Correo enviado con éxito.";
    } else {
        echo "Error al enviar: " . $mail->ErrorInfo;
    }

} catch (Exception $e) {
    echo "Error en el envío de correo: " . $mail->ErrorInfo;
    error_log("Error en SolicitudEmail: " . $e->getMessage());
}
?>