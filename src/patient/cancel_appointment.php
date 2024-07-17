<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: ../login.php");
    exit();
}
include('../../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cita_id = $_POST['cita_id'];

    $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ? AND paciente_id = ?");
    $stmt->execute([$cita_id, $_SESSION['user_id']]);

    header("Location: dashboard.php");
    exit();
}
?>
