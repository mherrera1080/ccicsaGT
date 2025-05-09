<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Assets/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com'; // Cambia por tu servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'soportegtvh@carsoca.com';
    $mail->Password = 'Vehiculos2024!';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinatarios
    $mail->setFrom('soportegtvh@carsoca.com', 'Sistema de Recursos Humanos');
    $mail->addAddress($arrData['correo_empresarial']);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Código de Verificación';
    $mail->Body = "
        <h1>Tu código de verificación</h1>
        <p>Usa el siguiente código para actualizar la contraseña de tu cuenta:</p>
        <h2>{$arrData['token']}</h2>
        <p>Este código expira en 5 minutos.</p>
    ";

    // Enviar el correo
    $mail->CharSet = 'UTF-8';

    $mail->send();
    $confirmacion_correo = true;
} catch (Exception $e) {
    $confirmacion_correo = false;
    error_log("Error al enviar el correo: {$mail->ErrorInfo}");
}
