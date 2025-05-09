<?php

class PersonalModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectBaja()
    {
        $activo = "Activo";
        $baja = "Baja";

        $sql = "SELECT * FROM `empleado_tb` WHERE estado = $baja";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAlta()
    {
        $activo = "Activo";
        $baja = "Baja";

        $sql = "SELECT * FROM `empleado_tb` WHERE estado = $activo";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectPersonal()
    {
        $sql = "SELECT 
            id_empleado, 
            IVR, 
            codigo_empleado, 
            empresa, 
            fecha_ingreso, 
            primer_apellido,
            segundo_apellido, 
            CONCAT(primer_apellido, ' ', segundo_apellido) as apellidos,
            nombres, 
            identificacion, 
            expedientes, 
            region, 
            puesto_contrato, 
            puesto_operativo,              
            lider_proceso, 
            jefe_inmediato, 
            departamento, 
            area,
            id_rol, 
            estado 
        FROM empleado_tb ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectUsuario($correo_empresarial)
    {
        $sql = "SELECT
                id_user,
                usuario_id,
                role_id
            FROM users_sistema 
            WHERE correo_empresarial = ?";
        $request = $this->select($sql, array($correo_empresarial));
        return $request ? $request : []; // Devuelve un array vacío si no hay registros
    }


    public function getNomina()
    {
        $sql = "SELECT 
            COUNT(*) AS total_nomina,
            SUM(CASE WHEN ie.genero = 'Hombre' THEN 1 ELSE 0 END) AS total_hombres,
            SUM(CASE WHEN ie.genero = 'Mujer' THEN 1 ELSE 0 END) AS total_mujeres
        FROM empleado_tb e
        INNER JOIN info_empleado ie ON e.identificacion = ie.identificacion
        WHERE e.estado != 'Baja';
        ";
        $request = $this->select($sql);
        return $request;
    }

    public function selectPersonalNomina()
    {
        $sql = "SELECT 
                    e.id_empleado,
                    e.IVR,
                    e.codigo_empleado,
                    e.fecha_ingreso,
                    e.primer_apellido,
                    e.segundo_apellido,
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
                    e.correo_empresarial,
                    e.nombres,
                    e.identificacion,
                    p1.nombre_puesto AS puesto_contrato, 
                    p2.nombre_puesto AS puesto_operativo, 
                    d.nombre_departamento as departamento,
                    CONCAT(la.nombre_area , ' | ', ln.codigo,'|', ln.descripcion ) as area_laboral,
                    e.estado 
                FROM 
                    empleado_tb e
                INNER JOIN 
                    puestos_tb p1 ON e.puesto_contrato = p1.id_puesto
                INNER JOIN 
                    puestos_tb p2 ON e.puesto_operativo = p2.id_puesto
                INNER JOIN 
                    departamento_laboral d ON e.departamento = d.id_departamento
                INNER JOIN 
                    area_laboral la ON e.area = la.id_area
                INNER JOIN 
                    linea_negocio ln ON la.linea_negocio = ln.id_ln
                WHERE e.estado =  'Activo'
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function buscarPersonal($termino)
    {
        $sql = "SELECT 
            id_empleado, 
            IVR, 
            codigo_empleado, 
            empresa, 
            fecha_ingreso, 
            primer_apellido,
            segundo_apellido,  
            CONCAT(primer_apellido, ' ', segundo_apellido) as apellidos,
            nombres, 
            identificacion, 
            expedientes, 
            region, 
            correo_empresarial,
            puesto_contrato, 
            puesto_operativo,
            lider_proceso, 
            jefe_inmediato, 
            departamento, 
            area, 
            id_rol, 
            evaluacion_competencia,
            fecha_evaluacion_competencia,
            archivo_evalu_competencia,
            estado 
        FROM empleado_tb  
        WHERE nombres LIKE ? OR primer_apellido LIKE ? OR segundo_apellido LIKE ? OR identificacion LIKE ?";

        $param = "%$termino%";
        $params = [$param, $param, $param, $param];

        // Llamar al método select_multi para ejecutar la consulta
        return $this->select_multi($sql, $params);
    }


    public function selectPersonalID($id_empleado)
    {
        $sql = "SELECT 
            id_empleado, 
            IVR, 
            codigo_empleado, 
            empresa, 
            fecha_ingreso, 
            primer_apellido,
            segundo_apellido,  
            CONCAT(primer_apellido, ' ', segundo_apellido) as apellidos,
            nombres, 
            CONCAT(primer_apellido, ' ', segundo_apellido, ' ', nombres) as nombre,
            CONCAT(nombres, ' ',primer_apellido, ' ', segundo_apellido) as nombre_completo,
            identificacion, 
            expedientes, 
            region, 
            correo_empresarial,
            puesto_contrato, 
            puesto_operativo,
            lider_proceso, 
            jefe_inmediato, 
            departamento, 
            area, 
            id_rol, 
            evaluacion_competencia,
            fecha_evaluacion_competencia,
            archivo_evalu_competencia,
            estado 
        FROM empleado_tb 
        WHERE id_empleado = ?";
        $request = $this->select($sql, array($id_empleado));
        return $request;
    }

    public function selectPersonalNominaBaja()
    {
        $sql = "SELECT 
                    e.id_empleado,
                    e.IVR,
                    e.codigo_empleado,
                    e.fecha_ingreso,
                    e.primer_apellido,
                    e.segundo_apellido,
                    CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
                    e.nombres,
                    e.identificacion,
                    p1.nombre_puesto AS puesto_contrato, 
                    p2.nombre_puesto AS puesto_operativo,  
                    d.nombre_departamento as departamento,
                    la.nombre_area AS nombre_area, 
                    e.estado 
                FROM 
                    empleado_tb e
                INNER JOIN 
                    puestos_tb p1 ON e.puesto_contrato = p1.id_puesto
                INNER JOIN 
                    puestos_tb p2 ON e.puesto_operativo = p2.id_puesto
                INNER JOIN 
                    departamento_laboral d ON e.departamento = d.id_departamento
                INNER JOIN 
                    area_laboral la ON e.area = la.id_area
                    WHERE e.estado =  'Baja'     
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectlinea_negocio()
    {
        $sql = "SELECT * FROM linea_negocio";
        $request = $this->select_all($sql);
        return $request;
    }

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


    public function selectPuestos()
    {
        $sql = "SELECT * FROM puestos_tb";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectJefesLideres()
    {
        $sql = "SELECT 
            j.id AS id,
            concat( e.primer_apellido,' ',e.segundo_apellido,' ',e.nombres ) AS  usuario,
            concat( p.nombre_area,' || ',p.descripcion ) as area,
            j.estado as estado
        FROM jefes_lideres j
        INNER JOIN empleado_tb e on j.usuario = e.id_empleado
        INNER JOIN area_laboral p on j.area = p.id_area";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectDepartamento()
    {
        $sql = "SELECT * FROM departamento_laboral";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectRenuncias()
    {
        $sql = "SELECT * FROM tb_categorias WHERE tb_perteneciente = 'bajas'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertPersonal(?string $codigo_empleado, string $fecha_ingreso, string $primer_apellido, string $segundo_apellido, string $nombres, string $identificacion, int $puesto_contrato, int $puesto_operativo, ?int $lider_proceso, ?int $jefe_inmediato, int $departamento, int $area, ?string $estado)
    {

        $this->$identificacion = $identificacion;

        $return = 0;

        // Verificar si el usuario ya existe
        $sql = "SELECT * FROM empleado_tb WHERE identificacion = '{$this->$identificacion}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            // Insertar nuevo usuario
            $columnas = "codigo_empleado, fecha_ingreso, primer_apellido, segundo_apellido, nombres, identificacion, puesto_contrato, puesto_operativo, lider_proceso, jefe_inmediato, departamento, linea_negocio, estado";
            $query_insert = "INSERT INTO empleado_tb($columnas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $arrData = array(
                $this->$codigo_empleado,
                $this->$fecha_ingreso,
                $this->$primer_apellido,
                $this->$segundo_apellido,
                $this->$nombres,
                $this->$identificacion,
                $this->$puesto_contrato,
                $this->$puesto_operativo,
                $this->$lider_proceso,
                $this->$jefe_inmediato,
                $this->$departamento,
                $this->$area,
                $this->$estado
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
        int $puesto_contrato,
        int $puesto_operativo,
        ?int $lider_proceso,
        ?int $jefe_inmediato,
        int $departamento,
        int $area,
        string $correo_empresarial,
        string $IVR,
        string $evaluacion_competencia,
        string $fecha_evaluacion_competencia,
        ?string $archivo_evalu_competencia,
        string $formulario_vacaciones, 
        ?int $salario_base,
        ?int $bonificacion,
        ?string $kpi1,
        ?string $kpi2,
        ?string $kpi_max,
        string $responsable
    ) {
        // Verificar el estado actual del empleado
        $sqlCurrent = "SELECT puesto_contrato, puesto_operativo, lider_proceso, jefe_inmediato 
                       FROM empleado_tb WHERE id_empleado = ?";
        $currentData = $this->select($sqlCurrent, array($id_empleado));

        // Preparar cambios en movimientos si hay diferencias
        $movimientos = [];
        if ($currentData['puesto_contrato'] != $puesto_contrato) {
            $movimientos[] = [
                'campo' => 'puesto_contrato',
                'anterior' => $this->getNombrePuesto($currentData['puesto_contrato']),
                'nuevo' => $this->getNombrePuesto($puesto_contrato)
            ];
        }
        if ($currentData['puesto_operativo'] != $puesto_operativo) {
            $movimientos[] = [
                'campo' => 'puesto_operativo',
                'anterior' => $this->getNombrePuesto($currentData['puesto_operativo']),
                'nuevo' => $this->getNombrePuesto($puesto_operativo)
            ];
        }
        if ($currentData['lider_proceso'] != $lider_proceso) {
            $movimientos[] = [
                'campo' => 'lider_proceso',
                'anterior' => $this->getNombreJefe($currentData['lider_proceso']),
                'nuevo' => $this->getNombreJefe($lider_proceso)
            ];
        }
        if ($currentData['jefe_inmediato'] != $jefe_inmediato) {
            $movimientos[] = [
                'campo' => 'jefe_inmediato',
                'anterior' => $this->getNombreJefe($currentData['jefe_inmediato']),
                'nuevo' => $this->getNombreJefe($jefe_inmediato)
            ];
        }

        // Actualizar datos del empleado
        $sql = "UPDATE empleado_tb SET 
                puesto_contrato = ?, 
                puesto_operativo = ?, 
                lider_proceso = ?, 
                jefe_inmediato = ?, 
                departamento = ?, 
                area = ?, 
                correo_empresarial = ?, 
                IVR = ?, 
                evaluacion_competencia = ?, 
                fecha_evaluacion_competencia = ?, 
                archivo_evalu_competencia = ?,
                formulario_vacaciones = ?,
                salario_base = ?, 
                bonificacion = ?, 
                kpi1 = ?, 
                kpi2 = ?, 
                kpi_max = ?
                WHERE id_empleado = ?";
        $arrData = array(
            $puesto_contrato,
            $puesto_operativo,
            $lider_proceso,
            $jefe_inmediato,
            $departamento,
            $area,
            $correo_empresarial,
            $IVR,
            $evaluacion_competencia,
            $fecha_evaluacion_competencia,
            $archivo_evalu_competencia,
            $formulario_vacaciones,
            $salario_base,
            $bonificacion,
            $kpi1,
            $kpi2,
            $kpi_max,
            $id_empleado
        );
        $request = $this->update($sql, $arrData);

        // Insertar movimientos en la tabla si hubo cambios
        if (!empty($movimientos)) {
            // Inicializar valores por defecto
            $puesto_contrato = 'N/C';
            $puesto_operativo = 'N/C';
            $lider_proceso = 'N/C';
            $jefe_inmediato = 'N/C';

            // Procesar los movimientos para actualizar los valores que cambiaron
            foreach ($movimientos as $movimiento) {
                switch ($movimiento['campo']) {
                    case 'puesto_contrato':
                        $puesto_contrato = $movimiento['nuevo'];
                        break;
                    case 'puesto_operativo':
                        $puesto_operativo = $movimiento['nuevo'];
                        break;
                    case 'lider_proceso':
                        $lider_proceso = $movimiento['nuevo'];
                        break;
                    case 'jefe_inmediato':
                        $jefe_inmediato = $movimiento['nuevo'];
                        break;
                }
            }

            // Preparar la consulta para registrar todos los cambios en un único registro
            $sqlMovimiento = "INSERT INTO movimientos_empleado 
                (empleado_id, puesto_contrato, puesto_operativo, lider_proceso, jefe_inmediato, 
                 fecha_cambio, responsable) 
                VALUES (?, ?, ?, ?, ?, NOW(), ?)";

            // Datos del registro
            $arrMovimiento = array(
                $id_empleado,
                $puesto_contrato,
                $puesto_operativo,
                $lider_proceso,
                $jefe_inmediato,
                $responsable
            );

            // Ejecutar la inserción
            $this->insert($sqlMovimiento, $arrMovimiento);
        }

        return $request;
    }

    public function Avance($id_empleado)
    {
        $sql = "SELECT 
            id_empleado, 
            codigo_empleado, 
            empresa, 
            fecha_ingreso, 
            primer_apellido,
            segundo_apellido, 
            CONCAT(primer_apellido, ' ', segundo_apellido) as apellidos,
            CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo,
            nombres, 
            identificacion, 
            expedientes, 
            region, 
            puesto_contrato, 
            puesto_operativo,
            lider_proceso, 
            jefe_inmediato, 
            departamento, 
            area, 
            id_rol, 
            estado 
        FROM empleado_tb 
        WHERE id_empleado = ?";
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


    public function updateEstadoUsuario(int $id_empleado, string $nuevo_estado, string $fecha_salida, string $razon_baja, string $comentario = null, string $observaciones)
    {
        // 1. Buscar el historial más reciente del empleado
        $sql_get_historial = "SELECT id_historial FROM historial_empleado 
                              WHERE empleado_id = ? 
                              AND fecha_baja is null
                              ORDER BY fecha_alta DESC LIMIT 1";
        $arrGetHistorial = array($id_empleado);
        $historial_data = $this->select($sql_get_historial, $arrGetHistorial);

        // 2. Consultar datos finales del puesto del empleado
        $sql_get_final_data = "SELECT 
                pc.nombre_puesto AS puesto_contrato_final, 
                po.nombre_puesto AS puesto_operativo_final,  
                d.nombre_departamento AS departamento_final,
                a.nombre_area AS area_final,
                e.salario_base as salario_final
        FROM empleado_tb e 
        LEFT JOIN puestos_tb pc ON e.puesto_contrato = pc.id_puesto
        LEFT JOIN puestos_tb po ON e.puesto_operativo = po.id_puesto
        LEFT JOIN departamento_laboral d ON e.departamento = d.id_departamento
        LEFT JOIN area_laboral a ON e.area = a.id_area
        LEFT JOIN linea_negocio ln ON a.linea_negocio = ln.codigo
        WHERE e.id_empleado = ?";
        $arrGetData = array($id_empleado);
        $final_data = $this->select($sql_get_final_data, $arrGetData);

        // 3. Si no hay historial, crear uno nuevo
        if (!$historial_data) {
            error_log("⚠️ No se encontro el historial ");
            return false;
        }

        // 4. Ahora que hay un historial, obtener su ID
        if ($historial_data) {
            $id_historial = $historial_data['id_historial'];

            // 5. Actualizar el historial con los datos de baja
            $sql_update_historial = "UPDATE historial_empleado 
                                     SET fecha_baja = CURDATE(),
                                        fecha_salida = ?,
                                        razon_baja = ?, 
                                        comentario = ?, 
                                        observaciones = ?, 
                                        puesto_contrato_final = ?, 
                                        puesto_operativo_final = ?, 
                                        departamento_final = ?, 
                                        area_final = ?,
                                        salario_final =?,
                                        estado = 'Baja'
                                     WHERE id_historial = ?";

            $arrUpdateHistorial = array(
                $fecha_salida,
                $razon_baja,
                $comentario,
                $observaciones,
                $final_data['puesto_contrato_final'],
                $final_data['puesto_operativo_final'],
                $final_data['departamento_final'],
                $final_data['area_final'],
                $final_data['salario_final'],
                $id_historial
            );

            $this->update($sql_update_historial, $arrUpdateHistorial);
        } else {
            error_log("⚠️ No se pudo obtener o crear un historial para empleado_id: " . $id_empleado);
            return false;
        }

        // 6. Actualizar el estado del empleado en la tabla empleado_tb
        $sql_update_estado = "UPDATE empleado_tb SET estado = ? WHERE id_empleado = ?";
        $arrData = array($nuevo_estado, $id_empleado);
        $request = $this->update($sql_update_estado, $arrData);

        return $request;
    }


    public function reContratacion(
        int $id_empleado,
        int $id_jefe_inmediato,
        int $id_lider_proceso,
        int $id_puesto_contrato,
        int $id_puesto_operativo,
        int $id_departamento,
        int $id_area,
        ?int $salario_base,
        ?int $bonificacion,
        ?string $kpi1,
        ?string $kpi2,
        ?string $kpi_max,
        string $responsable,
        string $estado
    ) {
        // Obtener los datos actuales del empleado antes de la actualización
        $sqlSelect = "SELECT 
                        jefe_inmediato,
                        lider_proceso,
                        puesto_contrato,
                        puesto_operativo,
                        departamento,
                        area
                      FROM empleado_tb
                      WHERE id_empleado = ?";

        $arrSelect = array($id_empleado);
        $empleadoActual = $this->select($sqlSelect, $arrSelect);

        if ($empleadoActual) {
            // Insertar en el historial con los IDs antes de actualizar
            $sqlInsert = "INSERT INTO movimientos_empleado (
                empleado_id, 
                jefe_inmediato,
                lider_proceso,
                puesto_contrato, 
                puesto_operativo, 
                departamento, 
                area, 
                responsable,
                movimiento,
                fecha_cambio
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?, NOW())";

            $arrInsert = array(
                $id_empleado,
                $empleadoActual['jefe_inmediato'],
                $empleadoActual['lider_proceso'],
                $empleadoActual['puesto_contrato'],
                $empleadoActual['puesto_operativo'],
                $empleadoActual['departamento'],
                $empleadoActual['area'],
                $responsable,
                $estado
            );

            $this->insert($sqlInsert, $arrInsert);
        }

        // Ahora actualizar la información del empleado con los nuevos datos
        $sqlUpdate = "UPDATE empleado_tb SET
                jefe_inmediato = ?,
                lider_proceso = ?,
                puesto_contrato = ?, 
                puesto_operativo = ?, 
                departamento = ?, 
                area = ?,
                salario_base = ?, 
                bonificacion = ?, 
                kpi1 = ?, 
                kpi2 = ?, 
                kpi_max = ?, 
                estado = ? 
                WHERE id_empleado = ?";

        $arrUpdate = array(
            $id_jefe_inmediato,
            $id_lider_proceso,
            $id_puesto_contrato,
            $id_puesto_operativo,
            $id_departamento,
            $id_area,
            $salario_base,
            $bonificacion,
            $kpi1,
            $kpi2,
            $kpi_max,
            $estado,
            $id_empleado
        );

        return $this->update($sqlUpdate, $arrUpdate);
    }



    private function getNombreJefe(?int $id_puesto): string
    {
        // Verificar si el valor es null o no es un número válido
        if ($id_puesto === null) {
            return "Desconocido";
        }

        $sql = "SELECT 
            CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre_completo
            FROM jefes_lideres j
            INNER JOIN empleado_tb e ON j.usuario = e.id_empleado
            WHERE j.id = ?";

        $request = $this->select($sql, array($id_puesto));

        // Retornar el nombre completo si existe, de lo contrario, retornar 'Desconocido'
        return $request['nombre_completo'] ?? "Desconocido";
    }


    private function getNombrePuesto(int $id_puesto): string
    {
        $sql = "SELECT nombre_puesto FROM puestos_tb WHERE id_puesto = ?";
        $request = $this->select($sql, array($id_puesto));
        return $request['nombre_puesto'] ?? "Desconocido";
    }

    private function getNombreDepartamento(int $id_departamento): string
    {
        $sql = "SELECT 
                CONCAT(d.nombre_departamento, ' | ', a.nombre_area ) AS nombre_departamento 
            FROM departamento_laboral d
            INNER JOIN area_laboral a ON d.id_departamento = a.id_area
            WHERE id_departamento = ?";
        $request = $this->select($sql, array($id_departamento));
        return $request['nombre_departamento'] ?? "Desconocido";
    }

    private function getNombreLineaNegocio(int $id_linea): string
    {
        $sql = "SELECT 
                CONCAT(codigo, '|', descripcion ) AS nombre_linea 
                FROM linea_negocio 
                WHERE id_ln = ?";
        $request = $this->select($sql, array($id_linea));
        return $request['nombre_linea'] ?? "Desconocido";
    }

    public function Perfil($id_empleado)
    {
        $sql = "SELECT 
            e.id_empleado,
            e.IVR,
            e.codigo_empleado, 
            e.empresa, 
            e.fecha_ingreso, 
            e.fecha_contratacion,
            e.fecha_prueba,
            e.primer_apellido,
            e.segundo_apellido, 
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
            CONCAT( e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) as nombre_completo,
            e.correo_empresarial,
            e.nombres, 
            e.identificacion, 
            e.expedientes, 
            e.region, 
            e.puesto_contrato, 
            e.puesto_operativo,
            e.lider_proceso,
            e.jefe_inmediato, 
            e.departamento, 
            e.area,
            e.salario_base,
            e.bonificacion,
            e.kpi1,
            e.kpi2,
            e.kpi_max,
            al.linea_negocio, 
            p1.nombre_puesto AS puesto_contrato_nombre,
            p1.id_puesto AS id_puesto_contrato,
            p2.nombre_puesto AS puesto_operativo_nombre,
            p2.id_puesto AS id_puesto_operativo,
            d.nombre_departamento AS nombre_departamento,
            d.id_departamento,
            CONCAT(al.nombre_area, '|', ln.descripcion) AS codigo_linea_negocio,
            al.id_area,
            ln.id_ln,
            e.evaluacion_competencia,
            e.fecha_evaluacion_competencia,
            e.archivo_evalu_competencia,
            e.id_rol, 
            e.estado,
            e.formulario_vacaciones 
        FROM empleado_tb e
        INNER JOIN puestos_tb p1 ON e.puesto_contrato = p1.id_puesto
        INNER JOIN puestos_tb p2 ON e.puesto_operativo = p2.id_puesto
        INNER JOIN departamento_laboral d ON e.departamento = d.id_departamento
        INNER JOIN area_laboral al ON e.area = al.id_area
        INNER JOIN linea_negocio ln ON al.linea_negocio = ln.id_ln
        WHERE e.id_empleado = ?";
        $request = $this->select($sql, array($id_empleado));
        return $request;
    }

    public function obtenerJefes($id_empleado)
    {
        $sql = "SELECT 
                jl_lider.id AS lider_id,
                CONCAT(lider.primer_apellido, ' ', lider.segundo_apellido, ' ', lider.nombres) AS lider_nombre,
                CONCAT(a_lider.nombre_area, ' || ', a_lider.descripcion) AS lider_area,
                jl_jefe.id AS jefe_id,
                CONCAT(jefe.primer_apellido, ' ', jefe.segundo_apellido, ' ', jefe.nombres) AS jefe_nombre,
                CONCAT(a_jefe.nombre_area, ' || ', a_jefe.descripcion) AS jefe_area
            FROM empleado_tb e
            -- Relación para líder de proceso
            LEFT JOIN jefes_lideres jl_lider ON e.lider_proceso = jl_lider.id
            LEFT JOIN empleado_tb lider ON jl_lider.usuario = lider.id_empleado
            LEFT JOIN area_laboral a_lider ON jl_lider.area = a_lider.id_area
            -- Relación para jefe inmediato
            LEFT JOIN jefes_lideres jl_jefe ON e.jefe_inmediato = jl_jefe.id
            LEFT JOIN empleado_tb jefe ON jl_jefe.usuario = jefe.id_empleado
            LEFT JOIN area_laboral a_jefe ON jl_jefe.area = a_jefe.id_area
            WHERE e.id_empleado = ?";
        $request = $this->select($sql, array($id_empleado));
        return $request;
    }

    public function InfoPerfil($id_empleado)
    {
        $sql = "SELECT
            e.id_empleado,
            e.identificacion,
            i.genero,
            i.estado_civil,
            i.pais,
            i.departamento AS departamento_info,
            i.municipio,
            i.fecha_nacimiento,
            i.mes_cumpleaños,
            i.edad,
            i.tipo_identificacion,
            i.pasaporte,
            i.lugar_nacimiento,
            i.no_seguro_social,
            i.info_academica,
            i.no_identificacion_tributaria,
            i.vig_licencia_conducir,
            i.numero_cel_corporativo,
            i.numero_cel_personal,
            i.numero_cel_emergencia,
            i.nombre_contacto_emergencia,
            i.parentesco_contacto_emergencia,
            i.direccion_domicilio,
            i.correo_electronico_personal,
            i.cant_hijos,
            i.tipo_sangre
        FROM empleado_tb AS e
        INNER JOIN info_empleado AS i ON e.identificacion = i.identificacion
        WHERE id_empleado = ?";
        $request = $this->select($sql, array($id_empleado));
        return $request;
    }


    public function infoDocumentos(int $id_empleado)
    {
        $sql = "SELECT
            a.id,
            a.empleado_id,
            em.id_empleado,
            CONCAT(em.nombres, ' ', em.primer_apellido, ' ', em.segundo_apellido) AS nombres,
            e.id_documento,
            e.nombre_documento,
            a.ubicacion
        FROM empleado_expedientes a
        INNER JOIN expedientes_documentos e ON a.expediente_id = e.id_documento
        INNER JOIN empleado_tb em ON a.empleado_id = em.id_empleado
        WHERE a.empleado_id = ?";
        $request = $this->select_multi($sql, array($id_empleado));
        return $request;
    }

    public function infoAcademica(int $id_empleado)
    {
        $sql = "SELECT 
            ae.id,
            ae.info_empleado,
            ae.academica_documentos,
            ae.respuesta,
            ae.ubicacion AS ubicacion,
            em.id_empleado,
            CONCAT(em.nombres, ' ', em.primer_apellido, ' ', em.segundo_apellido) AS nombres,
            ad.pregunta AS pregunta,
            ad.nombre_documento AS nombre,
            ad.descripcion
        FROM empleado_academica ae
        INNER JOIN academica_documentos ad ON ae.academica_documentos = ad.id_academica
        INNER JOIN info_empleado ie ON ae.info_empleado = ie.identificacion
        INNER JOIN empleado_tb em ON ie.identificacion = em.identificacion
        WHERE em.id_empleado = ?";

        $request = $this->select_multi($sql, array($id_empleado));
        return $request;
    }


}