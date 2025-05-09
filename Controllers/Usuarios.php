<?php
session_start();

class Usuarios extends Controllers
{

    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(USUARIOS);
    }

    public $views;
    public $model;
    public function Usuarios()
    {

        if (empty($_SESSION['permisos'][USUARIOS]['leer'])) {
            header("Location:" . base_url() . '/dashboard');
            exit();
        }

        $data['page_id'] = USUARIOS;
        $data['page_tag'] = "Usuarios";
        $data['page_title'] = "Usuarios";
        $data['page_name'] = "Usuarios";
        $data['page_functions_js'] = "functions_usuarios.js";

        $this->views->getView($this, "Usuarios", $data);
    }

    public function getUsuarios()
    {
        $arrData = $this->model->Usuarios();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getUsuariosbyId($id_user)
    {
        $data = $this->model->UsuariosbyID($id_user);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function deleteUsuario($id_user)
    {
        header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

        try {
            if (empty($id_user) || !is_numeric($id_user)) {
                throw new Exception("ID de usuario inválido.");
            }

            $data = $this->model->UsuariosbyID($id_user);
            $nombre = $data['nombre_completo'];
            $no_empleado = $_SESSION['PersonalData']['no_empleado'];
            $empleado = $_SESSION['PersonalData']['nombre_completo'];
            $modulo = "Usuarios";
            $accion = 'Se creo el usuario de "' . $nombre . '"';
            $fecha = date("Y-m-d H:i:s");
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
            $hostname = gethostbyaddr($ip);
            $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);

            $result = $this->model->deleteUsuario($id_user);

            if ($result) {
                $response = [
                    'status' => true,
                    'msg' => 'Usuario eliminado exitosamente.',
                ];
            } else {
                throw new Exception("No se pudo eliminar el usuario.");
            }
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
        }

        echo json_encode($response);
    }


    public function setUsuario()
    {
        if ($_POST) {
            $id_user = intval($_POST['id_user']);
            $usuario_id = !empty($_POST['usuario_id']) ? htmlspecialchars(trim($_POST['usuario_id'])) : "";
            $password = !empty($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : null;
            $estado = 'Activo'; // O puedes hacer que sea dinámico si es necesario
            $role_id = intval($_POST['role_id']);

            $info = $this->model->userName($usuario_id);
            $nombre = $info['nombre_completo'];
            $correo = $info['correo'];

            // Validar que usuario_id no esté vacío
            if (empty($usuario_id)) {
                $arrResponse = array("status" => false, "msg" => 'El ID de usuario es requerido.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            if (empty($correo)) {
                $arrResponse = array("status" => false, "msg" => 'El colaborador no tiene correo asignado.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Validar si la contraseña es requerida solo para inserciones
            if (empty($password) && $id_user == 0) {
                $arrResponse = array("status" => false, "msg" => 'La contraseña es requerida para un nuevo usuario.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            if ($id_user == 0) {
                // Insertar nuevo usuario con validación del correo
                $request_user = $this->model->insertUsuario($usuario_id, $password, $estado, $role_id);

                if (!empty($nombre)) {
                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Usuarios";
                    $accion = 'Se creo el usuario de "' . $nombre . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);

                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }
            } else {
                // Actualizar usuario existente
                $request_user = $this->model->updateUsuario($id_user, $password, $estado, $role_id);

                if (!empty($nombre)) {
                    $no_empleado = $_SESSION['PersonalData']['no_empleado'];
                    $empleado = $_SESSION['PersonalData']['nombre_completo'];
                    $modulo = "Usuarios";
                    $accion = 'Actualizo el usuario de "' . $nombre . '"';
                    $fecha = date("Y-m-d H:i:s");
                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                    $hostname = gethostbyaddr($ip);

                    $this->model->registroAvance($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);
                }
            }

            // Manejar la respuesta de la operación
            if ($request_user > 0) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
            } elseif ($request_user == 'exists') {
                $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
            } elseif ($request_user == 'no_correo') {
                $arrResponse = array('status' => false, 'msg' => 'No se encontró un correo asociado al colaborador.');
            } elseif ($request_user == 'invalid_email') {
                $arrResponse = array('status' => false, 'msg' => 'El correo asociado al usuario no es válido.');
            } elseif ($request_user == 'invalid_role') {
                $arrResponse = array('status' => false, 'msg' => 'Rol no válido.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }



    public function getEmpleados()
    {
        $dimension = $this->model->selectEmpleado(); // Método que obtiene todos los usuarios
        if (!empty($dimension)) {
            $response = ['status' => true, 'data' => $dimension];
        } else {
            $response = ['status' => false, 'msg' => 'No hay dimensiones disponibles.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getRol()
    {
        $dimension = $this->model->selectRol(); // Método que obtiene todos los usuarios
        if (!empty($dimension)) {
            $response = ['status' => true, 'data' => $dimension];
        } else {
            $response = ['status' => false, 'msg' => 'No hay dimensiones disponibles.'];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


}