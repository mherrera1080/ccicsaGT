<?php
session_start();
class Permisos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }
    public $views;
    public $model;

    public function Permisos()
    {
        $data['page_tag'] = "Permisos";
        $data['page_title'] = "Permisos";
        $data['page_name'] = "Permisos";
        $data['page_functions_js'] = "functions_Permisos.js";

        $this->views->getView($this, "Permisos", $data);
    }

    public function getPermisos()
    {
        $arrData = $this->model->Permisos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPermisosbyID($id)
    {
        $data = $this->model->PermisosbyID($id);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setPermisos()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos del formulario o la solicitud AJAX
            $role_id = intval($_POST['role_id']);
            $modulo_id = intval($_POST['modulo_id']);
            $crear = isset($_POST['crear']) ? 1 : 0;
            $leer = isset($_POST['leer']) ? 1 : 0;
            $editar = isset($_POST['editar']) ? 1 : 0;
            $eliminar = isset($_POST['eliminar']) ? 1 : 0;

            // Validación de los datos
            if ($role_id > 0 && $modulo_id > 0) {
                // Llamar al método del modelo para actualizar los permisos
                $update = $this->model->updatePermisos($role_id, $modulo_id, $crear, $leer, $editar, $eliminar);

                // Comprobar si la actualización fue exitosa
                if ($update) {
                    $response = [
                        'status' => true,
                        'msg' => 'Permisos actualizados correctamente.'
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'msg' => 'No se pudo actualizar los permisos.'
                    ];
                }
            } else {
                // Si la validación falla
                $response = [
                    'status' => false,
                    'msg' => 'Datos inválidos.'
                ];
            }

            // Devolver la respuesta en formato JSON
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }


}