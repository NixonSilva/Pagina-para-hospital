<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}
include('../../config/db.php');
include('../../templates/header.php');

// Obtener citas del paciente
$paciente_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT c.id, c.fecha, c.hora, tc.nombre AS tipo_cita, u.nombre_completo AS doctor
    FROM citas c
    JOIN tipos_citas tc ON c.tipo_cita_id = tc.id
    JOIN usuarios u ON c.doctor_id = u.id
    WHERE c.paciente_id = ?
    ORDER BY c.fecha, c.hora
");
$stmt->execute([$paciente_id]);
$citas = $stmt->fetchAll();
?>

<h1>Bienvenido, Paciente</h1>
<p>Este es tu dashboard.</p>
<a href="schedule_appointment.php">Agendar Cita</a>
<h2>Tus Citas</h2>
<?php if (count($citas) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tipo de Cita</th>
                <th>Doctor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= $cita['fecha'] ?></td>
                    <td><?= $cita['hora'] ?></td>
                    <td><?= $cita['tipo_cita'] ?></td>
                    <td><?= $cita['doctor'] ?></td>
                    <td>
                        <form action="cancel_appointment.php" method="post" style="display:inline;">
                            <input type="hidden" name="cita_id" value="<?= $cita['id'] ?>">
                            <button type="submit" onclick="return confirm('¿Estás seguro de que quieres cancelar esta cita?');">Cancelar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No tienes citas agendadas.</p>
<?php endif; ?>
<a href="../logout.php">Cerrar Sesión</a>
<?php include('../../templates/footer.php'); ?>
