<?php include('../templates/header.php'); ?>
<form action="validate_register.php" method="post">
    <label for="nombre_completo">Nombre Completo:</label>
    <input type="text" id="nombre_completo" name="nombre_completo" required>
    <label for="tipo_documento">Tipo de Documento:</label>
    <input type="text" id="tipo_documento" name="tipo_documento" required>
    <label for="numero_documento">Número de Documento:</label>
    <input type="text" id="numero_documento" name="numero_documento" required>
    <label for="correo_electronico">Correo Electrónico:</label>
    <input type="email" id="correo_electronico" name="correo_electronico" required>
    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required>
    <label for="rol">Rol:</label>
    <select id="rol" name="rol" required>
        <option value="1">Admin</option>
        <option value="2">Paciente</option>
        <option value="3">Doctor</option>
    </select>
    <button type="submit">Registrarse</button>
</form>
<p><a href="../src/login.php">Iniciar Sesión</a></p>
<?php include('../templates/footer.php'); ?>
