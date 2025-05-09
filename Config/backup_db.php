<?php
include 'config.php';

// Obtener los datos de configuración desde las constantes definidas en config.php
$host = DB_HOST;
$usuario = DB_USER;
$password = DB_PASSWORD;
$base_datos = DB_NAME;

$fecha = date("Y-m-d_H-i-s");
$nombreArchivo = "backup_$base_datos" . "_$fecha.sql";
$rutaDestino = "C:/BACKUPS/Respaldos/$nombreArchivo";

if (!is_dir(dirname($rutaDestino))) {
    mkdir(dirname($rutaDestino), 0777, true);
}

$comando = "C:/wamp64/bin/mysql/mysql9.1.0/bin/mysqldump.exe --user=$usuario --password=$password --no-create-info --skip-triggers --host=$host $base_datos > \"$rutaDestino\"";

system($comando, $resultado);

if ($resultado === 0) {
    date_default_timezone_set('America/Guatemala');

    $peso_bytes = filesize($rutaDestino);
    $peso_mb = round($peso_bytes / 1048576, 2);

    $base = BASE_URL; // Utiliza la constante BASE_URL definida en config.php
    $url = $base . "/Sistemas/setRegistroDB"; // Cambiar por tu ruta real

    $data = [
        'nombre_archivo' => $nombreArchivo,
        'ruta' => "Respaldos",
        'fecha_creacion' => date("Y-m-d H:i:s"),
        'peso' => $peso_mb,
        'usuario' => 'Sistema'
    ];

    $opciones = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $contexto = stream_context_create($opciones);
    $respuesta = file_get_contents($url, false, $contexto);

    echo $respuesta;
} else {
    echo "Error al crear el respaldo.";
}
?>