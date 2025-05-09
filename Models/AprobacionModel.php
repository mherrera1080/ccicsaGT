<?php
class AprobacionModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Aprobaciones()
    {
        $sql = "SELECT
        a.id_aprobaciones,
        e.fecha_ingreso,
        e.primer_apellido,
        e.segundo_apellido,
        CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
        e.nombres,
        e.identificacion,
        p1.nombre_puesto as puesto_contrato,
        p2.nombre_puesto as puesto_operativo,
        d.nombre_departamento as departamento,
        al.nombre_area as area_laboral, 
        a.asunto as aprobacion,
        a.descripcion
        FROM aprobaciones_empleado a
        INNER JOIN empleado_tb e ON a.empleado_id = e.id_empleado
        INNER JOIN puestos_tb p1 ON e.puesto_contrato = p1.id_puesto
        INNER JOIN puestos_tb p2 ON e.puesto_operativo = p2.id_puesto
        INNER JOIN departamento_laboral d ON e.departamento = d.id_departamento
        INNER JOIN area_laboral al on e.area = al.id_area
        WHERE a.aprobacion = 'Pendiente' or a.aprobacion = 'Recontratacion'
        ";

        $request = $this->select_all($sql);
        return $request;
    }

    public function AprobacionesbyId($id_aprobaciones)
    {
        $sql = "SELECT
        a.id_aprobaciones,
        e.codigo_empleado,
        e.fecha_ingreso,
        e.primer_apellido,
        e.segundo_apellido,
        CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
        e.nombres,
        e.identificacion,
        e.estado,
        e.salario_base,
        e.bonificacion,
        e.kpi1,
        e.kpi2,
        e.kpi_max,
        p1.id_puesto as puesto_contrato,
        p2.id_puesto as puesto_operativo,
        d.id_departamento as departamento,
        la.id_area as area_laboral, 
        p1.nombre_puesto as nombre_puesto_contrato,
        p2.nombre_puesto as nombre_puesto_operativo,
        d.nombre_departamento as nombre_departamento,
        la.id_area as area_laboral,
        la.nombre_area as nombre_area, 
        CONCAT(la.nombre_area, ' | ', ln.codigo,'|', ln.descripcion) AS nombre_area_completo,
        a.aprobacion,
        a.descripcion
        FROM aprobaciones_empleado a
        INNER JOIN empleado_tb e ON a.empleado_id = e.id_empleado
        INNER JOIN puestos_tb p1 ON e.puesto_contrato = p1.id_puesto
        INNER JOIN puestos_tb p2 ON e.puesto_operativo = p2.id_puesto
        INNER JOIN departamento_laboral d ON e.departamento = d.id_departamento
        INNER JOIN area_laboral la on e.area = la.id_area
        INNER JOIN linea_negocio ln ON la.linea_negocio = ln.id_ln
        WHERE a.id_aprobaciones = ?";
        $request = $this->select($sql, array($id_aprobaciones));
        return $request;
    }

    public function aprobarUsuario(int $id_aprobaciones, ?string $codigo_empleado, string $aprobacion, string $descripcion, string $estado)
    {
        $sql = "UPDATE aprobaciones_empleado ae
                INNER JOIN empleado_tb e ON ae.empleado_id = e.id_empleado
                SET 
                    e.codigo_empleado = ?,
                    ae.aprobacion = ?,
                    e.estado = ?,
                    ae.descripcion = ?
                WHERE ae.id_aprobaciones = ?";

        $arrData = array(
            $codigo_empleado,
            $aprobacion,
            $estado,
            $descripcion,
            $id_aprobaciones
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }


    public function aprobarResolicitud(
        int $id_aprobaciones,
        string $aprobacion,
        string $primer_apellido,
        string $segundo_apellido,
        string $nombres,
        string $identificacion,
        int $puesto_contrato,
        int $puesto_operativo,
        int $departamento,
        int $area,
        ?int $salario_base,
        ?int $bonificacion,
        ?string $kpi1,
        ?string $kpi2,
        ?string $kpi_max,
        string $estado
    ) {
        // Actualiza la tabla aprobaciones_empleado y el estado del empleado
        $sql = "UPDATE aprobaciones_empleado ae
                INNER JOIN empleado_tb e ON ae.empleado_id = e.id_empleado
                SET 
                    ae.aprobacion = ?,
                    e.primer_apellido = ?,
                    e.segundo_apellido = ?,
                    e.nombres = ?,
                    e.identificacion = ?,
                    e.puesto_contrato = ?,
                    e.puesto_operativo = ?,
                    e.departamento = ?,
                    e.area = ?, 
                    e.salario_base = ?, 
                    e.bonificacion = ?, 
                    e.kpi1 = ?, 
                    e.kpi2 = ?, 
                    e.kpi_max = ?, 
                    e.estado = ?
                WHERE ae.id_aprobaciones = ?;";

        $arrData = array(
            $aprobacion,
            $primer_apellido,
            $segundo_apellido,
            $nombres,
            $identificacion,
            $puesto_contrato,
            $puesto_operativo,
            $departamento,
            $area,
            $salario_base,
            $bonificacion,
            $kpi1,
            $kpi2,
            $kpi_max,
            $estado,
            $id_aprobaciones
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }


    public function aprobarRecontratacion(int $id_aprobaciones, string $aprobacion, string $descripcion, string $estado)
    {
        // Actualiza la tabla aprobaciones_empleado y el estado del empleado
        $sql = "UPDATE aprobaciones_empleado ae
                INNER JOIN empleado_tb e ON ae.empleado_id = e.id_empleado
                SET 
                    ae.aprobacion = ?,
                    e.estado = ?,
                    ae.descripcion = ?
                WHERE ae.id_aprobaciones = ?;";

        $arrData = array(
            $aprobacion,
            $estado,
            $descripcion,
            $id_aprobaciones
        );

        $request = $this->update($sql, $arrData);

        // Si el estado es 'Baja' (rechazo de recontratación), restaurar los datos previos del empleado
        if ($estado == 'Baja') {
            // Obtener el id del empleado relacionado a la recontratación rechazada
            $sqlEmpleadoId = "SELECT empleado_id FROM aprobaciones_empleado WHERE id_aprobaciones = ?";
            $empleado = $this->select($sqlEmpleadoId, array($id_aprobaciones));

            if ($empleado) {
                $empleado_id = $empleado['empleado_id'];

                // Buscar el último movimiento antes de la recontratación
                $sqlRestore = "SELECT id_movimiento, puesto_contrato, puesto_operativo, lider_proceso, jefe_inmediato, departamento, area
                               FROM movimientos_empleado
                               WHERE empleado_id = ? AND movimiento = 'Recontratacion'
                               ORDER BY fecha_cambio DESC
                               LIMIT 1";

                $lastMovement = $this->select($sqlRestore, array($empleado_id));

                // Si se encontró un movimiento, restaurar los datos
                if (!empty($lastMovement)) {
                    $sqlRestoreEmpleado = "UPDATE empleado_tb 
                                           SET puesto_contrato = ?, 
                                               puesto_operativo = ?, 
                                               lider_proceso = ?, 
                                               jefe_inmediato = ?, 
                                               departamento = ?, 
                                               area = ?
                                           WHERE id_empleado = ?";

                    $arrRestoreData = array(
                        $lastMovement['puesto_contrato'],
                        $lastMovement['puesto_operativo'],
                        $lastMovement['lider_proceso'],
                        $lastMovement['jefe_inmediato'],
                        $lastMovement['departamento'],
                        $lastMovement['area'],
                        $empleado_id
                    );

                    $this->update($sqlRestoreEmpleado, $arrRestoreData);

                    // **Eliminar el movimiento después de restaurar los datos**
                    $sqlDeleteMovimiento = "DELETE FROM movimientos_empleado WHERE id_movimiento = ?";
                    $this->deletebyid($sqlDeleteMovimiento, array($lastMovement['id_movimiento']));
                } else {
                    error_log("No se encontraron datos válidos en movimientos_empleado para el empleado_id: " . $empleado_id);
                }
            } else {
                error_log("No se encontró el empleado con id_aprobaciones: " . $id_aprobaciones);
            }
        }


        return $request;
    }

    public function descartarAprobacion($id_aprobaciones)
    {
        $sql = "DELETE FROM aprobaciones_empleado WHERE id_aprobaciones = ?";
        $request = $this->deletebyid($sql, [$id_aprobaciones]);

        // Asegúrate de que `$request` esté retornando verdadero o falso según la eliminación
        return $request;
    }


    public function Rechazos()
    {
        $sql = "SELECT
        a.id_aprobaciones,
        e.fecha_ingreso,
        e.primer_apellido,
        e.segundo_apellido,
        CONCAT(e.primer_apellido, ' ',e.segundo_apellido) AS apellidos,
        e.nombres,
        e.identificacion,
        p1.nombre_puesto as puesto_contrato,
        p2.nombre_puesto as puesto_operativo,
        d.nombre_departamento as departamento,
        la.nombre_area as nombre_area, 
        CONCAT(la.nombre_area, ' | ', ln.codigo,'|', ln.descripcion) AS nombre_area_completa,
        a.aprobacion,
        a.descripcion
        FROM aprobaciones_empleado a
        INNER JOIN empleado_tb e ON a.empleado_id = e.id_empleado
        INNER JOIN puestos_tb p1 ON e.puesto_contrato = p1.id_puesto
        INNER JOIN puestos_tb p2 ON e.puesto_operativo = p2.id_puesto
        INNER JOIN departamento_laboral d ON e.departamento = d.id_departamento
        INNER JOIN area_laboral la on e.area = la.id_area
        INNER JOIN linea_negocio ln on la.linea_negocio = ln.id_ln
        WHERE a.aprobacion = 'Rechazado'
        ";

        $request = $this->select_all($sql);
        return $request;
    }

    public function insertPersonal(
        string $fecha_contratacion,
        string $fecha_prueba,
        string $primer_apellido,
        string $segundo_apellido,
        string $nombres,
        string $identificacion,
        ?int $salario_base,
        ?int $bonificacion,
        ?string $kpi1,
        ?string $kpi2,
        ?string $kpi_max,
        int $puesto_contrato,
        int $puesto_operativo,
        int $departamento,
        int $area,
        string $estado,
        string $reclutador // Nuevo parámetro
    ) {
        // Verificar si el usuario ya existe
        $sql = "SELECT id_empleado FROM empleado_tb WHERE identificacion = ?";
        $request = $this->select($sql, array($identificacion));
    
        if (empty($request)) {
            // Insertar nuevo empleado
            $columnas = "fecha_contratacion, fecha_prueba, primer_apellido, segundo_apellido, nombres, identificacion, salario_base, bonificacion, kpi1, kpi2, kpi_max, puesto_contrato, puesto_operativo, departamento, area, estado";
            $query_insert = "INSERT INTO empleado_tb($columnas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $arrData = array(
                $fecha_contratacion,
                $fecha_prueba,
                $primer_apellido,
                $segundo_apellido,
                $nombres,
                $identificacion,
                $salario_base,
                $bonificacion,
                $kpi1,
                $kpi2,
                $kpi_max,
                $puesto_contrato,
                $puesto_operativo,
                $departamento,
                $area,
                $estado
            );
    
            $request = $this->insert($query_insert, $arrData);

            $id_empleado = $request;
            $fecha_reclutacion = $fecha_contratacion;
    
            if ($id_empleado > 0) {
                // Llamar al procedimiento almacenado con el ID del nuevo empleado y el reclutador
                $sql_procedure = "InsertarAprobacionEmpleado";
                $this->callProcedure($sql_procedure, array($id_empleado, $reclutador, $fecha_reclutacion ));
            }
    
            return $request;
        } else { 
            return "exist";
        }
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



    public function updatePersonal(
        int $id_empleado,
        string $codigo_empleado,
        string $fecha_ingreso,
        string $primer_apellido,
        string $segundo_apellido,
        string $nombres,
        string $identificacion,
        int $puesto_contrato, // Cambiar a int si corresponde
        int $puesto_operativo, // Cambiar a int si corresponde
        int $lider_proceso, // Cambiar a int si corresponde
        int $jefe_inmediato, // Cambiar a int si corresponde
        int $departamento, // Cambiar a int si corresponde
        int $area, // Cambiar a int si corresponde
        string $estado // Cambiar a string
    ) {

        $sql = "UPDATE empleado_tb SET 
            codigo_empleado = ?, 
            fecha_ingreso = ?, 
            primer_apellido = ?,
            segundo_apellido = ?, 
            nombres = ?, 
            identificacion = ?, 
            puesto_contrato = ?, 
            puesto_operativo = ?, 
            lider_proceso = ?, 
            jefe_inmediato = ?, 
            departamento = ?, 
            area = ?, 
            estado = ? 
            WHERE id_empleado = ?";

        $arrData = array(
            $codigo_empleado,
            $fecha_ingreso,
            $primer_apellido,
            $segundo_apellido,
            $nombres,
            $identificacion,
            $puesto_contrato,
            $puesto_operativo,
            $lider_proceso,
            $jefe_inmediato,
            $departamento,
            $area,
            $estado,
            $id_empleado
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }


    // listados

    public function selectAreas()
    {
        $sql = "SELECT 
            a.id_area,
            a.nombre_area,
            a.descripcion as descripcion_area,
            a.linea_negocio,
            a.estado,
            ln.codigo ,
            ln.descripcion as descripcion_ln
        FROM area_laboral a
        LEFT JOIN linea_negocio ln ON a.linea_negocio = ln.id_ln
        ";
        $request = $this->select_all($sql);
        return $request;
    }



}