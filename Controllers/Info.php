<?php
session_start();

class Info extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(INFO);
    }

    public $views;
    public $model;

    public function Info()
    {
        $data['page_id'] = INFO;
        $data['page_tag'] = "Informacion";
        $data['page_title'] = "Informacion";
        $data['page_name'] = "Informacion";
        $data['page_functions_js'] = "functions_info.js";

        $this->views->getView($this, "Info", $data);
    }

    public function Mostrar($identificacion)
    {
        $info = $this->model->MostrarInfo($identificacion);

        $nombre = $info['nombres'];

        if (!empty($nombre)) {
            $no_empleado = $_SESSION['PersonalData']['no_empleado'];
            $empleado = $_SESSION['PersonalData']['nombre_completo'];
            $modulo = "Info Personal";
            $accion = 'Ingreso a informacion personal del usuario "' . $nombre . '"';
            $fecha = date("Y-m-d H:i:s");
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
            $hostname = gethostbyaddr($ip);

            $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
        }

        $data['info'] = $info;
        $data['page_id'] = INFO;
        $data['page_tag'] = "Informacion Personal";
        $data['page_title'] = "Informacion Personal";
        $data['page_name'] = "Informacion Personal";
        $data['page_functions_js'] = "functions_info.js";

        $this->views->getView($this, "Info", $data);
    }

    public function Editar($identificacion)
    {
        $info = $this->model->MostrarInfo($identificacion);

        $data = [
            'page_tag' => 'Editar Info',
            'page_title' => 'Editar Info',
            'page_name' => 'Info_edit',
            'page_functions_js' => 'functions_info.js',
            'info' => $info
        ];

        $this->views->getView($this, 'Editar', $data);
    }

    public function getSelectEstudios()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectEstudios();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_categoria'] . '">' . $arrData[$i]['asunto'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectNivel()
    {
        $htmlOptions = ''; // Añadir esta opción una sola vez
        $arrData = $this->model->selectNivel();
        if (count($arrData) > 0) {
            // Ahora solo agregas las opciones de los empleados
            foreach ($arrData as $empleado) {
                $htmlOptions .= '<option value="' . $empleado['id_categoria'] . '">' . $empleado['asunto'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setInfo()
    {
        if ($_POST) {
            // Validación de campos
            if (empty($_POST['identificacion'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                // Filtrado y validación de los datos
                $identificacion = intval($_POST['identificacion']);
                $genero = isset($_POST['genero']) ? $_POST['genero'] : '';
                $estado_civil = isset($_POST['estado_civil']) ? $_POST['estado_civil'] : '';
                $Pais = isset($_POST['Pais']) ? $_POST['Pais'] : '';
                $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : '';
                $municipio = isset($_POST['municipio']) ? $_POST['municipio'] : '';
                $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
                $mes_cumpleaños = intval($_POST['mes_cumpleaños']);
                $edad = intval($_POST['edad']);
                $tipo_identificacion = isset($_POST['tipo_identificacion']) ? $_POST['tipo_identificacion'] : '';
                $pasaporte = isset($_POST['pasaporte']) ? $_POST['pasaporte'] : '';
                $lugar_nacimiento = isset($_POST['lugar_nacimiento']) ? $_POST['lugar_nacimiento'] : '';
                $no_seguro_social = isset($_POST['no_seguro_social']) ? $_POST['no_seguro_social'] : '';
                $no_identificacion_tributaria = isset($_POST['no_identificacion_tributaria']) ? $_POST['no_identificacion_tributaria'] : '';
                $vig_licencia_conducir = isset($_POST['vig_licencia_conducir']) ? $_POST['vig_licencia_conducir'] : '';
                $estudios = isset($_POST['estudios']) ? $_POST['estudios'] : '';
                $nivel_educativo = isset($_POST['nivel_educativo']) ? $_POST['nivel_educativo'] : '';
                $numero_cel_corporativo = isset($_POST['numero_cel_corporativo']) ? $_POST['numero_cel_corporativo'] : '';
                $numero_cel_personal = isset($_POST['numero_cel_personal']) ? $_POST['numero_cel_personal'] : '';
                $numero_cel_emergencia = isset($_POST['numero_cel_emergencia']) ? $_POST['numero_cel_emergencia'] : '';
                $nombre_contacto_emergencia = isset($_POST['nombre_contacto_emergencia']) ? $_POST['nombre_contacto_emergencia'] : '';
                $parentesco_contacto_emergencia = isset($_POST['parentesco_contacto_emergencia']) ? $_POST['parentesco_contacto_emergencia'] : '';
                $direccion_domicilio = isset($_POST['direccion_domicilio']) ? $_POST['direccion_domicilio'] : '';
                $correo_electronico_personal = isset($_POST['correo_electronico_personal']) ? $_POST['correo_electronico_personal'] : '';
                $cant_hijos = intval($_POST['cant_hijos']);
                $tipo_sangre = isset($_POST['tipo_sangre']) ? $_POST['tipo_sangre'] : '';

                try {
                    $request_user = $this->model->updateInfo(
                        $identificacion,
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
                        $nivel_educativo,
                        $numero_cel_corporativo,
                        $numero_cel_personal,
                        $numero_cel_emergencia,
                        $nombre_contacto_emergencia,
                        $parentesco_contacto_emergencia,
                        $direccion_domicilio,
                        $correo_electronico_personal,
                        $cant_hijos,
                        $tipo_sangre
                    );

                    $info = $this->model->MostrarInfo($identificacion);
                    $nombre = $info['nombres'];

                    if (!empty($nombre)) {
                        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                        $empleado = $_SESSION['PersonalData']['nombre_completo'];
                        $modulo = "Info Personal";
                        $accion = 'Actualizo información personal del usuario "' . $nombre . '"';
                        $fecha = date("Y-m-d H:i:s");
                        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                        $hostname = gethostbyaddr($ip);
                    
                        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                    }


                    if ($request_user) {
                        $arrResponse = array("status" => true, "msg" => 'Información actualizada correctamente');
                    } else {
                        $arrResponse = array("status" => false, "msg" => 'No se pudo actualizar la información');
                    }
                } catch (Exception $e) {
                    $arrResponse = array("status" => false, "msg" => 'Error en la actualización: ' . $e->getMessage());
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die(); // Finaliza la ejecución del script
    }

    public function Academica($info_empleado)
    {
        $info = $this->model->getAcademica($info_empleado);
        if (empty($info)) {
            // Manejar el caso donde no se encuentra el empleado
            header('Location: ' . base_url() . '/Info');
        }

        $nombre = $info['nombres'];

        if (!empty($nombre)) {
            $no_empleado = $_SESSION['PersonalData']['no_empleado'];
            $empleado = $_SESSION['PersonalData']['nombre_completo'];
            $modulo = "Info Academica";
            $accion = 'Ingreso a informacion academica del usuario "' . $nombre . '"';
            $fecha = date("Y-m-d H:i:s");
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
            $hostname = gethostbyaddr($ip);

            $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
        }

        $data = [
            'page_tag' => 'Info Academica',
            'page_title' => 'Info Academica',
            'page_name' => 'Info Academica',
            'page_functions_js' => 'functions_academica.js',
            'info' => $info
        ];

        $this->views->getView($this, 'Academica', $data);
    }

    public function getAcademica($info_empleado)
    {
        $arrData = $this->model->getAcademicabyID($info_empleado);

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

    public function getAcademicaPrincipal($id)
    {
        $arrData = $this->model->AcademicabyID($id);

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

    public function subirDocumento()
    {
        if ($_POST) {
            $id = intval($_POST['id']);
            $fecha_act = date('Y-m-d');

            // Obtén la información del documento actual
            $documento = $this->model->getDocumentoById($id);

            if ($documento) {
                // Verifica si se subió un archivo
                if (isset($_FILES['ubicacion']) && $_FILES['ubicacion']['error'] == 0) {
                    $file = $_FILES['ubicacion'];
                    $fileTmpPath = $file['tmp_name'];
                    $fileName = $documento['nombre_documento'] . '.pdf'; // Nombre del documento con extensión
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));

                    // Definir la carpeta destino
                    $uploadDir = 'Academica/' . $documento['info_empleado'] . '/';

                    // Verificar si la carpeta existe, si no, crearla
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // Establecer la ruta de destino
                    $dest_path = $uploadDir . $fileName;

                    // Mover el archivo al destino
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Actualizar la ubicación del archivo en la base de datos
                        $ubicacion = $dest_path;
                    } else {
                        $ubicacion = null; // Si hubo un error al mover el archivo, lo establecemos en NULL
                        $arrResponse = array('status' => false, 'msg' => 'No se pudo mover el archivo.');
                        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                        die();
                    }
                } else {
                    // Si no se subió ningún archivo, se establece la ubicación como NULL
                    $ubicacion = null;
                }

                $info_empleado = $documento['info_empleado'];
                $info = $this->model->getAcademica($info_empleado);
                $nombre = $info['nombres'];
                $documento = $documento['nombre_documento'];

                if (!empty($nombre)) {
                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Info Academica";
                    $accion = 'Se cargo el documento Academico "' . $documento . '" al usuario "' . $nombre . '"' ;
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);

                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }

                // Actualizar la información en la base de datos
                $request = $this->model->subirDocumento($id, $ubicacion, $fecha_act);

                if ($request) {
                    $arrResponse = array('status' => true, 'msg' => 'Documento subido correctamente.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar la ubicación.');
                }
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Documento no encontrado.');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


}