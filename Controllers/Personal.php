<?php

session_start();

class Personal extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos(PERSONAL);
	}

	public $views;
	public $model;

	public function Personal()
	{
		$nomina = $this->model->getNomina();

		$data['nomina'] = $nomina;
		$data['page_id'] = PERSONAL;
		$data['page_tag'] = "Personal";
		$data['page_title'] = "Personal";
		$data['page_name'] = "Personal";
		$data['page_functions_js'] = "functions_personal.js";

		$this->views->getView($this, "Personal", $data);
	}

	public function PersonalBaja()
	{
		$data['page_id'] = PERSONAL;
		$data['page_tag'] = "Personal Baja";
		$data['page_title'] = "Personal Baja";
		$data['page_name'] = "personal";
		$data['page_functions_js'] = "functions_baja.js";

		$this->views->getView($this, "Baja", $data);
	}

	public function Perfil($id_empleado)
	{
		$perfil = $this->model->Perfil($id_empleado);

		$info = $this->model->InfoPerfil($id_empleado);

		$documentos = $this->model->infoDocumentos($id_empleado);

		$academica = $this->model->infoAcademica($id_empleado);

		$data = [
			'page_tag' => 'Perfil Personal',
			'page_title' => 'Perfil Personal',
			'page_name' => 'usuario_perfil',
			'page_functions_js' => 'functions_perfil.js',
			'perfil' => $perfil,
			'info' => $info,
			'documentos' => $documentos,
			'academica' => $academica
		];

		$this->views->getView($this, 'Perfil', $data);
	}

	public function Avance($id_empleado)
	{
		$personal = $this->model->Avance($id_empleado);

		$identificacion = $personal["identificacion"];
		$info = $this->model->infoPersonal($identificacion);


		$nombre = $personal["nombre_completo"];

		if (!empty($nombre)) {
			$no_empleado = $_SESSION['PersonalData']['no_empleado'];
			$empleado = $_SESSION['PersonalData']['nombre_completo'];
			$modulo = "Avance Personal";
			$accion = 'Ingreso a avance del usuario "' . $nombre . '"';
			$fecha = date("Y-m-d H:i:s");
			$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
			$hostname = gethostbyaddr($ip);

			$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
		}

		$data = [
			'page_tag' => 'Editar Usuario',
			'page_title' => 'Editar Usuario',
			'page_name' => 'usuario_edit',
			'page_functions_js' => 'functions_personal.js',
			'personal' => $personal,
			'info' => $info
		];
		$this->views->getView($this, 'Avance', $data);
	}

	public function Info($id_empleado)
	{
		$personal = $this->model->Perfil($id_empleado);
		$jefes = $this->model->obtenerJefes($id_empleado);

		$nombre = $personal['nombre_completo'];

		if (!empty($nombre)) {
			$no_empleado = $_SESSION['PersonalData']['no_empleado'];
			$empleado = $_SESSION['PersonalData']['nombre_completo'];
			$modulo = "Info Laboral";
			$accion = 'Ingreso a informacion laboral del usuario "' . $nombre . '"';
			$fecha = date("Y-m-d H:i:s");
			$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
			$hostname = gethostbyaddr($ip);

			$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
		}

		$data = [
			'page_tag' => 'Editar Usuario',
			'page_title' => 'Editar Usuario',
			'page_name' => 'usuario_edit',
			'page_functions_js' => 'functions_personal.js',
			'personal' => $personal,
			'jefes' => $jefes
		];
		$this->views->getView($this, 'Info', $data);
	}

	public function Editar($id_empleado)
	{
		$personal = $this->model->Perfil($id_empleado);
		$jefes = $this->model->obtenerJefes($id_empleado);
		$data = [
			'page_tag' => 'Editar Usuario',
			'page_title' => 'Editar Usuario',
			'page_name' => 'usuario_edit',
			'page_functions_js' => 'functions_edit_personal.js',
			'personal' => $personal,
			'jefes' => $jefes
		];
		$this->views->getView($this, 'Editar', $data);
	}

	public function getPersonalNominaAlta()
	{
		$arrData = $this->model->selectPersonalNomina();

		if (empty($arrData)) {
			$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
		} else {
			$arrResponse = array('status' => true, 'data' => $arrData);
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getPersonalNominaBaja()
	{
		$arrData = $this->model->selectPersonalNominaBaja();

		if (empty($arrData)) {
			$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
		} else {
			$arrResponse = array('status' => true, 'data' => $arrData);
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getPersonalbyID($id_empleado)
	{
		$arrData = $this->model->selectPersonalID($id_empleado);

		if (empty($arrData)) {
			$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
		} else {
			$arrResponse = array('status' => true, 'data' => $arrData);
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function setPersonalNomina()
	{
		if ($_POST) {
			if (empty($_POST['fecha_ingreso']) || empty($_POST['nombres']) || empty($_POST['identificacion'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_empleado = intval($_POST['id_empleado']);
				$codigo_empleado = !empty($_POST['codigo_empleado']) ? htmlspecialchars(trim($_POST['codigo_empleado'])) : null;
				$fecha_ingreso = htmlspecialchars($_POST['fecha_ingreso']);
				$primer_apellido = htmlspecialchars($_POST['primer_apellido']);
				$segundo_apellido = htmlspecialchars($_POST['segundo_apellido']);
				$nombres = htmlspecialchars($_POST['nombres']);
				$identificacion = htmlspecialchars($_POST['identificacion']);

				$puesto_contrato = htmlspecialchars($_POST['puesto_contrato']);
				$puesto_operativo = htmlspecialchars($_POST['puesto_operativo']);
				$lider_proceso = !empty($_POST['lider_proceso']) ? intval($_POST['lider_proceso']) : null;
				$jefe_inmediato = !empty($_POST['jefe_inmediato']) ? intval($_POST['jefe_inmediato']) : null;
				$departamento = htmlspecialchars($_POST['departamento']);
				$linea_negocio = htmlspecialchars($_POST['linea_negocio']);
				$evaluacion_competencia = !empty($_POST['evaluacion_competencia']) ? htmlspecialchars($_POST['evaluacion_competencia']) : null;
				$fecha_evaluacion_competencia = !empty($_POST['fecha_evaluacion_competencia']) ? htmlspecialchars($_POST['fecha_evaluacion_competencia']) : null;

				// Evaluar si se subió un archivo
				$archivoEvaluCompetencia = null; // Valor predeterminado
				if (isset($_FILES['archivo_evalu_competencia']) && $_FILES['archivo_evalu_competencia']['error'] == 0) {
					$archivoEvaluCompetencia = 'subido'; // Cambiar a 'subido' si se envía un archivo
				}

				$estado = !empty($_POST['estado']) ? htmlspecialchars($_POST['estado']) : null;

				if ($id_empleado == 0) {
					$option = 1;
					$request_user = $this->model->insertPersonal(
						$codigo_empleado,
						$fecha_ingreso,
						$primer_apellido,
						$segundo_apellido,
						$nombres,
						$identificacion,
						$puesto_contrato,
						$puesto_operativo,
						$lider_proceso,
						$jefe_inmediato,
						$departamento,
						$linea_negocio,
						$estado,
						$archivoEvaluCompetencia
					);
				} else {
					$option = 2;
					$request_user = $this->model->updatePersonal(
						$id_empleado,
						$codigo_empleado,
						$fecha_ingreso,
						$primer_apellido,
						$segundo_apellido,
						$nombres,
						$identificacion,
						$puesto_contrato,
						$puesto_operativo,
						$lider_proceso,
						$jefe_inmediato,
						$departamento,
						$linea_negocio,
						$evaluacion_competencia,
						$fecha_evaluacion_competencia,
						$archivoEvaluCompetencia,
						$estado
					);
				}

				$info = $this->model->selectPersonalID($id_empleado);
				$nombre = $info['nombre_completo'];

				if (!empty($nombre)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Personal";
					$accion = 'Actualizo información laboral del usuario "' . $nombre . '"';
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);

					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}

				if ($request_user > 0) {
					$msg = ($option == 1) ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.';
					$arrResponse = array('status' => true, 'msg' => $msg);
				} elseif ($request_user == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
	}

	public function updateInfo()
	{
		if ($_POST) {
			if (empty($_POST['id_empleado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$id_empleado = intval($_POST['id_empleado']);
				$puesto_contrato = $_POST['puesto_contrato'];
				$puesto_operativo = $_POST['puesto_operativo'];
				$lider_proceso = $_POST['lider_proceso'];
				$jefe_inmediato = $_POST['jefe_inmediato'];
				$departamento = $_POST['departamento'];
				$area = $_POST['area'];
				$correo_empresarial = $_POST['correo_empresarial'];
				$IVR = $_POST['IVR'];
				$evaluacion_competencia = $_POST['evaluacion_competencia'];
				$fecha_evaluacion_competencia = $_POST['fecha_evaluacion_competencia'];
				$archivo_evalu_competencia = null; // Valor predeterminado
				if (isset($_FILES['archivo_evalu_competencia']) && $_FILES['archivo_evalu_competencia']['error'] == 0) {
					$archivo_evalu_competencia = 'subido'; // Cambiar a 'subido' si se envía un archivo
				}
				$formulario_vacaciones = $_POST['formulario_vacaciones'];
				$salario_base = !empty($_POST['salario_base']) ? $_POST['salario_base'] : null;
				$bonificacion = !empty($_POST['bonificacion']) ? $_POST['bonificacion'] : null;
				$kpi1 = !empty($_POST['kpi1']) ? $_POST['kpi1'] : null;
				$kpi2 = !empty($_POST['kpi2']) ? $_POST['kpi2'] : null;
				$kpi_max = !empty($_POST['kpi_max']) ? $_POST['kpi_max'] : null;

				$responsable = $_SESSION['userData']['nombre_completo'];
				// Validar si el correo ya está en uso por otro empleado
				$sql_check = "SELECT id_empleado FROM empleado_tb WHERE correo_empresarial = ? AND id_empleado != ?";
				$request_check = $this->model->select($sql_check, [$correo_empresarial, $id_empleado]);

				if (!empty($request_check)) {
					$arrResponse = array("status" => false, "msg" => 'El correo empresarial ya está en uso por otro empleado.');
				} else {
					// Proceder con la actualización
					$request_user = $this->model->updatePersonal(
						$id_empleado,
						$puesto_contrato,
						$puesto_operativo,
						$lider_proceso, // Puede ser null
						$jefe_inmediato, // Puede ser null
						$departamento,
						$area,
						$correo_empresarial,
						$IVR,
						$evaluacion_competencia,
						$fecha_evaluacion_competencia,
						$archivo_evalu_competencia,
						$formulario_vacaciones,
						$salario_base,
						$bonificacion,
						$kpi1,
						$kpi2,
						$kpi_max,
						$responsable
					);

					if ($request_user) {
						$arrResponse = array("status" => true, "msg" => 'Información actualizada correctamente.');
					} else {
						$arrResponse = array("status" => false, "msg" => 'No se pudo actualizar la información.');
					}
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
	}


	public function subirEvaluacionCompetencia()
	{
		if ($_POST) {
			$id_empleado = intval($_POST['id_empleado']);
			$perfil = $this->model->Perfil($id_empleado); // Obtener el perfil del empleado

			if ($perfil) {
				$archivoGuardado = false;

				// Intentar subir el archivo si fue proporcionado
				if (isset($_FILES['archivo_evalu_competencia']) && $_FILES['archivo_evalu_competencia']['error'] == 0) {
					$file = $_FILES['archivo_evalu_competencia'];
					$fileTmpPath = $file['tmp_name'];
					$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

					// Validar que el archivo sea un PDF
					if ($fileExtension === 'pdf') {
						// Definir el directorio donde se guardará el archivo
						$uploadDir = 'Expedientes/SGN/';
						$fileName = $perfil['identificacion'] . '.pdf'; // Nombre del archivo con el ID del empleado
						$dest_path = $uploadDir . $fileName;

						// Crear el directorio si no existe
						if (!is_dir($uploadDir)) {
							mkdir($uploadDir, 0755, true);
						}

						// Mover el archivo a la carpeta de destino
						if (move_uploaded_file($fileTmpPath, $dest_path)) {
							$archivoGuardado = true;
						}
					}
				}

				// Continuar independientemente de si el archivo fue subido
				echo json_encode([
					'status' => true,
					'msg' => $archivoGuardado
						? 'Archivo PDF subido correctamente.'
						: 'Operación realizada correctamente (sin archivo).',
				]);
			} else {
				echo json_encode(['status' => false, 'msg' => 'Empleado no encontrado.']);
			}
		}
		die();
	}

	public function setAltaUsuario()
	{
		if ($_POST) {
			// Verificar que el ID del empleado esté presente y sea un número válido
			if (empty($_POST['id_empleado']) || !is_numeric($_POST['id_empleado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_empleado = intval($_POST['id_empleado']);
				$estado = 'Recontratacion';

				// Llamar al modelo para actualizar el estado del empleado
				$request_user = $this->model->updateEstadoUsuario($id_empleado, $estado);

				// Verificar la respuesta del modelo
				if ($request_user) {
					$arrResponse = array('status' => true, 'msg' => 'Estado del usuario actualizado a Activo correctamente.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del usuario.');
				}
			}

			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die(); // Finalizar el script
	}

	public function setBajaUsuario()
	{
		if ($_POST) {
			if (empty($_POST['id_empleado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				$id_empleado = intval($_POST['id_empleado']);
				$nuevo_estado = 'Baja';
				$fecha_salida = htmlspecialchars($_POST['fecha_salida']);
				$razon_baja = htmlspecialchars($_POST['razon_baja']);
				$comentario = trim($_POST['comentario']);
				$observaciones = htmlspecialchars($_POST['observaciones']);

				// Llamar al modelo para actualizar el estado del empleado
				$request_user = $this->model->updateEstadoUsuario($id_empleado, $nuevo_estado, $fecha_salida, $razon_baja, $comentario, $observaciones);

				$info = $this->model->selectPersonalID($id_empleado);
				$nombre = $info['nombre_completo'];

				if (!empty($nombre)) {
					$no_empleado = $_SESSION['PersonalData']['no_empleado'];
					$empleado = $_SESSION['PersonalData']['nombre_completo'];
					$modulo = "Personal";
					$accion = 'Dio de baja al colaborador "' . $nombre . '"';
					$fecha = date("Y-m-d H:i:s");
					$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
					$hostname = gethostbyaddr($ip);

					$this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
				}

				// Verificar la respuesta del modelo
				if ($request_user) {
					$arrResponse = array('status' => true, 'msg' => 'Estado del usuario actualizado a Baja correctamente.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'No se pudo actualizar el estado del usuario.');
				}
			}

			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die(); // Finalizar el script
	}

	public function setRecontratacion()
	{
		if ($_POST) {
			if (empty($_POST['id_empleado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
			} else {
				// Convertir y limpiar los datos recibidos del POST
				$id_empleado = intval($_POST['id_empleado']);
				$jefe_inmediato = intval($_POST['jefe_inmediato']);
				$lider_proceso = intval($_POST['lider_proceso']);
				$puesto_contrato = intval($_POST['puesto_contrato']);
				$puesto_operativo = intval($_POST['puesto_operativo']);
				$departamento = intval($_POST['departamento']);
				$area = intval($_POST['area']);
				$salario_base = !empty($_POST['salario_base']) ? $_POST['salario_base'] : null;
				$bonificacion = !empty($_POST['bonificacion']) ? $_POST['bonificacion'] : null;
				$kpi1 = !empty($_POST['kpi1']) ? $_POST['kpi1'] : null;
				$kpi2 = !empty($_POST['kpi2']) ? $_POST['kpi2'] : null;
				$kpi_max = !empty($_POST['kpi_max']) ? $_POST['kpi_max'] : null;
				$estado = 'Recontratacion';
				$responsable = $_POST['responsable']; // Obtener el responsable desde el formulario o la sesión

				// Validar si responsable existe (en caso de que algo falle con la sesión)
				if (empty($responsable)) {
					$arrResponse = array("status" => false, "msg" => 'Responsable no encontrado');
				} else {
					// Llamar al modelo para actualizar el estado del empleado
					$request_user = $this->model->reContratacion(
						$id_empleado,
						$jefe_inmediato,
						$lider_proceso,
						$puesto_contrato,
						$puesto_operativo,
						$departamento,
						$area,
						$salario_base,
						$bonificacion,
						$kpi1,
						$kpi2,
						$kpi_max,
						$responsable,
						$estado
					);
					$info = $this->model->selectPersonalID($id_empleado);
                    $nombre = $info['nombre_completo'];

                    if (!empty($nombre)) {
                        $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                        $empleado = $_SESSION['PersonalData']['nombre_completo'];
                        $modulo = "Baja Personal";
                        $accion = 'Se inicio proceso de recontratacion del colaborador "' . $nombre . '"';
                        $fecha = date("Y-m-d H:i:s");
                        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                        $hostname = gethostbyaddr($ip);
                    
                        $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                    }
					if ($request_user) {
						$arrResponse = array('status' => true, 'msg' => 'Empleado recontratado correctamente y movimientos registrados.');
					} else {
						$arrResponse = array('status' => false, 'msg' => 'No se pudo completar la recontratación del empleado.');
					}
				}
			}

			// Retornar respuesta en formato JSON
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die(); // Finalizar el script
	}

	public function getSelectAreas()
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



	public function getSelectlinea_negocio()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectlinea_negocio();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {

				$htmlOptions .= '<option value="' . $arrData[$i]['id_ln'] . '">' . $arrData[$i]['codigo'] . '  |  ' . $arrData[$i]['descripcion'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}


	public function getSelectPuestos()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectPuestos();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				$htmlOptions .= '<option value="' . $arrData[$i]['id_puesto'] . '">' . $arrData[$i]['nombre_puesto'] . " " . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}

	public function getSelectJefesLideres()
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

	public function getSelectEncargados()
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

	public function getSelectDepartamento()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectDepartamento();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				$htmlOptions .= '<option value="' . $arrData[$i]['id_departamento'] . '">' . $arrData[$i]['nombre_departamento'] . " | " . $arrData[$i]['descripcion'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}

	public function getSelectBaja()
	{
		$htmlOptions = '<option selected disabled value="">Seleccione una Razon</option>';
		$arrData = $this->model->selectRenuncias();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				$htmlOptions .= '<option value="' . $arrData[$i]['asunto'] . '">' . $arrData[$i]['asunto'] . '</option>';
			}
		}
		echo $htmlOptions;
		die();
	}

	public function buscarPersonal()
	{
		// Verificamos si hay un término de búsqueda y no está vacío
		if (!empty($_POST['termino'])) {
			$termino = trim($_POST['termino']);

			// Llamamos al modelo para obtener los resultados
			$resultados = $this->model->buscarPersonal($termino);

			// Devolvemos los resultados en formato JSON
			header('Content-Type: application/json');
			echo json_encode($resultados);
		} else {
			// En caso de que no haya término de búsqueda, devolvemos un array vacío
			header('Content-Type: application/json');
			echo json_encode([]);
		}
	}

	public function subirFotoPerfil()
	{
		if ($_POST) {
			$id_empleado = intval($_POST['id_empleado']);
			$perfil = $this->model->Perfil($id_empleado);

			if ($perfil) {
				if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
					$file = $_FILES['file'];
					$fileTmpPath = $file['tmp_name'];
					$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

					$uploadDir = 'Assets/images/Perfiles/';
					$fileName = $perfil['identificacion'] . '.png';  // Guardar siempre como PNG
					$dest_path = $uploadDir . $fileName;

					if (!is_dir($uploadDir)) {
						mkdir($uploadDir, 0755, true);
					}

					// Convertir la imagen al formato PNG si no es PNG
					$image = null;
					switch ($fileExtension) {
						case 'jpeg':
						case 'jpg':
							$image = imagecreatefromjpeg($fileTmpPath);
							break;
						case 'gif':
							$image = imagecreatefromgif($fileTmpPath);
							break;
						case 'png':
							$image = imagecreatefrompng($fileTmpPath);
							break;
						default:
							echo json_encode(['status' => false, 'msg' => 'Formato de archivo no soportado.']);
							die();
					}

					// Guardar la imagen como PNG
					if ($image && imagepng($image, $dest_path)) {
						imagedestroy($image);  // Liberar memoria
						echo json_encode(['status' => true, 'msg' => 'Imagen convertida y subida como PNG correctamente.']);
					} else {
						echo json_encode(['status' => false, 'msg' => 'No se pudo guardar el archivo como PNG.']);
					}
				} else {
					echo json_encode(['status' => false, 'msg' => 'No se ha subido ningún archivo o hubo un error.']);
				}
			} else {
				echo json_encode(['status' => false, 'msg' => 'No se recibió la solicitud correctamente.']);
			}
		}
		die();
	}




}