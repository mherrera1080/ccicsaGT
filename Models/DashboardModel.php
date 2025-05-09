<?php
class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getContrataciones()
    {
        $sql = "SELECT 
            id_historial,
            empleado_id,
            fecha_alta AS Fecha,
            'Nuevo Ingreso' AS TipoMovimiento
        FROM historial_empleado
        WHERE caso = 'Nuevo Ingreso'

        UNION ALL

        SELECT 
            id_historial,
            empleado_id,
            fecha_baja AS Fecha,
            'Baja' AS TipoMovimiento
        FROM historial_empleado
        WHERE estado = 'Baja'

        UNION ALL

        SELECT 
            id_historial,
            empleado_id,
            fecha_alta AS Fecha, 
            'Recontratacion' AS TipoMovimiento
        FROM historial_empleado
        WHERE caso = 'Recontratacion' 

        ORDER BY Fecha, empleado_id";
        $request = $this->select_all($sql);

        // Opcional: Ordenar por fecha en PHP como respaldo
        usort($request, function ($a, $b) {
            return strtotime($a['Fecha']) - strtotime($b['Fecha']);
        });

        return $request;
    }

    public function getSolicitudVacaciones()
    {
        $sql = "SELECT 
            id_solicitud,
            id_empleado,
            fecha_solicitud AS Fecha,
            'Pendiente' AS TipoMovimiento
        FROM solicitud_vacaciones
        WHERE estado LIKE '%Pendiente%'

        UNION ALL

        SELECT 
            id_solicitud,
            id_empleado,
            fecha_aprobacion AS Fecha,
            'Aprobado' AS TipoMovimiento
        FROM solicitud_vacaciones
        WHERE estado = 'Aprobado'

        UNION ALL

        SELECT 
            id_solicitud,
            id_empleado,
            fecha_aprobacion AS Fecha,
            'Rechazado' AS TipoMovimiento
        FROM solicitud_vacaciones
        WHERE estado = 'Rechazado'

        UNION ALL

        SELECT 
            id_solicitud,
            id_empleado,
            fecha_solicitud AS Fecha,
            'Cancelado' AS TipoMovimiento
        FROM solicitud_vacaciones
        WHERE estado = 'Cancelado'  

        UNION ALL

        SELECT 
            id_solicitud,
            id_empleado,
            fecha_aprobacion AS Fecha,
            'Revertido' AS TipoMovimiento
        FROM solicitud_vacaciones
        WHERE estado = 'Revertido'
        
        ORDER BY Fecha, id_empleado ";
        $request = $this->select_all($sql);

        // Opcional: Ordenar por fecha en PHP como respaldo
        usort($request, function ($a, $b) {
            return strtotime($a['Fecha']) - strtotime($b['Fecha']);
        });

        return $request;
    }

    public function getContratacionesYear($year = null)
    {
        $year ?? date("Y"); // Si no se proporciona un año, usa el actual

        $sql = "SELECT 
            id_historial,
            empleado_id,
            fecha_alta AS Fecha,
            'Nuevo Ingreso' AS TipoMovimiento
        FROM historial_empleado
        WHERE caso = 'Nuevo Ingreso'
        AND YEAR(fecha_alta) = ?
    
                UNION ALL
    
                SELECT 
            id_historial,
            empleado_id,
            fecha_baja AS Fecha,
            'Baja' AS TipoMovimiento
        FROM historial_empleado
        WHERE estado = 'Baja' AND YEAR(fecha_baja) = ?
    
                UNION ALL
    
                SELECT 
            id_historial,
            empleado_id,
            fecha_alta AS Fecha, 
            'Recontratacion' AS TipoMovimiento
        FROM historial_empleado
        WHERE caso = 'Recontratacion' 
        AND YEAR(fecha_alta) = ?
    
        ORDER BY Fecha, empleado_id";

        $arrParams = [$year, $year, $year]; // Se pasa el mismo año a las 3 consultas
        $request = $this->select_multi($sql, $arrParams);

        return $request;
    }

    public function getSolicitudesYear($year = null)
    {
        $year ?? date("Y"); // Si no se proporciona un año, usa el actual

        $sql = "SELECT 
                id_solicitud,
                id_empleado,
                fecha_solicitud AS Fecha,
                'Pendiente' AS TipoMovimiento
            FROM solicitud_vacaciones
            WHERE estado LIKE '%Pendiente%' AND YEAR(fecha_solicitud) = ?
                
            UNION ALL

            SELECT 
                id_solicitud,
                id_empleado,
                fecha_aprobacion AS Fecha,
                'Aprobado' AS TipoMovimiento
            FROM solicitud_vacaciones
            WHERE estado = 'Aprobado' AND YEAR(fecha_aprobacion) = ?

            UNION ALL

            SELECT 
                id_solicitud,
                id_empleado,
                fecha_aprobacion AS Fecha,
                'Rechazado' AS TipoMovimiento
            FROM solicitud_vacaciones
            WHERE estado = 'Rechazado' AND YEAR(fecha_aprobacion) = ?

            UNION ALL

            SELECT 
                id_solicitud,
                id_empleado,
                fecha_solicitud AS Fecha,
                'Cancelado' AS TipoMovimiento
            FROM solicitud_vacaciones
            WHERE estado = 'Cancelado' AND YEAR(fecha_solicitud) = ?

            UNION ALL

            SELECT 
                id_solicitud,
                id_empleado,
                fecha_aprobacion AS Fecha,
                'Revertido' AS TipoMovimiento
            FROM solicitud_vacaciones
            WHERE estado = 'Revertido' AND YEAR(fecha_aprobacion) = ?
    
            ORDER BY Fecha, empleado_id";

        $arrParams = [$year, $year, $year]; // Se pasa el mismo año a las 3 consultas
        $request = $this->select_multi($sql, $arrParams);

        return $request;
    }

    public function selectAnio()
    {
        $sql = "SELECT 
            YEAR(fecha_alta) AS anio
        FROM historial_empleado
        GROUP BY anio
        HAVING COUNT(*) > 0
        ORDER BY anio";
        $request = $this->select_all($sql);
        return $request;
    }


    public function selectAnioVacaciones()
    {
        $sql = "SELECT 
            YEAR(fecha_solicitud) AS anio
        FROM solicitud_vacaciones
        GROUP BY anio
        HAVING COUNT(*) > 0
        ORDER BY anio";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectColaboradores()
    {
        $sql = "SELECT 
            id_empleado,
            CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido ) AS nombre_completo
        FROM empleado_tb
        GROUP BY id_empleado";
        $request = $this->select_all($sql);
        return $request;
    }


    public function getSolicitudesVacaciones()
    {
        $sql = "SELECT
            e.id_empleado,
            CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido ) AS nombre_completo,
            COUNT(s.id_solicitud) AS total_solicitudes
        FROM empleado_tb e
        LEFT JOIN solicitud_vacaciones s ON e.id_empleado = s.id_empleado
        GROUP BY e.id_empleado, nombre_completo
        ORDER BY e.id_empleado ASC";
        $request = $this->select_all($sql);
        return $request;
    }

    public function getRazonesBaja()
    {
        $sql = "SELECT 
            razon_baja, 
            COUNT(*) AS total_historial
        FROM historial_empleado
        WHERE estado = 'Baja' AND YEAR(fecha_baja) = YEAR(CURDATE())
        GROUP BY razon_baja
        ORDER BY total_historial DESC";

        return $this->select_all($sql);
    }


    public function getRazonesBajaMonths($mesBaja)
    {
        $sql = "SELECT 
            razon_baja, 
            COUNT(*) AS total_historial
        FROM historial_empleado
        WHERE estado = 'Baja' 
            AND YEAR(fecha_baja) = YEAR(CURDATE())
            AND MONTH(fecha_baja) = ?
        GROUP BY razon_baja
        ORDER BY total_historial DESC";

        return $this->select_multi($sql, [$mesBaja]);
    }


    public function getReclutamientos()
    {
        $sql = "SELECT 
            reclutador,
            asunto, 
            COUNT(*) AS total_reclutamientos
        FROM aprobaciones_empleado
        WHERE aprobacion = 'Aprobado' 
            AND YEAR(fecha_reclutacion) = YEAR(CURDATE())
        GROUP BY reclutador
        ORDER BY total_reclutamientos DESC";

        return $this->select_all($sql);
    }

    public function getReclutamientosMes($mesReclutamiento)
    {
        $sql = "SELECT 
            reclutador,
            asunto, 
            COUNT(*) AS total_reclutamientos
        FROM aprobaciones_empleado
        WHERE aprobacion = 'Aprobado' 
            AND YEAR(fecha_reclutacion) = YEAR(CURDATE())
            AND MONTH(fecha_reclutacion) = ?
        GROUP BY reclutador
        ORDER BY total_reclutamientos DESC";

        return $this->select_multi($sql, [$mesReclutamiento]);
    }

    public function getAreasAltas()
    {
        $sql = "SELECT 
            COUNT(*) AS total_altas,
            area_inicio
        FROM historial_empleado
        WHERE caso = 'Nuevo Ingreso'
            AND YEAR(fecha_alta) = YEAR(CURDATE())
        GROUP BY area_inicio 
        ORDER BY total_altas DESC";
        return $this->select_all($sql);
    }
    public function getAreasAltasMes($mesArea)
    {
        $sql = "SELECT 
            COUNT(*) AS total_altas,
            area_inicio
        FROM historial_empleado
        WHERE caso = 'Nuevo Ingreso'
            AND YEAR(fecha_alta) = YEAR(CURDATE())
             AND MONTH(fecha_alta) = ?
        GROUP BY area_inicio 
        ORDER BY total_altas DESC";

        return $this->select_multi($sql, [$mesArea]);
    }

    public function getAreasBajas()
    {
        $sql = "SELECT 
            COUNT(*) AS total_bajas,
            area_final
        FROM historial_empleado
        WHERE estado = 'Baja'
            AND YEAR(fecha_baja) = YEAR(CURDATE())
        GROUP BY area_final 
        ORDER BY total_bajas DESC";

        return $this->select_all($sql);
    }
    public function getAreasBajasMes($mesArea)
    {
        $sql = "SELECT 
            COUNT(*) AS total_bajas,
            area_final
        FROM historial_empleado
        WHERE estado = 'Baja'
            AND YEAR(fecha_baja) = YEAR(CURDATE())
            AND MONTH(fecha_baja) = ?
        GROUP BY area_final 
        ORDER BY total_bajas DESC";

        return $this->select_multi($sql, [$mesArea]);
    }

    public function selectDocumentos()
    {
        $sql = "SELECT 
            e.id_empleado, 
            CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
            COUNT(CASE WHEN ex.ubicacion IS NOT NULL AND ex.ubicacion <> '' THEN ex.expediente_id END) AS documentos_llenos,
            COUNT(ex.expediente_id) AS total_documentos_empleado,
            ROUND(
                (COUNT(CASE WHEN ex.ubicacion IS NOT NULL AND ex.ubicacion <> '' THEN ex.expediente_id END) * 100) / 
                NULLIF(COUNT(ex.expediente_id), 0), 2
            ) AS porcentaje_completo
        FROM empleado_tb e
        LEFT JOIN empleado_expedientes ex ON e.id_empleado = ex.empleado_id
        LEFT JOIN expedientes_documentos ed ON ex.expediente_id = ed.id_documento
        WHERE ed.estado = 'Activo'
        GROUP BY e.id_empleado
        ";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectDocumentosbyID($id_empleado)
    {
        $sql = "SELECT
            e.id_empleado,
            ed.nombre_documento,
            CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
            ex.ubicacion,
            ex.fecha_act            
        FROM empleado_expedientes ex
        INNER JOIN empleado_tb e ON ex.empleado_id = e.id_empleado
        INNER JOIN expedientes_documentos ed ON ex.expediente_id = ed.id_documento
        WHERE ed.estado = 'Activo' AND e.id_empleado = ?
        ";

        return $this->select_multi($sql, array($id_empleado));
    }

    public function getVacacionesAreas()
    {
        $sql = "SELECT 
            COUNT(*) AS total_solicitudes,
            a.nombre_area as areas_vacaciones
        FROM solicitud_vacaciones s 
        INNER JOIN area_laboral a ON s.area_solicitud = a.id_area
            AND YEAR(fecha_solicitud) = YEAR(CURDATE())
            AND MONTH(fecha_solicitud) = MONTH(CURDATE())
        GROUP BY s.area_solicitud 
        ";

        return $this->select_all($sql);
    }

    public function getVacacionesAreasbyMes($mesVacaciones)
    {
        $sql = "SELECT 
            COUNT(*) AS total_solicitudes,
            a.nombre_area as areas_vacaciones
        FROM solicitud_vacaciones s 
        INNER JOIN area_laboral a ON s.area_solicitud = a.id_area
            AND YEAR(s.fecha_solicitud) = YEAR(CURDATE())
            AND MONTH(s.fecha_solicitud) = ?
        GROUP BY s.area_solicitud 
        ";

        return $this->select_multi($sql, [$mesVacaciones]);
    }

    public function getRechazoSolicitudes()
    {
        $sql = "SELECT 
            SUM(total_solicitudes) AS total_solicitudes,
            nombre_completo
        FROM (
            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.responsable_aprobacion = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_solicitud) = YEAR(CURDATE())
                AND MONTH(s.fecha_solicitud) = MONTH(CURDATE())
                AND s.responsable_aprobacion IS NOT NULL
                AND s.comentario_respuesta IS NOT NULL
            GROUP BY nombre_completo

            UNION ALL

            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.revision_aprobador_1 = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_solicitud) = YEAR(CURDATE())
                AND MONTH(s.fecha_solicitud) = MONTH(CURDATE())
                AND s.revision_aprobador_1 IS NOT NULL
                AND s.comentario_aprobador_1 IS NOT NULL
            GROUP BY nombre_completo

            UNION ALL

            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.revision_aprobador_2 = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_solicitud) = YEAR(CURDATE())
                AND MONTH(s.fecha_solicitud) = MONTH(CURDATE())
                AND s.revision_aprobador_2 IS NOT NULL
                AND s.comentario_aprobador_2 IS NOT NULL
            GROUP BY nombre_completo

            UNION ALL

            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.revision_aprobador_3 = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_solicitud) = YEAR(CURDATE())
                AND MONTH(s.fecha_solicitud) = MONTH(CURDATE())
                AND s.revision_aprobador_3 IS NOT NULL
                AND s.comentario_aprobador_3 IS NOT NULL
            GROUP BY nombre_completo
        ) AS rechazos
        GROUP BY nombre_completo
        ORDER BY total_solicitudes DESC;
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function getRechazoSolicitudesbyMes($mesRechazos)
    {

        $sql = "SELECT 
            SUM(total_solicitudes) AS total_solicitudes,
            nombre_completo
        FROM (
            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.responsable_aprobacion = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_aprobacion) = YEAR(CURDATE())
                AND MONTH(s.fecha_aprobacion) = ?
                AND s.responsable_aprobacion IS NOT NULL
                AND s.comentario_respuesta IS NOT NULL
            GROUP BY nombre_completo

            UNION ALL

            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.revision_aprobador_1 = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_aprobacion) = YEAR(CURDATE())
                AND MONTH(s.fecha_aprobacion) = ?
                AND s.revision_aprobador_1 IS NOT NULL
                AND s.comentario_aprobador_1 IS NOT NULL
            GROUP BY nombre_completo

            UNION ALL

            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.revision_aprobador_2 = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_aprobacion) = YEAR(CURDATE())
                AND MONTH(s.fecha_aprobacion) = ?
                AND s.revision_aprobador_2 IS NOT NULL
                AND s.comentario_aprobador_2 IS NOT NULL
            GROUP BY nombre_completo

            UNION ALL

            SELECT 
                COUNT(s.id_solicitud) AS total_solicitudes,
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM solicitud_vacaciones s
            INNER JOIN jefes_lideres jl ON s.revision_aprobador_3 = jl.id
            INNER JOIN empleado_tb e ON jl.usuario = e.id_empleado
            WHERE s.estado = 'Rechazado' 
                AND YEAR(s.fecha_aprobacion) = YEAR(CURDATE())
                AND MONTH(s.fecha_aprobacion) = ?
                AND s.revision_aprobador_3 IS NOT NULL
                AND s.comentario_aprobador_3 IS NOT NULL
            GROUP BY nombre_completo
        ) AS rechazos
        GROUP BY nombre_completo
        ORDER BY total_solicitudes DESC;
        ";

        $arrParams = [$mesRechazos, $mesRechazos, $mesRechazos, $mesRechazos]; // Se pasa el mismo año a las 3 consultas
        $request = $this->select_multi($sql, $arrParams);

        return $request;
    }

    public function registroCierreSecion(string $no_empleado, string $empleado, string $modulo, string $accion, string $fecha, string $ip, string $hostname)
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

    public function getUserByIdentificacion($correo_empresarial)
    {
        $sql = "SELECT 
        u.correo_empresarial,
        e.estado,
        u.password
        FROM users_sistema u
        INNER JOIN empleado_tb e ON u.usuario_id = e.id_empleado
        WHERE u.correo_empresarial = ? AND e.estado = 'Activo'";
        $request = $this->select($sql, [$correo_empresarial]);
        return $request;
    }

    public function verificarCorreo($correo_empresarial)
    {
        $sql = "SELECT 
        id_empleado, 
        nombres,
        CONCAT(nombres,' ', primer_apellido,' ', segundo_apellido) AS nombre_completo
        FROM empleado_tb 
        WHERE correo_empresarial = ? AND estado = 'Activo'";
        $arrData = array($correo_empresarial);
        return $this->select($sql, $arrData); // Devuelve los datos si existe
    }

    public function guardarToken($correo, $token, $expires_at)
    {
        $sql = "INSERT INTO login_tokens (correo_empresarial, token, expires_at) VALUES (?, ?, ?)";
        $arrData = [$correo, $token, $expires_at];
        return $this->insert($sql, $arrData);
    }

    public function updateContra($correo_empresarial, string $password)
    {
        $updatePassword = "password = ?";
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users_sistema 
                SET $updatePassword
                WHERE correo_empresarial = ?";
        $arrData = array($password_hashed, $correo_empresarial);
        return $this->update($sql, $arrData);
    }

    public function validarToken($correo_empresarial, $token)
    {
        // Definir la zona horaria en PHP (por seguridad)
        date_default_timezone_set('America/Guatemala'); // Ajusta según tu zona horaria

        // Obtener la fecha y hora actual en la zona correcta
        $query = date("Y-m-d H:i:s");

        $sql = "SELECT 
            e.id_empleado, 
            e.nombres,
            e.codigo_empleado AS no_empleado,
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
            CONCAT(e.nombres, ' ',e.primer_apellido, ' ', e.segundo_apellido) as nombre_completo,
            e.correo_empresarial 
                FROM login_tokens t
                INNER JOIN empleado_tb e ON e.correo_empresarial = t.correo_empresarial
                WHERE t.correo_empresarial = ? 
                AND t.token = ? 
                AND t.expires_at > ?";

        $arrData = [$correo_empresarial, $token, $query];

        return $this->select($sql, $arrData); // Retorna los datos del usuario si el token es válido
    }

}