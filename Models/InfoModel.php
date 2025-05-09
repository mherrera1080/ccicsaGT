<?php
class InfoModel extends Mysql
{

    public $info_empleado;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectPersonalID($id_empleado)
    {
        $sql = "SELECT 
            id_empleado, 
            IVR, 
            codigo_empleado, 
            empresa, 
            fecha_ingreso, 
            CONCAT(primer_apellido, ' ', segundo_apellido) as apellidos 
            nombres, 
            identificacion, 
            uniformes, 
            expedientes, 
            region, 
            nombre_encargado, 
            puesto_contrato, 
            puesto_operativo,  
            lider_proceso, 
            jefe_inmediato, 
            departamento, 
            LN, 
            clasificacion, 
            id_rol, 
            otros, 
            estado 
        FROM empleado_tb 
        WHERE id_empleado = ?";
        $request = $this->select($sql, array($id_empleado));
        return $request;
    }

    public function selectEstudios()
    {
        $sql = "SELECT 
        id_categoria,
        asunto,
        tb_perteneciente
        FROM tb_categorias WHERE tb_perteneciente = 'info_empleados_Estudios'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectNivel()
    {
        $sql = "SELECT 
        id_categoria,
        asunto,
        tb_perteneciente
        FROM tb_categorias WHERE tb_perteneciente = 'info_empleados_Nivel'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function MostrarInfo($identificacion)
    {
        $sql = "SELECT
            CONCAT(primer_apellido, ' ', segundo_apellido) as apellidos,
            concat(e.nombres, ' ', e.primer_apellido, ' ',e.segundo_apellido) as nombres,
            i.identificacion,
            i.genero,
            i.estado_civil,
            i.Pais,
            i.departamento,
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
            ec.id_categoria AS id_categoria_estudios,
            ec.asunto AS estudios,
            nc.id_categoria AS id_categoria_educacion,
            nc.asunto AS nivel_educativo,
            i.vig_licencia_conducir,
            i.numero_cel_corporativo,
            i.numero_cel_emergencia,
            i.parentesco_contacto_emergencia,
            i.numero_cel_personal,
            i.nombre_contacto_emergencia,
            i.direccion_domicilio,
            i.correo_electronico_personal,
            i.cant_hijos,
            i.tipo_sangre
        FROM info_empleado i
        INNER JOIN empleado_tb e ON i.identificacion = e.identificacion 
        LEFT JOIN tb_categorias ec ON i.estudios = ec.id_categoria
        LEFT JOIN tb_categorias nc ON i.nivel_educativo = nc.id_categoria
        WHERE i.identificacion = ?";
        $request = $this->select($sql, array($identificacion));
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

    public function updateInfo(string $identificacion, string $genero, string $estado_civil, string $Pais, string $departamento, string $municipio, string $fecha_nacimiento, int $mes_cumpleaños, int $edad, string $tipo_identificacion, string $pasaporte, string $lugar_nacimiento, string $no_seguro_social, string $no_identificacion_tributaria, string $vig_licencia_conducir, int $estudios, int $nivel_estudios, string $numero_cel_corporativo, string $numero_cel_personal, string $numero_cel_emergencia, string $nombre_contacto_emergencia, string $parentesco_contacto_emergencia, string $direccion_domicilio, string $correo_electronico_personal, int $cant_hijos, string $tipo_sangre)
    {
        $sql = "UPDATE info_empleado SET 
            genero = ?,
            estado_civil = ?,
            Pais = ?,
            departamento = ?,
            municipio = ?,
            fecha_nacimiento = ?,
            mes_cumpleaños = ?,
            edad = ?,
            tipo_identificacion = ?,
            pasaporte = ?,
            lugar_nacimiento = ?,
            no_seguro_social = ?,
            no_identificacion_tributaria = ?,
            vig_licencia_conducir = ?,
            estudios = ?,
            nivel_educativo = ?,
            numero_cel_corporativo = ?,
            numero_cel_personal = ?,
            numero_cel_emergencia = ?,
            nombre_contacto_emergencia = ?,
            parentesco_contacto_emergencia = ?,
            direccion_domicilio = ?,
            correo_electronico_personal = ?,
            cant_hijos = ?,
            tipo_sangre = ?
            WHERE identificacion = ?";
    
        $arrData = array(
            $genero,
            $estado_civil,
            $Pais,
            $departamento,
            $municipio,
            $fecha_nacimiento,
            $mes_cumpleaños,
            $edad,
            $tipo_identificacion,
            $pasaporte,
            $lugar_nacimiento,
            $no_seguro_social,
            $no_identificacion_tributaria,
            $vig_licencia_conducir,
            $estudios,
            $nivel_estudios,
            $numero_cel_corporativo,
            $numero_cel_personal,
            $numero_cel_emergencia,
            $nombre_contacto_emergencia,
            $parentesco_contacto_emergencia,
            $direccion_domicilio,
            $correo_electronico_personal,
            $cant_hijos,
            $tipo_sangre,
            $identificacion
        );
    
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function mostrarAcademica()
    {
        $sql = "SELECT
            ae.id,
            ae.info_empleado,
            ae.academica_documentos,
            ae.respuesta,
            ae.ubicacion,
            em.id_empleado,
            concat(em.nombres, '', em.primer_apellido, ' ',em.segundo_apellido) as nombres,
            ad.nombre_documento,
            ad.descripcion
        FROM empleado_academica ea
        inner join academica_documentos ad ON ea.academica_documentos = ad.id_academica
        inner join info_empleado ie ON ea.info_empleado = ie.identificacion
        INNER JOIN empleado_tb em ON a.empleado_id = em.id_empleado";
        $request = $this->select_all($sql);
        return $request;
    }


    public function getAcademica(string $info_empleado)
    {

        $this->info_empleado = $info_empleado;
        $sql =
            "SELECT 
            ae.id,
            ae.info_empleado,
            ae.academica_documentos,
            ae.respuesta,
            ae.ubicacion as ubicacion,
            em.id_empleado,
            concat(em.nombres, ' ', em.primer_apellido, ' ',em.segundo_apellido) as nombres,
            ad.nombre_documento as nombre,
            ad.descripcion
        FROM empleado_academica ae
        INNER JOIN academica_documentos ad ON ae.academica_documentos = ad.id_academica
        INNER JOIN info_empleado ie ON ae.info_empleado = ie.identificacion
        INNER JOIN empleado_tb em ON ie.identificacion = em.identificacion
        WHERE info_empleado = $this->info_empleado";
        $request = $this->select($sql);
        return $request;
    }

    public function getDocumentoById(int $id)
    {
        $sql = "SELECT 
        e.nombre_documento, 
        a.info_empleado 
        FROM empleado_academica a
        inner join academica_documentos e ON a.academica_documentos = e.id_academica
        WHERE a.id = ?";
        $params = [$id];
        $request = $this->select($sql, $params);
        return $request;
    }

    public function getAcademicabyID(string $info_empleado)
    {
        $sql = "SELECT 
            ae.id,
            ae.info_empleado,
            ae.academica_documentos,
            ae.respuesta,
            ae.ubicacion as ubicacion,
            em.id_empleado,
            concat(em.nombres, '', em.primer_apellido, ' ',em.segundo_apellido) as nombres,
            ad.nombre_documento as nombre,
            ad.descripcion
        FROM empleado_academica ae
        INNER JOIN academica_documentos ad ON ae.academica_documentos = ad.id_academica
        INNER JOIN info_empleado ie ON ae.info_empleado = ie.identificacion
        INNER JOIN empleado_tb em ON ie.identificacion = em.identificacion
        WHERE ae.info_empleado = ?";
        $request = $this->select_multi($sql, array($info_empleado));
        return $request;
    }

    public function AcademicabyID(int $id)
    {
        $sql = "SELECT 
            ae.id,
            em.id_empleado,
            ae.info_empleado,
            ae.academica_documentos,
            ae.respuesta,
            ae.ubicacion as ubicacion,
            em.id_empleado,
            CONCAT(em.nombres, ' ', em.primer_apellido, ' ',em.segundo_apellido) as nombres,
            ad.nombre_documento as nombre_documento,
            ad.descripcion
        FROM empleado_academica ae
        INNER JOIN academica_documentos ad ON ae.academica_documentos = ad.id_academica
        INNER JOIN info_empleado ie ON ae.info_empleado = ie.identificacion
        INNER JOIN empleado_tb em ON ie.identificacion = em.identificacion
        WHERE id = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }

    public function subirDocumento(int $id, ?string $ubicacion, string $fecha_act)
    {
        $sql = "UPDATE empleado_academica SET 
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
