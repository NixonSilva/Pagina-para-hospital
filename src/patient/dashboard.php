<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}
include('../../templates/header.php');
?>
<h1>Bienvenido, Paciente</h1>
<p>Este es tu dashboard.</p>
<a href="schedule_appointment.php">Agendar Cita</a>
<a href="../logout.php">Cerrar Sesión</a>
<?php include('../../templates/footer.php'); ?>
