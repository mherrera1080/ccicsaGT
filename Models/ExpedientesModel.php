<?php
class ExpedientesModel extends Mysql
{

    public $empleado_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function mostrarExpedientes()
    {
        $sql = "SELECT
            a.id,
            em.id_empleado,
            em.apellidos,
            em.nombres,
            e.id_documento,
            e.nombre_documento,
            a.ubicacion
        FROM empleado_expedientes a
        INNER JOIN expedientes_documentos e ON a.expediente_id = e.id_documento
        INNER JOIN empleado_tb em ON a.empleado_id = em.id_empleado";
        $request = $this->select_all($sql);
        return $request;
    }


    public function getExpedientes(int $empleado_id)
    {
        $this->empleado_id = $empleado_id;
        $sql = "SELECT
            a.id,
            a.empleado_id,
            em.id_empleado,
            CONCAT(em.nombres, ' ', em.primer_apellido, ' ',em.segundo_apellido) as nombres,
            e.id_documento,
            e.nombre_documento,
            a.ubicacion
        FROM empleado_expedientes a
        INNER JOIN expedientes_documentos e ON a.expediente_id = e.id_documento
        INNER JOIN empleado_tb em ON a.empleado_id = em.id_empleado
        WHERE empleado_id = $this->empleado_id";
        $request = $this->select($sql);
        return $request;
    }

    public function registroAvance(string $no_empleado, string $empleado, string $modulo, string $accion, string $fecha, string $ip, string $hostname)
    {
        if (empty($request)) {
            // Insertar nuevo usuario
            $columnas = "no_empleado, empleado, modulo, accion, fecha, ip, hostname";
            $query_insert = "INSERT INTO log_actividad($columnas) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $arrData = array(
                $no_empleado,
                $empleado,
                $modulo,
                $accion,
                $fecha,
                $ip,
                $hostname
            );
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function getDocumentoById(int $id)
    {
        $sql = "SELECT 
        e.nombre_documento, 
        a.empleado_id 
        FROM empleado_expedientes a
        INNER JOIN expedientes_documentos e ON a.expediente_id = e.id_documento
        WHERE id = ?";
        $params = [$id];
        $request = $this->select($sql, $params);
        return $request;
    }


    public function getExpedientesbyID(int $empleado_id)
    {
        $sql = "SELECT
            a.id,
            e.nombre_documento as nombre,
            a.ubicacion as ubicacion
        FROM empleado_expedientes a
        INNER JOIN expedientes_documentos e ON a.expediente_id = e.id_documento
        WHERE e.estado = 'Activo' AND empleado_id = ?";
        $request = $this->select_multi($sql, array($empleado_id));
        return $request;
    }

    public function ExpedientesbyID(int $id)
    {
        $sql = "SELECT
            a.id,
            a.empleado_id,
            em.id_empleado,
            CONCAT(em.nombres, ' ', em.primer_apellido, ' ',em.segundo_apellido) as nombres,
            e.id_documento,
            e.nombre_documento,
            a.ubicacion
        FROM empleado_expedientes a
        INNER JOIN expedientes_documentos e ON a.expediente_id = e.id_documento
        INNER JOIN empleado_tb em ON a.empleado_id = em.id_empleado
        WHERE id = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }

    public function subirDocumento(int $id, string $ubicacion, string $fecha_act)
    {
        $sql = "UPDATE empleado_expedientes SET 
                ubicacion = ?, 
                fecha_act = ?
                WHERE id = ?";

        $arrData = array(
            $ubicacion,
            $fecha_act,
            $id
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

}