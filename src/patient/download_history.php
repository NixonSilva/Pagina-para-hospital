<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}

require_once('../../config/db.php');
require_once('../../vendor/autoload.php');

// Obtener información del paciente
$paciente_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT nombre_completo, tipo_documento, numero_documento FROM usuarios WHERE id = ?");
$stmt->execute([$paciente_id]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener historial clínico
$stmt = $pdo->prepare("
    SELECT hc.*, m.motivo, d.nombre_completo AS doctor_nombre, ec.nombre AS estado
    FROM historial_clinico hc
    JOIN motivos_consulta m ON hc.motivo = m.id
    JOIN usuarios d ON hc.doctor_id = d.id
    JOIN estado_consulta ec ON hc.estado_id = ec.id
    WHERE hc.paciente_id = ?
");
$stmt->execute([$paciente_id]);
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, 'Historial Clínico', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

// Crear PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Hospital');
$pdf->SetTitle('Historial Clínico');
$pdf->SetHeaderData('', 0, 'Historial Clínico', '');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();

// Contenido del PDF
$html = '
<h2>Información del Paciente</h2>
<p><strong>Nombre Completo:</strong> ' . htmlspecialchars($paciente['nombre_completo']) . '</p>
<p><strong>Tipo de Documento:</strong> ' . htmlspecialchars($paciente['tipo_documento']) . '</p>
<p><strong>Número de Documento:</strong> ' . htmlspecialchars($paciente['numero_documento']) . '</p>
<h2>Historial Clínico</h2>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Motivo</th>
            <th>Tratamiento</th>
            <th>Observaciones</th>
            <th>Doctor</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>';

foreach ($historial as $registro) {
    $html .= '
        <tr>
            <td>' . htmlspecialchars($registro['fecha']) . '</td>
            <td>' . htmlspecialchars($registro['motivo']) . '</td>
            <td>' . htmlspecialchars($registro['tratamiento']) . '</td>
            <td>' . htmlspecialchars($registro['observaciones']) . '</td>
            <td>' . htmlspecialchars($registro['doctor_nombre']) . '</td>
            <td>' . htmlspecialchars($registro['estado']) . '</td>
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
            <td>' . htmlspecialchars($cita['fecha']) . '</td>
            <td>' . htmlspecialchars($cita['hora']) . '</td>
            <td>' . htmlspecialchars($cita['tipo_cita']) . '</td>
            <td>' . htmlspecialchars($cita['doctor']) . '</td>
            <td>' . htmlspecialchars($cita['estado']) . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF
$pdf->Output('historial_clinico.pdf', 'D');
?>
