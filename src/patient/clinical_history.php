<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}
include('../../config/db.php');
include('../../templates/header.php');

// Obtener información del paciente
$paciente_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT nombre_completo, tipo_documento, numero_documento FROM usuarios WHERE id = ?");
$stmt->execute([$paciente_id]);
$paciente = $stmt->fetch();

// Obtener historial clínico
$stmt = $pdo->prepare("
    SELECT c.fecha, c.hora, tc.nombre AS tipo_cita, u.nombre_completo AS doctor
    FROM citas c
    JOIN tipos_citas tc ON c.tipo_cita_id = tc.id
    JOIN usuarios u ON c.doctor_id = u.id
    WHERE c.paciente_id = ?
    ORDER BY c.fecha, c.hora
");
$stmt->execute([$paciente_id]);
$citas = $stmt->fetchAll();
?>

<h1>Historial Clínico</h1>
<h2>Información del Paciente</h2>
<p><strong>Nombre Completo:</strong> <?= $paciente['nombre_completo'] ?></p>
<p><strong>Tipo de Documento:</strong> <?= $paciente['tipo_documento'] ?></p>
<p><strong>Número de Documento:</strong> <?= $paciente['numero_documento'] ?></p>

<h2>Citas Asistidas</h2>
<?php if (count($citas) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tipo de Cita</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= $cita['fecha'] ?></td>
                    <td><?= $cita['hora'] ?></td>
                    <td><?= $cita['tipo_cita'] ?></td>
                    <td><?= $cita['doctor'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No tienes citas asistidas.</p>
<?php endif; ?>
<p><a href="download_history.php">Descargar Historial Clínico (PDF)</a></p>
<p><a href="dashboard.php">Volver al Dashboard</a></p>
<p><a href="../logout.php">Cerrar Sesión</a></p>
<?php include('../../templates/footer.php'); ?>
