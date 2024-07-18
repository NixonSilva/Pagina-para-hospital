<?php
function conectarDB() {
    $conexion = new mysqli('localhost', 'root', '', 'sistema_hospital');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    return $conexion;
}

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
?>