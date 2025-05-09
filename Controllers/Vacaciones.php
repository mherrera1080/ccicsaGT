<?php

class Vacaciones extends Controllers
{

    public function __construct()
    {
        parent::__construct();
    }

    public $views;
    public $model;

    public function Vacaciones()
    {
        // Verificar si el empleado está logueado

        $data['page_id'] = VACACIONES;
        $data['page_tag'] = "Vacaciones";
        $data['page_title'] = "Vacaciones";
        $data['page_name'] = "Vacaciones";
        $data['page_functions_js'] = "functions_vacaciones.js";

        $this->views->getView($this, "Vacaciones", $data);
    }

    public function getPermisosUsuario()
    {
        session_start();
        if (!isset($_SESSION['permisos'])) {
            echo json_encode([]);
            return;
        }
        echo json_encode($_SESSION['permisos']);
    }


    public function solicitud()
    {
        if (!isLoggedIn()) {
            header("Location: " . base_url() . "/vacaciones/login");
            exit();
        }

        $correo_empresarial = $_SESSION['userData']['correo_empresarial'];

        $usuario = $this->model->selectPersonalID($correo_empresarial);


        $data['usuario'] = $usuario;
        $data['page_id'] = VACACIONES;
        $data['page_tag'] = "Solicitud";
        $data['page_title'] = "Solicitud";
        $data['page_name'] = "Solicitud";
        $data['page_functions_js'] = "functions_solicitud_vacaciones.js";

        $this->views->getView($this, "Solicitud", $data);
    }

    public function Estadisticas()
    {
        if (!isLoggedIn()) {
            header("Location: " . base_url() . "/vacaciones/login");
            exit();
        }

        $correo_empresarial = $_SESSION['userData']['correo_empresarial'];

        $usuario = $this->model->selectPersonalID($correo_empresarial);
        $estadistica = $this->model->selectEstadisticas($correo_empresarial);

        $data['usuario'] = $usuario;
        $data['estadistica'] = $estadistica;
        $data['page_id'] = VACACIONES;
        $data['page_tag'] = "Estadisticas";
        $data['page_title'] = "Estadisticas";
        $data['page_name'] = "Estadisticas";
        $data['page_functions_js'] = "functions_Estadisticas_vacaciones.js";

        $this->views->getView($this, "Estadisticas", $data);
    }

    public function getSolicitudesbyID($id_empleado)
    {
        $arrData = $this->model->selectSolicitudes($id_empleado);

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

    public function Login()
    {
        $data['page_id'] = VACACIONES;
        $data['page_tag'] = " Vacaciones";
        $data['page_title'] = " Vacaciones";
        $data['page_name'] = " Vacaciones";
        $data['page_functions_js'] = "functions_login_vacaciones.js";

        $this->views->getView($this, "Login", $data);
    }

    public function getJefesbyID($id_solicitud)
    {
        $solicitud = $this->model->selectJefesID($id_solicitud);

        if ($solicitud) {
            echo json_encode(["status" => true, "data" => $solicitud]);
        } else {
            echo json_encode(["status" => false, "message" => "No se encontró la solicitud."]);
        }
        exit;
    }

    public function enviarCorreoToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar si el correo fue enviado
            if (!isset($_POST['correo_empresarial']) || empty($_POST['correo_empresarial'])) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El campo correo empresarial es obligatorio.',
                ]);
                die();
            }

            $correo_empresarial = htmlspecialchars($_POST['correo_empresarial']);

            // Validar formato de correo
            if (!filter_var($correo_empresarial, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El correo ingresado no es válido.',
                ]);
                die();
            }

            // Validar si el correo existe en la base de datos
            $userData = $this->model->verificarCorreo($correo_empresarial);
            if (!$userData) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El correo no está registrado en el sistema.',
                ]);
                die();
            }

            // Generar token y fecha de expiración
            $token = rand(100000, 999999);
            $expires_at = date('Y-m-d H:i:s', strtotime('+2 minutes'));

            // Guardar token en la base de datos
            $result = $this->model->guardarToken($correo_empresarial, $token, $expires_at);
            if (!$result) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'No se pudo guardar el token en la base de datos.',
                ]);
                die();
            }

            // Preparar datos para enviar el correo
            $arrData = [
                'correo_empresarial' => $correo_empresarial,
                'token' => $token,
                'nombres' => $userData['nombres'], // Opcional: Puedes usar el nombre en el correo
            ];

            $emailTemplatePath = 'Views/Template/Email/TokenEmail.php';

            if (file_exists($emailTemplatePath)) {

                require $emailTemplatePath; // Cargar la plantilla para enviar el correo

                // Verificar si se envió el correo correctamente
                if ($confirmacion_correo) {
                    echo json_encode([
                        'status' => true,
                        'msg' => 'El correo con el token se envió correctamente.',
                    ]);
                } else {
                    echo json_encode([
                        'status' => false,
                        'msg' => 'Hubo un error al enviar el correo.',
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'msg' => 'No se encontró la plantilla del correo.',
                ]);
            }
            die();
        }
    }

    // Método para validar el token
    public function validarToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['correo_empresarial']) || !isset($_POST['token'])) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'Correo y token son obligatorios.',
                ]);
                die();
            }

            $correo_empresarial = htmlspecialchars($_POST['correo_empresarial']);
            $token = htmlspecialchars($_POST['token']);

            // Validar el token en la base de datos
            $userData = $this->model->validarToken($correo_empresarial, $token);
            if (!$userData) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El token ah expirado.',
                ]);
                die();
            }

            if ($userData) {
                // Iniciar sesión
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['userData'] = $userData; // Asegúrate de que userData contenga los datos correctos
                $_SESSION['userData']['token'] = $token; // Guarda el token en la sesión
                echo json_encode([
                    'status' => true,
                    'msg' => 'Inicio de sesión exitoso.',
                    'redirect' => base_url() . '/vacaciones/solicitud/' . $correo_empresarial, // URL de redirección
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El token ingresado no es válido o ha expirado.',
                ]);
            }
            die();
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header('Location: ' . base_url() . '/vacaciones/login');
        exit;
    }


    public function getSolicitudesUsuario()
    {
        $arrData = $this->model->selectSolicitud();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    // ENVIAR CORREO DE APROBACION
    public function getSolicitudesPendiente()
    {
        // Obtener las solicitudes pendientes
        $arrSolicitudes = $this->model->selectPendientes();

        // Verificar que haya solicitudes pendientes
        if (!empty($arrSolicitudes)) {

            // Recorrer las solicitudes para enviar un correo a cada empleado
            foreach ($arrSolicitudes as $solicitud) {

                // Datos del correo del empleado
                $correo_empresarial = $solicitud['correo'];
                $id_solicitud = $solicitud['id_solicitud'];

                // Obtener datos adicionales de jefes y empleado
                $arrData = [
                    'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                    'colaborador' => $this->model->selectPersonalID($correo_empresarial),
                    'jefes' => $this->model->selectJefesID($id_solicitud)
                ];

                // Comprobar si la plantilla existe
                $sendcorreo = 'Views/Template/Email/RecordatorioEmail.php';
                if (file_exists($sendcorreo)) {
                    // Requiere la plantilla para enviar el correo
                    require $sendcorreo;
                } else {
                    // Log y mensaje si no se encuentra la plantilla
                    error_log("Error: Plantilla de correo no encontrada en $sendcorreo");
                    echo json_encode(["status" => false, "message" => "Error: No se encontró la plantilla de correo"]);
                    exit;
                }
            }

            // Respuesta indicando que los correos fueron enviados
            $arrResponse = [
                "status" => true,
                "message" => "Correos enviados correctamente a los empleados con solicitudes pendientes"
            ];

        } else {
            // Si no hay solicitudes pendientes
            $arrResponse = [
                "status" => false,
                "message" => "No hay solicitudes pendientes"
            ];
        }

        // Enviar la respuesta en formato JSON
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getActualizarDiasRetraso()
    {
        $procedimiento = 'actualizar_dias_retraso';
        $success = $this->model->callProcedure($procedimiento, []);

        if ($success) {
            echo json_encode(["status" => true, "message" => "Días de retraso actualizados correctamente."]);
        } else {
            echo json_encode(["status" => false, "message" => "Error al actualizar días de retraso."]);
        }
        exit;
    }
    public function getActualizarDiasPeriodos()
    {
        $procedimiento = 'actualizar_periodo_vacaciones';
        $success = $this->model->callProcedure($procedimiento, []);

        if ($success) {
            echo json_encode(["status" => true, "message" => "Días de Periodos actualizados correctamente."]);
        } else {
            echo json_encode(["status" => false, "message" => "Error al actualizar días de retraso."]);
        }
        exit;
    }


    public function actualizarPeriodos($id_empleado)
    {

        // Validar que el ID del empleado esté presente
        if (!$id_empleado) {
            echo json_encode(["status" => false, "message" => "ID de empleado no proporcionado."]);
            var_dump($id_empleado); // Para ver si el ID está siendo recibido correctamente
            exit;
        }

        // Llamar al método del modelo para actualizar los períodos de vacaciones
        $resultado = $this->model->actualizarPeriodosVacaciones($id_empleado);

        // Verificar el resultado de la actualización
        if ($resultado) {
            echo json_encode(["status" => true, "message" => "Período de vacaciones actualizado correctamente."]);
        } else {
            echo json_encode(["status" => false, "message" => "Error al actualizar el período de vacaciones."]);
        }
    }

    public function guardarSolicitudOperativa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir datos del formulario
            $id_empleado = $_POST['id_empleado'];
            $comentario_solicitud = $_POST['comentario_solicitud'];
            $revision_aprobador_1 = $_POST['revision_aprobador_1'];
            $id_categoria = $_POST['id_categoria'];
            $area_solicitud = htmlspecialchars($_POST['area_solicitud']);
            $fechas = $_POST['fechas']; // Array de fechas
            $valores = $_POST['valor']; // Array de valores (1 o 0.5)
            $dias = $_POST['dia']; // Array de días (AM, PM, Completo)

            // Validación inicial
            if (empty($id_empleado) || empty($fechas) || empty($valores) || empty($dias)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Validar si hay fechas duplicadas en el formulario
            if (count($fechas) !== count(array_unique($fechas))) {
                echo json_encode(["status" => false, "message" => "Hay fechas duplicadas en la solicitud."]);
                exit;
            }

            // Obtener días disponibles del empleado
            $periodo = $this->model->obtenerDiasDisponibles($id_empleado);
            if (!$periodo) {
                echo json_encode(["status" => false, "message" => "No tienes días de vacaciones disponibles."]);
                exit;
            }

            $dias_disponibles = floatval($periodo['dias_disponibles']); // Convertir a número
            $dias_solicitados = array_sum($valores); // Sumar todos los valores solicitados

            // Verificar si tiene suficientes díasx|
            if ($dias_solicitados > $dias_disponibles) {
                echo json_encode([
                    "status" => false,
                    "message" => "No tienes suficientes días de vacaciones. Disponibles: $dias_disponibles, Solicitados: $dias_solicitados."
                ]);
                exit;
            }

            // Validar si las fechas ya existen y el estado
            $errores = [];
            foreach ($fechas as $fecha) {
                $detalle = $this->model->getDetallePorFechaEmpleado($id_empleado, $fecha);

                if ($detalle) {
                    if ($detalle['estado'] === 'Consumido') {
                        $errores[] = "La fecha {$fecha} ya ha sido consumida anteriormente.";
                    } elseif ($detalle['estado'] === 'Pendiente') {
                        $errores[] = "La fecha {$fecha} esta en proceso de aprobacion.";
                    } elseif (in_array($detalle['estado'], ['Cancelado', 'Rechazado'])) {
                        // Permitir reutilizar fechas con estado "Cancelado" o "Rechazado"
                        continue;
                    } else {
                        $errores[] = "La fecha {$fecha} esta en estado de validacion.";
                    }
                }
            }

            // Si hay errores, devolverlos
            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            // Insertar la solicitud principal
            $id_solicitud = $this->model->insertSolicitudOperativa($id_empleado, $comentario_solicitud, $revision_aprobador_1, $id_categoria, $area_solicitud);

            if (!$id_solicitud) {
                echo json_encode(["status" => false, "message" => "Error al guardar la solicitud principal."]);
                exit;
            }

            // Insertar los detalles de la solicitud
            $errores = [];
            foreach ($fechas as $index => $fecha) {
                $valorDia = $valores[$index] ?? null;
                $diaTipo = $dias[$index] ?? "Completo";

                // Validación de datos antes de insertar
                if (!$fecha || !$valorDia || !$diaTipo) {
                    $errores[] = "Error: Datos incompletos para la fecha {$fecha}.";
                    continue; // Saltar al siguiente detalle
                }

                // Insertar el detalle
                if (!$this->model->insertDetalleSolicitud($fecha, $valorDia, $diaTipo, $id_solicitud)) {
                    $errores[] = "Error al insertar la fecha {$fecha}.";
                }
            }

            //ENVIAR CORREO

            $responsable_aprobacion = $revision_aprobador_1; // Convertir a número


            $arrData = [
                'solicitud' => $this->model->selectPersonalIDempleado($id_empleado),
                'jefe' => $this->model->selectPersonalIJefe($responsable_aprobacion),
                'fechas' => $fechas,
                'valores' => $valores,
                'dias_solicitados' => $dias_solicitados,
                'dias' => $dias,
                'comentario_solicitud' => $comentario_solicitud
            ];

            if (!empty($arrData['solicitud']) && isset($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudEmail.php';
                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode(["status" => true, "message" => "Solicitud guardada pero plantilla de correo no encontrada"]);
                    exit;
                }
            }

            if (!empty($arrData['jefe']) && isset($arrData['jefe']['correo_empresarial'])) {
                $sendcorreo_responsable = 'Views/Template/Email/SolicitudEmailCC.php'; // Puedes usar otra plantilla
                if (file_exists($sendcorreo_responsable)) {
                    require $sendcorreo_responsable;
                } else {
                    echo json_encode(["status" => true, "message" => "Solicitud guardada pero plantilla de correo para responsable no encontrada"]);
                    exit;
                }
            }

            // Responder según el resultado
            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            echo json_encode(["status" => true, "message" => "Solicitud registrada correctamente."]);
        }
    }

    public function getSelectAprobador()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectJefesLideres();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['usuario'] . " | " . $arrData[$i]['area'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectAprobadorOperativa()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectJefesLideres();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['usuario'] . " | " . $arrData[$i]['area'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectCategorias()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectCategorias();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_categoria'] . '">' . $arrData[$i]['asunto'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectCordinadorArea()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectCordinadoresArea();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['usuario'] . " | " . $arrData[$i]['area'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSolicitudbyID($id_solicitud)
    {
        $arrData = $this->model->selectSolicitudesbyID($id_solicitud);

        // Verificar si se encontraron datos
        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            // Transformar las fechas y valores a arrays
            $arrData['fechas'] = explode(",", $arrData['fechas']);
            $arrData['valores'] = explode(",", $arrData['valores']);
            $arrData['dias'] = explode(',', $arrData['dias']);


            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudbyIDOperativa($id_solicitud)
    {
        $solicitud = $this->model->selectSolicitudesbyID($id_solicitud);

        if ($solicitud) {
            echo json_encode(["status" => true, "data" => $solicitud]);
        } else {
            echo json_encode(["status" => false, "message" => "No se encontró la solicitud."]);
        }
        exit;
    }


    public function getSolicitudbyIDPendiente($id_empleado)
    {
        $arrData = $this->model->selectSolicitudPendiente($id_empleado);

        // Verificar si se encontraron datos
        if ($arrData) {
            echo json_encode(["status" => true, "data" => $arrData]);
        } else {
            echo json_encode(["status" => false, "message" => "No se encontró la solicitud."]);
        }
        exit;
    }

    public function getSolicitudID($id_solicitud)
    {
        $solicitud = $this->model->selectSolicitudesID($id_solicitud);

        if ($solicitud) {
            echo json_encode(["status" => true, "data" => $solicitud]);
        } else {
            echo json_encode(["status" => false, "message" => "No se encontró la solicitud."]);
        }
        exit;
    }

    public function actualizarSolicitud()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud'];
            $id_empleado = $_POST['id_empleado'];
            $responsable_aprobacion = $_POST['responsable_aprobacion'] ?? NULL;
            $revision_aprobador_1 = $_POST['revision_aprobador_1'] ?? NULL;
            $comentario_solicitud = $_POST['comentario_solicitud'];
            $id_categoria = $_POST['id_categoria'];
            $fechas = $_POST['fechas']; // Array de fechas del formulario
            $valores = $_POST['valor']; // Array de valores
            $dias = $_POST['dia']; // Array de tipos de día

            // Validación básica
            if (empty($id_solicitud) || empty($fechas) || empty($valores) || empty($dias)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Validar si hay fechas duplicadas en el formulario
            if (count($fechas) !== count(array_unique($fechas))) {
                echo json_encode(["status" => false, "message" => "Hay fechas duplicadas en el formulario."]);
                exit;
            }

            // Obtener días disponibles del empleado
            $periodo = $this->model->obtenerDiasDisponibles($id_empleado);
            if (!$periodo) {
                echo json_encode(["status" => false, "message" => "No tienes días de vacaciones disponibles."]);
                exit;
            }

            $dias_disponibles = floatval($periodo['dias_disponibles']); // Convertir a número
            $dias_solicitados = array_sum($valores); // Sumar todos los valores solicitados

            // Verificar si tiene suficientes díasx|
            if ($dias_solicitados > $dias_disponibles) {
                echo json_encode([
                    "status" => false,
                    "message" => "No tienes suficientes días de vacaciones. Disponibles: $dias_disponibles, Solicitados: $dias_solicitados."
                ]);
                exit;
            }

            // Actualizar la solicitud principal
            $updateSolicitud = $this->model->updateSolicitud($id_solicitud, $responsable_aprobacion, $revision_aprobador_1, $comentario_solicitud, $id_categoria);

            if (!$updateSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al actualizar la solicitud principal."]);
                exit;
            }

            // Obtener las fechas existentes en la base de datos para la solicitud
            $detallesExistentes = $this->model->getDetallesPorSolicitud($id_solicitud);
            $fechasExistentes = array_column($detallesExistentes, 'fecha'); // Extraer fechas existentes

            // Identificar detalles a eliminar
            $fechasEliminar = array_diff($fechasExistentes, $fechas);

            // Eliminar detalles que no están en el formulario
            foreach ($fechasEliminar as $fechaEliminar) {
                if (!$this->model->deleteDetalleSolicitud($id_solicitud, $fechaEliminar)) {
                    $errores[] = "Error al eliminar la fecha {$fechaEliminar}.";
                }
            }

            // Manejar los detalles nuevos o existentes
            $errores = [];
            foreach ($fechas as $index => $fecha) {
                $valorDia = $valores[$index] ?? null;
                $diaTipo = $dias[$index] ?? "Completo";

                // Validar datos faltantes
                if (!$fecha || !$valorDia || !$diaTipo) {
                    $errores[] = "Datos incompletos en la fecha {$fecha}.";
                    continue;
                }

                if (in_array($fecha, $fechasExistentes)) {
                    // Si la fecha ya existe, actualizar el registro
                    if (!$this->model->updateDetalleSolicitud($id_solicitud, $fecha, $valorDia, $diaTipo)) {
                        $errores[] = "Error al actualizar la fecha {$fecha}.";
                    }
                } else {
                    // Si la fecha no existe, insertar el detalle
                    if (!$this->model->insertDetalleSolicitud($fecha, $valorDia, $diaTipo, $id_solicitud)) {
                        $errores[] = "Error al insertar la fecha {$fecha}.";
                    }
                }
            }

            //ENVIAR CORREO
            $id_responsable = !empty($responsable_aprobacion) ? $responsable_aprobacion : $revision_aprobador_1;

            if (!$id_responsable) {
                echo json_encode(["status" => false, "message" => "No se encontró un responsable válido."]);
                exit;
            }

            $arrData = [
                'solicitud' => $this->model->selectSolicitudesID($id_solicitud),
                'correo' => $this->model->selectAprobador($id_responsable),
                'dias_solicitados' => $dias_solicitados,
                'fechas' => $fechas,
                'valores' => $valores,
                'dias' => $dias,
                'comentario_solicitud' => $comentario_solicitud
            ];

            if (!empty($arrData['solicitud']) && isset($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/updateSolicitudEmail.php';
                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode(["status" => true, "message" => "Solicitud guardada pero plantilla de correo no encontrada"]);
                    exit;
                }
            }

            if (!empty($arrData['correo']) && isset($arrData['correo']['correo_empresarial'])) {
                $sendcorreoEmpleadoCC = 'Views/Template/Email/updateSolicitudEmailCC.php';
                if (file_exists($sendcorreoEmpleadoCC)) {
                    require $sendcorreoEmpleadoCC;
                } else {
                    echo json_encode(["status" => true, "message" => "Solicitud guardada pero plantilla de correo para responsable no encontrada"]);
                    exit;
                }
            }

            // Responder según el resultado
            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            echo json_encode(["status" => true, "message" => "Solicitud actualizada correctamente."]);
        }
    }

    public function eliminarFecha()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud'];
            $fecha = $_POST['fecha'];

            if (empty($id_solicitud) || empty($fecha)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Llamar al modelo para eliminar el detalle
            $result = $this->model->deleteFechaDetalle($id_solicitud, $fecha);

            if ($result) {
                echo json_encode(["status" => true, "message" => "Fecha eliminada correctamente."]);
            } else {
                echo json_encode(["status" => false, "message" => "Error al eliminar la fecha."]);
            }
        }
    }

    public function guardarSolicitud()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibir datos del formulario
            $id_empleado = intval($_POST['id_empleado']);
            $comentario_solicitud = htmlspecialchars($_POST['comentario_solicitud'], ENT_QUOTES, 'UTF-8');
            $responsable_aprobacion = intval($_POST['responsable_aprobacion']);
            $id_categoria = intval($_POST['id_categoria']);
            $area_solicitud = intval($_POST['area_solicitud']);
            $fechas = $_POST['fechas']; // Array de fechas
            $valores = $_POST['valor']; // Array de valores (1 o 0.5)
            $dias = $_POST['dia']; // Array de días (AM, PM, Completo)

            // Validación inicial
            if (empty($id_empleado) || empty($fechas) || empty($valores) || empty($dias)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Validar si hay fechas duplicadas en el formulario
            if (count($fechas) !== count(array_unique($fechas))) {
                echo json_encode(["status" => false, "message" => "Hay fechas duplicadas en la solicitud."]);
                exit;
            }

            // Obtener días disponibles del empleado
            $periodo = $this->model->obtenerDiasDisponibles($id_empleado);
            if (!$periodo) {
                echo json_encode(["status" => false, "message" => "No tienes días de vacaciones disponibles."]);
                exit;
            }

            $dias_disponibles = floatval($periodo['dias_disponibles']); // Convertir a número
            $dias_solicitados = array_sum($valores); // Sumar todos los valores solicitados

            // Verificar si tiene suficientes díasx|
            if ($dias_solicitados > $dias_disponibles) {
                echo json_encode([
                    "status" => false,
                    "message" => "No tienes suficientes días de vacaciones. Disponibles: $dias_disponibles, Solicitados: $dias_solicitados."
                ]);
                exit;
            }

            // Validar si las fechas ya existen y el estado
            $errores = [];
            foreach ($fechas as $fecha) {
                $detalle = $this->model->getDetallePorFechaEmpleado($id_empleado, $fecha);

                if ($detalle) {
                    switch ($detalle['estado']) {
                        case 'Consumido':
                            $errores[] = "La fecha {$fecha} ya ha sido consumida anteriormente.";
                            break;
                        case 'Pendiente':
                            $errores[] = "La fecha {$fecha} está en proceso de aprobación.";
                            break;
                        case 'Cancelado':
                        case 'Rechazado':
                            // Permitir reutilización
                            break;
                        default:
                            $errores[] = "La fecha {$fecha} está en estado de validación.";
                            break;
                    }
                }
            }


            // Si hay errores, devolverlos
            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            // Insertar la solicitud principal
            $id_solicitud = $this->model->insertSolicitud($id_empleado, $comentario_solicitud, $responsable_aprobacion, $id_categoria, $area_solicitud);

            if (!$id_solicitud) {
                echo json_encode(["status" => false, "message" => "Error al guardar la solicitud principal."]);
                exit;
            }

            // Insertar los detalles de la solicitud
            $errores = [];
            foreach ($fechas as $index => $fecha) {
                $valorDia = isset($valores[$index]) ? floatval($valores[$index]) : null;
                $diaTipo = isset($dias[$index]) ? $dias[$index] : "Completo";

                if (!$fecha || !$valorDia) {
                    $errores[] = "Error: Datos incompletos para la fecha {$fecha}.";
                    continue;
                }

                if (!$this->model->insertDetalleSolicitud($fecha, $valorDia, $diaTipo, $id_solicitud)) {
                    $errores[] = "Error al insertar la fecha {$fecha}.";
                }
            }

            //ENVIAR CORREO

            $arrData = [
                'solicitud' => $this->model->selectPersonalIDempleado($id_empleado),
                'jefe' => $this->model->selectPersonalIJefe($responsable_aprobacion),
                'fechas' => $fechas,
                'valores' => $valores,
                'dias_solicitados' => $dias_solicitados,
                'dias' => $dias,
                'comentario_solicitud' => $comentario_solicitud
            ];

            if (!empty($arrData['solicitud']) && isset($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudEmail.php';
                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode(["status" => true, "message" => "Solicitud guardada pero plantilla de correo no encontrada"]);
                    exit;
                }
            }

            if (!empty($arrData['jefe']) && isset($arrData['jefe']['correo_empresarial'])) {
                $sendcorreo_responsable = 'Views/Template/Email/SolicitudEmailCC.php'; // Puedes usar otra plantilla
                if (file_exists($sendcorreo_responsable)) {
                    require $sendcorreo_responsable;
                } else {
                    echo json_encode(["status" => true, "message" => "Solicitud guardada pero plantilla de correo para responsable no encontrada"]);
                    exit;
                }
            }

            // Responder según el resultado
            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            echo json_encode(["status" => true, "message" => "Solicitud registrada correctamente."]);
        }
    }

    public function cancelarSolicitud()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud'] ?? null;
            $estado = 'Cancelado';
            $comentario = $_POST['comentario'] ?? '';

            // Validar los datos recibidos
            if (empty($id_solicitud) || empty($comentario)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Cancelado"
            $actualizadoSolicitud = $this->model->updateSolicitudEstado($id_solicitud, $estado, $comentario);

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al cancelar la solicitud principal."]);
                exit;
            }

            // Cambiar el estado de las fechas relacionadas a "Rechazado"
            $actualizadoFechas = $this->model->rechazarFechasSolicitud($id_solicitud);

            if (!$actualizadoFechas) {
                echo json_encode(["status" => false, "message" => "Error al actualizar el estado de las fechas."]);
                exit;
            }

            // Obtener información del colaborador
            $colaborador = $this->model->selectSolicitudesID($id_solicitud);

            if (!$colaborador || empty($colaborador['correo_empresarial'])) {
                echo json_encode(["status" => true, "message" => "Solicitud cancelada, pero no se encontró información del colaborador para enviar correo."]);
                exit;
            }


            // Datos para el correo
            $arrData = [
                'colaborador' => $colaborador,
                'comentario' => $comentario,
                'jefes' => $this->model->selectJefesID($id_solicitud)
            ];

            // Enviar correo
            $sendcorreo = 'Views/Template/Email/SolicitudCanceladaEmail.php';
            $sendcorreocc = 'Views/Template/Email/SolicitudCanceladaEmailCC.php';

            if (file_exists($sendcorreo)) {
                require $sendcorreo;
                require $sendcorreocc;
            } else {
                error_log("Error: Plantilla de correo no encontrada en $sendcorreo");
                echo json_encode(["status" => true, "message" => "Solicitud cancelada, pero no se encontró la plantilla de correo."]);
                exit;
            }

            echo json_encode(["status" => true, "message" => "Solicitud cancelada correctamente y fechas actualizadas a 'Rechazado'."]);
            exit;
        }
    }

    public function revertirSolicitud()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud_reversion'] ?? null;
            $estado = 'Reversion';
            $comentario = $_POST['comentario_solicitud'];


            // Validar los datos recibidos
            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Reversion"
            $actualizadoSolicitud = $this->model->updateSolicitudEstado($id_solicitud, $estado, $comentario);

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al cancelar la solicitud principal."]);
                exit;
            }

            // Obtener información del colaborador
            $colaborador = $this->model->selectSolicitudesID($id_solicitud);

            // Datos para el correo
            $arrData = [
                'colaborador' => $colaborador,
                'jefes' => $this->model->selectJefesID($id_solicitud)
            ];

            // Enviar correo
            $sendcorreo = 'Views/Template/Email/SolicitudReversion.php';

            if (file_exists($sendcorreo)) {
                require $sendcorreo;
            } else {
                error_log("Error: Plantilla de correo no encontrada en $sendcorreo");
                echo json_encode(["status" => true, "message" => "Solicitud Completada, pero no se encontró la plantilla de correo."]);
                exit;
            }

            echo json_encode(["status" => true, "message" => "Solicitud Comenzada. Se ha iniciado el proceso de reversion de dias."]);
            exit;
        }
    }

}