<?php
class BitacorasModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Historial_Empleado()
    {
        $sql =
            "SELECT 
            he.id_historial,
            CONCAT(et.primer_apellido, ' ', et.segundo_apellido) as apellidos,
            et.nombres,
            he.fecha_alta,
            he.salario_inicial,
            he.puesto_contrato_inicio,
            he.puesto_operativo_inicio,
            he.departamento_inicio,
            he.fecha_baja,
            he.fecha_salida,
            he.salario_final,
            he.puesto_contrato_final,
            he.puesto_operativo_final,
            he.departamento_final,
            he.fecha_liquidacion,
            he.caso,
            he.razon_baja,
            he.comentario,
            he.observaciones
        FROM historial_empleado he
        INNER JOIN empleado_tb et ON he.empleado_id = et.id_empleado";
        $request = $this->select_all($sql);
        return $request;
    }

    public function HistorialbyID($id_historial)
    {
        $sql =
            "SELECT 
            id_historial,
            nombre,
            descripcion,
            nit,
            estado
        FROM historial_empleado
        where id_historial = ?";
        $request = $this->select($sql, array($id_historial));
        return $request;
    }


    public function Movimientos_Empleados()
    {
        $sql =
            "SELECT 
            he.id_movimiento,
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
            e.nombres,
            e.identificacion,
            he.puesto_contrato,
            he.puesto_operativo,
            he.jefe_inmediato,
            he.lider_proceso,
            he.departamento,
            he.area, 
            he.fecha_cambio,
            he.responsable,
            he.movimiento
        FROM movimientos_empleado he
        INNER JOIN empleado_tb e ON he.empleado_id = e.id_empleado
        WHERE he.movimiento is null";
        $request = $this->select_all($sql);
        return $request;
    }


} // FIN DE LA CLASE