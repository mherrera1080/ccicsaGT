<?php
session_start();

class Vacaciones_Revision extends Controllers
{

    public $views;
    public $model;

    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(VACACIONES);
    }

    public function Vacaciones_Periodos()
    {
        $data['page_id'] = PERIODOS;
        $data['page_tag'] = "Periodos";
        $data['page_title'] = "Periodos";
        $data['page_name'] = "Periodos";
        $data['page_functions_js'] = "functions_vacaciones_Periodos.js";

        $this->views->getView($this, "Vacaciones_Periodos", $data);
    }

    public function getAllPeriodos()
    {
        $id_empleado = isset($_POST['id_empleado']) ? intval($_POST['id_empleado']) : 0;

        // Obtener todos los periodos o filtrar por empleado
        $data = ($id_empleado > 0) ?
            $this->model->getPeriodosVacaciones($id_empleado) :
            $this->model->getPeriodosVacaciones();

        echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
        die();
    }


    public function getSelectEmpleado()
    {
        $htmlOptions = '<option selected disabled value="">Seleccione un empleado</option>'; // Añadir esta opción una sola vez
        $arrData = $this->model->selectPersonal();
        if (count($arrData) > 0) {
            // Ahora solo agregas las opciones de los empleados
            foreach ($arrData as $empleado) {
                $htmlOptions .= '<option value="' . $empleado['id_empleado'] . '">' . $empleado['nombre_completo'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }


    public function getAllSolicitudes()
    {
        $arrData = $this->model->selectAllSolicitudes();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getAllSolicitudesbyMes($mes)
    {
        $arrData = $this->model->selectAllSolicitudesbyMes($mes);

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    

    public function getSolicitudesRevisadasMes()
    {
        $request = $this->model->selectSolicitudesRevisadasMes();
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesAprobadasMes()
    {
        $request = $this->model->selectSolicitudesAprobadasMes();
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesrechazadasMes()
    {
        $request = $this->model->selectSolicitudesRechazadasMes();
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesPendientesMes()
    {
        $request = $this->model->selectSolicitudesPendientesMes();
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesCanceladasMes()
    {
        $request = $this->model->selectSolicitudesCacenladasMes();
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesRevisadasbyMes($mes)
    {
        $request = $this->model->getSolicitudesRevisadasMes($mes);
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesAprobadasbyMes($mes)
    {
        $request = $this->model->getSolicitudesAprobadasMes($mes);
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSolicitudesRechazadasbyMes($mes)
    {
        $request = $this->model->getSolicitudesRechazadoMes($mes);
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function getSolicitudesPendientesbyMes($mes)
    {
        $request = $this->model->getSolicitudesPendienteMes($mes);
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function getSolicitudesCanceladasbyMes($mes)
    {
        $request = $this->model->getSolicitudesCanceladasMes($mes);
        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function Vacaciones_Revision()
    {
        $data['page_id'] = VACACIONES;
        $data['page_tag'] = "Vacaciones General";
        $data['page_title'] = "Vacaciones General";
        $data['page_name'] = "Vacaciones General";
        $data['page_functions_js'] = "functions_vacaciones_revision.js";

        $this->views->getView($this, "Vacaciones_Revision", $data);
    }

    public function Vacaciones()
    {
        $correo_empresarial = $_SESSION['userData']['correo_empresarial'];
        $id_empleado = $_SESSION['userData']['id_empleado'];

        $estadistica = $this->model->selectPersonalIDempleado($id_empleado);
        $usuario = isset($estadistica['jefe_id']) ? $estadistica['jefe_id'] : 0;
        $registro = $this->model->getSolicitudes($usuario);

        $jefe = $this->model->selectPersonalID($correo_empresarial);

        $data['jefe'] = $jefe;
        $data['registro'] = $registro;
        $data['page_id'] = ADMINISTRATIVO;
        $data['page_tag'] = "Vacaciones";
        $data['page_title'] = "Vacaciones";
        $data['page_name'] = "Vacaciones";
        $data['page_functions_js'] = "functions_solicitud_vacaciones_revision.js";

        $this->views->getView($this, "Vacaciones", $data);
    }

    public function VacacionesOperativa()
    {
        $correo_empresarial = $_SESSION['userData']['correo_empresarial'];
        $id_empleado = $_SESSION['userData']['id_empleado'];

        $estadistica = $this->model->selectPersonalIDempleado($id_empleado);
        $usuario = isset($estadistica['jefe_id']) ? $estadistica['jefe_id'] : 0;
        $registro = $this->model->getSolicitudesOperativa($usuario);

        $jefe = $this->model->selectPersonalID($correo_empresarial);
        $data['jefe'] = $jefe;
        $data['registro'] = $registro;
        $data['page_id'] = OPERATIVA;
        $data['page_tag'] = "Operativa";
        $data['page_title'] = "Operativa";
        $data['page_name'] = "Operativa";
        $data['page_functions_js'] = "functions_solicitud_vacaciones_operativa.js";

        $this->views->getView($this, "Operativa", $data);
    }

    public function getTotalesByJefe($id_empleado)
    {
        $arrData = $this->model->getSolicitudes($id_empleado);

        if ($arrData) {
            // Asegurar que los valores nulos sean reemplazados por 0
            $arrData = array_map(function ($value) {
                return $value ?? 0;
            }, $arrData);

            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function getSolicitudesbyJefe($id_empleado)
    {
        $arrData = $this->model->selectSolicitudesPorJefe($id_empleado);

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

    public function getSolicitudesbyJefeOperativa($id_empleado)
    {
        $arrData = $this->model->selectSolicitudesPorJefeOperativa($id_empleado);

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

    public function getSolicitudbyID($id_solicitud)
    {
        $solicitud = $this->model->selectSolicitudesbyID($id_solicitud);

        if ($solicitud) {
            echo json_encode(["status" => true, "data" => $solicitud]);
        } else {
            echo json_encode(["status" => false, "message" => "No se encontró la solicitud."]);
        }
        exit;
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



    public function setAprobarEmpleadoOperativa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_solicitud = $_POST['id_solicitud_operativa'] ?? null;
            $estado = 'Pendiente Aprob. 2';
            $revision_aprobador_2 = $_POST['revision_aprobador_2'] ?? null;
            $comentario = null;
            $comentario_respuesta = 'Aprobada-' . date('Y-m-d') . ' | ' . ($_POST['comentario_respuesta'] ?? '');

            if (empty($comentario_respuesta)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Comentario necesario para Aprobar o Rechazar."
                ]);
                exit;
            }

            // Actualizar el estado de la solicitud
            $actualizadoSolicitud = $this->model->updateAprobarOperativa1(
                $id_solicitud,
                $estado,
                $comentario,
                $comentario_respuesta,
                $revision_aprobador_2

            );

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al aprobar la solicitud."]);
                exit;
            }

            // ENVIAR CORREO

            $id_responsable = $revision_aprobador_2;

            $arrData = [
                'colaborador' => $this->model->selectAprobador($id_responsable),
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'estado' => $estado,
                'comentario' => $comentario_respuesta
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            if (!empty($arrData['colaborador']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaEmailCC.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }


            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido aprobada correctamente."]);
            exit;
        }
    }

    public function setRechazoEmpleadoOperativa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud_operativa'] ?? null;
            $comentario_respuesta = 'Rechazada - ' . date('Y-m-d') . ' | ' . ($_POST['comentario_respuesta'] ?? '');
            $comentario = $comentario_respuesta;
            if (empty($id_solicitud) || empty($comentario_respuesta)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Debe proporcionar un ID de solicitud y un comentario para rechazarla."
                ]);
                exit;
            }

            // Actualizar el estado de la solicitud
            $actualizadoSolicitud = $this->model->updateAprobarOperativa1(
                $id_solicitud,
                'Rechazado',
                $comentario,
                $comentario_respuesta,
                null
            );

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de los detalles
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Rechazado');

            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            // ENVIAR CORREO
            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'comentario' => $comentario_respuesta
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaRechazadaEmail.php';
                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido rechazada correctamente."]);
            exit;
        }
    }


    public function setAprobarEmpleadoOperativa2()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_revision_aprobador_2'] ?? null;
            $estado = 'Pendiente Aprob. 3';
            $revision_aprobador_3 = $_POST['revision_aprobador_3'] ?? null;
            $comentario = null;
            $comentario_respuesta = 'Aprobada-' . date('Y-m-d') . ' | ' . ($_POST['comentario_respuesta'] ?? '');

            if (empty($comentario_respuesta)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Comentario necesario para Aprobar o Rechazar."
                ]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Aprobado"
            $actualizadoSolicitud = $this->model->updateAprobarOperativa2(
                $id_solicitud,
                $estado,
                $comentario,
                $comentario_respuesta,
                $revision_aprobador_3
            );

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al aprobar la solicitud."]);
                exit;
            }

            // Actualizar el estado de los detalles asociados a "Consumido"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Pendiente', $comentario);

            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            // ENVIAR CORREO
            $id_responsable = $revision_aprobador_3;

            $arrData = [
                'colaborador' => $this->model->selectAprobador($id_responsable),
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'estado' => $estado,
                'comentario' => $comentario_respuesta
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            if (!empty($arrData['colaborador']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaEmailCC.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido aprobada correctamente."]);
            exit;
        }
    }

    public function setRechazoEmpleadoOperativa2()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_revision_aprobador_2'] ?? null;
            $comentario_respuesta = 'Rechazada - ' . date('Y-m-d') . ' | ' . ($_POST['comentario_respuesta'] ?? '');
            $comentario = $comentario_respuesta;

            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            if (empty($comentario)) {
                echo json_encode(["status" => false, "message" => "Debe proporcionar un comentario para rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Rechazado"
            $actualizadoSolicitud = $this->model->updateAprobarOperativa2(
                $id_solicitud,
                'Rechazado',
                $comentario,
                $comentario_respuesta,
                null,
            );

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de los detalles asociados a "Rechazado"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Rechazado');

            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            // ENVIAR CORREO
            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaRechazadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido rechazada correctamente."]);
            exit;
        }
    }

    public function setAprobarEmpleadoOperativa3()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_revision_aprobador_3'] ?? null;
            $comentario = 'Aprobada-' . date('Y-m-d') . ' | ' . ($_POST['comentario_respuesta'] ?? '');
            $categoria_solicitud = $_POST['categoria_solicitud'] ?? null;

            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            if (empty($comentario)) {
                echo json_encode(["status" => false, "message" => "Debe proporcionar un comentario para aprobar la solicitud."]);
                exit;
            }

            $detalles = $this->model->getDetallesSolicitud($id_solicitud);
            if (empty($detalles)) {
                echo json_encode(["status" => false, "message" => "No se encontraron detalles de la solicitud."]);
                exit;
            }

            $diasAprobados = 0;
            foreach ($detalles as $detalle) {
                $diasAprobados += floatval($detalle['valor']); // Convertimos el valor a float para evitar errores
            }

            $id_empleado = $detalles[0]['id_empleado'];


            // Actualizar el estado de la solicitud a "Aprobado"
            $actualizadoSolicitud = $this->model->updateEstadoSolicitud($id_solicitud, 'Aprobado', $comentario);
            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al aprobar la solicitud."]);
                exit;
            }

            // Actualizar el estado de los detalles asociados a "Consumido"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Consumido', $comentario);
            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            $actualizadoDias = $this->model->actualizarPeriodoEmpleado($id_empleado, $diasAprobados, $categoria_solicitud);
            if (!$actualizadoDias) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los días de vacaciones del empleado."]);
                exit;
            }

            // ENVIAR CORREO

            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaAprobadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido aprobada correctamente."]);
            exit;
        }
    }

    public function setRechazoEmpleadoOperativa3()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_revision_aprobador_3'] ?? null;
            $comentario = 'Aprobada-' . date('Y-m-d') . ' | ' . ($_POST['comentario_respuesta'] ?? '');

            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            if (empty($comentario)) {
                echo json_encode(["status" => false, "message" => "Debe proporcionar un comentario para rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Rechazado"
            $actualizadoSolicitud = $this->model->updateEstadoSolicitud($id_solicitud, 'Rechazado', $comentario);

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de los detalles asociados a "Rechazado"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Rechazado');

            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            // ENVIAR CORREO
            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudOperativaRechazadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido rechazada correctamente."]);
            exit;
        }
    }

    public function setAprobarEmpleado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_solicitud = $_POST['id_solicitud'] ?? null;
            $comentario = $_POST['comentario_respuesta'] ?? null;
            $categoria_solicitud = $_POST['categoria_solicitud'] ?? null;

            if (!$id_solicitud) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            // Obtener detalles de la solicitud
            $detalles = $this->model->getDetallesSolicitud($id_solicitud);

            if (empty($detalles)) {
                echo json_encode(["status" => false, "message" => "No se encontraron detalles de la solicitud."]);
                exit;
            }

            // Sumar los días aprobados
            $diasAprobados = 0;
            foreach ($detalles as $detalle) {
                $diasAprobados += floatval($detalle['valor']); // Convertimos el valor a float para evitar errores
            }

            // Obtener el id del empleado
            $id_empleado = $detalles[0]['id_empleado'];

            // Actualizar la solicitud de vacaciones
            $actualizadoSolicitud = $this->model->updateEstadoSolicitud($id_solicitud, 'Aprobado', $comentario);
            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al aprobar la solicitud."]);
                exit;
            }

            // Marcar los detalles como "Consumido"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Consumido', $comentario);
            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            // Actualizar los días del empleado
            $actualizadoDias = $this->model->actualizarPeriodoEmpleado($id_empleado, $diasAprobados, $categoria_solicitud);
            if (!$actualizadoDias) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los días de vacaciones del empleado."]);
                exit;
            }

            $arrData = [
                'colaborador' => $this->model->selectPersonalIDempleado($id_empleado),
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['colaborador']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudAprobadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }
            // Enviar correo de aprobación si el empleado tiene un correo registrado
            if (!empty($arrData['colaborador']['correo_jefe'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudAprobadaEmailCC.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido aprobada correctamente."]);
            exit;
        }
    }

    public function setRechazoEmpleado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud'] ?? null;
            $comentario = $_POST['comentario_respuesta'] ?? null;

            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            if (empty($comentario)) {
                echo json_encode(["status" => false, "message" => "Debe proporcionar un comentario para rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Rechazado"
            $actualizadoSolicitud = $this->model->updateEstadoSolicitud($id_solicitud, 'Rechazado', $comentario);

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de los detalles asociados a "Rechazado"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Rechazado');

            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }


            // CORREOOO

            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'jefes' => $this->model->selectJefesID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudRechazadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }
            // Enviar correo de aprobación si el empleado tiene un correo registrado
            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/SolicitudRechazadaEmailCC.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido rechazada correctamente."]);
            exit;
        }
    }


    public function setAprobarReversion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_solicitud = $_POST['id_solicitud_reversion'] ?? null;
            $comentario = $_POST['comentario_respuesta'] ?? null;
            $categoria_solicitud = $_POST['categoria_solicitud'] ?? null;

            if (!$id_solicitud) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            // Obtener detalles de la solicitud
            $detalles = $this->model->getDetallesSolicitud($id_solicitud);

            if (empty($detalles)) {
                echo json_encode(["status" => false, "message" => "No se encontraron detalles de la solicitud."]);
                exit;
            }

            // Sumar los días aprobados
            $diasAprobados = 0;
            foreach ($detalles as $detalle) {
                $diasAprobados += floatval($detalle['valor']); // Convertimos el valor a float para evitar errores
            }

            // Obtener el id del empleado
            $id_empleado = $detalles[0]['id_empleado'];

            // Actualizar la solicitud de vacaciones
            $actualizadoSolicitud = $this->model->updateEstadoSolicitud($id_solicitud, 'Revertido', $comentario);
            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al aprobar la solicitud."]);
                exit;
            }

            // Marcar los detalles como "Consumido"
            $actualizadoDetalles = $this->model->updateEstadoDetalles($id_solicitud, 'Revertido', $comentario);
            if (!$actualizadoDetalles) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los detalles de la solicitud."]);
                exit;
            }

            // Actualizar los días del empleado
            $actualizadoDias = $this->model->revertirSolicitud($id_empleado, $diasAprobados, $categoria_solicitud);
            if (!$actualizadoDias) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los días de vacaciones del empleado."]);
                exit;
            }

            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['colaborador']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/reversionAprobadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "Solicitud de Reversion Aprobada."]);
            exit;
        }
    }

    public function setRechazoReversion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud_reversion'] ?? null;
            $comentario = $_POST['comentario_respuesta'] ?? null;

            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "ID de solicitud no proporcionado."]);
                exit;
            }

            if (empty($comentario)) {
                echo json_encode(["status" => false, "message" => "Debe proporcionar un comentario para rechazar la solicitud."]);
                exit;
            }

            // Actualizar el estado de la solicitud a "Rechazado"
            $actualizadoSolicitud = $this->model->updateEstadoSolicitud($id_solicitud, 'Aprobado', $comentario);

            if (!$actualizadoSolicitud) {
                echo json_encode(["status" => false, "message" => "Error al rechazar la solicitud."]);
                exit;
            }


            // CORREOOO

            $arrData = [
                'solicitud' => $this->model->selectSolicitudesbyID($id_solicitud),
                'comentario' => $comentario
            ];

            if (!empty($arrData['solicitud']['correo_empresarial'])) {
                $sendcorreoEmpleado = 'Views/Template/Email/reversionRechazadaEmail.php';

                if (file_exists($sendcorreoEmpleado)) {
                    require $sendcorreoEmpleado;
                } else {
                    echo json_encode([
                        "status" => true,
                        "message" => "Solicitud aprobada, pero la plantilla de correo no fue encontrada."
                    ]);
                    exit;
                }
            }

            // Respuesta exitosa
            echo json_encode(["status" => true, "message" => "La solicitud ha sido rechazada correctamente."]);
            exit;
        }
    }


}