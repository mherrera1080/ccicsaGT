<?php
session_start();

class Expedientes extends Controllers
{
	public function __construct()
	{
		parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(EXPEDIENTES);
	}

	public $views;
	public $model;

	public function Expedientes(): void
	{
		$data['page_id'] = EXPEDIENTES;
		$data['page_tag'] = "Expedientes";
		$data['page_title'] = "Expedientes";
		$data['page_name'] = "Expedientes";
		$data['page_functions_js'] = "functions_expedientes.js";

		$this->views->getView($this, "Expedientes", $data);
	}

	public function Mostrar($empleado_id)
	{
		$expedientes = $this->model->getExpedientes($empleado_id);
		if (empty($expedientes)) {
			// Manejar el caso donde no se encuentra el empleado
			header('Location: ' . base_url() . '/Expedientes');
			die();  // Es importante salir después de redirigir
		}

        $nombre = $expedientes['nombres'];

        if (!empty($nombre)) {
			$no_empleado = $_SESSION['PersonalData']['no_empleado'];
			$empleado = $_SESSION['PersonalData']['nombre_completo'];
			$modulo = "Expedientes";
			$accion = 'Ingreso a modulo de expedientes de "' . $nombre . '"';
			$fecha = date("Y-m-d H:i:s");
			$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
			$hostname = gethostbyaddr($ip);
		
			$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
		}

		$data['expedientes'] = $expedientes;
		$data['page_id'] = EXPEDIENTES;
		$data['page_tag'] = "Expedientes";
		$data['page_title'] = "Expedientes";
		$data['page_name'] = "Expedientes";
		$data['page_functions_js'] = "functions_expedientes.js";

		$this->views->getView($this, "Expedientes", $data);
	}

	public function getExpedientes($empleado_id)
	{
		$arrData = $this->model->getExpedientesbyID($empleado_id);

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

	public function getExpedientesPrincipal($id)
	{
		$arrData = $this->model->ExpedientesbyID($id);

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
			$fecha_act = date( 'Y-m-d');

			// Obtén la información del documento actual
			$documento = $this->model->getDocumentoById($id);

			if ($documento) {
				// Verifica si se subió un archivo
				if (isset($_FILES['ubicacion']) && $_FILES['ubicacion']['error'] == 0) {
					$file = $_FILES['ubicacion'];
					$fileTmpPath = $file['tmp_name'];
					$fileName = $documento['nombre_documento'] . '.pdf'; // Nombre del documento con extensión
					$fileSize = $file['size'];
					$fileType = $file['type'];
					$fileNameCmps = explode(".", $fileName);
					$fileExtension = strtolower(end($fileNameCmps));

					// Definir la carpeta destino
					$uploadDir = 'Expedientes/' . $documento['empleado_id'] . '/';

					// Verificar si la carpeta existe, si no, crearla
					if (!is_dir($uploadDir)) {
						mkdir($uploadDir, 0755, true);
					}

					// Establecer la ruta de destino
					$dest_path = $uploadDir . $fileName;

					// Mover el archivo al destino
					if (move_uploaded_file($fileTmpPath,  $dest_path)) {
						// Actualizar la ubicación del archivo en la base de datos
						$ubicacion = $dest_path;
						$request = $this->model->subirDocumento($id, $ubicacion, $fecha_act);

						if ($request) {
							$arrResponse = array('status' => true, 'msg' => 'Documento subido correctamente.');
						} else {
							$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar la ubicación.');
						}
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No se pudo mover el archivo.');
					}
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No se ha subido ningún archivo o hubo un error.');
				}

				$personal = $this->model->getExpedientes($documento['empleado_id']);

				$nombre = $personal['nombres'];
				$documento = $documento['nombre_documento'];
		
				if (!empty($nombre)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Expedientes";
					$accion = 'Se cargo el documento "' . $documento . '" al usuario "' . $nombre . '"' ;
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);
				
					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}

			} else {
				$arrResponse = array('status' => false, 'msg' => 'Documento no encontrado.');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}
	
}