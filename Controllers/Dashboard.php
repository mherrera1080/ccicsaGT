<?php
session_start();
class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(DASHBOARD);

    }

    public function Dashboard()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['page_name'] = "dashboard";
        $data['page_functions_js'] = "functions_dashboard.js";

        $this->views->getView($this, "Dashboard", $data);
    }

    public function barColaboradores()
    {
        $anioActual = date("Y"); // Año actual
        $arrData = $this->model->getContrataciones(); // Obtener todos los datos

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            // Inicializar contadores
            $conteoContrataciones = [];
            $conteoBajas = [];
            $conteoRecontrataciones = [];

            // Filtrar solo los registros del año actual y contar
            foreach ($arrData as $row) {
                $fecha = strtotime($row['Fecha']);
                $anioRegistro = date("Y", $fecha);
                if ($anioRegistro == $anioActual) { // Solo registros del año actual
                    $mesAnio = date("Y-m", $fecha);

                    if (!isset($conteoContrataciones[$mesAnio])) {
                        $conteoContrataciones[$mesAnio] = 0;
                        $conteoBajas[$mesAnio] = 0;
                        $conteoRecontrataciones[$mesAnio] = 0;
                    }

                    switch ($row['TipoMovimiento']) {
                        case 'Nuevo Ingreso':
                            $conteoContrataciones[$mesAnio]++;
                            break;
                        case 'Baja':
                            $conteoBajas[$mesAnio]++;
                            break;
                        case 'Recontratacion':
                            $conteoRecontrataciones[$mesAnio]++;
                            break;
                    }
                }
            }

            // Generar lista de meses para el año actual
            $todosMeses = [];
            for ($i = 1; $i <= 12; $i++) {
                $mes = sprintf("%04d-%02d", $anioActual, $i); // Asegura el formato YYYY-MM
                $todosMeses[] = $mes;
            }

            // Preparar datos para el gráfico
            $labels = [];
            $contratacionesData = [];
            $bajasData = [];
            $recontratacionesData = [];

            foreach ($todosMeses as $mes) {
                $labels[] = date("F Y", strtotime($mes . '-01')); // Nombre del mes
                $contratacionesData[] = $conteoContrataciones[$mes] ?? 0;
                $bajasData[] = $conteoBajas[$mes] ?? 0;
                $recontratacionesData[] = $conteoRecontrataciones[$mes] ?? 0;
            }

            $arrResponse = array(
                'status' => true,
                'data' => [
                    'labels' => $labels,
                    'contrataciones' => $contratacionesData,
                    'bajas' => $bajasData,
                    'recontrataciones' => $recontratacionesData
                ]
            );
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function barRH()
    {
        // Verificar si 'anio' está presente en la URL y es un número válido
        $year = isset($_GET['anio']) && is_numeric($_GET['anio']) ? intval($_GET['anio']) : date("Y"); // Usa el año actual si no se pasa

        $arrData = $this->model->getContratacionesYear($year); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            // Inicializar las variables para el conteo de movimientos
            $labels = [];
            $conteoContrataciones = [];
            $conteoBajas = [];
            $conteoRecontrataciones = [];

            // Recorrer los datos de contrataciones
            foreach ($arrData as $row) {
                $mesAnio = date("Y-m", strtotime($row['Fecha'])); // Obtener el mes y año

                // Inicializar los contadores para cada mes
                if (!isset($conteoContrataciones[$mesAnio])) {
                    $conteoContrataciones[$mesAnio] = 0;
                    $conteoBajas[$mesAnio] = 0;
                    $conteoRecontrataciones[$mesAnio] = 0;
                }

                // Contar por tipo de movimiento
                switch ($row['TipoMovimiento']) {
                    case 'Nuevo Ingreso':
                        $conteoContrataciones[$mesAnio]++;
                        break;
                    case 'Baja':
                        $conteoBajas[$mesAnio]++;
                        break;
                    case 'Recontratacion':
                        $conteoRecontrataciones[$mesAnio]++;
                        break;
                }
            }

            // Generar los meses del año seleccionado
            $todosMeses = [];
            for ($i = 1; $i <= 12; $i++) {
                $mes = sprintf("%04d-%02d", $year, $i);
                $todosMeses[] = $mes;
            }

            // Preparar los datos para la gráfica
            $labels = [];
            $contratacionesData = [];
            $bajasData = [];
            $recontratacionesData = [];

            // Llenar los datos de cada mes
            foreach ($todosMeses as $mes) {
                $labels[] = date("F Y", strtotime($mes . '-01'));
                $contratacionesData[] = $conteoContrataciones[$mes] ?? 0;
                $bajasData[] = $conteoBajas[$mes] ?? 0;
                $recontratacionesData[] = $conteoRecontrataciones[$mes] ?? 0;
            }

            // Respuesta final con los datos
            $arrResponse = array(
                'status' => true,
                'data' => [
                    'labels' => $labels,
                    'contrataciones' => $contratacionesData,
                    'bajas' => $bajasData,
                    'recontrataciones' => $recontratacionesData
                ]
            );
        }

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function barSolicitudesVacaciones()
    {
        $anioActual = date("Y"); // Año actual
        $arrData = $this->model->getSolicitudVacaciones(); // Obtener todos los datos

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            // Inicializar contadores
            $pendientes = [];
            $aprobaciones = [];
            $rechazadas = [];
            $canceladas = [];
            $revertidas = [];

            // Filtrar solo los registros del año actual y contar
            foreach ($arrData as $row) {
                $fecha = strtotime($row['Fecha']);
                $anioRegistro = date("Y", $fecha);
                if ($anioRegistro == $anioActual) { // Solo registros del año actual
                    $mesAnio = date("Y-m", $fecha);

                    if (!isset($pendientes[$mesAnio])) {
                        $pendientes[$mesAnio] = 0;
                        $aprobaciones[$mesAnio] = 0;
                        $rechazadas[$mesAnio] = 0;
                        $canceladas[$mesAnio] = 0;
                        $revertidas[$mesAnio] = 0;
                    }

                    switch ($row['TipoMovimiento']) {
                        case 'Pendiente':
                            $pendientes[$mesAnio]++;
                            break;
                        case 'Aprobado':
                            $aprobaciones[$mesAnio]++;
                            break;
                        case 'Rechazado':
                            $rechazadas[$mesAnio]++;
                            break;
                        case 'Cancelado':
                            $canceladas[$mesAnio]++;
                            break;
                        case 'Revertido':
                            $revertidas[$mesAnio]++;
                            break;
                    }
                }
            }

            // Generar lista de meses para el año actual
            $todosMeses = [];
            for ($i = 1; $i <= 12; $i++) {
                $mes = sprintf("%04d-%02d", $anioActual, $i); // Asegura el formato YYYY-MM
                $todosMeses[] = $mes;
            }

            // Preparar datos para el gráfico
            $labels = [];
            $pendientesData = [];
            $aprobacionesData = [];
            $rechazadasData = [];
            $canceladasData = [];
            $revertidasData = [];

            foreach ($todosMeses as $mes) {
                $labels[] = date("F Y", strtotime($mes . '-01')); // Nombre del mes
                $pendientesData[] = $pendientes[$mes] ?? 0;
                $aprobacionesData[] = $aprobaciones[$mes] ?? 0;
                $rechazadasData[] = $rechazadas[$mes] ?? 0;
                $canceladasData[] = $canceladas[$mes] ?? 0;
                $revertidasData[] = $revertidas[$mes] ?? 0;
            }

            $arrResponse = array(
                'status' => true,
                'data' => [
                    'labels' => $labels,
                    'pendientes' => $pendientesData,
                    'aprobaciones' => $aprobacionesData,
                    'rechazadas' => $rechazadasData,
                    'canceladas' => $canceladasData,
                    'revertidas' => $revertidasData,
                ]
            );
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function barVacacionesYear()
    {
        // Verificar si 'anio' está presente en la URL y es un número válido
        $year = isset($_GET['anio']) && is_numeric($_GET['anio']) ? intval($_GET['anio']) : date("Y"); // Usa el año actual si no se pasa

        $arrData = $this->model->getSolicitudesYear($year); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            // Inicializar las variables para el conteo de movimientos
            $labels = [];
            $pendientes = [];
            $aprobaciones = [];
            $rechazadas = [];
            $canceladas = [];
            $revertidas = [];

            // Recorrer los datos de contrataciones
            foreach ($arrData as $row) {
                $mesAnio = date("Y-m", strtotime($row['Fecha'])); // Obtener el mes y año

                // Inicializar los contadores para cada mes
                if (!isset($pendientes[$mesAnio])) {
                    $pendientes[$mesAnio] = 0;
                    $aprobaciones[$mesAnio] = 0;
                    $rechazadas[$mesAnio] = 0;
                    $canceladas[$mesAnio] = 0;
                    $revertidas[$mesAnio] = 0;
                }

                // Contar por tipo de movimiento
                switch ($row['TipoMovimiento']) {
                    case 'Pendiente':
                        $pendientes[$mesAnio]++;
                        break;
                    case 'Aprobado':
                        $aprobaciones[$mesAnio]++;
                        break;
                    case 'Rechazado':
                        $rechazadas[$mesAnio]++;
                        break;
                    case 'Cancelado':
                        $canceladas[$mesAnio]++;
                        break;
                    case 'Revertido':
                        $revertidas[$mesAnio]++;
                        break;
                }
            }

            // Generar los meses del año seleccionado
            $todosMeses = [];
            for ($i = 1; $i <= 12; $i++) {
                $mes = sprintf("%04d-%02d", $year, $i);
                $todosMeses[] = $mes;
            }

            // Preparar los datos para la gráfica
            $labels = [];
            $pendientesData = [];
            $aprobacionesData = [];
            $rechazadasData = [];
            $canceladasData = [];
            $revertidasData = [];

            // Llenar los datos de cada mes
            foreach ($todosMeses as $mes) {
                $labels[] = date("F Y", strtotime($mes . '-01'));
                $pendientesData[] = $pendientes[$mes] ?? 0;
                $aprobacionesData[] = $aprobaciones[$mes] ?? 0;
                $rechazadasData[] = $rechazadas[$mes] ?? 0;
                $canceladasData[] = $canceladas[$mes] ?? 0;
                $revertidasData[] = $revertidas[$mes] ?? 0;
            }

            // Respuesta final con los datos
            $arrResponse = array(
                'status' => true,
                'data' => [
                    'labels' => $labels,
                    'pendientes' => $pendientesData,
                    'aprobaciones' => $aprobacionesData,
                    'rechazos' => $rechazadasData,
                    'canceladas' => $canceladasData,
                    'reversiones' => $revertidasData,
                ]
            );
        }

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSelectAnio()
    {
        $htmlOptions = '<option selected disabled value="">Seleccione un Año</option>'; // Añadir esta opción una sola vez
        $arrData = $this->model->selectAnio();
        if (count($arrData) > 0) {
            // Ahora solo agregas las opciones de los empleados
            foreach ($arrData as $anio) {
                $htmlOptions .= '<option value="' . $anio['anio'] . '">' . $anio['anio'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectAnioVacaciones()
    {
        $htmlOptions = '<option selected disabled value="">Seleccione un Año</option>'; // Añadir esta opción una sola vez
        $arrData = $this->model->selectAnioVacaciones();
        if (count($arrData) > 0) {
            // Ahora solo agregas las opciones de los empleados
            foreach ($arrData as $anio) {
                $htmlOptions .= '<option value="' . $anio['anio'] . '">' . $anio['anio'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectColaboradores()
    {
        $htmlOptions = '<option selected disabled value="">Selecciona un Colaborador</option>'; // Añadir esta opción una sola vez
        $arrData = $this->model->selectColaboradores();
        if (count($arrData) > 0) {
            // Ahora solo agregas las opciones de los empleados
            foreach ($arrData as $colaborador) {
                $htmlOptions .= '<option value="' . $colaborador['id_empleado'] . '">' . $colaborador['nombre_completo'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function pieRazonesBajas()
    {
        $arrData = $this->model->getRazonesBaja();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['razon_baja'];  // Nombre del empleado
                $data[] = $row['total_historial'];  // Número de solicitudes
                $total_historial += $row['total_historial'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieRazonesBajasMes()
    {
        // Verificar si 'anio' está presente en la URL y es un número válido
        $mesBaja = isset($_GET['mesBaja']) && is_numeric($_GET['mesBaja']) ? intval($_GET['mesBaja']) : date("M");

        $arrData = $this->model->getRazonesBajaMonths($mesBaja); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['razon_baja'];  // Nombre del empleado
                $data[] = $row['total_historial'];  // Número de solicitudes
                $total_historial += $row['total_historial'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieReclutamientos()
    {
        $arrData = $this->model->getReclutamientos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $asunto = [];
            $totalSolicitudes = 0; // Inicializamos el total

            foreach ($arrData as $row) {
                $labels[] = $row['reclutador'];  // Nombre del empleado
                $data[] = $row['total_reclutamientos'];  // Número de solicitudes
                $asunto[] = $row['asunto'];
                $totalSolicitudes += $row['total_reclutamientos']; // Sumamos el total
            }

            // Enviamos también el total de solicitudes
            $arrResponse = array(
                'status' => true,
                'data' => [
                    'labels' => $labels,
                    'data' => $data,
                    'asunto' => $asunto,
                    'totalSolicitudes' => $totalSolicitudes // Agregamos esta línea
                ]
            );
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieReclutamientosMes()
    {
        // Verificar si 'anio' está presente en la URL y es un número válido
        $mesReclutamiento = isset($_GET['mesReclutamiento']) && is_numeric($_GET['mesReclutamiento']) ? intval($_GET['mesReclutamiento']) : date("Y");

        $arrData = $this->model->getReclutamientosMes($mesReclutamiento); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $asunto = [];
            $totalSolicitudes = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['reclutador'];  // Nombre del empleado
                $data[] = $row['total_reclutamientos'];  // Número de solicitudes
                $asunto[] = $row['asunto'];
                $totalSolicitudes += $row['total_reclutamientos']; // Sumamos el total

            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'asunto' => $asunto, 'totalSolicitudes' => $totalSolicitudes]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieAreasAltas()
    {
        $arrData = $this->model->getAreasAltas();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['area_inicio'];  // Nombre del empleado
                $data[] = $row['total_altas'];  // Número de solicitudes
                $total_historial += $row['total_altas'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieAreasAltasMes()
    {
        // Verificar si 'anio' está presente en la URL y es un número válido
        $mesArea = isset($_GET['mesArea']) && is_numeric($_GET['mesArea']) ? intval($_GET['mesArea']) : date("M");

        $arrData = $this->model->getAreasAltasMes($mesArea); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['area_inicio'];  // Nombre del empleado
                $data[] = $row['total_altas'];  // Número de solicitudes
                $total_historial += $row['total_altas'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieAreasBajas()
    {
        $arrData = $this->model->getAreasBajas();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['area_final'];  // Nombre del empleado
                $data[] = $row['total_bajas'];  // Número de solicitudes
                $total_historial += $row['total_bajas'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieAreasBajasMes()
    {
        // Verificar si 'anio' está presente en la URL y es un número válido
        $mesArea = isset($_GET['mesArea']) && is_numeric($_GET['mesArea']) ? intval($_GET['mesArea']) : date("M");

        $arrData = $this->model->getAreasBajasMes($mesArea); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['area_final'];  // Nombre del empleado
                $data[] = $row['total_bajas'];  // Número de solicitudes
                $total_historial += $row['total_bajas'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getDocumentosTable()
    {
        $arrData = $this->model->selectDocumentos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getDocumentosbyID($id_empleado)
    {
        if (empty($id_empleado) || !is_numeric($id_empleado)) {
            $arrResponse = array('status' => false, 'msg' => 'ID de empleado no válido.');
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }

        $arrData = $this->model->selectDocumentosbyID(intval($id_empleado));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieAreasVacaciones()
    {
        $arrData = $this->model->getVacacionesAreas();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['areas_vacaciones'];  // Nombre del empleado
                $data[] = $row['total_solicitudes'];  // Número de solicitudes
                $total_historial += $row['total_solicitudes'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieAreasVacacionesbyMes()
    {
        $mesVacaciones = isset($_GET['mesVacaciones']) && is_numeric($_GET['mesVacaciones']) ? intval($_GET['mesVacaciones']) : date("M");

        $arrData = $this->model->getVacacionesAreasbyMes($mesVacaciones); // Obtener los datos del año seleccionado

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['areas_vacaciones'];  // Nombre del empleado
                $data[] = $row['total_solicitudes'];  // Número de solicitudes
                $total_historial += $row['total_solicitudes'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieRechazoSolicitudes()
    {
        $arrData = $this->model->getRechazoSolicitudes();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['nombre_completo'];  // Nombre del empleado
                $data[] = $row['total_solicitudes'];  // Número de solicitudes
                $total_historial += $row['total_solicitudes'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pieRechazoSolicitudesbyMES()
    {
        $mesRechazos = isset($_GET['mesRechazos']) && is_numeric($_GET['mesRechazos']) ? intval($_GET['mesRechazos']) : date("M");

        $arrData = $this->model->getRechazoSolicitudesbyMes($mesRechazos);

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $labels = [];
            $data = [];
            $total_historial = 0;

            foreach ($arrData as $row) {
                $labels[] = $row['nombre_completo'];  // Nombre del empleado
                $data[] = $row['total_solicitudes'];  // Número de solicitudes
                $total_historial += $row['total_solicitudes'];
            }

            $arrResponse = array('status' => true, 'data' => ['labels' => $labels, 'data' => $data, 'total_historial' => $total_historial]);
        }

        header('Content-Type: application/json');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    //logouttttttt
    public function logout()
    {
        // Registrar actividad
        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
        $empleado = $_SESSION['PersonalData']['nombre_completo'];
        $modulo = "Logout";
        $accion = "Cierre de Sesión";
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $hostname = gethostbyaddr($ip);

        $this->model->registroCierreSecion($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

        // Destruir la sesión
        session_unset();
        session_destroy();

        // Redirigir al login
        header('Location: ' . base_url() . '/Login');
        die();
    }

    public function logoutOperativa()
    {
        // Destruir la sesión
        session_unset();
        session_destroy();

        // Redirigir al login
        header('Location: ' . base_url() . '/Vacaciones/Login');
        die();
    }

    public function actualizarPassword()
    {
        // Inicializar la variable de respuesta
        $arrResponse = array();

        if ($_POST) {
            $correo_empresarial = $_POST['correo_empresarial'];
            $password = $_POST['password'];
            $password_new = $_POST['password_new'];
            $password_confirmacion = $_POST['password_confirmacion'];

            // Verificar si los campos están vacíos
            if (empty($correo_empresarial) || empty($password) || empty($password_new) || empty($password_confirmacion)) {
                $arrResponse = array('status' => false, 'msg' => 'Formulario Imcompleto');
            }

            if ($password == $password_new && $password == $password_confirmacion) {
                $arrResponse = array("status" => false, "msg" => 'La nueva contraseña tiene quer diferente a la anterior');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            if ($password_new !== $password_confirmacion) {
                $arrResponse = array("status" => false, "msg" => 'La nueva contraseña y su confirmación no coinciden.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            $requestUser = $this->model->getUserByIdentificacion($correo_empresarial);
            $hashedPassword = $requestUser['password'];

            if (password_verify($password, $hashedPassword)) {

                $userData = $this->model->verificarCorreo($correo_empresarial);
                if (!$userData) {
                    $arrResponse = array('status' => false, 'msg' => 'El correo no está registrado en el sistema.');
                } else {
                    $token = rand(100000, 999999);
                    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                    // Guardar el token en la base de datos
                    $result = $this->model->guardarToken($correo_empresarial, $token, $expires_at);
                    if (!$result) {
                        $arrResponse = array('status' => false, 'msg' => 'No se pudo guardar el token en la base de datos.');
                    } else {
                        // Preparar los datos para enviar el correo
                        $arrData = [
                            'correo_empresarial' => $correo_empresarial,
                            'token' => $token,
                            'nombres' => $userData['nombres'],
                        ];

                        $emailTemplatePath = 'Views/Template/Email/PasswordEmail.php';

                        // Incluir la plantilla para enviar el correo
                        require $emailTemplatePath;
                        $arrResponse = array('status' => true, 'msg' => 'El correo con el token ha sido enviado.');
                    }
                }
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Contraseña incorrecta');
            }


        } else {
            // Si no se recibe la solicitud POST
            $arrResponse = array('status' => false, 'msg' => 'Método de solicitud incorrecto');
        }

        // Devolver la respuesta como JSON
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function validarToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['correo_empresarial']) || empty($_POST['token'])) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'Correo y token son obligatorios.',
                ]);
                exit;
            }

            $correo_empresarial = trim($_POST['correo_empresarial']);
            $password = trim($_POST['password']);

            $token = trim($_POST['token']);

            // Validar el token en la base de datos
            $userData = $this->model->validarToken($correo_empresarial, $token);
            if ($userData) {

                $this->model->updateContra($correo_empresarial, $password);

                echo json_encode([
                    'status' => true,
                    'msg' => 'Actualizacion de Contraseña Exitosa.'
                ]);

            } else {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El token ingresado no es válido o ha expirado.',
                ]);
            }
            exit;
        }
    }


}
