<?php

require "Assets/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Libraries/phpmailer/Exception.php';
require 'Libraries/phpmailer/PHPMailer.php';
require 'Libraries/phpmailer/SMTP.php';

//Retorla la url del proyecto
function base_url()
{
    return BASE_URL;
}
//Retorla la url de Assets
function media()
{
    return BASE_URL . "/Assets";
}
function headerAdmin($data = "")
{
    $view_header = "Views/Template/header_admin.php";
    require_once($view_header);
}

function headerVacaciones($data = "")
{
    $view_header = "Views/Template/header_vacaciones.php";
    require_once($view_header);
}

function navAdmin($data = "")
{
    $view_header = "Views/Template/nav_admin.php";
    require_once($view_header);
}
function footerAdmin($data = "")
{
    $view_footer = "Views/Template/footer_admin.php";
    require_once($view_footer);
}


function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    return $string;
}


function strComillas($cadena)
{
    $string = str_replace(['"', '\\'], '', $cadena);
    return $string;
}

function sessionUser(int $id_user)
{
    require_once("Models/LoginModel.php");
    $objLogin = new LoginModel(); // Asegúrate de que este nombre de clase sea correcto
    $request = $objLogin->sessionLogin($id_user); 
    return $request;
}

function sessionStart()
{
    session_start(); // Inicia la sesión

    $inactive = 1800; // media hora
    
    // Si existe 'timeout' en la sesión, verifica la inactividad
    if (isset($_SESSION['timeout'])) {
        // Calcula el tiempo de inactividad
        $session_in = time() - $_SESSION['timeout'];
        
        // Si el tiempo de inactividad excede el límite permitido
        if ($session_in > $inactive) {
            session_unset();    // Elimina todas las variables de sesión
            session_destroy();  // Destruye la sesión actual
            header("Location: " . BASE_URL . "/login"); // Redirige al login o logout
            exit(); // Finaliza el script después de la redirección
        }
    }

    // Actualiza el tiempo de la última actividad
    $_SESSION['timeout'] = time(); 
}

function isLoggedIn()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica si el usuario está logueado
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        return false; // No está logueado
    }

    // Obtén el correo y el token de la sesión
    $correo = $_SESSION['userData']['correo_empresarial'] ?? null;
    $token = $_SESSION['userData']['token'] ?? null;

    if (!$correo || !$token) {
        session_unset();
        session_destroy();
        return false; // Datos de sesión incompletos
    }

    // Valida el token en la base de datos
    require_once("Models/VacacionesModel.php");
    $model = new VacacionesModel();


    // RETIRAR PARA FUNCIONAMIENTO DEL LOGIN 

    // if (!$model->isTokenValid($correo, $token)) {
    //     session_unset();
    //     session_destroy();
    //     return false; // Token inválido o expirado
    // }

    return true; // Sesión válida
}

function validateTokenSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Inicia la sesión si no está iniciada
    }

    // Verifica si el usuario está logueado
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        exit();
    }

    // Obtén los datos de la sesión
    $correo = $_SESSION['userData']['correo_empresarial'] ?? null;
    $token = $_SESSION['userData']['token'] ?? null;

    if (!$correo || !$token) {
        session_unset();
        session_destroy();
        header("Location: " . base_url() . "/vacaciones/login");
        exit();
    }

    // Llama al modelo para verificar si el token sigue siendo válido
    require_once("Models/VacacionesModel.php");
    $model = new VacacionesModel();

    if (!$model->isTokenValid($correo, $token)) {
        session_unset();
        session_destroy();
        header("Location: " . base_url() . "/vacaciones/login?expired=true");
        exit();
    }
}


function getPermisos(int $modulo_id)
{
    require_once("Models/PermisosModel.php"); 
    $objPermisos = new PermisosModel(); 
    if (!empty($_SESSION['PersonalData'])) {
        $role_id = $_SESSION['PersonalData']['role_id']; // Obtiene el role_id del usuario
        $arrPermisos = $objPermisos->permisosModulo($role_id); // Obtiene los permisos del rol
        $permisos = '';
        $permisosMod = '';
        if (count($arrPermisos) > 0) {
            $permisos = $arrPermisos; // Asigna los permisos completos a la sesión
            $permisosMod = isset($arrPermisos[$modulo_id]) ? $arrPermisos[$modulo_id] : ""; // Asigna los permisos específicos del módulo actual
        }
        $_SESSION['permisos'] = $permisos; // Guarda los permisos en la sesión
        $_SESSION['permisosMod'] = $permisosMod;
    }
}

function Meses()
{
    $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    );
    return $meses;
}
