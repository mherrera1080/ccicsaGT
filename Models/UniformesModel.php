<?php
class UniformesModel extends Mysql
{

    public $id_empleado;
    public $id_uniforme;
    public $grupo_asignacion;

    public function __construct()
    {
        parent::__construct();
    }

    public function select_uniformes()
    {
        $sql = "SELECT
            ua.id_asignacion,
          	ua.id_empleado,
            CONCAT(et.nombres, ' ', et.primer_apellido, ' ',et.segundo_apellido) as nombres,
            ua.grupo_asignacion,
            MAX(CASE WHEN ut.nombre_prenda = 'Camisas' THEN ua.cantidad ELSE '' END) AS Camisas,
            MAX(CASE WHEN ut.nombre_prenda = 'Pantalones' THEN ua.cantidad ELSE '' END) AS Pantalones,
            MAX(CASE WHEN ut.nombre_prenda = 'Botas' THEN ua.cantidad ELSE '' END) AS Botas,
            MAX(CASE WHEN ut.nombre_prenda = 'Capa' THEN ua.cantidad ELSE '' END) AS Capas,
            MAX(ua.fecha_asignacion) AS fecha_asignacion,
            YEAR(MAX(ua.fecha_asignacion)) AS año
        FROM uniformes_asignacion ua
        INNER JOIN uniformes_tb ut ON ua.id_uniforme = ut.id_uniforme   
        INNER JOIN empleado_tb et ON ua.id_empleado = et.id_empleado
        GROUP BY ua.grupo_asignacion, ua.id_empleado";
        $request = $this->select_all($sql);
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
                CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo,
                e.nombres,
                e.identificacion,
                e.correo_empresarial,
                e.formulario_vacaciones,
                e.jefe_inmediato,
                CONCAT(jefe.nombres, ' ', jefe.primer_apellido, ' ', jefe.segundo_apellido) AS nombre_jefe,
                p.nombre_puesto as nombre_puesto,
                d.nombre_departamento,
                a.nombre_area AS area_jefe,
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
            LEFT JOIN area_laboral a ON jl.area = a.id_area
            LEFT JOIN puestos_tb p ON e.puesto_contrato = p.id_puesto
            LEFT JOIN departamento_laboral d ON e.departamento = d.id_departamento
            WHERE e.correo_empresarial = ?";

        $request = $this->select($sql, array($correo_empresarial));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }

    public function getUniformes($id_empleado)
    {

        $sql = "SELECT
                id_empleado,
                codigo_empleado,
                fecha_ingreso,
                primer_apellido,
                segundo_apellido,
                CONCAT(primer_apellido, ' ', segundo_apellido) AS apellidos,
                nombres,
                CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo,
                identificacion,
                correo_empresarial,
                formulario_vacaciones
            FROM empleado_tb
            WHERE id_empleado = ? ";

        $request = $this->select($sql, array($id_empleado));
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

    public function getUniformebyId(int $grupo_asignacion)
    {
        $this->grupo_asignacion = $grupo_asignacion;
        $sql = "SELECT
                    ua.id_asignacion,
                    ua.id_empleado,
                    ua.grupo_asignacion,
                    et.codigo_empleado,
                    MAX(CASE WHEN ut.nombre_prenda = 'Camisas' THEN ua.cantidad ELSE '' END) AS Camisas,
                    MAX(CASE WHEN ut.nombre_prenda = 'Pantalones' THEN ua.cantidad ELSE '' END) AS Pantalones,
                    MAX(CASE WHEN ut.nombre_prenda = 'Botas' THEN ua.cantidad ELSE '' END) AS Botas,
                    MAX(ua.fecha_asignacion) AS fecha_asignacion,
                    YEAR(MAX(ua.fecha_asignacion)) AS año,
                    CONCAT(et.nombres, ' ', et.primer_apellido, ' ', et.segundo_apellido) as nombres
                FROM uniformes_asignacion ua
                INNER JOIN uniformes_tb ut ON ua.id_uniforme = ut.id_uniforme   
                INNER JOIN empleado_tb et ON ua.id_empleado = et.id_empleado
                WHERE ua.grupo_asignacion = ?
                GROUP BY ua.grupo_asignacion, ua.id_empleado";

        $params = array($this->grupo_asignacion);
        $request = $this->select($sql, $params); // Usa parámetros para evitar inyección SQL
        return $request;
    }

    public function getUniformebyIdUP(int $grupo_asignacion)
    {
        $this->grupo_asignacion = $grupo_asignacion;
        $sql = "SELECT
                    ua.id_asignacion,
                    ua.id_empleado,
                    ua.grupo_asignacion,
                    MAX(ua.fecha_asignacion) AS fecha_asignacion,
                    YEAR(MAX(ua.fecha_asignacion)) AS año,
                    CONCAT(et.nombres, ' ', et.primer_apellido, ' ', et.segundo_apellido) as nombres,
                    ud.ubicacion as ubicacion
                FROM uniformes_asignacion ua
                INNER JOIN uniformes_tb ut ON ua.id_uniforme = ut.id_uniforme   
                INNER JOIN empleado_tb et ON ua.id_empleado = et.id_empleado
                LEFT JOIN uniformes_documento ud ON et.id_empleado = ud.id_empleado
                WHERE ua.grupo_asignacion = ?
                GROUP BY ua.grupo_asignacion, ua.id_empleado";

        $params = array($this->grupo_asignacion);
        $request = $this->select($sql, $params); // Ejecuta la consulta con parámetros preparados
        return $request;
    }


    public function getUniformesbyId($id_empleado)
    {
        $sql = "SELECT
            ua.id_empleado,
            ua.id_asignacion,
            ua.grupo_asignacion,
            MAX(CASE WHEN ut.nombre_prenda = 'Camisas' THEN ua.cantidad ELSE '' END) AS Camisas,
            MAX(CASE WHEN ut.nombre_prenda = 'Pantalones' THEN ua.cantidad ELSE '' END) AS Pantalones,
            MAX(CASE WHEN ut.nombre_prenda = 'Botas' THEN ua.cantidad ELSE '' END) AS Botas,
            MAX(ua.fecha_asignacion) AS fecha_asignacion
        FROM uniformes_asignacion ua
        LEFT JOIN uniformes_tb ut ON ua.id_uniforme = ut.id_uniforme
        WHERE ua.id_empleado = ?
        GROUP BY ua.grupo_asignacion, ua.id_empleado
        ";

        $request = $this->select_multi($sql, array($id_empleado));
        return $request;
    }

    public function getAllUniformes()
    {
        $sql = "SELECT id_uniforme, nombre_prenda, unidad FROM uniformes_tb";
        return $this->select_all($sql);
    }

    public function getNextGroupId()
    {
        $sql = "SELECT MAX(grupo_asignacion) + 1 AS next_group FROM uniformes_asignacion";
        $result = $this->select($sql);
        return ($result['next_group'] != null) ? $result['next_group'] : 1; // Si no hay ningún grupo, empezamos con 1
    }

    // Insertar una asignación de uniforme
    public function insertAsignacionUniforme($id_empleado, $grupo_asignacion, $uniforme_id, $cantidad)
    {
        // Insertar en uniformes_asignacion
        $sql = "INSERT INTO uniformes_asignacion (id_empleado, grupo_asignacion, id_uniforme, cantidad, fecha_asignacion) 
                VALUES (?, ?, ?, ?, NOW())";
        $arrData = array($id_empleado, $grupo_asignacion, $uniforme_id, $cantidad);
        $this->insert($sql, $arrData);

        // Verificar si el grupo_asignacion ya existe en aprobacion_uniformes
        $sqlCheck = "SELECT grupo_aprobacion FROM aprobacion_uniformes WHERE grupo_aprobacion = ?";
        $request = $this->select($sqlCheck, [$grupo_asignacion]);

        if (empty($request)) {
            // Si no existe, inserta en aprobacion_uniformes
            $sqlInsert = "INSERT INTO aprobacion_uniformes (grupo_aprobacion, constancia, estado) 
                          VALUES (?, '', 'pendiente')";
            $arrAprobacion = array($grupo_asignacion);
            $this->insert($sqlInsert, $arrAprobacion);

            return "Asignación y grupo de aprobación insertados correctamente.";
        }

        return "Asignación de uniforme insertada. El grupo de aprobación ya existía.";
    }


    public function subirDocumento($id_empleado, $fecha_ingresada, $ubicacion, $grupo_asignacion)
    {
        $sql = "INSERT INTO uniformes_documento (id_empleado, fecha_ingresada, ubicacion, id_grupo) 
                VALUES (?, ?, ?, ?)";
        $arrData = array($id_empleado, $fecha_ingresada, $ubicacion, $grupo_asignacion);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

}
