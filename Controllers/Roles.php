<?php
session_start();
class Roles extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos( ROLES);
	}

    public $views;
    public $model;
    public function Roles()
    {
        $data['page_id'] = ROLES;
        $data['page_tag'] = "Roles";
        $data['page_title'] = "Roles";
        $data['page_name'] = "Roles";
        $data['page_functions_js'] = "functions_roles.js";

        $this->views->getView($this, "Roles", $data);
    }

    public function getRoles()
    {
        $arrData = $this->model->Roles();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getRolbyID($id)
    {
        $data = $this->model->PermisosID($id);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function deleteRol($id)
    {
        $result = $this->model->deleteRol($id);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Rol eliminado exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar el Rol.',
            ];
        }

        echo json_encode($response);
    }


    public function setRol()
    {
        if ($_POST) {
            $nombreRol = strClean($_POST['role_name']); // Sanitiza el input

            // Verifica si el rol ya existe
            $requestRol = $this->model->insertRol($nombreRol);

            if ($requestRol > 0) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
            } elseif ($requestRol == 'exists') {
                $arrResponse = array('status' => false, 'msg' => 'El rol ya existe.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'No fue posible almacenar los datos.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function updatePermissions()
    {
        $data = json_decode(file_get_contents("php://input"), true); // Obtener los datos JSON

        $idRol = $data['idRol'];
        $permisos = $data['permisos'];

        foreach ($permisos as $permiso) {
            // Aquí deberías tener lógica para actualizar los permisos en la base de datos
            $moduloNombre = $permiso['moduloNombre'];
            $crear = $permiso['crear'];
            $leer = $permiso['leer'];
            $editar = $permiso['editar'];
            $eliminar = $permiso['eliminar'];

            // Actualiza los permisos en la base de datos (asegúrate de tener el método adecuado)
            // Por ejemplo:
            $this->model->updatePermiso($idRol, $moduloNombre, $crear, $leer, $editar, $eliminar);
        }

        // Respuesta
        echo json_encode(['status' => true, 'msg' => 'Permisos actualizados con éxito.']);
    }
    
}