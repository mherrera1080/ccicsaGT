<?php
session_start();

class Uniformes extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(UNIFORMES);
    }

    public $views;
    public $model;
    public function Uniformes()
    {
        $data['page_id'] = UNIFORMES;
        $data['page_tag'] = "Uniformes";
        $data['page_title'] = "Uniformes";
        $data['page_name'] = "Uniformes";
        $data['page_functions_js'] = "functions_Uniformes.js";

        $this->views->getView($this, "Uniformes", $data);
    }

    public function General()
    {
        $data['page_id'] = UNIFORMES;
        $data['page_tag'] = "Uniformes";
        $data['page_title'] = "Uniformes";
        $data['page_name'] = "Uniformes";
        $data['page_functions_js'] = "functions_general.js";

        $this->views->getView($this, "General", $data);
    }

    public function Mostrar($id_empleado)
    {
        $uniformes = $this->model->getUniformes($id_empleado);

        $nombre = $uniformes['nombre_completo'];

        if (!empty($nombre)) {
			$no_empleado = $_SESSION['PersonalData']['no_empleado'];
			$empleado = $_SESSION['PersonalData']['nombre_completo'];
			$modulo = "Uniformes";
			$accion = 'Ingreso a informacion de uniformes de "' . $nombre . '"';
			$fecha = date("Y-m-d H:i:s");
			$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
			$hostname = gethostbyaddr($ip);
		
			$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
		}

        $data['uniformes'] = $uniformes;
        $data['page_id'] = UNIFORMES;
        $data['page_tag'] = "Uniformes";
        $data['page_title'] = "Uniformes";
        $data['page_name'] = "Uniformes";
        $data['page_functions_js'] = "functions_uniformes.js";

        $this->views->getView($this, "Uniformes", $data);
    }

    public function uniformesGeneral()
    {
        $arrData = $this->model->select_uniformes();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getUniformeGeneral()
    {
        try {
            $uniforme = $this->model->getAllUniformes();
            if (!empty($uniforme)) {
                $response = ['status' => true, 'data' => $uniforme];
            } else {
                $response = ['status' => false, 'msg' => 'No hay Uniformes disponibles.'];
            }
        } catch (Exception $e) {
            $response = ['status' => false, 'msg' => 'Error al obtener los uniformes.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }




    public function getUniformes($id_empleado)
    {
        $arrData = $this->model->getUniformesbyId($id_empleado);

        // Verificar y depurar datos
        error_log(print_r($arrData, true));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function saveUniformes()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recibir los datos del formulario
            $id_empleado = intval($_POST['id_empleado']);
            $grupo_asignacion = $this->model->getNextGroupId(); // Obtenemos un nuevo ID grupal para la asignación

            $uniformes = $_POST['uniforme']; // Array de IDs de uniformes
            $cantidades = $_POST['cantidad']; // Array de cantidades correspondientes

            // Verificar que ambos arrays tengan la misma longitud
            if (count($uniformes) != count($cantidades)) {
                $response = ['status' => false, 'msg' => 'Error en los datos.'];
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                return;
            }

            $success = true;

            // Recorrer los datos y realizar las inserciones
            for ($i = 0; $i < count($uniformes); $i++) {
                $uniforme_id = intval($uniformes[$i]);
                $cantidad = intval($cantidades[$i]);

                // Validar que los datos sean correctos
                if ($uniforme_id > 0 && $cantidad > 0) {
                    // Insertar cada uniforme asignado en la tabla de asignaciones
                    $insert = $this->model->insertAsignacionUniforme($id_empleado, $grupo_asignacion, $uniforme_id, $cantidad);
                    if (!$insert) {
                        $success = false;
                        break;
                    }
                }
            }

            $uniformes = $this->model->getUniformes($id_empleado);

            $nombre = $uniformes['nombre_completo'];
    
            if (!empty($nombre)) {
                $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                $empleado = $_SESSION['PersonalData']['nombre_completo'];
                $modulo = "Uniformes";
                $accion = 'Se hizo de registro de uniformes a "' . $nombre . '"';
                $fecha = date("Y-m-d H:i:s");
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                $hostname = gethostbyaddr($ip);
            
                $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
            }



            // Enviar la respuesta según el resultado de las inserciones
            if ($success) {
                $response = ['status' => true, 'msg' => 'Uniformes asignados correctamente.'];
            } else {
                $response = ['status' => false, 'msg' => 'Hubo un error al asignar los uniformes.'];
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        die();
    }




    public function getUniformesbyId($grupo_asignacion)
    {
        $data = $this->model->getUniformebyIdUP($grupo_asignacion);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function generarPDFuniformes(int $grupo_asignacion)
    {
        if ($grupo_asignacion) {
            // Obtener los datos del uniforme desde el modelo
            $correo_empresarial = $_SESSION['userData']['correo_empresarial'];
            $uniforme = $this->model->getUniformebyId($grupo_asignacion); // Ajustar el nombre aquí
            $usuario = $this->model->selectPersonalID($correo_empresarial);

            if (empty($uniforme)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            // Ruta al archivo de plantilla del PDF
            $ruta_pdf = 'Views/Template/PDF/Uniformes.php';
            // Pasar los datos a la vista para generar el PDF
            $arrData['uniforme'] = $uniforme;
            $arrData['usuario'] = $usuario;


            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                require_once $ruta_pdf;
                exit();
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Seleccione Uniforme');
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }


    public function subirDocumento()
    {
        if ($_POST) {
            $id_empleado = $_POST['id_empleado'];
            $grupo_asignacion = intval($_POST['grupo_asignacion']);
            $fecha_ingresada = date( 'Y-m-d');
            $documento = $this->model->getUniformebyIdUP($grupo_asignacion);

            if ($documento) {
                if (isset($_FILES['ubicacion']) && $_FILES['ubicacion']['error'] == 0) {
                    $file = $_FILES['ubicacion'];
					$fileTmpPath = $file['tmp_name'];
                    $fileName = 'Constancia_' . $documento['grupo_asignacion'] . '.pdf';
                    $fileSize = $file['size'];
					$fileType = $file['type'];
                    $fileNameCmps = explode(".", $fileName);
					$fileExtension = strtolower(end($fileNameCmps));

                    $uploadDir = 'Uniformes/' . $documento['año'] . '/' . $documento['nombres'] . '/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $dest_path = $uploadDir . $fileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Actualizar la ubicación del archivo en la base de datos
                        $ubicacion = $dest_path;
                        $request = $this->model->subirDocumento($id_empleado, $fecha_ingresada, $ubicacion, $grupo_asignacion);

                        if ($request) {
                            $arrResponse = array('status' => true, 'msg' => 'Documento subido correctamente.');
                        } else {
                            $arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar la ubicación.');
                        }
                    } else {
                        $arrResponse = array('status' => false, 'msg' => 'No se pudo mover el archivo.');
                    }

                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No se ha subido ningún archivo o hubo un error en la subida.');
                }
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Documento no encontrado.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }




}