<?php
class RolesModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }


    public function Roles()
    {
        $sql = "SELECT
            id,
            role_name
            FROM roles_sistemas";

        $request = $this->select_all($sql);
        return $request;
    }


    public function rolesID($id)
    {
        $sql =
            "SELECT
        rs.id,
        rs.role_name,
        rm.modulo_id,
        m.nombre,
        rm.crear,
        rm.leer,
        rm.editar,
        rm.eliminar
        FROM roles_sistemas rs
        INNER JOIN roles_modulos rm ON rm.id = rs.id
        INNER JOIN modulos m ON rm.id = m.id_modulo
        where id = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }

    public function PermisosID($id)
    {
        $sql = "SELECT
                    rs.id,
                    rs.role_name,
                    rm.modulo_id,
                    m.nombre,
                    rm.crear,
                    rm.leer,
                    rm.editar,
                    rm.eliminar
                FROM roles_modulos rm
                INNER JOIN roles_sistemas rs ON rm.role_id = rs.id
                INNER JOIN modulos m ON rm.modulo_id = m.id_modulo
                WHERE rs.id = ?";

        // Usamos select_multi para múltiples resultados
        $request = $this->select_multi($sql, array($id));
        return $request;
    }


    public function deleteRol($id)
    {
        $sql = "DELETE FROM roles_sistemas WHERE id = ?";
        $request = $this->deletebyid($sql, [$id]);
        return $request;
    }

    public function insertRol(string $role_name)
    {
        $this->$role_name = $role_name;

        $return = 0;

        if (empty($request)) {
            $columnas = "role_name";
            $query_insert = "INSERT INTO roles_sistemas($columnas) VALUES (?)";
            $arrdata = array(
                $this->$role_name,
            );

            $request_insert = $this->insert($query_insert, $arrdata);
            $return = $request_insert;
        } else {
            $return = 'exists';
        }
        return $return;
    }

    public function updatePermiso($idRol, $moduloNombre, $crear, $leer, $editar, $eliminar)
    {
        $sql = "UPDATE roles_modulos 
            SET crear = ?, leer = ?, editar = ?, eliminar = ? 
            WHERE role_id = ? AND modulo_id = (SELECT id_modulo FROM modulos WHERE nombre = ?)";

        $params = [$crear, $leer, $editar, $eliminar, $idRol, $moduloNombre];
        return $this->update($sql, $params);
    }



}