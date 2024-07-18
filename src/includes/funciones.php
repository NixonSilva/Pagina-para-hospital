<?php
/**
 * Conectar a la base de datos.
 *
 * @return mysqli Conexión a la base de datos.
 */
function conectarDB() {
    $conexion = new mysqli('localhost', 'root', '', 'sistema_hospital');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    return $conexion;
}

/**
 * Obtener todos los usuarios de la base de datos.
 *
 * @return array Lista de usuarios.
 */
function obtenerUsuarios() {
    $conexion = conectarDB();
    $sql = "SELECT * FROM usuarios";
    $resultado = $conexion->query($sql);

    $usuarios = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    $conexion->close();
    return $usuarios;
}

/**
 * Obtener un usuario por su ID.
 *
 * @param int $id ID del usuario.
 * @return array Datos del usuario.
 */
function obtenerUsuarioPorId($id) {
    $conexion = conectarDB();
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    $stmt->close();
    $conexion->close();
    return $usuario;
}

/**
 * Actualizar los datos de un usuario.
 *
 * @param int $id ID del usuario.
 * @param string $nombre Nombre del usuario.
 * @param string $email Email del usuario.
 * @param int $rol_id ID del rol del usuario.
 */
function actualizarUsuario($id, $nombre, $email, $rol_id) {
    $conexion = conectarDB();
    $sql = "UPDATE usuarios SET nombre_completo = ?, correo_electronico = ?, rol_id = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ssii', $nombre, $email, $rol_id, $id);
    $stmt->execute();

    $stmt->close();
    $conexion->close();
}

/**
 * Eliminar un usuario por su ID.
 *
 * @param int $id ID del usuario.
 */
function eliminarUsuario($id) {
    $conexion = conectarDB();

    // Eliminar citas asociadas al usuario
    $sql_eliminar_citas = "DELETE FROM citas WHERE paciente_id = ?";
    $stmt_citas = $conexion->prepare($sql_eliminar_citas);
    $stmt_citas->bind_param('i', $id);
    $stmt_citas->execute();
    $stmt_citas->close();

    // Luego eliminar al usuario
    $sql_eliminar_usuario = "DELETE FROM usuarios WHERE id = ?";
    $stmt_usuario = $conexion->prepare($sql_eliminar_usuario);
    $stmt_usuario->bind_param('i', $id);
    $stmt_usuario->execute();
    $stmt_usuario->close();

    $conexion->close();
}

/**
 * Obtener todas las citas de la base de datos.
 *
 * @return array Lista de citas.
 */
function obtenerTodasLasCitas() {
    $conexion = conectarDB();
    $sql = "SELECT citas.id, usuarios.nombre_completo AS paciente, citas.fecha, tipos_citas.nombre AS tipos_citas 
            FROM citas 
            JOIN usuarios ON citas.paciente_id = usuarios.id 
            JOIN tipos_citas ON citas.tipo_cita_id = tipos_citas.id";
    $resultado = $conexion->query($sql);

    $citas = [];
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $citas[] = $row;
        }
    }

    $conexion->close();
    return $citas;
}
?>