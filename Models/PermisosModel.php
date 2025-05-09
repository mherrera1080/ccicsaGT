<?php
class PermisosModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Permisos()
    {
        $sql =
            "SELECT 
            rm.id,
            rm.role_id,
            rm.modulo_id,
            m.nombre as nombre_modulo,
            rm.crear,
            rm.leer,
            rm.editar,
            rm.eliminar
        FROM roles_modulos rm
        INNER JOIN modulos m ON rm.modulo_id = m.id_modulo";

        $request = $this->select_all($sql);
        return $request;
    }

    public function PermisosbyID($id)
    {
        $sql =
            "SELECT 
            rm.id,
            rm.role_id,
            rm.modulo_id,
            m.nombre as nombre_modulo,
            rm.crear,
            rm.leer,
            rm.editar,
            rm.eliminar
        FROM roles_modulos rm
        INNER JOIN modulos m ON rm.modulo_id = m.id_modulo
        where rm.id = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }

    public function updatePermisos($role_id, $modulo_id, $crear, $leer, $editar, $eliminar)
    {
        $sql = "UPDATE roles_modulos SET crear = ?, leer = ?, editar = ?, eliminar = ? WHERE role_id = ? AND modulo_id = ?";
        $params = [$crear, $leer, $editar, $eliminar, $role_id, $modulo_id];
        return $this->update($sql, $params);
    }

    public $role_id;

    public function permisosModulo(int $role_id)
    {
        $this->role_id = $role_id;
        $sql = "SELECT 
            r.role_id,
            r.modulo_id,
            m.nombre as modulo,
            r.crear,
            r.leer,
            r.editar,
            r.eliminar 
        FROM roles_modulos r 
        INNER JOIN modulos m ON r.modulo_id = m.id_modulo
        WHERE r.role_id = $this->role_id";
        
        $request = $this->select_all($sql); 
        $arrPermisos = array();
        for ($i = 0; $i < count($request); $i++) {
            $arrPermisos[$request[$i]['modulo_id']] = $request[$i];
        }
        return $arrPermisos;
    }
    


}