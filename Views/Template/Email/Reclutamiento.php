<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'Assets/vendor/autoload.php';

$mail = new PHPMailer(true);
try {

    $mail->SMTPDebug = 0;
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'reclutamiento@carsoca.com';
    $mail->Password = 'R3clu2024!';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('reclutamiento@carsoca.com', 'Recluamiento General');

    if ($id_pais == 1) {
        $mail->addAddress('m.herreramu@ccicsa.com.mx', 'Alejandra Maria Porres');
    } elseif ($id_pais == 2) {
        $mail->addAddress('reclutamientosv@ccicsa.com.mx', 'Gabriel Alejandro Hernandez');
    } elseif ($id_pais == 3) {
        $mail->addAddress('reclutamientohn@ccicsa.com.mx', 'Liliam Aurora Carranza');
    } elseif ($id_pais == 4) {
        $mail->addAddress('reclutamientoni@ccicsa.com.mx', 'Yariela Marbella Laampin');
    } elseif ($id_pais == 5) {
        $mail->addAddress('reclutamientocr@ccicsa.com.mx', 'Sofia Paola Lopez Brenes');
    }

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Nuevo Reclutamiento';

    $body = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
            }
            .container {
                margin: 20px auto;
                background-color: #fff;
                padding: 25px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }
            h3 {
                color: #444;
            }
            table {
                width: 90%;
                border-collapse: collapse;
                margin: 0 auto; 
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
                color: #333;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h3><u>Detalles de Candidato</u></h3>
            <h4><u>Se reciben los datos de siguiente postulante:</u>  </h4>
            <table>
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>País</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Área de Interés</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>' . $nombres . '</td>
                        <td>' . $apellidos . '</td>
                        <td>' . $email . '</td>
                        <td>' . $telefono_celular . '</td>
                        <td>' . $nombre_pais . '</td>
                        <td>' . $nombre_departamento . '</td>
                        <td>' . $nombre_municipio . '</td>
                        <td>' . $nombre_area . '</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
    </html>
    ';

    $mail->Body = $body;
    // Adjuntar el archivo
    $mail->addAttachment($fileTmpPath, $newFileName); // Usa las variables del controlador

    $mail->CharSet = 'UTF-8';
    $mail->send();
} catch (Exception $e) {
    return false;
}