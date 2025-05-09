<?php
session_start();
class Sistemas extends Controllers
{
    public function __construct()
    {
        parent::__construct();

    }

    public function Sistemas()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Sistemas";
        $data['page_title'] = "Sistemas";
        $data['page_name'] = "Sistemas";
        $data['page_functions_js'] = "functions_dashboard.js";

        $this->views->getView($this, "Sistemas", $data);
    }

    public function Registros()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Registros";
        $data['page_title'] = "Registros";
        $data['page_name'] = "Registros";
        $data['page_functions_js'] = "functions_registros.js";

        $this->views->getView($this, "Registros", $data);
    }

    public function registroActividad()
    {
        $arrData = $this->model->selectRegistros();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function setRegistroBackUp()
    {
        if ($_POST) {
            if (empty($_POST['nombre_archivo'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $nombre_archivo = $_POST['nombre_archivo'];
                $ruta = $_POST['ruta'];
                $fecha_creacion = $_POST['fecha_creacion'];
                $peso = !empty($_POST['peso']) ? $_POST['peso'] : null;
                $usuaio = 'Sistema';

                $request_user = $this->model->insertRegistro(
                    $nombre_archivo,
                    $ruta,
                    $fecha_creacion,
                    $peso,
                    $usuaio
                );

                if ($request_user > 0) {
                    $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');

                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos. Dimension Existente');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function BackUps()
    {
        $correo_empresarial = $_SESSION['userData']['correo_empresarial'];

        $usuario = $this->model->selectPersonalID($correo_empresarial);

        $data['usuario'] = $usuario;

        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "BackUps";
        $data['page_title'] = "BackUps";
        $data['page_name'] = "BackUps";
        $data['page_functions_js'] = "functions_backups.js";

        $this->views->getView($this, "BackUps", $data);
    }

    public function registroBackUps()
    {
        $arrData = $this->model->selectBackups();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setRegistroDB()
    {
        if ($_POST) {
            if (empty($_POST['usuario'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $nombre_archivo = $_POST['nombre_archivo'];
                $ruta = $_POST['ruta'];
                $fecha_creacion = $_POST['fecha_creacion'];
                $peso = $_POST['peso'];
                $usuaio = $_POST['usuario'];
                

                $request_user = $this->model->insertRegistro(
                    $nombre_archivo,
                    $ruta,
                    $fecha_creacion,
                    $peso,
                    $usuaio
                );

                if ($request_user > 0) {
                    $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');

                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos. Dimension Existente');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    

}
