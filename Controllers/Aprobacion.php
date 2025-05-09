<?php
session_start();

class Aprobacion extends Controllers
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
	public function Aprobacion()
	{
		$data['page_id'] = RECLUTAMIENTO;
		$data['page_tag'] = "En Espera";
		$data['page_title'] = "En Espera";
		$data['page_name'] = "Es Espera";
		$data['page_functions_js'] = "functions_aprobacion.js";

		$this->views->getView($this, "Aprobacion", $data);
	}

	public function getAprobaciones()
	{
		$arrData = $this->model->Aprobaciones();

		if (empty($arrData)) {
			$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
		} else {
			$arrResponse = array('status' => true, 'data' => $arrData);
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getAprobacionesbyID($id_aprobaciones)
	{
		$data = $this->model->AprobacionesbyId($id_aprobaciones);
		if ($data) {
			$arrResponse = array('status' => true, 'data' => $data);
		} else {
			$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function setAprobarEmpleado()
	{
		if ($_POST) {
			if (empty($_POST['codigo_empleado'])) {
				$arrResponse = array("status" => false, "msg" => 'Ingrese el Código de Empleado para Aprobar.');
			} else {
				$codigo_empleado = htmlspecialchars($_POST['codigo_empleado']);
				$id_aprobaciones = intval($_POST['id_aprobaciones']);
				$aprobacion = 'Aprobado';
				$descripcion = trim($_POST['descripcion']);
				$estado = 'Activo';

				$sql_check = "SELECT id_empleado FROM empleado_tb WHERE codigo_empleado = ? ";
				$request_check = $this->model->select($sql_check, [$codigo_empleado]);

				if (!empty($request_check)) {
					$arrResponse = array('status' => false, 'msg' => 'El código de empleado ya está registrado.');
				} else {
					// Llamar al modelo para actualizar el estado del empleado
					$request_user = $this->model->aprobarUsuario($id_aprobaciones, $codigo_empleado, $aprobacion, $descripcion, $estado);

					$info = $this->model->AprobacionesbyId($id_aprobaciones);
					$nombre = $info['nombre_completo'];
					if (!empty($nombre)) {
						$no_empleado = $_SESSION['PersonalData']['no_empleado'];
						$empleado = $_SESSION['PersonalData']['nombre_completo'];
						$modulo = "Aprobacion";
						$accion = 'Se finalizo el proceso de contratacion de "' . $nombre . '"';
						$fecha = date("Y-m-d H:i:s");
						$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
						$hostname = gethostbyaddr($ip);
						$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
					}
					if ($request_user) {
						$arrResponse = array('status' => true, 'msg' => 'Empleado aprobado correctamente.');
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del empleado.');
					}
				}
			}
			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function setResolicitar()
	{
		if ($_POST) {
			if (empty($_POST['id_aprobaciones'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_aprobaciones = intval($_POST['id_aprobaciones']);
				$aprobacion = 'Pendiente';
				$primer_apellido = htmlspecialchars($_POST['primer_apellido']);
				$segundo_apellido = htmlspecialchars($_POST['segundo_apellido']);
				$nombres = htmlspecialchars($_POST['nombres']);
				$identificacion = htmlspecialchars($_POST['identificacion']);
				$puesto_contrato = intval($_POST['puesto_contrato']);
				$puesto_operativo = intval($_POST['puesto_operativo']);
				$departamento = intval($_POST['departamento']);
				$area = intval($_POST['area']);
				$salario_base = !empty($_POST['salario_base']) ? $_POST['salario_base'] : null;
				$bonificacion = !empty($_POST['bonificacion']) ? $_POST['bonificacion'] : null;
				$kpi1 = !empty($_POST['kpi1']) ? $_POST['kpi1'] : null;
				$kpi2 = !empty($_POST['kpi2']) ? $_POST['kpi2'] : null;
				$kpi_max = !empty($_POST['kpi_max']) ? $_POST['kpi_max'] : null;
				$estado = 'Pendiente';

				// Llamar al modelo para actualizar el estado del empleado
				$request_user = $this->model->aprobarResolicitud(
					$id_aprobaciones,
					$aprobacion,
					$primer_apellido,
					$segundo_apellido,
					$nombres,
					$identificacion,
					$puesto_contrato,
					$puesto_operativo,
					$departamento,
					$area,
					$salario_base,
					$bonificacion,
					$kpi1,
					$kpi2,
					$kpi_max,
					$estado
				);

				$info = $this->model->AprobacionesbyId($id_aprobaciones);
				
				$nombre = $info['nombre_completo'];
				if (!empty($nombre)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Rechazos";
					$accion = 'Se retomo el proceso de contratacion de "' . $nombre . '"';
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);

					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}

				// Verificar la respuesta del modelo
				if ($request_user) {
					$arrResponse = array('status' => true, 'msg' => 'Empleado rechazado correctamente.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del empleado.');
				}
			}

			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die(); // Finalizar el script
	}

	public function setRechazoEmpleado()
	{
		if ($_POST) {
			if (empty($_POST['id_aprobaciones'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_aprobaciones = intval($_POST['id_aprobaciones']);
				$codigo_empleado = null;
				$aprobacion = 'Rechazado';
				$descripcion = trim($_POST['descripcion']); // Eliminar espacios innecesarios
				$estado = 'Pendiente';

				// Llamar al modelo para actualizar el estado del empleado
				$request_user = $this->model->aprobarUsuario($id_aprobaciones, $codigo_empleado, $aprobacion, $descripcion, $estado);

				$info = $this->model->AprobacionesbyId($id_aprobaciones);

				$nombre = $info['nombre_completo'];
				if (!empty($nombre)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Rechazos";
					$accion = 'Se rechazo la contratacion de "' . $nombre . '"';
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);
					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}

				// Verificar la respuesta del modelo
				if ($request_user) {
					$arrResponse = array('status' => true, 'msg' => 'Empleado rechazado correctamente.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del empleado.');
				}
			}
			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die(); // Finalizar el script
	}

	public function setAprobarRecontratacion()
	{
		if ($_POST) {
			if (empty($_POST['id_aprobaciones'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_aprobaciones = intval($_POST['id_aprobaciones']);
				$aprobacion = 'Aprobado';
				$descripcion = trim($_POST['descripcion']); // Eliminar espacios innecesarios
				$estado = 'Activo';
				if (empty($_POST['descripcion'])) {
					$arrResponse = array('status' => false, 'msg' => 'La confirmacion necesita una Descripcion.');
				} else {
					// Llamar al modelo para actualizar el estado del empleado
					$request_user = $this->model->aprobarRecontratacion($id_aprobaciones, $aprobacion, $descripcion, $estado);

					$info = $this->model->AprobacionesbyId($id_aprobaciones);
					$nombre = $info['nombre_completo'];
					if (!empty($nombre)) {
						$no_empleado = $_SESSION['PersonalData']['no_empleado'];
						$empleado = $_SESSION['PersonalData']['nombre_completo'];
						$modulo = "Aprobacion";
						$accion = 'Se finalizo el proceso de recontratacion de "' . $nombre . '"';
						$fecha = date("Y-m-d H:i:s");
						$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
						$hostname = gethostbyaddr($ip);
						$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
					}
					// Verificar la respuesta del modelo
					if ($request_user) {
						$arrResponse = array('status' => true, 'msg' => 'Empleado aprobado correctamente.');
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del empleado.');
					}
				}
			}
			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function setRechazoRecontratacion()
	{
		if ($_POST) {
			if (empty($_POST['id_aprobaciones'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_aprobaciones = intval($_POST['id_aprobaciones']);
				$aprobacion = 'Rechazado';
				$descripcion = trim($_POST['descripcion']); // Eliminar espacios innecesarios
				$estado = 'Baja';

				// Llamar al modelo para actualizar el estado del empleado
				$request_user = $this->model->aprobarRecontratacion($id_aprobaciones, $aprobacion, $descripcion, $estado);
				$info = $this->model->AprobacionesbyId($id_aprobaciones);

				$nombre = $info['nombre_completo'];
				if (!empty($nombre)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Aprobacion";
					$accion = 'Se rechazo la recontratacion de "' . $nombre . '"';
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);

					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}
				// Verificar la respuesta del modelo
				if ($request_user) {
					$arrResponse = array('status' => true, 'msg' => 'Empleado rechazado correctamente.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del empleado.');
				}
			}
			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die(); // Finalizar el script
	}

	public function setDescartar()
	{
		if ($_POST) {
			if (empty($_POST['id_aprobaciones'])) {
				$response = [
					'status' => false,
					'msg' => 'ID de aprobación no proporcionado.',
				];
				echo json_encode($response);
				return;
			}

			$id_aprobaciones = intval($_POST['id_aprobaciones']);

			$info = $this->model->AprobacionesbyId($id_aprobaciones);
			$nombre = $info['nombre_completo'];
			if (!empty($nombre)) {
				$no_empleado = $_SESSION['PersonalData']['no_empleado'];
				$empleado = $_SESSION['PersonalData']['nombre_completo'];
				$modulo = "Aprobacion";
				$accion = 'Se descarto proceso de contratacion de "' . $nombre . '"';
				$fecha = date("Y-m-d H:i:s");
				$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
				$hostname = gethostbyaddr($ip);

				$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
			}

			// Llamar al modelo para eliminar la aprobación
			$result = $this->model->descartarAprobacion($id_aprobaciones);

			if ($result) {
				// Aquí puedes agregar la lógica para eliminar el empleado, si es necesario
				$response = [
					'status' => true,
					'msg' => 'Aprobación descartada correctamente.',
				];
			} else {
				$response = [
					'status' => false,
					'msg' => 'No se pudo descartar la aprobación.',
				];
			}
			error_log("ID Aprobación: $id_aprobaciones");

			echo json_encode($response);
		}
	}

	public function Reprobados()
	{
		$data['page_id'] = RECLUTAMIENTO;
		$data['page_tag'] = "Rechazos";
		$data['page_title'] = "Rechazos";
		$data['page_name'] = "Rechazos";
		$data['page_functions_js'] = "functions_reprobados.js";

		$this->views->getView($this, "Reprobados", $data);
	}

	public function getRechazos()
	{
		$arrData = $this->model->Rechazos();

		if (empty($arrData)) {
			$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
		} else {
			$arrResponse = array('status' => true, 'data' => $arrData);
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function Reclutamiento()
	{

		$data['page_id'] = RECLUTAMIENTO;
		$data['page_tag'] = "Reclutamiento";
		$data['page_title'] = "Reclutamiento";
		$data['page_name'] = "Reclutamiento";
		$data['page_functions_js'] = "functions_reclutamiento.js";

		$this->views->getView($this, "Reclutamiento", $data);
	}

	public function setReclutamiento()
	{
		if ($_POST) {
			// Validar datos obligatorios
			if (
				empty($_POST['fecha_prueba']) || empty($_POST['fecha_contratacion']) ||
				empty($_POST['nombres']) || empty($_POST['primer_apellido']) ||
				empty($_POST['identificacion'])
			) {
				$arrResponse = ["status" => false, "msg" => "Datos incorrectos. Revisa que los datos sean válidos."];
			} else {
				// Preparar variables para la inserción
				$codigo_empleado = !empty($_POST['codigo_empleado']) ? htmlspecialchars(trim($_POST['codigo_empleado'])) : null;
				$fecha_contratacion = htmlspecialchars($_POST['fecha_contratacion']);
				$fecha_prueba = htmlspecialchars($_POST['fecha_prueba']);
				$primer_apellido = htmlspecialchars($_POST['primer_apellido']);
				$segundo_apellido = htmlspecialchars($_POST['segundo_apellido']);
				$nombres = htmlspecialchars($_POST['nombres']);
				$identificacion = htmlspecialchars($_POST['identificacion']);
				$salario_base = !empty($_POST['salario_base']) ? $_POST['salario_base'] : null;
				$bonificacion = !empty($_POST['bonificacion']) ? $_POST['bonificacion'] : null;
				$kpi1 = !empty($_POST['kpi1']) ? $_POST['kpi1'] : null;
				$kpi2 = !empty($_POST['kpi2']) ? $_POST['kpi2'] : null;
				$kpi_max = !empty($_POST['kpi_max']) ? $_POST['kpi_max'] : null;
				$puesto_contrato = htmlspecialchars($_POST['puesto_contrato']);
				$puesto_operativo = htmlspecialchars($_POST['puesto_operativo']);
				$departamento = htmlspecialchars($_POST['departamento']);
				$area = htmlspecialchars($_POST['area']);
				$estado = "Pendiente"; // Estado predeterminado para nuevas inserciones
				$reclutador = $_SESSION['userData']['nombre_completo'];

				// Llamar al modelo para insertar los datos
				$request_user = $this->model->insertPersonal(
					$fecha_contratacion,
					$fecha_prueba,
					$primer_apellido,
					$segundo_apellido,
					$nombres,
					$identificacion,
					$salario_base,
					$bonificacion,
					$kpi1,
					$kpi2,
					$kpi_max,
					$puesto_contrato,
					$puesto_operativo,
					$departamento,
					$area,
					$estado,
					$reclutador
				);

				if (!empty($nombres)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Contratacion";
					$accion = 'Se inicio el proceso de contratacion de "' . $nombres . ' ' . $primer_apellido . ' ' . $segundo_apellido . '"';
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);

					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}

				// Respuesta basada en el resultado
				if ($request_user > 0) {
					$arrResponse = ["status" => true, "msg" => "Datos guardados correctamente."];
				} elseif ($request_user == "exist") {
					$arrResponse = ["status" => false, "msg" => "¡Atención! El número de identificación ya está en uso."];
				} else {
					$arrResponse = ["status" => false, "msg" => "No es posible almacenar los datos."];
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
	}


	public function getAreas_laborales()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectAreas();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {

				$htmlOptions .= '<option value="' . $arrData[$i]['id_area'] . '">' . $arrData[$i]['nombre_area'] . '  |  ' . $arrData[$i]['codigo'] . '|' . $arrData[$i]['descripcion_ln'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}



}