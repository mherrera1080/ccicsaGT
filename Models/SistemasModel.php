<?php
class SistemasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectRegistros()
    {
        $sql = "SELECT
        id_actividad,
        no_empleado,
        empleado,
        modulo,
        accion,
        fecha,
        ip,
        hostname
        FROM log_actividad
        ";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectPersonalID($correo_empresarial)
    {
        $sql = "SELECT
                CONCAT(e.nombres, ' ',e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM empleado_tb e
            WHERE e.correo_empresarial = ?";

        $request = $this->select($sql, array($correo_empresarial));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }


    public function selectBackups()
    {
        $sql = "SELECT
        id,
        nombre_archivo,
        peso,
        ruta,
        fecha_creacion,
        usuario
        FROM respaldo_backups
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertRegistro(?string $nombre_archivo, ?string $ruta, ?string $fecha_creacion, ?string $peso, ?string $usuario)
    {
        try {

            $sql = "INSERT INTO respaldo_backups(nombre_archivo, ruta, fecha_creacion, peso, usuario) VALUES (?, ?, ?, ?, ?)";
            $arrData = array($nombre_archivo, $ruta, $fecha_creacion, $peso, $usuario);
            $result = $this->insert($sql, $arrData);
            
            return $result;
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }
    
}