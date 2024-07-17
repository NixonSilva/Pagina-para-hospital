<?php include('../templates/header.php'); ?>
<form action="validate_login.php" method="post">
    <label for="correo_electronico">Correo Electrónico:</label>
    <input type="email" id="correo_electronico" name="correo_electronico" required>
    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required>
    <button type="submit">Iniciar Sesión</button>
</form>
<p><a href="../src/register.php">Crear cuenta</a></p>
<?php include('../templates/footer.php'); ?>
