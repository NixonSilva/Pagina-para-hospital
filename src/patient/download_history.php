<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}

require_once('../../config/db.php');
require_once('../../vendor/autoload.php'); // O la ruta correcta si no usas Composer

// Obtener información del paciente
$paciente_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT nombre_completo, tipo_documento, numero_documento FROM usuarios WHERE id = ?");
$stmt->execute([$paciente_id]);
$paciente = $stmt->fetch();

// Obtener historial clínico
$stmt = $pdo->prepare("
    SELECT fecha, diagnostico, tratamiento, observaciones 
    FROM historial_clinico 
    WHERE paciente_id = ?
    ORDER BY fecha
");
$stmt->execute([$paciente_id]);
$historial = $stmt->fetchAll();

// Obtener citas del paciente
$stmt = $pdo->prepare("
    SELECT c.fecha, c.hora, t.nombre AS tipo_cita, u.nombre_completo AS doctor, c.estado 
    FROM citas c
    JOIN tipos_citas t ON c.tipo_cita_id = t.id
    JOIN usuarios u ON c.doctor_id = u.id
    WHERE c.paciente_id = ?
    ORDER BY c.fecha, c.hora
");
$stmt->execute([$paciente_id]);
$citas = $stmt->fetchAll();

// Crear PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Hospital');
$pdf->SetTitle('Historial Clínico');
$pdf->SetHeaderData('', 0, 'Historial Clínico', "Paciente: " . $paciente['nombre_completo']);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 30, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

// Contenido del PDF
$html = '
<h2>Información del Paciente</h2>
<p><strong>Nombre Completo:</strong> ' . $paciente['nombre_completo'] . '</p>
<p><strong>Tipo de Documento:</strong> ' . $paciente['tipo_documento'] . '</p>
<p><strong>Número de Documento:</strong> ' . $paciente['numero_documento'] . '</p>
<h2>Historial Clínico</h2>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Diagnóstico</th>
            <th>Tratamiento</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>';

foreach ($historial as $registro) {
    $html .= '
        <tr>
            <td>' . $registro['fecha'] . '</td>
            <td>' . $registro['diagnostico'] . '</td>
            <td>' . $registro['tratamiento'] . '</td>
            <td>' . $registro['observaciones'] . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

$html .= '
<h2>Citas Médicas</h2>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Tipo de Cita</th>
            <th>Doctor</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>';

foreach ($citas as $cita) {
    $html .= '
        <tr>
            <td>' . $cita['fecha'] . '</td>
            <td>' . $cita['hora'] . '</td>
            <td>' . $cita['tipo_cita'] . '</td>
            <td>' . $cita['doctor'] . '</td>
            <td>' . $cita['estado'] . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF
$pdf->Output('historial_clinico.pdf', 'D');
?>
