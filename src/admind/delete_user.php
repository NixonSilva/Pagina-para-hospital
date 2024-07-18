<?php
require '../includes/funciones.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (eliminarUsuario($id)) {
        header('Location: dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit();
}
?>
