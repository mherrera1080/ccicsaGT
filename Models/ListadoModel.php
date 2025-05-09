<?php
class ListadoModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Empresa()
    {
        $sql =
            "SELECT 
            id_empresa,
            nombre,
            descripcion,
            nit,
            estado
        FROM empresa";

        $request = $this->select_all($sql);
        return $request;
    }

    public function EmpresabyID($id_empresa)
    {
        $sql =
            "SELECT 
            id_empresa,
            nombre,
            descripcion,
            nit,
            estado
        FROM empresa
        where id_empresa = ?";
        $request = $this->select($sql, array($id_empresa));
        return $request;
    }

    public function deleteEmpresabyID($id_empresa)
    {
        $sql = "DELETE FROM empresa WHERE id_empresa = ?";
        $request = $this->deletebyid($sql, [$id_empresa]);
        return $request;
    }

    public function insertEmpresa(string $nombre, string $descripcion, string $nit, string $estado)
    {
        $this->$nombre = $nombre;
        $this->$descripcion = $descripcion;
        $this->$nit = $nit;
        $this->$estado = $estado;

        $return = 0;

        if (empty($request)) {
            $columnas = "nombre, descripcion, nit, estado";
            $query_insert = "INSERT INTO empresa($columnas) VALUES (?,?,?,?)";
            $arrdata = array(
                $this->$nombre,
                $this->$descripcion,
                $this->$nit,
                $this->$estado,
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateEmpresa(int $id_empresa, string $nombre, string $descripcion, string $nit, string $estado)
    {
        $sql = " UPDATE empresa SET
        nombre = ?,
        descripcion = ?,
        nit = ?,
        estado = ?
        WHERE id_empresa = ?";

        $arrData = array(
            $nombre,
            $descripcion,
            $nit,
            $estado,
            $id_empresa
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    //PUESTOS

    public function Puestos()
    {
        $sql =
            "SELECT 
            id_puesto,
            nombre_puesto,
            descripcion,
            estado
        FROM puestos_tb";

        $request = $this->select_all($sql);
        return $request;
    }

    public function insertPuesto(string $nombre_puesto, string $descripcion, string $estado)
    {
        $this->$nombre_puesto = $nombre_puesto;
        $this->$descripcion = $descripcion;
        $this->$estado = $estado;

        $return = 0;

        if (empty($request)) {
            $columnas = "nombre_puesto, descripcion, estado";
            $query_insert = "INSERT INTO puestos_tb($columnas) VALUES (?,?,?)";
            $arrdata = array(
                $this->$nombre_puesto,
                $this->$descripcion,
                $this->$estado
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function puestosbyID($id_puesto)
    {
        $sql =
            "SELECT 
            id_puesto,
            nombre_puesto,
            descripcion,
            estado
        FROM puestos_tb
        WHERE id_puesto = ?";
        $request = $this->select($sql, array($id_puesto));
        return $request;
    }

    public function updatePuesto(int $id_puesto, string $nombre_puesto, string $descripcion, string $estado)
    {
        $sql = " UPDATE puestos_tb SET
        nombre_puesto = ?,
        descripcion = ?,
        estado = ?
        WHERE id_puesto = ?";

        $arrData = array(
            $nombre_puesto,
            $descripcion,
            $estado,
            $id_puesto
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function deletePuestosbyID($id_puesto)
    {
        $sql = "DELETE FROM puestos_tb WHERE id_puesto = ?";
        $request = $this->deletebyid($sql, [$id_puesto]);
        return $request;
    }


    public function Jefes()
    {
        $sql =
            "SELECT 
            j.id AS id,
            concat( e.primer_apellido, ' ',e.segundo_apellido,' ',e.nombres ) AS   usuario,
            concat( p.nombre_area,' || ',p.descripcion ) as area,
            j.estado as estado
        FROM jefes_lideres j
        INNER JOIN empleado_tb e on j.usuario = e.id_empleado
        INNER JOIN area_laboral p on j.area = p.id_area";

        $request = $this->select_all($sql);
        return $request;
    }

    public function jefesbyID($id)
    {
        $sql =
            "SELECT 
            j.id,
            j.usuario,
            j.area,
            concat( e.nombres, ' ',e.primer_apellido,' ',e.segundo_apellido ) AS nombre_completo,
            j.estado
        FROM jefes_lideres j
        INNER JOIN empleado_tb e ON j.usuario = e.id_empleado 
        where j.id = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }

    public function usuariosbyID($usuario)
    {
        $sql =
            "SELECT 
        id_empleado, 
        nombres, 
        CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo
        FROM empleado_tb
        where id_empleado = ?";
        $request = $this->select($sql, array($usuario));
        return $request;
    }

    public function deletejefesbyID($id)
    {
        $sql = "DELETE FROM jefes_lideres WHERE id = ?";
        $request = $this->deletebyid($sql, [$id]);
        return $request;
    }

    public function insertJefe(int $usuario, string $area, string $estado)
    {
        $this->$usuario = $usuario;
        $this->$area = $area;
        $this->$estado = $estado;

        $return = 0;

        if (empty($request)) {
            $columnas = "usuario, area, estado";
            $query_insert = "INSERT INTO jefes_lideres($columnas) VALUES (?,?,?)";
            $arrdata = array(
                $this->$usuario,
                $this->$area,
                $this->$estado
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateJefe(int $id, int $usuario, string $area, string $estado)
    {
        $sql = " UPDATE jefes_lideres  SET
        usuario = ?,
        area = ?,
        estado = ?
        WHERE id = ?";

        $arrData = array(
            $usuario,
            $area,
            $estado,
            $id
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function selectUsuarios()
    {
        $sql = "SELECT 
        id_empleado, 
        nombres, 
        CONCAT(primer_apellido, ' ', segundo_apellido) AS apellidos
        FROM empleado_tb
        WHERE estado = 'Activo'"; // Ajusta esta consulta a tu tabla de usuarios
        return $this->select_all($sql);
    }

    public function selectDimension()
    {
        $sql = "SELECT id_dimension, numero, descripcion, naturaleza FROM dimension "; // Ajusta esta consulta a tu tabla de usuarios
        return $this->select_all($sql);
    }

    // DEPARTAMENTOS

    public function Departamento()
    {
        $sql =
            "SELECT 
            d.id_departamento as id_departamento, 
            d.nombre_departamento as nombre_departamento,
            d.descripcion as descripcion,
            concat( a.nombre_area ) AS area,
            d.estado as estado
        FROM departamento_laboral d
        INNER JOIN area_laboral a on d.area = a.id_area";

        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAreas()
    {
        $sql = "SELECT id_area, nombre_area, descripcion FROM area_laboral"; // Ajusta esta consulta a tu tabla de usuarios
        return $this->select_all($sql);
    }

    public function departamentobyID($id_departamento)
    {
        $sql =
            "SELECT 
            id_departamento,
            nombre_departamento,
            descripcion,
            area,
            estado
        FROM departamento_laboral
        where id_departamento = ?";
        $request = $this->select($sql, array($id_departamento));
        return $request;
    }

    public function deleteDepartamentobyID($id_departamento)
    {
        $sql = "DELETE FROM departamento_laboral WHERE id_departamento = ?";
        $request = $this->deletebyid($sql, [$id_departamento]);
        return $request;
    }

    public function insertDepartamento(string $nombre_departamento, string $descripcion, int $area, string $estado)
    {
        $this->$nombre_departamento = $nombre_departamento;
        $this->$descripcion = $descripcion;
        $this->$area = $area;
        $this->$estado = $estado;

        $return = 0;

        if (empty($request)) {
            $columnas = "nombre_departamento, descripcion, area, estado";
            $query_insert = "INSERT INTO departamento_laboral($columnas) VALUES (?,?,?,?)";
            $arrdata = array(
                $this->$nombre_departamento,
                $this->$descripcion,
                $this->$area,
                $this->$estado,
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateDepartamento(int $id_departamento, string $nombre_departamento, string $descripcion, int $area, string $estado)
    {
        $sql = " UPDATE departamento_laboral SET
        nombre_departamento = ?,
        descripcion = ?,
        area = ?,
        estado = ?
        WHERE id_departamento = ?";

        $arrData = array(
            $nombre_departamento,
            $descripcion,
            $area,
            $estado,
            $id_departamento
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }


    // LINEA DE NEGOCIO
    public function LN()
    {
        $sql =
            "SELECT 
            ln.id_ln,
            ln.codigo,
            ln.descripcion,
            d.descripcion,
            CONCAT(d.numero, ' | ', d.descripcion ) AS descripcion_dimension
            FROM linea_negocio ln
            INNER JOIN dimension d ON ln.dimension = id_dimension
            ";

        $request = $this->select_all($sql);
        return $request;
    }

    public function lnbyID($id_ln)
    {
        $sql =
            "SELECT 
            id_ln,
            codigo,
            descripcion,
            dimension
        FROM linea_negocio
        where id_ln = ?";
        $request = $this->select($sql, array($id_ln));
        return $request;
    }

    public function deletelnbyID($id_ln)
    {
        $sql = "DELETE FROM linea_negocio WHERE id_ln = ?";
        $request = $this->deletebyid($sql, [$id_ln]);
        return $request;
    }

    public function insertLN(string $codigo, string $descripcion, int $dimension)
    {
        $this->$codigo = $codigo;
        $this->$descripcion = $descripcion;
        $this->$dimension = $dimension;

        $return = 0;

        if (empty($request)) {
            $columnas = "codigo, descripcion, dimension";
            $query_insert = "INSERT INTO linea_negocio($columnas) VALUES (?,?,?)";
            $arrdata = array(
                $this->$codigo,
                $this->$descripcion,
                $this->$dimension
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateLN(int $id_ln, string $codigo, string $descripcion, int $dimension)
    {
        $sql = " UPDATE linea_negocio SET
        codigo = ?,
        descripcion = ?,
        dimension = ?
        WHERE id_ln = ?";

        $arrData = array(
            $codigo,
            $descripcion,
            $dimension,
            $id_ln
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    //AREAS 

    public function Areas()
    {
        $sql =
            "SELECT 
            id_area,
            nombre_area,
            descripcion,
            estado
            FROM area_laboral";

        $request = $this->select_all($sql);
        return $request;
    }

    public function areasbyID($id_area)
    {
        $sql =
            "SELECT 
            id_area,
            nombre_area,
            descripcion,
            estado
        FROM area_laboral
        where id_area = ?";
        $request = $this->select($sql, array($id_area));
        return $request;
    }

    public function deleteAreasbyID($id_area)
    {
        $sql = "DELETE FROM area_laboral WHERE id_area = ?";
        $request = $this->deletebyid($sql, [$id_area]);
        return $request;
    }

    public function insertAreas(string $nombre_area, string $descripcion, string $estado)
    {
        $this->$nombre_area = $nombre_area;
        $this->$descripcion = $descripcion;
        $this->$estado = $estado;

        $return = 0;

        if (empty($request)) {
            $columnas = "nombre_area, descripcion, estado";
            $query_insert = "INSERT INTO area_laboral($columnas) VALUES (?,?,?)";
            $arrdata = array(
                $this->$nombre_area,
                $this->$descripcion,
                $this->$estado,
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateAreas(int $id_area, string $nombre_area, string $descripcion, string $estado)
    {
        $sql = " UPDATE area_laboral SET
        nombre_area = ?,
        descripcion = ?,
        estado = ?
        WHERE id_area = ?";

        $arrData = array(
            $nombre_area,
            $descripcion,
            $estado,
            $id_area
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function Dimensiones()
    {
        $sql =
            "SELECT 
            id_dimension,
            numero,
            descripcion,
            naturaleza,
            estado
            FROM dimension";

        $request = $this->select_all($sql);
        return $request;
    }

    public function dimensionesbyID($id_dimension)
    {
        $sql =
            "SELECT 
            id_dimension,
            numero,
            descripcion,
            naturaleza,
            estado
            FROM dimension
        where id_dimension = ?";
        $request = $this->select($sql, array($id_dimension));
        return $request;
    }

    public function deleteDimensionesbyID($id_dimension)
    {
        $sql = "DELETE FROM dimension WHERE id_dimension = ?";
        $request = $this->deletebyid($sql, [$id_dimension]);
        return $request;
    }

    public function insertDimensiones(string $numero, string $descripcion, string $naturaleza, string $estado)
    {
        // Asignación de valores a las variables de la clase
        $this->$numero = $numero;
        $this->$descripcion = $descripcion;
        $this->$naturaleza = $naturaleza;
        $this->$estado = $estado;

        // Comprobación de existencia previa
        $sql = "SELECT * FROM dimension WHERE numero = '{$this->$numero}'";
        $request = $this->select_all($sql);

        $return = 0;

        if (empty($request)) {
            // Inserción de nuevos datos
            $columnas = "numero, descripcion, naturaleza, estado";
            $query_insert = "INSERT INTO dimension($columnas) VALUES (?, ?, ?, ?)";
            $arrdata = array(
                $this->$numero,
                $this->$descripcion,
                $this->$naturaleza,
                $this->$estado,
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists'; // Regresar "exists" si el registro ya existe
        }

        return $return;
    }

    public function updateDimension(int $id_dimension, string $numero, string $descripcion, string $naturaleza, string $estado)
    {
        $sql = " UPDATE dimension SET
        numero = ?,
        descripcion = ?,
        naturaleza = ?,
        estado = ?
        WHERE id_dimension = ?";

        $arrData = array(
            $numero,
            $descripcion,
            $naturaleza,
            $estado,
            $id_dimension
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function Documentos()
    {
        $sql =
            "SELECT 
            id_documento,
            nombre_documento,
            estado
        FROM expedientes_documentos";

        $request = $this->select_all($sql);
        return $request;
    }

    public function DocumentosbyId($id_documento)
    {
        $sql =
            "SELECT 
            id_documento,
            nombre_documento,
            estado
        FROM expedientes_documentos
        where id_documento = ?";
        $request = $this->select($sql, array($id_documento));
        return $request;
    }

    public function insertDocumentos(string $nombre_documento)
    {
        $this->$nombre_documento = $nombre_documento;

        $return = 0;

        if (empty($request)) {
            $columnas = "nombre_documento";
            $query_insert = "INSERT INTO expedientes_documentos($columnas) VALUES (?)";
            $arrdata = array(
                $this->$nombre_documento
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateDocumento(int $id_documento, string $nombre_documento)
    {
        $sql = " UPDATE expedientes_documentos SET
        nombre_documento = ?
        WHERE id_documento = ?";
        $arrData = array(
            $nombre_documento,
            $id_documento
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function deleteDocumentosbyID(int $id_documento)
    {
        $sql = " UPDATE expedientes_documentos SET
        estado = 'Inhabilitado'
        WHERE id_documento = ?";

        $arrData = array(
            $id_documento
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function habilitarDocumentosbyID(int $id_documento)
    {
        $sql = " UPDATE expedientes_documentos SET
        estado = 'Activo'
        WHERE id_documento = ?";

        $arrData = array(
            $id_documento
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function Academico()
    {
        $sql =
            "SELECT 
            id_academica,
            nombre_documento,
            descripcion,
            estado
        FROM academica_documentos";

        $request = $this->select_all($sql);
        return $request;
    }

    public function AcademicobyId($id_academica)
    {
        $sql =
            "SELECT 
            id_academica,
            nombre_documento,
            descripcion,
            estado
        FROM academica_documentos
        where id_academica = ?";
        $request = $this->select($sql, array($id_academica));
        return $request;
    }


    public function deleteAcademicobyID(int $id_documento)
    {
        $sql = "UPDATE academica_documentos SET
        estado = 'Inhabilitado'
        WHERE id_academica = ?";

        $arrData = array(
            $id_documento
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function habilitarAcacemicobyID(int $id_documento)
    {
        $sql = " UPDATE academica_documentos SET
        estado = 'Activo'
        WHERE id_academica = ?";

        $arrData = array(
            $id_documento
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function insertAcademico(string $nombre_documento, string $descripcion)
    {
        $this->$nombre_documento = $nombre_documento;
        $this->$descripcion = $descripcion;

        $return = 0;

        if (empty($request)) {
            $columnas = "nombre_documento, descripcion";
            $query_insert = "INSERT INTO academica_documentos($columnas) VALUES (?,?)";
            $arrdata = array(
                $this->$nombre_documento,
                $this->$descripcion
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updateAcademico(int $id_academica, string $nombre_documento, string $descripcion)
    {
        $sql = " UPDATE academica_documentos SET
        nombre_documento = ?,
        descripcion = ?
        WHERE id_academica = ?";

        $arrData = array(
            $nombre_documento,
            $descripcion,
            $id_academica
        );

        $request = $this->update($sql, $arrData);
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
}