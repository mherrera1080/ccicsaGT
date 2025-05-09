<?php
session_start();

class Listado extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(LISTADO);
    }

    public $views;
    public $model;
    public function Listado()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Listado";
        $data['page_title'] = "Listado";
        $data['page_name'] = "Listado";
        $data['page_functions_js'] = "functions_listado.js";

        $this->views->getView($this, "Listado", $data);
    }

    public function Empresa()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Empresa";
        $data['page_title'] = "Empresa";
        $data['page_name'] = "Empresa";
        $data['page_functions_js'] = "functions_empresa.js";

        $this->views->getView($this, "Empresa", $data);
    }

    public function getEmpresa()
    {
        $arrData = $this->model->Empresa();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getEmpresabyID($id_empresa)
    {
        $data = $this->model->EmpresabyID($id_empresa);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteEmpresa($id_empresa)
    {
        $result = $this->model->deleteEmpresabyID($id_empresa);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Empresa eliminada exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar la empresa.',
            ];
        }

        echo json_encode($response);
    }

    public function setEmpresa()
    {
        if ($_POST) {
            if (empty($_POST['nombre'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_empresa = intval($_POST['id_empresa']);
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $nit = $_POST['nit'];
                $estado = $_POST['estado'];

                if ($id_empresa == 0) {
                    $option = 1;
                    $request_user = $this->model->insertEmpresa(
                        $nombre,
                        $descripcion,
                        $nit,
                        $estado
                    );
                } else {
                    $option = 2;
                    $request_user = $this->model->updateEmpresa(
                        $id_empresa,
                        $nombre,
                        $descripcion,
                        $nit,
                        $estado
                    );
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function Puestos()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Puesto";
        $data['page_title'] = "Puesto";
        $data['page_name'] = "Puesto";
        $data['page_functions_js'] = "functions_puestos.js";

        $this->views->getView($this, "Puestos", $data);
    }

    public function getPuestos()
    {
        $arrData = $this->model->Puestos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPuestosbyID($id_puesto)
    {
        $data = $this->model->puestosbyID($id_puesto);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deletePuesto($id_puesto)
    {
        $info = $this->model->puestosbyID($id_puesto);
        $nombre = $info['nombre_puesto'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Puestos";
        $accion = 'Se elimino el puesto "' . $nombre . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deletePuestosbyID($id_puesto);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Puesto eliminado exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar el Puesto.',
            ];
        }

        echo json_encode($response);
    }

    public function setPuestos()
    {
        if ($_POST) {
            if (empty($_POST['nombre_puesto'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_puesto = intval($_POST['id_puesto']);
                $nombre_puesto = $_POST['nombre_puesto'];
                $descripcion = $_POST['descripcion'];
                $estado = $_POST['estado'];

                if ($id_puesto == 0) {
                    $option = 1;
                    $request_user = $this->model->insertPuesto(
                        $nombre_puesto,
                        $descripcion,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Puestos";
                    $accion = 'Se agrego el puesto "' . $nombre_puesto . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                } else {
                    $option = 2;
                    $request_user = $this->model->updatePuesto(
                        $id_puesto,
                        $nombre_puesto,
                        $descripcion,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Puestos";
                    $accion = 'Se actualizo el puesto "' . $id_puesto . ' / ' . $nombre_puesto . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    // Jefes y Lideres 
    public function Jefes()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Jefes";
        $data['page_title'] = "Jefes";
        $data['page_name'] = "Jefes";
        $data['page_functions_js'] = "functions_jefes.js";

        $this->views->getView($this, "Jefes", $data);
    }

    public function getJefes()
    {
        $arrData = $this->model->Jefes();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getUsuarios()
    {
        $usuarios = $this->model->selectUsuarios(); // Método que obtiene todos los usuarios
        if (!empty($usuarios)) {
            $response = ['status' => true, 'data' => $usuarios];
        } else {
            $response = ['status' => false, 'msg' => 'No hay usuarios disponibles.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getPuesto()
    {
        $puestos = $this->model->Puestos(); // Método que obtiene todos los usuarios
        if (!empty($puestos)) {
            $response = ['status' => true, 'data' => $puestos];
        } else {
            $response = ['status' => false, 'msg' => 'No hay usuarios disponibles.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    public function getJefesbyID($id)
    {
        $data = $this->model->jefesbyID($id);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteJefes($id)
    {

        $info = $this->model->jefesbyID($id);
        $nombre = $info['nombre_completo'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Puestos";
        $accion = 'Se descarto a "' . $nombre . '" como jefe';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deletejefesbyID($id);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Empresa eliminada exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar la empresa.',
            ];
        }

        echo json_encode($response);
    }

    public function setJefes()
    {
        if ($_POST) {
            if (empty($_POST['usuario'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id = intval($_POST['id']);
                $usuario = $_POST['usuario'];
                $area = $_POST['area'];
                $estado = $_POST['estado'];

                if ($id == 0) {
                    $option = 1;
                    $request_user = $this->model->insertJefe(
                        $usuario,
                        $area,
                        $estado
                    );

                    $info = $this->model->usuariosbyID($usuario);
                    $nombre = $info['nombre_completo'];

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Jefes";
                    $accion = 'Se agrego a "' . $nombre . '" como jefe ';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                } else {
                    $option = 2;
                    $request_user = $this->model->updateJefe(
                        $id,
                        $usuario,
                        $area,
                        $estado
                    );

                    $info = $this->model->jefesbyID($id);
                    $nombre = $info['nombre_completo'];

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Jefes";
                    $accion = 'Se edito el jefe "' . $nombre . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function Departamento()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Departamento";
        $data['page_title'] = "Departamento";
        $data['page_name'] = "Departamento";
        $data['page_functions_js'] = "functions_departamento.js";

        $this->views->getView($this, "Departamento", $data);
    }

    public function getDepartamentos()
    {
        $arrData = $this->model->Departamento();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getAreasLaborales()
    {
        $areas = $this->model->selectAreas(); // Método que obtiene todos los usuarios
        if (!empty($areas)) {
            $response = ['status' => true, 'data' => $areas];
        } else {
            $response = ['status' => false, 'msg' => 'No hay areas disponibles.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    public function getDepartamentobyID($id_departamento)
    {
        $data = $this->model->departamentobyID($id_departamento);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteDepartamento($id_departamento)
    {
        $info = $this->model->departamentobyID($id_departamento);
        $nombre = $info['nombre_departamento'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Departamento Laboral";
        $accion = 'Se elimino el departamento laboral "' . $nombre . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deleteDepartamentobyID($id_departamento);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Empresa eliminada exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar la empresa.',
            ];
        }

        echo json_encode($response);
    }

    public function setDepartamento()
    {
        if ($_POST) {
            if (empty($_POST['nombre_departamento'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_departamento = intval($_POST['id_departamento']);
                $nombre_departamento = $_POST['nombre_departamento'];
                $descripcion = $_POST['descripcion'];
                $area = $_POST['area'];
                $estado = $_POST['estado'];

                if ($id_departamento == 0) {
                    $option = 1;
                    $request_user = $this->model->insertDepartamento(
                        $nombre_departamento,
                        $descripcion,
                        $area,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Departamento Laboral";
                    $accion = 'Se agrego el departamento laboral "' . $nombre_departamento . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                } else {
                    $option = 2;
                    $request_user = $this->model->updateDepartamento(
                        $id_departamento,
                        $nombre_departamento,
                        $descripcion,
                        $area,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Departamento Laboral";
                    $accion = 'Se edito la informacion del departamento "' . $nombre_departamento . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function LN()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "LN";
        $data['page_title'] = "LN";
        $data['page_name'] = "LN";
        $data['page_functions_js'] = "functions_ln.js";

        $this->views->getView($this, "LN", $data);
    }

    public function getDimensionLN()
    {
        $dimension = $this->model->selectDimension(); // Método que obtiene todos los usuarios
        if (!empty($dimension)) {
            $response = ['status' => true, 'data' => $dimension];
        } else {
            $response = ['status' => false, 'msg' => 'No hay dimensiones disponibles.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getLN()
    {
        $arrData = $this->model->LN();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getLNbyID($id_ln)
    {
        $data = $this->model->lnbyID($id_ln);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteLN($id_ln)
    {
        $data = $this->model->lnbyID($id_ln);
        $codigo = $data['codigo'];
        $descripcion = $data['descripcion'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Linea de Negocios";
        $accion = 'Se elimino la linea de negocio "' . $codigo . ' | ' . $descripcion . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deletelnbyID($id_ln);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Empresa eliminada exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar la empresa.',
            ];
        }

        echo json_encode($response);
    }

    public function setLN()
    {
        if ($_POST) {
            if (empty($_POST['codigo'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_ln = intval($_POST['id_ln']);
                $codigo = $_POST['codigo'];
                $descripcion = $_POST['descripcion'];
                $dimension = intval($_POST['dimension']);

                if ($id_ln == 0) {
                    $option = 1;
                    $request_user = $this->model->insertLN(
                        $codigo,
                        $descripcion,
                        $dimension
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Linea de Negocios";
                    $accion = 'Se agrego la linea de negocio "' . $codigo . ' | ' . $descripcion . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                } else {
                    $option = 2;
                    $request_user = $this->model->updateLN(
                        $id_ln,
                        $codigo,
                        $descripcion,
                        $dimension
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Linea de Negocios";
                    $accion = 'Se edito informacion de la linea de negocio "' . $codigo . ' | ' . $descripcion . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function Areas()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Areas";
        $data['page_title'] = "Areas";
        $data['page_name'] = "Areas";
        $data['page_functions_js'] = "functions_areas.js";

        $this->views->getView($this, "Areas", $data);
    }

    public function getAreas()
    {
        $arrData = $this->model->Areas();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getAreabyID($id_area)
    {
        $data = $this->model->areasbyID($id_area);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteArea($id_area)
    {
        $data = $this->model->areasbyID($id_area);
        $nombre_area = $data['nombre_area'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Areas";
        $accion = 'Se elimino el area laboral "' . $nombre_area . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deleteAreasbyID($id_area);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Empresa eliminada exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar la empresa.',
            ];
        }

        echo json_encode($response);
    }


    public function setArea()
    {
        if ($_POST) {
            if (empty($_POST['nombre_area'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_area = intval($_POST['id_area']);
                $nombre_area = $_POST['nombre_area'];
                $descripcion = $_POST['descripcion'];
                $estado = $_POST['estado'];

                if ($id_area == 0) {
                    $option = 1;
                    $request_user = $this->model->insertAreas(
                        $nombre_area,
                        $descripcion,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Areas";
                    $accion = 'Se agrego el area laboral "' . $nombre_area . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                } else {
                    $option = 2;
                    $request_user = $this->model->updateAreas(
                        $id_area,
                        $nombre_area,
                        $descripcion,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Areas";
                    $accion = 'Se edito informacion del area "' . $nombre_area . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function Dimension()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Dimensiones";
        $data['page_title'] = "Dimensiones";
        $data['page_name'] = "Dimensiones";
        $data['page_functions_js'] = "functions_dimension.js";

        $this->views->getView($this, "Dimension", $data);
    }

    public function getDimensiones()
    {
        $arrData = $this->model->Dimensiones();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getDimensionbyID($id_dimension)
    {
        $data = $this->model->dimensionesbyID($id_dimension);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteDimension($id_dimension)
    {
        $data = $this->model->dimensionesbyID($id_dimension);
        $numero = $data['numero'];
        $descripcion = $data['descripcion'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Dimensiones";
        $accion = 'Se elimino la dimension "' . $numero . ' | ' . $descripcion . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deleteDimensionesbyID($id_dimension);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Dimension eliminada exitosamente.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo eliminar la Dimension.',
            ];
        }

        echo json_encode($response);
    }

    public function setDimension()
    {
        if ($_POST) {
            if (empty($_POST['numero'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_dimension = intval($_POST['id_dimension']);
                $numero = $_POST['numero'];
                $descripcion = $_POST['descripcion'];
                $naturaleza = $_POST['naturaleza'];
                $estado = $_POST['estado'];



                if ($id_dimension == 0) {
                    $option = 1;
                    $request_user = $this->model->insertDimensiones(
                        $numero,
                        $descripcion,
                        $naturaleza,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Dimensiones";
                    $accion = 'Se agrego la dimension "' . $numero . ' | ' . $descripcion . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);


                } else {
                    $option = 2;
                    $request_user = $this->model->updateDimension(
                        $id_dimension,
                        $numero,
                        $descripcion,
                        $naturaleza,
                        $estado
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Dimensiones";
                    $accion = 'Se edito informacion de la dimension "' . $numero . ' | ' . $descripcion . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos. Dimension Existente');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function Documentos()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Documentos";
        $data['page_title'] = "Documentos";
        $data['page_name'] = "Documentos";
        $data['page_functions_js'] = "functions_documentos.js";

        $this->views->getView($this, "Documentos", $data);
    }

    public function getDocumentos()
    {
        $arrData = $this->model->Documentos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getDocumentosbyId($id_documento)
    {
        $data = $this->model->DocumentosbyId($id_documento);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function deleteDocumentos($id_documento)
    {
        $data = $this->model->DocumentosbyId($id_documento);
        $nombre_documento = $data['nombre_documento'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Documentos Expedientes";
        $accion = 'Se inhabilito el documento "' . $nombre_documento . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deleteDocumentosbyID($id_documento);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Documento Inhabilitado.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo inhabilitar el Documento.',
            ];
        }

        echo json_encode($response);
    }

    public function habilitarDocumentos($id_documento)
    {
        $data = $this->model->DocumentosbyId($id_documento);
        $nombre_documento = $data['nombre_documento'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Documentos Expedientes";
        $accion = 'Se habilito el documento "' . $nombre_documento . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->habilitarDocumentosbyID($id_documento);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Documento Habilitado.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo habilitar el Documento.',
            ];
        }

        echo json_encode($response);
    }


    public function setDocumentos()
    {
        if ($_POST) {
            if (empty($_POST['nombre_documento'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_documento = intval($_POST['id_documento']);
                $nombre_documento = $_POST['nombre_documento'];

                if ($id_documento == 0) {
                    $option = 1;
                    $request_user = $this->model->insertDocumentos(
                        $nombre_documento
                    );
                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Documentos Expedientes";
                    $accion = 'Se agrego el expediente "' . $nombre_documento . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                } else {
                    $option = 2;
                    $request_user = $this->model->updateDocumento(
                        $id_documento,
                        $nombre_documento
                    );
                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Documentos Expedientes";
                    $accion = 'Se actualizo el expediente "' . $nombre_documento . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function Academicos()
    {
        $data['page_id'] = LISTADO;
        $data['page_tag'] = "Academicos";
        $data['page_title'] = "Academicos";
        $data['page_name'] = "Academicos";
        $data['page_functions_js'] = "functions_academicos.js";

        $this->views->getView($this, "Academicos", $data);
    }


    public function getAcademico()
    {
        $arrData = $this->model->Academico();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getAcademicobyId($id_academica)
    {
        $data = $this->model->AcademicobyId($id_academica);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function deleteAcademico($id_documento)
    {
        $data = $this->model->AcademicobyId($id_documento);
        $nombre_documento = $data['nombre_documento'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Documentos Academicos";
        $accion = 'Se inhabilito el documento "' . $nombre_documento . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->deleteAcademicobyID($id_documento);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Documento Inhabilitado.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo inhabilitar el Documento.',
            ];
        }

        echo json_encode($response);
    }

    public function habilitarAcademicos($id_documento)
    {
        $data = $this->model->AcademicobyId($id_documento);
        $nombre_documento = $data['nombre_documento'];

        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Documentos Academicos";
        $accion = 'Se habilito el documento "' . $nombre_documento . '"';
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);
        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        $result = $this->model->habilitarAcacemicobyID($id_documento);

        if ($result) {
            $response = [
                'status' => true,
                'msg' => 'Documento Habilitado.',
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No se pudo habilitar el Documento.',
            ];
        }

        echo json_encode($response);
    }

    public function setAcademico()
    {
        if ($_POST) {
            // Validar que todos los campos requeridos están presentes y no están vacíos
            if (empty($_POST['nombre_documento']) || empty($_POST['descripcion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o incompletos.');
            } else {
                // Verificar si 'id_academica' está presente, si no, asignar 0 para indicar que es una inserción
                $id_academica = isset($_POST['id_academica']) ? intval($_POST['id_academica']) : 0;
                $nombre_documento = $_POST['nombre_documento'];
                $descripcion = $_POST['descripcion'];

                if ($id_academica == 0) {
                    // Inserción de nuevos datos
                    $option = 1;
                    $request_user = $this->model->insertAcademico(
                        $nombre_documento,
                        $descripcion
                    );

                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Documentos Academicos";
                    $accion = 'Se agrego el documento academico "' . $nombre_documento . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

                } else {
                    // Actualización de datos existentes
                    $option = 2;
                    $request_user = $this->model->updateAcademico(
                        $id_academica,
                        $nombre_documento,
                        $descripcion
                    );
                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Documentos Academicos";
                    $accion = 'Se actualizo el documento academico "' . $nombre_documento . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);
                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                // Verificar el resultado de la operación
                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }

            // Devolver la respuesta en formato JSON
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }




}