<?php
class Login extends Controllers
{
    public function __construct()
    {

        if (isset($_SESSION['login'])) {
            header('Location: ' . base_url() . '/dashboard');
            die();
        }
        parent::__construct();
    }

    public function login()
    {
        $data['page_tag'] = "Login";
        $data['page_title'] = "Login";
        $data['page_name'] = "Login";
        $data['page_functions_js'] = "functions_login.js";
        $this->views->getView($this, "Login", $data);
    }

    public function loginUser()
    {
        // Inicializar la variable de respuesta
        $arrResponse = array();

        if ($_POST) {
            $correo_empresarial = $_POST['correo_empresarial'];
            $password = $_POST['password'];

            // Verificar si los campos están vacíos
            if (empty($correo_empresarial) || empty($password)) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $requestUser = $this->model->getUserByIdentificacion($correo_empresarial);

                if (empty($requestUser)) {
                    $arrResponse = array('status' => false, 'msg' => 'El usuario no existe o esta de Baja');
                } else {
                    $hashedPassword = $requestUser['password'];

                    // Verificar si la contraseña es correcta
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

                                $emailTemplatePath = 'Views/Template/Email/TokenEmail.php';

                                // Incluir la plantilla para enviar el correo
                                require $emailTemplatePath;
                                $arrResponse = array('status' => true, 'msg' => 'El correo con el token ha sido enviado.');
                            }
                        }
                    } else {
                        $arrResponse = array('status' => false, 'msg' => 'Contraseña incorrecta');
                    }
                }
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
            $token = trim($_POST['token']);

            // Validar el token en la base de datos
            $userData = $this->model->validarToken($correo_empresarial, $token);
            $PersonalData = $this->model->loginUser($correo_empresarial);

            if ($userData) {
                // Iniciar sesión
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['PersonalData'] = $PersonalData;
                $_SESSION['userData'] = $userData;
                $_SESSION['userData']['token'] = $token;

                // Registrar actividad
                $no_empleado = $PersonalData['no_empleado'];
                $empleado = $PersonalData['nombre_completo'];
                $modulo = "Login";
                $accion = "Inicio de Sesión";
                $fecha = date("Y-m-d H:i:s");
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
                $hostname = gethostbyaddr($ip);

                $this->model->insertSecion($no_empleado, $empleado, $modulo, $accion, $fecha, $ip, $hostname);


                // Verificar si tiene un rol asignado
                if (empty($PersonalData['role_id'])) {
                    echo json_encode([
                        'status' => false,
                        'msg' => 'Error: El usuario no tiene un role_id asignado.',
                    ]);
                    exit;
                }

                $role_id = $PersonalData['role_id'];
                $acceso = $this->model->permisosModulo($role_id);
                $redirectUrl = base_url() . '/Dashboard'; // Valor por defecto

                // Determinar redirección según permisos
                if (empty($acceso[1]['leer'])) {
                    $redirectUrl = base_url() . '/Personal';
                } elseif (empty($acceso[2]['leer'])) {
                    $redirectUrl = base_url() . '/OtraVista';
                }


                echo json_encode([
                    'status' => true,
                    'msg' => 'Inicio de sesión exitoso.',
                    'redirect' => $redirectUrl,
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


    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header('Location: ' . base_url() . '/login');
        exit;
    }



}
