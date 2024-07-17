<?php
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $rol_id = $_POST['rol'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, tipo_documento, numero_documento, correo_electronico, contrasena, rol_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre_completo, $tipo_documento, $numero_documento, $correo_electronico, $contrasena, $rol_id]);

    header("Location: login.php");
    exit();
}
?>
