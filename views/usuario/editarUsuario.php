<h1>Editar Usuario</h1>

<form action="<?= BASE_URL ?>usuario/actualizarUsuario" method="POST">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required>
    <br>
    <label for="apellidos">Apellidos:</label>
    <input type="text" name="apellidos" value="<?= $usuario['apellidos'] ?>" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= $usuario['email'] ?>" required>
    <br>
    <label for="rol">Rol:</label>
    <select name="rol" required>
        <option value="usuario" <?= $usuario['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
        <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>
    <br>
    <input type="submit" value="Actualizar">
</form>