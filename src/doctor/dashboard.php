<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Doctor</title>
    <link rel="stylesheet" href="../../public/styles.css">
</head>
<body>
    <h1>Dashboard Doctor</h1>
    <ul>
        <li><a href="view_appointments.php">Ver Citas Agendadas</a></li>
        <li><a href="../../public/index.php">Volver al inicio</a></li>
    </ul>
</body>
</html>
