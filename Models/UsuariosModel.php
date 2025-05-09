<?php
class UsuariosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Usuarios()
    {
        $sql =
            "SELECT
            us.id_user,
            us.usuario_id,
            us.correo_empresarial,
            CONCAT(et.primer_apellido, ' ', et.segundo_apellido ,' ', et.nombres) as nombres,
            rs.role_name,
            us.estado
        FROM users_sistema us
        INNER JOIN roles_sistemas rs ON us.role_id = rs.id
        INNER JOIN empleado_tb et ON us.usuario_id = et.id_empleado";

        $request = $this->select_all($sql);
        return $request;
    }

    public function UsuariosbyID($id_user)
    {
        $sql =
            "SELECT 
            us.id_user,
            us.usuario_id,
            et.identificacion,
            et.correo_empresarial,
            CONCAT(et.primer_apellido, ' ', et.segundo_apellido ,' ', et.nombres) as nombres,
            CONCAT(et.nombres, ' ', et.primer_apellido ,' ', et.segundo_apellido) as nombre_completo,
            us.password,
            us.role_id,
            us.estado
        FROM users_sistema us
        INNER JOIN empleado_tb et ON us.usuario_id = et.id_empleado 
        where us.id_user = ?";
        $request = $this->select($sql, array($id_user));
        return $request;
    }

    public function userName($usuario_id)
    {
        $sql =
            "SELECT
            correo_empresarial as correo, 
            CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) as nombre_completo
        FROM empleado_tb 
        where id_empleado = ?";
        $request = $this->select($sql, array($usuario_id));
        return $request;
    }

    public function deleteUsuario($id_user)
    {
        $sql = "DELETE FROM users_sistema WHERE id_user = ?";
        $request = $this->deletebyid($sql, [$id_user]);
        return $request;
    }

    public function insertUsuario(int $usuario_id, string $password, string $estado, int $role_id)
    {
        // Buscar el correo empresarial en la tabla empleado_tb
        $sql_correo_empresarial = "SELECT correo_empresarial FROM empleado_tb WHERE id_empleado = ?";
        $correo_result = $this->select($sql_correo_empresarial, [$usuario_id]);

        // Verificar si se encontró el correo y que no esté vacío
        if (!empty($correo_result) && !empty($correo_result['correo_empresarial'])) {
            $correo_empresarial = trim($correo_result['correo_empresarial']);

            // Validar formato de correo
            if (!filter_var($correo_empresarial, FILTER_VALIDATE_EMAIL)) {
                return 'invalid_email';
            }

            // Encriptar la contraseña
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Verificar si el usuario ya existe en la tabla users_sistema
            $sql_check = "SELECT * FROM users_sistema WHERE usuario_id = ?";
            $request_check = $this->select($sql_check, [$usuario_id]);

            if (empty($request_check)) {
                // Validar si el rol existe en la tabla roles_sistemas antes de insertar
                $sql_role_check = "SELECT id FROM roles_sistemas WHERE id = ?";
                $role_check = $this->select($sql_role_check, [$role_id]);

                if (!empty($role_check)) {
                    // Insertar el nuevo usuario en la tabla users_sistema
                    $query_insert = "INSERT INTO users_sistema(usuario_id, correo_empresarial, password, estado, role_id) 
                                     VALUES (?, ?, ?, ?, ?)";
                    $arrdata = array(
                        $usuario_id,
                        $correo_empresarial, // Usar el correo empresarial obtenido de la tabla empleado_tb
                        $password_hashed,
                        htmlspecialchars($estado), // Limpieza del valor del estado
                        $role_id
                    );

                    $request_insert = $this->insert($query_insert, $arrdata);
                    return $request_insert;
                } else {
                    return 'invalid_role'; // El rol no existe
                }
            } else {
                return 'exists'; // El usuario ya existe
            }
        } else {
            return 'no_correo'; // No se encontró correo
        }
    }

    public function registroAvance(string $no_empleado, string $empleado, string $modulo, string $accion, string $fecha, string $ip, string $hostname)
    {
        if (empty($request)) {
            // Insertar nuevo usuario
            $columnas = "no_empleado, empleado, modulo, accion, fecha, ip, hostname";
            $query_insert = "INSERT INTO log_actividad($columnas) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $arrData = array(
                $no_empleado,
                $empleado,
                $modulo,
                $accion,
                $fecha,
                $ip,
                $hostname
            );
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateUsuario(int $id_user, ?string $password, string $estado, int $role_id)
    {
        // Validar si el rol existe en la tabla roles_sistemas antes de actualizar
        $sql_role_check = "SELECT id FROM roles_sistemas WHERE id = ?";
        $role_check = $this->select($sql_role_check, [$role_id]);

        if (!empty($role_check)) {
            // Si se proporciona una nueva contraseña, hashearla
            $password_hashed = null;
            $updatePassword = '';

            if (!is_null($password)) {
                $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                $updatePassword = "password = ?, "; // Incluir el campo en la consulta solo si se proporciona la contraseña
            }

            // Consulta SQL para actualizar la información del usuario
            $sql = "UPDATE users_sistema SET
                {$updatePassword}
                estado = ?,
                role_id = ?
                WHERE id_user = ?";

            // Array de datos para la consulta
            $arrData = array();

            // Si se proporcionó una nueva contraseña, agregarla a los datos
            if (!is_null($password_hashed)) {
                $arrData[] = $password_hashed;
            }

            // Agregar el estado, role_id e id_user
            $arrData[] = htmlspecialchars($estado); // Limpieza del estado
            $arrData[] = $role_id;
            $arrData[] = $id_user;

            // Ejecutar la consulta
            $request = $this->update($sql, $arrData);
            return $request;
        } else {
            return 'invalid_role'; // Retornar error si el rol no es válido
        }
    }

    public function selectEmpleado()
    {
        $sql = "SELECT
            et.id_empleado, 
            CONCAT(et.primer_apellido, ' ', et.segundo_apellido) as apellidos,
            et.nombres, 
            et.identificacion,
            et.estado
        FROM empleado_tb et
        LEFT JOIN users_sistema us ON us.usuario_id = et.id_empleado
        WHERE us.usuario_id IS NULL AND et.estado != 'baja'"; // Solo muestra empleados que no están en la tabla de usuarios

        return $this->select_all($sql);
    }

    public function selectRol()
    {
        $sql = "SELECT 
            id, 
            role_name
        FROM roles_sistemas "; // Ajusta esta consulta a tu tabla de usuarios
        return $this->select_all($sql);
    }

}