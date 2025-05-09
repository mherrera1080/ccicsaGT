<?php

class VacacionesModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectSolicitud()
    {
        $sql = "SELECT
                    sv.id_solicitud AS solicitud_id,
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    e.nombres AS nombres,
                    e.identificacion AS identificacion,
                    e.correo_empresarial AS correo,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.dias_retraso AS dias_retraso,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                INNER JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";

        $request = $this->select_all($sql); // Cambiar a select_all si esperas múltiples registros
        return $request;
    }

    public function callProcedimientos($procedimiento)
    {
        $request = $this->callProcedure($procedimiento, []); // Sin parámetros
        return $request;
    }


    public function selectPendientes()
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                    e.identificacion, 
                    e.correo_empresarial AS correo,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    sv.revision_aprobador_1 AS revision_aprobador_1,
                    sv.revision_aprobador_2 AS revision_aprobador_2,
                    sv.revision_aprobador_3 AS revision_aprobador_3,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    tc.asunto as categorias,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    SUM(ds.valor) AS dias,
                    sv.dias_retraso
                FROM solicitud_vacaciones sv
                LEFT JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN jefes_lideres jefe ON sv.responsable_aprobacion = jefe.id
                LEFT JOIN empleado_tb jefe_info ON jefe.usuario = jefe_info.id_empleado
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.estado LIKE '%Pendiente%'
                AND sv.dias_retraso % 2 = 0
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";

        $request = $this->select_all($sql); // Cambiar a select_all si esperas múltiples registros
        return $request;
    }


    public function selectPersonalID($correo_empresarial)
    {
        $sql = "SELECT
                e.id_empleado,
                e.codigo_empleado,
                e.fecha_ingreso,
                e.primer_apellido,
                e.segundo_apellido,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                e.nombres,
                CONCAT(e.nombres, ' ',e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                e.identificacion,
                e.correo_empresarial,
                e.formulario_vacaciones,
                e.jefe_inmediato,
                CONCAT(jefe.nombres, ' ', jefe.primer_apellido, ' ', jefe.segundo_apellido) AS nombre_jefe,
                jefe.correo_empresarial as correo_jefe,
                CONCAT(pt.nombre_puesto, ' | ', pt.descripcion) AS puesto,
                al.id_area AS area_solicitud,
                CONCAT(al.nombre_area, ' | ', ln.codigo, '|', ln.descripcion) AS area_laboral,
                CONCAT(dl.nombre_departamento, ' | ', dl.descripcion) AS departamento_laboral,
                CASE WHEN EXISTS (
                        SELECT 1 
                        FROM solicitud_vacaciones sv2 
                        WHERE sv2.id_empleado = e.id_empleado 
                        AND sv2.estado LIKE '%pendiente%'
                    ) THEN 'pendiente'
                    ELSE 'no-pendiente'
                END AS solicitudes
            FROM empleado_tb e
            LEFT JOIN jefes_lideres jl ON e.jefe_inmediato = jl.id
            LEFT JOIN empleado_tb jefe ON jl.usuario = jefe.id_empleado
            LEFT JOIN puestos_tb pt ON e.puesto_contrato = pt.id_puesto
            LEFT JOIN departamento_laboral dl ON e.departamento = dl.id_departamento
            LEFT JOIN area_laboral al ON jl.area = al.id_area
            LEFT JOIN linea_negocio ln ON al.linea_negocio = ln.id_ln
            WHERE e.correo_empresarial = ?";

        $request = $this->select($sql, array($correo_empresarial));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }

    public function selectJefesID($id_solicitud)// jefes
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    sv.responsable_aprobacion,
                    CONCAT(responsable_info.nombres, ' ', responsable_info.primer_apellido, ' ', responsable_info.segundo_apellido) AS responsable_aprobador,
                    responsable_info.correo_empresarial AS correo_responsable,
                    responsable_info.estado AS estado_aprobador ,
                    sv.comentario_respuesta,
                    sv.revision_aprobador_1,
                    CONCAT(aprobador1_info.nombres, ' ', aprobador1_info.primer_apellido, ' ', aprobador1_info.segundo_apellido) AS nombre_aprobador_1,
                    aprobador1_info.correo_empresarial AS correo_aprobador_1,
                    aprobador1_info.estado AS estado_aprobador1 ,
                    sv.comentario_aprobador_1,
                    sv.revision_aprobador_2,
                    CONCAT(aprobador2_info.nombres, ' ', aprobador2_info.primer_apellido, ' ', aprobador2_info.segundo_apellido) AS nombre_aprobador_2,
                    aprobador2_info.correo_empresarial AS correo_aprobador_2,
                    aprobador2_info.estado AS estado_aprobador2 ,
                    sv.comentario_aprobador_2,
                    sv.revision_aprobador_3,
                    CONCAT(aprobador3_info.nombres, ' ', aprobador3_info.primer_apellido, ' ', aprobador3_info.segundo_apellido) AS nombre_aprobador_3,
                    aprobador3_info.correo_empresarial AS correo_aprobador_3,
                    aprobador3_info.estado AS estado_aprobador3 ,
                    sv.comentario_aprobador_3
                FROM solicitud_vacaciones sv
                LEFT JOIN jefes_lideres responsable ON sv.responsable_aprobacion = responsable.id
                LEFT JOIN jefes_lideres aprobador1 ON sv.revision_aprobador_1 = aprobador1.id
                LEFT JOIN jefes_lideres aprobador2 ON sv.revision_aprobador_2 = aprobador2.id
                LEFT JOIN jefes_lideres aprobador3 ON sv.revision_aprobador_3 = aprobador3.id
                LEFT JOIN empleado_tb responsable_info ON responsable.usuario = responsable_info.id_empleado
                LEFT JOIN empleado_tb aprobador1_info ON aprobador1.usuario = aprobador1_info.id_empleado
                LEFT JOIN empleado_tb aprobador2_info ON aprobador2.usuario = aprobador2_info.id_empleado
                LEFT JOIN empleado_tb aprobador3_info ON aprobador3.usuario = aprobador3_info.id_empleado
                WHERE sv.id_solicitud = ?
                GROUP BY sv.id_solicitud";

        $request = $this->select($sql, array($id_solicitud)); // Devuelve solo un registro
        return $request;
    }

    public function selectEstadisticas($correo_empresarial)
    {
        $sql = "SELECT 
            p.id_empleado,
    		COALESCE(SUM(DISTINCT p.dias_disponibles), 0) AS dias_disponibles,
    		COALESCE(SUM(DISTINCT p.dias_consumidos), 0) AS dias_consumidos,
            e.id_empleado,
            -- Cantidad de solicitudes por estado (evita contar duplicados)
            COALESCE(COUNT(DISTINCT CASE WHEN s.estado = 'Aprobado' THEN s.id_solicitud END), 0) AS solicitudes_aprobadas,
            COALESCE(COUNT(DISTINCT CASE WHEN s.estado LIKE '%Pendiente%' THEN s.id_solicitud END), 0) AS solicitudes_pendientes,
            COALESCE(COUNT(DISTINCT CASE WHEN s.estado = 'Cancelado' THEN s.id_solicitud END), 0) AS solicitudes_canceladas,
            COALESCE(COUNT(DISTINCT CASE WHEN s.estado = 'Rechazado' THEN s.id_solicitud END), 0) AS solicitudes_rechazadas,
            COALESCE(COUNT(DISTINCT CASE WHEN s.estado NOT LIKE '%Pendiente%' AND s.estado != 'Cancelado' THEN s.id_solicitud END), 0) AS solicitudes_revisadas
        FROM periodo_vacaciones p
        LEFT JOIN solicitud_vacaciones s ON p.id_empleado = s.id_empleado
        LEFT JOIN empleado_tb e ON p.id_empleado = e.id_empleado
        WHERE e.correo_empresarial = ?
        GROUP BY p.id_empleado";

        $request = $this->select($sql, array($correo_empresarial));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }



    public function selectPersonalIDempleado($id_empleado)
    {
        $sql = "SELECT
                e.id_empleado,
                e.codigo_empleado,
                e.fecha_ingreso,
                e.primer_apellido,
                e.segundo_apellido,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                e.nombres,
                e.identificacion,
                e.correo_empresarial,
                e.formulario_vacaciones,
                e.jefe_inmediato,
                CONCAT(jefe.nombres, ' ', jefe.primer_apellido, ' ', jefe.segundo_apellido) AS nombre_jefe,
                jefe.correo_empresarial as correo_jefe,
                p.nombre_area AS area_jefe,
                                CASE 
                    WHEN EXISTS (
                        SELECT 1 
                        FROM solicitud_vacaciones sv2 
                        WHERE sv2.id_empleado = e.id_empleado 
                        AND sv2.estado LIKE '%pendiente%'
                    ) THEN 'pendiente'
                    ELSE 'no-pendiente'
                END AS solicitudes
            FROM empleado_tb e
            LEFT JOIN jefes_lideres jl ON e.jefe_inmediato = jl.id
            LEFT JOIN empleado_tb jefe ON jl.usuario = jefe.id_empleado
            LEFT JOIN area_laboral p ON jl.area = p.id_area
            WHERE e.id_empleado = ?";

        $request = $this->select($sql, array($id_empleado));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }

    public function selectPersonalIJefe($responsable_aprobacion)
    {
        $sql = "SELECT
                e.id_empleado,
                jl.id AS id_jefe,
                e.codigo_empleado,
                e.fecha_ingreso,
                e.primer_apellido,
                e.segundo_apellido,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                e.nombres,
                e.identificacion,
                e.correo_empresarial,
                jl.usuario AS jefe_inmediato,
                p.nombre_area AS area_jefe
            FROM jefes_lideres jl
            LEFT JOIN empleado_tb e ON jl.usuario = e.id_empleado
            LEFT JOIN area_laboral p ON jl.area = p.id_area
            WHERE jl.id = ?";

        $request = $this->select($sql, array($responsable_aprobacion));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }

    public function selectAprobador($id_responsable)
    {
        $sql = "SELECT
                jl.id,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                e.correo_empresarial,
                jl.usuario
            FROM jefes_lideres jl
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE jl.id = ?";

        $request = $this->select($sql, array($id_responsable));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }


    public function selectSolicitudes($id_empleado)
    {
        $sql = "SELECT
                    sv.id_solicitud AS solicitud_id,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    e.primer_apellido,
                    e.segundo_apellido,  
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                    e.nombres AS nombres, 
                    e.identificacion, 
                    e.correo_empresarial AS correo,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    CONCAT(jefe_info.primer_apellido, ' ', jefe_info.segundo_apellido, ' ', jefe_info.nombres) AS jefe_aprobador,
                    sv.estado AS estado,
                    ds.estado as estado_detalle,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
                    tc.asunto as categorias,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    SUM(ds.valor) AS dias 
                FROM solicitud_vacaciones sv
                LEFT JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN jefes_lideres jefe ON sv.responsable_aprobacion = jefe.id
                LEFT JOIN empleado_tb jefe_info ON jefe.usuario = jefe_info.id_empleado
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE e.id_empleado = ?
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";

        $request = $this->select_multi($sql, array($id_empleado));
        return $request;
    }


    public function selectSolicitudesbyID($id_solicitud)
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    e.primer_apellido,
                    e.segundo_apellido,  
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                    CONCAT(jefe.nombres, ' ', jefe.primer_apellido, ' ', jefe.segundo_apellido) AS nombre_jefe,
                    jefe.correo_empresarial as correo_jefe,
                    e.nombres, 
                    e.identificacion, 
                    e.correo_empresarial,
                    sv.grupo_dias,
                    sv.fecha_solicitud,
                    COALESCE(sv.fecha_aprobacion, '') AS fecha_aprobacion,
                    COALESCE(sv.responsable_aprobacion, '') AS responsable_aprobacion,
                    COALESCE(sv.revision_aprobador_1, '') AS revision_aprobador_1,
                    COALESCE(sv.revision_aprobador_2, '') AS revision_aprobador_2,
                    COALESCE(sv.revision_aprobador_3, '') AS revision_aprobador_3,
                    sv.estado,
                    COALESCE(sv.comentario_solicitud, '') AS comentario_solicitud,
                    COALESCE(sv.comentario_respuesta, '') AS comentario_respuesta,
                    sv.id_categoria,
                    tc.asunto,
                    SUM(ds.valor) AS dias_suma,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    GROUP_CONCAT(ds.dia ORDER BY ds.fecha ASC) AS dias,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC SEPARATOR '|') AS fechas_cancel,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC SEPARATOR '|') AS valores_cancel,
                    GROUP_CONCAT(ds.dia ORDER BY ds.fecha ASC SEPARATOR '|') AS dias_cancel
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN jefes_lideres jl ON e.jefe_inmediato = jl.id
                LEFT JOIN empleado_tb jefe ON jl.usuario = jefe.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.id_solicitud = ?
                GROUP BY sv.id_solicitud";
    
        $request = $this->select($sql, array($id_solicitud)); // Devuelve solo un registro
        return $request;
    }
    

    public function selectSolicitudPendiente($id_empleado)
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    e.id_empleado,
                    e.primer_apellido,
                    e.segundo_apellido,
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                    e.identificacion, 
                    sv.estado AS estado,
                    tc.asunto,
                    sv.fecha_solicitud AS fecha_solicitud,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    GROUP_CONCAT(ds.dia ORDER BY ds.fecha ASC) AS dias,
                    SUM(ds.valor) AS dias_pendientes
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                INNER JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.id_empleado = ? and sv.estado LIKE '%pendiente%'
                GROUP BY sv.id_empleado";

        $request = $this->select($sql, array($id_empleado)); // Devuelve solo un registro
        return $request;
    }

    public function selectSolicitudesID($id_solicitud)// Solicitud
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    e.id_empleado,
                    e.codigo_empleado,
                    CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                    e.correo_empresarial,
                    e.nombres, 
                    e.identificacion, 
                    sv.grupo_dias,
                    sv.fecha_solicitud,
                    sv.fecha_aprobacion,
                    sv.dias_retraso,
                    sv.estado,
                    sv.comentario_solicitud,
                    sv.comentario_respuesta,
                    sv.id_categoria,
                    tc.asunto,
                    SUM(ds.valor) AS dias_suma,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    GROUP_CONCAT(ds.dia ORDER BY ds.fecha ASC) AS dias,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC SEPARATOR '|') AS fechas_cancel,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC SEPARATOR '|') AS valores_cancel,
                    GROUP_CONCAT(ds.dia ORDER BY ds.fecha ASC SEPARATOR '|') AS dias_cancel
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                INNER JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.id_solicitud = ?
                GROUP BY sv.id_solicitud";

        $request = $this->select($sql, array($id_solicitud)); // Devuelve solo un registro
        return $request;
    }



    public function getEmpleadoBycorreo_empresarial($id_empleado)
    {
        $sql = "SELECT
            sv.id_solicitud,
            e.fecha_ingreso, 
            e.primer_apellido,
            e.segundo_apellido,  
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
            e.nombres, 
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ' ', e.nombres) as nombre,
            CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
            e.identificacion, 
            e.correo_empresarial,
            sv.grupo_dias,
            sv.fecha_solicitud,
            sv.fecha_aprobacion,
            sv.dias_retraso,
            sv.responsable_aprobacion,
            sv.estado,
            sv.comentario_solicitud,
            sv.comentario_respuesta
        FROM solicitud_vacaciones sv
        INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado  
        WHERE e.id_empleado = ?";
        $request = $this->select($sql, array($id_empleado));
        return $request;
    }

    public function insertSolicitud($id_empleado, $comentario_solicitud, $responsable_aprobacion, $id_categoria, $area_solicitud)
    {
        $sql = "INSERT INTO solicitud_vacaciones (id_empleado, fecha_solicitud, comentario_solicitud, responsable_aprobacion, estado, id_categoria, area_solicitud) 
                VALUES (?, NOW(), ?, ?, 'Pendiente', ?, ?)";
        $arrData = array($id_empleado, $comentario_solicitud, $responsable_aprobacion, $id_categoria, $area_solicitud);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

    public function insertSolicitudOperativa($id_empleado, $comentario_solicitud, $revision_aprobador_1, $id_categoria, $area_solicitud)
    {
        $sql = "INSERT INTO solicitud_vacaciones (id_empleado, fecha_solicitud, comentario_solicitud, revision_aprobador_1, estado, id_categoria, area_solicitud) 
                VALUES (?, NOW(), ?, ?, 'Pendiente Aprob. 1', ?, ?)";
        $arrData = array($id_empleado, $comentario_solicitud, $revision_aprobador_1, $id_categoria, $area_solicitud);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

    public function insertDetalleSolicitud($fecha, $valor, $dia, $id_solicitud)
    {
        $sql = "INSERT INTO detalle_solicitud_vacaciones (fecha, valor, dia, id_solicitud, estado) 
                VALUES (?, ?, ?, ?, 'Pendiente')";
        return $this->insert($sql, [$fecha, $valor, $dia, $id_solicitud]);
    }


    public function updateSolicitud($id_solicitud, ?int $responsable_aprobacion, ?int $revision_aprobador_1, $comentario_solicitud, $id_categoria)
    {
        $sql = "UPDATE solicitud_vacaciones 
                SET responsable_aprobacion = ?, revision_aprobador_1 = ?, comentario_solicitud = ?, id_categoria = ?
                WHERE id_solicitud = ?";
        $arrData = array($responsable_aprobacion, $revision_aprobador_1, $comentario_solicitud, $id_categoria, $id_solicitud);
        return $this->update($sql, $arrData);
    }


    public function updateDetalleSolicitud($id_solicitud, $fecha, $valor, $dia)
    {
        $sql = "UPDATE detalle_solicitud_vacaciones 
                SET valor = ?, dia = ?
                WHERE id_solicitud = ? AND fecha = ?";
        $arrData = array($valor, $dia, $id_solicitud, $fecha);
        return $this->update($sql, $arrData);
    }


    public function deleteDetalle($id_solicitud, $fechas, $valores, $dias)
    {
        // Eliminar todos los detalles existentes para la solicitud
        $sqlDelete = "DELETE FROM detalle_solicitud_vacaciones WHERE id_solicitud = ?";
        $this->deleteSolicitud($sqlDelete, array($id_solicitud));

        // Insertar los nuevos detalles (fechas)
        foreach ($fechas as $index => $fecha) {
            $valor = $valores[$index];
            $dia = $dias[$index];
            $sqlInsert = "INSERT INTO detalle_solicitud_vacaciones (fecha, valor, dia, id_solicitud) 
                          VALUES (?, ?, ?, ?)";
            $arrData = array($fecha, $valor, $dia, $id_solicitud);
            $this->insert($sqlInsert, $arrData);
        }

        return true;
    }

    public function deleteFechaDetalle($id_solicitud, $fecha)
    {
        $sql = "DELETE FROM detalle_solicitud_vacaciones WHERE id_solicitud = ? AND fecha = ?";
        $arrData = [$id_solicitud, $fecha];
        return $this->deleteSolicitud($sql, $arrData); // Devuelve true si se eliminó correctamente
    }
    public function deleteDetalleSolicitud($id_solicitud, $fecha)
    {
        $sql = "DELETE FROM detalle_solicitud_vacaciones WHERE id_solicitud = ? AND fecha = ?";
        return $this->deleteSolicitud($sql, [$id_solicitud, $fecha]);
    }



    public function checkDetalleExistente($id_solicitud, $fecha)
    {
        $sql = "SELECT id_fecha FROM detalle_solicitud_vacaciones WHERE id_solicitud = ? AND fecha = ?";
        $arrData = array($id_solicitud, $fecha);
        $result = $this->select($sql, $arrData);
        return !empty($result); // Devuelve true si existe, false si no
    }



    public function getDetallePorFechaEmpleado($id_empleado, $fecha)
    {
        $sql = "SELECT dsv.estado 
                FROM detalle_solicitud_vacaciones dsv
                INNER JOIN solicitud_vacaciones sv ON dsv.id_solicitud = sv.id_solicitud
                WHERE sv.id_empleado = ? 
                  AND dsv.fecha = ? 
                  AND dsv.estado NOT IN ('Cancelado', 'Rechazado')";
        return $this->select($sql, [$id_empleado, $fecha]);
    }


    public function isFechaDuplicada($id_solicitud, $fecha)
    {
        $sql = "SELECT COUNT(*) as total FROM detalle_solicitud_vacaciones 
            WHERE id_solicitud = ? AND fecha = ?";
        $arrData = array($id_solicitud, $fecha);
        $result = $this->select($sql, $arrData);
        return $result['total'] > 0; // Retorna true si existe la fecha duplicada
    }

    public function getDetallesPorSolicitud($id_solicitud)
    {
        $sql = "SELECT fecha FROM detalle_solicitud_vacaciones WHERE id_solicitud = ?";
        return $this->select_multi($sql, [$id_solicitud]);
    }



    public function updateSolicitudEstado($id_solicitud, $estado, $comentario)
    {
        $sql = "UPDATE solicitud_vacaciones 
            SET estado = ?, comentario_solicitud = ? 
            WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $comentario, $id_solicitud]);
    }

    public function rechazarFechasSolicitud($id_solicitud)
    {
        $sql = "UPDATE detalle_solicitud_vacaciones 
            SET estado = 'Cancelado' 
            WHERE id_solicitud = ?";
        return $this->update($sql, [$id_solicitud]);
    }

    public function updateEstadoSolicitud($id_solicitud, $estado, $comentario = null)
    {
        $sql = "UPDATE solicitud_vacaciones 
                SET estado = ?, comentario_respuesta = ? 
                WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $comentario, $id_solicitud]);
    }

    public function updateEstadoDetalles($id_solicitud, $estado)
    {
        $sql = "UPDATE detalle_solicitud_vacaciones 
            SET estado = ? 
            WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $id_solicitud]);
    }

    public function selectCordinadoresArea()
    {
        $sql = "SELECT 
            j.id AS id,
            concat( e.primer_apellido,' ',e.segundo_apellido,' ',e.nombres ) AS   usuario,
            concat( p.nombre_area ) as area,
            j.estado as estado
        FROM jefes_lideres j
        INNER JOIN empleado_tb e on j.usuario = e.id_empleado
        INNER JOIN area_laboral p on j.area = p.id_area
        ";
        $request = $this->select_all($sql);
        return $request;
    }


    public function selectJefesLideres()
    {
        $sql = "SELECT 
            j.id AS id,
            concat( e.primer_apellido,' ',e.segundo_apellido,' ',e.nombres ) AS   usuario,
            concat( p.nombre_area,' || ',p.descripcion ) as area,
            j.estado as estado
        FROM jefes_lideres j
        INNER JOIN empleado_tb e on j.usuario = e.id_empleado
        INNER JOIN area_laboral p on j.area = p.id_area
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCategorias()
    {
        $sql = "SELECT * FROM tb_categorias WHERE tb_perteneciente = 'Vacaciones'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function verificarCorreo($correo_empresarial)
    {
        $sql = "SELECT 
        id_empleado, 
        nombres 
        FROM empleado_tb 
        WHERE correo_empresarial = ? AND estado = 'Activo'";
        $arrData = array($correo_empresarial);
        return $this->select($sql, $arrData); // Devuelve los datos si existe
    }

    public function generarToken($correo_empresarial)
    {
        $token = rand(100000, 999999); 
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $sql = "INSERT INTO auth_tokens (correo_empresarial, token, expires_at) VALUES (?, ?, ?)";
        $arrData = array($correo_empresarial, $token, $expires_at);
        $this->insert($sql, $arrData);

        return $token;
    }

    public function guardarToken($correo, $token, $expires_at)
    {
        $sql = "INSERT INTO auth_tokens (correo_empresarial, token, expires_at) VALUES (?, ?, ?)";
        $arrData = [$correo, $token, $expires_at];
        return $this->insert($sql, $arrData);
    }

    // Validar el token
    public function validarToken($correo_empresarial, $token)
    {
        // Definir la zona horaria en PHP (por seguridad)
        date_default_timezone_set('America/Guatemala'); // Ajusta según tu zona horaria
    
        // Obtener la fecha y hora actual en la zona correcta
        $query = date("Y-m-d H:i:s");
    
        $sql = "SELECT e.id_empleado, e.nombres, 
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
                    e.correo_empresarial 
                FROM auth_tokens t
                INNER JOIN empleado_tb e ON e.correo_empresarial = t.correo_empresarial
                WHERE t.correo_empresarial = ? 
                AND t.token = ? 
                AND t.expires_at > ?";
        
        $arrData = [$correo_empresarial, $token, $query];
    
        return $this->select($sql, $arrData); // Retorna los datos del usuario si el token es válido
    }

    public function isTokenValid($correo, $token)
    {
        $sql = "SELECT id FROM auth_tokens 
                WHERE correo_empresarial = ? 
                AND token = ? 
                AND expires_at > NOW()";
        $arrData = [$correo, $token];
        return !empty($this->select($sql, $arrData)); // Retorna true si el token sigue siendo válido
    }


    // VACACIONES VALIDACIONES

    public function obtenerDiasDisponibles($id_empleado)
    {
        $sql = "SELECT 
            id_empleado, 
            fecha_inicio, 
            fecha_fin, 
            dias_consumidos, 
            dias_disponibles 
            FROM periodo_vacaciones 
            WHERE id_empleado = ? AND dias_disponibles > 0.5
            ORDER BY fecha_inicio ASC 
            LIMIT 1";

        $request = $this->select($sql, array($id_empleado));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }



}