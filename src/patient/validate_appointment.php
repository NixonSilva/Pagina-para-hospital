<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}
include('../../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente_id = $_SESSION['user_id'];
    $tipo_cita_id = $_POST['tipo_cita'];
    $doctor_id = $_POST['doctor'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $stmt = $pdo->prepare("INSERT INTO citas (paciente_id, doctor_id, tipo_cita_id, fecha, hora) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$paciente_id, $doctor_id, $tipo_cita_id, $fecha, $hora]);

    header("Location: dashboard.php");
    exit();
}
?>
