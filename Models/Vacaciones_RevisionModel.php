<?php

class Vacaciones_RevisionModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectAllSolicitudes()
    {
        $sql = "SELECT
                    sv.id_solicitud AS solicitud_id,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    e.primer_apellido,
                    e.segundo_apellido,  
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    e.nombres AS nombres, 
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo,
                    CONCAT(jefe_info.primer_apellido, ' ', jefe_info.segundo_apellido, ', ', jefe_info.nombres) AS jefe,
                    e.identificacion, 
                    e.correo_empresarial AS correo,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.dias_retraso AS dias_retraso,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
                    tc.asunto,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    SUM(ds.valor) AS dias -- Nueva columna para acumular los días
                FROM solicitud_vacaciones sv
                LEFT JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN jefes_lideres jefe ON sv.responsable_aprobacion = jefe.id
                LEFT JOIN empleado_tb jefe_info ON jefe.usuario = jefe_info.id_empleado
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";

        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAllPeriodos()
    {
        $sql = "SELECT 
            p.id_periodo, 
            p.id_empleado, 
            p.fecha_inicio, 
            p.fecha_fin, 
            p.dias_consumidos, 
            p.dias_disponibles,
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo
        FROM periodo_vacaciones p
        INNER JOIN empleado_tb e ON p.id_empleado = e.id_empleado";

        $request = $this->select_all($sql);
        return $request;
    }

    public function getPeriodosVacaciones($id_empleado = 0)
    {
        if ($id_empleado > 0) {
            $sql = "SELECT 
                p.id_periodo, 
                p.id_empleado, 
                p.fecha_inicio, 
                p.fecha_fin, 
                p.dias_totales,
                p.dias_consumidos, 
                p.dias_disponibles,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo
            FROM periodo_vacaciones p
            INNER JOIN empleado_tb e ON p.id_empleado = e.id_empleado 
            WHERE p.id_empleado = ?";
            return $this->select_multi($sql, [$id_empleado]);
        } else {
            $sql = "SELECT 
                p.id_periodo, 
                p.id_empleado, 
                p.fecha_inicio, 
                p.fecha_fin, 
                p.dias_consumidos, 
                p.dias_disponibles,
                p.dias_totales,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo
            FROM periodo_vacaciones p
            INNER JOIN empleado_tb e ON p.id_empleado = e.id_empleado";
            return $this->select_all($sql);
        }
    }

    public function selectPersonal()
    {
        $sql = "SELECT 
        id_empleado,
        CONCAT(primer_apellido, ' ', segundo_apellido, ', ', nombres) AS nombre_completo
        FROM empleado_tb 
        WHERE estado = 'Activo'";
        $request = $this->select_all($sql);
        return $request;
    }


    public function selectAllSolicitudesbyMes($mes)
    {
        $sql = "SELECT
                    sv.id_solicitud AS solicitud_id,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    e.primer_apellido,
                    e.segundo_apellido,  
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    e.nombres AS nombres, 
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo,
                    CONCAT(jefe_info.primer_apellido, ' ', jefe_info.segundo_apellido, ', ', jefe_info.nombres) AS jefe,
                    e.identificacion, 
                    e.correo_empresarial AS correo,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.dias_retraso AS dias_retraso,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
                    tc.asunto,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS dias,
                    SUM(ds.valor) AS dias_suma -- Nueva columna para acumular los días
                FROM solicitud_vacaciones sv
                LEFT JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN jefes_lideres jefe ON sv.responsable_aprobacion = jefe.id
                LEFT JOIN empleado_tb jefe_info ON jefe.usuario = jefe_info.id_empleado
                INNER JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE MONTH(sv.fecha_solicitud) = ?
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";
        $request = $this->select_multi($sql, array($mes));
        return $request;
    }

    public function selectSolicitudesRevisadasMes()
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE estado != 'Pendiente' 
              AND MONTH(fecha_aprobacion) = MONTH(CURDATE())
              AND YEAR(fecha_aprobacion) = YEAR(CURDATE());
        ";
        $request = $this->select($sql);
        return $request;
    }
    public function selectSolicitudesAprobadasMes()
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE estado = 'Aprobado' 
              AND MONTH(fecha_aprobacion) = MONTH(CURDATE())
              AND YEAR(fecha_aprobacion) = YEAR(CURDATE());
        ";
        $request = $this->select($sql);
        return $request;
    }
    public function selectSolicitudesRechazadasMes()
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE estado = 'Rechazado' 
              AND MONTH(fecha_aprobacion) = MONTH(CURDATE())
              AND YEAR(fecha_aprobacion) = YEAR(CURDATE());
        ";
        $request = $this->select($sql);
        return $request;
    }
    public function selectSolicitudesPendientesMes()
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE estado = 'Pendiente' 
              AND MONTH(fecha_solicitud) = MONTH(CURDATE())
              AND YEAR(fecha_solicitud) = YEAR(CURDATE());
        ";
        $request = $this->select($sql);
        return $request;
    }

    public function selectSolicitudesCacenladasMes()
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE estado = 'Cancelado' 
              AND MONTH(fecha_solicitud) = MONTH(CURDATE())
              AND YEAR(fecha_solicitud) = YEAR(CURDATE());
        ";
        $request = $this->select($sql);
        return $request;
    }

    public function getSolicitudesRevisadasMes($mes)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE MONTH(fecha_aprobacion) = ?
              AND estado != 'Pendiente' 
              AND YEAR(fecha_aprobacion) = YEAR(CURDATE());";

        $request = $this->select($sql, array($mes)); // Devuelve solo un registro
        return $request;
    }
    public function getSolicitudesAprobadasMes($mes)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE MONTH(fecha_aprobacion) = ?
              AND estado = 'Aprobado' 
              AND YEAR(fecha_aprobacion) = YEAR(CURDATE());";

        $request = $this->select($sql, array($mes)); // Devuelve solo un registro
        return $request;
    }
    public function getSolicitudesRechazadoMes($mes)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE MONTH(fecha_aprobacion) = ?
              AND estado = 'Rechazado' 
              AND YEAR(fecha_aprobacion) = YEAR(CURDATE());";

        $request = $this->select($sql, array($mes)); // Devuelve solo un registro
        return $request;
    }
    public function getSolicitudesPendienteMes($mes)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE MONTH(fecha_solicitud) = ?
              AND estado = 'Pendiente' 
              AND YEAR(fecha_solicitud) = YEAR(CURDATE());";

        $request = $this->select($sql, array($mes)); // Devuelve solo un registro
        return $request;
    }
    public function getSolicitudesCanceladasMes($mes)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM solicitud_vacaciones 
            WHERE MONTH(fecha_solicitud) = ?
              AND estado = 'Cancelado' 
              AND YEAR(fecha_solicitud) = YEAR(CURDATE());";

        $request = $this->select($sql, array($mes)); // Devuelve solo un registro
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
                e.identificacion,
                e.correo_empresarial,
                jle.id as responsable,
                e.jefe_inmediato,
                CONCAT(jefe.nombres, ' ', jefe.primer_apellido, ' ', jefe.segundo_apellido) AS nombre_jefe,
                p.nombre_area AS area_jefe
            FROM empleado_tb e
            INNER JOIN jefes_lideres jle ON e.id_empleado = jle.usuario
            LEFT JOIN jefes_lideres jl ON e.jefe_inmediato = jl.id
            LEFT JOIN empleado_tb jefe ON jl.usuario = jefe.id_empleado
            LEFT JOIN area_laboral p ON jl.area = p.id_area
            WHERE e.correo_empresarial = ?";

        $request = $this->select($sql, array($correo_empresarial));
        return $request; // Devuelve un array vacío si no hay registros
    }

    public function selectSolicitudesPorJefe($id_empleado)
    {
        $sql = "SELECT
                    sv.id_solicitud AS solicitud_id,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    e.primer_apellido,
                    e.segundo_apellido,  
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    e.nombres AS nombres, 
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo,
                    e.identificacion, 
                    e.correo_empresarial AS correo,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.dias_retraso AS dias_retraso,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
                    tc.asunto,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    SUM(ds.valor) AS dias_suma -- Nueva columna para acumular los días
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.responsable_aprobacion = ?
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";

        return $this->select_multi_parameters($sql, array($id_empleado));
    }

    public function selectSolicitudesPorJefeOperativa($id_empleado)
    {
        $sql = "SELECT
                    sv.id_solicitud AS solicitud_id,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.fecha_ingreso, 
                    e.primer_apellido,
                    e.segundo_apellido,  
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    e.nombres AS nombres, 
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ', ', e.nombres) AS nombre_completo,
                    e.identificacion, 
                    e.correo_empresarial AS correo,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.dias_retraso AS dias_retraso,
                    sv.responsable_aprobacion AS responsable_aprobacion,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
                    tc.asunto,
                    SUM(ds.valor) AS dias_suma,
                    GROUP_CONCAT(ds.fecha ORDER BY ds.fecha ASC) AS fechas,
                    GROUP_CONCAT(ds.valor ORDER BY ds.fecha ASC) AS valores,
                    CASE 
                        WHEN sv.revision_aprobador_1 = ? THEN 'revision_aprobador_1'
                        WHEN sv.revision_aprobador_2 = ? THEN 'revision_aprobador_2'
                        WHEN sv.revision_aprobador_3 = ? THEN 'revision_aprobador_3'
                        ELSE 'Ninguno'
                    END AS mi_revision
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.responsable_aprobacion = ?
                OR (sv.responsable_aprobacion IS NULL AND sv.revision_aprobador_1 = ?)
                OR (sv.responsable_aprobacion IS NULL AND sv.revision_aprobador_2 = ?)
                OR (sv.responsable_aprobacion IS NULL AND sv.revision_aprobador_3 = ?)
                GROUP BY sv.id_solicitud
                ORDER BY sv.fecha_solicitud DESC";
        return $this->select_multi_parameters($sql, array($id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado));
    }


    public function getSolicitudes($id_empleado)
    {
        $sql = "SELECT 
                    e.id_empleado,
                    -- Cantidad de solicitudes por estado
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Aprobado' THEN sv.id_solicitud END), 0) AS solicitudes_aprobadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Pendiente' THEN sv.id_solicitud END), 0) AS solicitudes_pendientes,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Cancelado' THEN sv.id_solicitud END), 0) AS solicitudes_canceladas,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Rechazado' THEN sv.id_solicitud END), 0) AS solicitudes_rechazadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado !='Pendiente' AND sv.estado != 'Cancelado' THEN sv.id_solicitud END), 0) AS solicitudes_revisadas
                FROM empleado_tb e
                LEFT JOIN solicitud_vacaciones sv ON e.id_empleado = sv.id_empleado
                LEFT JOIN jefes_lideres jl ON e.id_empleado = jl.usuario
                WHERE sv.responsable_aprobacion = ? ";

        $request = $this->select_parameters($sql, array($id_empleado));
        return $request;
    }

    public function getSolicitudesOperativa($usuario)
    {
        $sql = "SELECT 
                    e.id_empleado,
                    -- Cantidad de solicitudes por estado
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Aprobado' THEN sv.id_solicitud END), 0) AS solicitudes_aprobadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado LIKE '%Pendiente%' THEN sv.id_solicitud END), 0) AS solicitudes_pendientes,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Cancelado' THEN sv.id_solicitud END), 0) AS solicitudes_canceladas,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado = 'Rechazado' THEN sv.id_solicitud END), 0) AS solicitudes_rechazadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN sv.estado NOT LIKE '%Pendiente%' AND sv.estado != 'Cancelado' THEN sv.id_solicitud END), 0) AS solicitudes_revisadas
    
                FROM empleado_tb e
                LEFT JOIN solicitud_vacaciones sv ON e.id_empleado = sv.id_empleado
                LEFT JOIN jefes_lideres jl ON e.id_empleado = jl.usuario
                WHERE sv.responsable_aprobacion = ? 
                OR ? IN (sv.revision_aprobador_1, sv.revision_aprobador_2, sv.revision_aprobador_3)
                ";
    
        $request = $this->select_parameters($sql, array($usuario, $usuario));
        return $request ?: []; // Devuelve un array vacío si no hay registros
    }

    public function getSolicitudesByMes($mes)
    {
        $sql = "SELECT 
                    -- Cantidad de solicitudes por estado en el mes seleccionado
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Aprobado' THEN id_solicitud END), 0) AS solicitudes_aprobadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Pendiente' THEN id_solicitud END), 0) AS solicitudes_pendientes,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Cancelado' THEN id_solicitud END), 0) AS solicitudes_canceladas,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Rechazado' THEN id_solicitud END), 0) AS solicitudes_rechazadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado NOT IN ('Pendiente', 'Cancelado') THEN id_solicitud END), 0) AS solicitudes_revisadas
                FROM solicitud_vacaciones
                WHERE MONTH(fecha_solicitud) = ?";  

        $request = $this->select_parameters($sql, array($mes));
        return $request;
    }

    public function getSolicitudesSolo()
    {
        $sql = "SELECT 
                    -- Cantidad de solicitudes por estado en el mes seleccionado
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Aprobado' THEN id_solicitud END), 0) AS solicitudes_aprobadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Pendiente' THEN id_solicitud END), 0) AS solicitudes_pendientes,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Cancelado' THEN id_solicitud END), 0) AS solicitudes_canceladas,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado = 'Rechazado' THEN id_solicitud END), 0) AS solicitudes_rechazadas,
                    COALESCE(COUNT(DISTINCT CASE WHEN estado NOT IN ('Pendiente', 'Cancelado') THEN id_solicitud END), 0) AS solicitudes_revisadas
                FROM solicitud_vacaciones
                WHERE MONTH(fecha_solicitud) = MONTH(CURDATE())";  

        $request = $this->select($sql);
        return $request;
    }



    public function selectSolicitudesRevisadasJefes($id_empleado)
    {
        $sql = "SELECT 
                    COUNT(*) as total 
                FROM solicitud_vacaciones sv
                INNER JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                INNER JOIN jefes_lideres jl ON e.id_empleado = jl.usuario
                WHERE sv.estado NOT IN ('Pendiente', 'Cancelado') 
                AND sv.responsable_aprobacion = ?";

        $request = $this->select($sql, array($id_empleado)); // Pasa el parámetro
        return $request;
    }


    public function selectSolicitudesbyID($id_solicitud)
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    e.id_empleado,
                    e.codigo_empleado,
                    e.primer_apellido,
                    e.segundo_apellido,
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                    CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                    e.correo_empresarial,
                    e.nombres AS nombres, 
                    e.identificacion, 
                    e.formulario_vacaciones,
                    sv.grupo_dias AS grupo_dias,
                    sv.fecha_solicitud AS fecha_solicitud,
                    sv.fecha_aprobacion AS fecha_aprobacion,
                    sv.dias_retraso AS dias_retraso,
                    COALESCE(sv.responsable_aprobacion, '') AS responsable_aprobacion,
                    COALESCE(sv.revision_aprobador_1, '') AS revision_aprobador_1,
                    COALESCE(sv.revision_aprobador_2, '') AS revision_aprobador_2,
                    COALESCE(sv.revision_aprobador_3, '') AS revision_aprobador_3,
                    sv.estado AS estado,
                    sv.comentario_solicitud AS comentario_solicitud,
                    sv.comentario_respuesta AS comentario_respuesta,
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
                LEFT JOIN empleado_tb e ON sv.id_empleado = e.id_empleado
                LEFT JOIN jefes_lideres jefe ON sv.responsable_aprobacion = jefe.id
                LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
                LEFT JOIN tb_categorias tc ON sv.id_categoria = tc.id_categoria
                WHERE sv.id_solicitud = ?
                GROUP BY sv.id_solicitud ";

        $request = $this->select($sql, array($id_solicitud)); // Devuelve solo un registro
        return $request;
    }

    public function selectJefesID($id_solicitud)// jefes
    {
        $sql = "SELECT
                    sv.id_solicitud,
                    sv.responsable_aprobacion,
                    CONCAT(responsable_info.nombres, ' ', responsable_info.primer_apellido, ' ', responsable_info.segundo_apellido) AS responsable_aprobador,
                    responsable_info.correo_empresarial AS correo_responsable,
                    sv.comentario_respuesta,
                    sv.revision_aprobador_1,
                    CONCAT(aprobador1_info.nombres, ' ', aprobador1_info.primer_apellido, ' ', aprobador1_info.segundo_apellido) AS nombre_aprobador_1,
                    aprobador1_info.correo_empresarial AS correo_aprobador_1,
                    sv.comentario_aprobador_1,
                    sv.revision_aprobador_2,
                    CONCAT(aprobador2_info.nombres, ' ', aprobador2_info.primer_apellido, ' ', aprobador2_info.segundo_apellido) AS nombre_aprobador_2,
                    aprobador2_info.correo_empresarial AS correo_aprobador_2,
                    sv.comentario_aprobador_2,
                    sv.revision_aprobador_3,
                    CONCAT(aprobador3_info.nombres, ' ', aprobador3_info.primer_apellido, ' ', aprobador3_info.segundo_apellido) AS nombre_aprobador_3,
                    aprobador3_info.correo_empresarial AS correo_aprobador_3,
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

    public function selectPersonalIDempleado($id_empleado)
    {
        $sql = "SELECT
                e.id_empleado,
                je.id AS jefe_id,
                e.codigo_empleado,
                e.fecha_ingreso,
                e.primer_apellido,
                e.segundo_apellido,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido) AS apellidos,
                CONCAT(e.primer_apellido, ' ', e.segundo_apellido, ' ', e.nombres) AS nombre_completo,
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
            LEFT JOIN jefes_lideres je ON e.id_empleado = je.usuario
            LEFT JOIN empleado_tb jefe ON jl.usuario = jefe.id_empleado
            LEFT JOIN area_laboral p ON jl.area = p.id_area
            WHERE e.id_empleado = ?";

        $request = $this->select($sql, array($id_empleado));
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

    public function getSolicitudWithDetails($id_solicitud)
    {
        $sql = "SELECT sv.*, ds.fecha, ds.valor, ds.dia 
            FROM solicitud_vacaciones sv
            LEFT JOIN detalle_solicitud_vacaciones ds ON sv.id_solicitud = ds.id_solicitud
            WHERE sv.id_solicitud = ?";
        return $this->select_multi($sql, [$id_solicitud]);
    }


    public function updateAprobarOperativa1(int $id_solicitud, string $estado, ?string $comentario, ?string $comentario_respuesta, ?string $revision_aprobador_2)
    {
        $sql = "UPDATE solicitud_vacaciones 
                SET estado = ?,
                    comentario_respuesta = ?,  
                    comentario_aprobador_1 = ?, 
                    revision_aprobador_2 = ?,
                    fecha_aprobacion = NOW()
                WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $comentario, $comentario_respuesta, $revision_aprobador_2, $id_solicitud]);
    }

    public function updateAprobarOperativa2(int $id_solicitud, string $estado, ?string $comentario, ?string $comentario_respuesta, ?string $revision_aprobador_3)
    {
        $sql = "UPDATE solicitud_vacaciones 
                SET estado = ?, 
                    comentario_respuesta = ?, 
                    comentario_aprobador_2 = ?, 
                    revision_aprobador_3 = ?,
                    fecha_aprobacion = NOW()
                WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $comentario, $comentario_respuesta, $revision_aprobador_3, $id_solicitud]);
    }

    public function updateAprobarOperativa3(int $id_solicitud, string $estado, ?string $comentario, ?string $comentario_respuesta, ?string $revision_aprobador_2)
    {
        $sql = "UPDATE solicitud_vacaciones 
                SET estado = ?, 
                    comentario_aprobador_1 = ?, 
                    comentario_respuesta = ?, 
                    revision_aprobador_2 = ?,
                    fecha_aprobacion = NOW()
                WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $comentario, $comentario_respuesta, $revision_aprobador_2, $id_solicitud]);
    }


    public function updateEstadoDetalles($id_solicitud, $estado)
    {
        $sql = "UPDATE detalle_solicitud_vacaciones 
            SET estado = ? 
            WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $id_solicitud]);
    }

    public function updateEstadoSolicitud($id_solicitud, $estado, $comentario = null)
    {
        $sql = "UPDATE solicitud_vacaciones 
                SET estado = ?, comentario_respuesta = ?, fecha_aprobacion = NOW()
                WHERE id_solicitud = ?";
        return $this->update($sql, [$estado, $comentario, $id_solicitud]);
    }

    // VACACIONES LOGICA 

    public function getDetallesSolicitud($id_solicitud)
    {
        $sql = "SELECT d.id_solicitud, d.fecha, d.valor, s.id_empleado
                FROM detalle_solicitud_vacaciones d
                INNER JOIN solicitud_vacaciones s ON d.id_solicitud = s.id_solicitud
                WHERE d.id_solicitud = ?";

        return $this->select_multi($sql, [$id_solicitud]);  // Usamos select_all para obtener múltiples filas
    }



    // Actualizar los días en empleado_tb
    public function actualizarPeriodoEmpleado($id_empleado, $diasAprobados, $categoria_solicitud)
    {
        // Si el estado es 12, no se resta nada
        if ($categoria_solicitud == 12) {
            return true;
        }

        // Obtener los periodos de vacaciones disponibles
        $sql = "SELECT id_periodo, dias_disponibles FROM periodo_vacaciones 
                WHERE id_empleado = ? AND dias_disponibles > 0 
                ORDER BY fecha_inicio ASC";

        $periodos = $this->select_multi($sql, [$id_empleado]);

        if (empty($periodos)) {
            return false; // No hay días disponibles
        }

        foreach ($periodos as $periodo) {
            if ($diasAprobados <= 0)
                break; // Ya se restó todo lo necesario

            $id_periodo = $periodo['id_periodo'];
            $disponibles = $periodo['dias_disponibles'];

            // Restar días de este periodo
            $diasARestar = min($diasAprobados, $disponibles);
            $diasAprobados -= $diasARestar;

            // Actualizar el periodo con los nuevos valores
            $sqlUpdate = "UPDATE periodo_vacaciones 
                          SET dias_disponibles = dias_disponibles - ?, 
                              dias_consumidos = dias_consumidos + ? 
                          WHERE id_periodo = ?";

            $this->update($sqlUpdate, [$diasARestar, $diasARestar, $id_periodo]);
        }

        return true;
    }

    public function revertirSolicitud($id_empleado, $diasAprobados, $categoria_solicitud)
    {
        // Si el estado es 12, no se resta nada
        if ($categoria_solicitud == 12) {
            return true;
        }
    
        // Obtener los periodos de vacaciones disponibles
        $sql = "SELECT id_periodo, dias_disponibles, dias_consumidos FROM periodo_vacaciones 
                WHERE id_empleado = ? AND dias_consumidos >= 0 
                ORDER BY fecha_inicio ASC";
    
        $periodos = $this->select_multi($sql, [$id_empleado]);
    
        if (empty($periodos)) {
            return false; // No hay días consumidos que se puedan devolver
        }
    
        foreach ($periodos as $periodo) {
            if ($diasAprobados <= 0) {
                break; // Ya se han renovado todos los días necesarios
            }
    
            $id_periodo = $periodo['id_periodo'];
            $consumidos = $periodo['dias_consumidos'];
    
            // Sumamos días a los disponibles y restamos de los consumidos
            $diasARestar = min($diasAprobados, $consumidos);
            $diasAprobados -= $diasARestar;
    
            // Actualizar el periodo con los nuevos valores
            if ($diasARestar > 0) {
                $sqlUpdate = "UPDATE periodo_vacaciones 
                              SET dias_disponibles = dias_disponibles + ?, 
                                  dias_consumidos = dias_consumidos - ? 
                              WHERE id_periodo = ?";
    
                $this->update($sqlUpdate, [$diasARestar, $diasARestar, $id_periodo]);
            }
        }
    
        return true;
    }
    


}
