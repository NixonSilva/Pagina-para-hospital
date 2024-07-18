<?php
session_start();
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../login.php");
    exit();
}
require '../includes/funciones.php';

include('../../templates/header.php');
$usuarios = obtenerUsuarios();
?>
<h1>Bienvenido, Administrador</h1>
<p>Este es tu dashboard.</p>
<div">
    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo $usuario['nombre_completo']; ?></td>
                        <td><?php echo $usuario['correo_electronico']; ?></td>
                        <td><?php echo $usuario['rol_id']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="delete_user.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>        
    </div>
    <br>
<a href="../logout.php">Cerrar Sesión</a>
<?php include('../../templates/footer.php'); ?>
