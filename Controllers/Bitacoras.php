
<?php
session_start();
class Bitacoras extends Controllers
{

    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        // getPermisos(BITACORAS);
    }

    public $views;
    public $model;

    public function Bitacoras()
    {
        $data['page_id'] = HISTORIAL;
        $data['page_tag'] = "Bitacoras";
        $data['page_title'] = "Bitacoras";
        $data['page_name'] = "Bitacoras";
        $data['page_functions_js'] = "functions_bitacoras.js";

        $this->views->getView($this, "Bitacoras", $data);
    }

    public function Historial_Empleados()
    {
        $data['page_id'] = HISTORIAL;
        $data['page_tag'] = "Historial";
        $data['page_title'] = "Historial";
        $data['page_name'] = "Historial";
        $data['page_functions_js'] = "functions_historial_empleados.js";

        $this->views->getView($this, "Historial_Empleados", $data);
    }

    public function getHistorial()
    {
        $arrData = $this->model->Historial_Empleado();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function Movimientos_Empleados()
    {
        $data['page_id'] = MOVIMIENTOS;
        $data['page_tag'] = "Movimientos";
        $data['page_title'] = "Movimientos";
        $data['page_name'] = "Movimientos";
        $data['page_functions_js'] = "functions_movimientos_empleados.js";

        $this->views->getView($this, "Movimientos_Empleados", $data);
    }

    public function getMovimientos()
    {
        $arrData = $this->model->Movimientos_Empleados();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


} // FIN DEL CONTROLADOR