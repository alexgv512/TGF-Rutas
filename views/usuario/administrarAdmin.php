<h1>Gestión de Usuarios</h1>
  
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Eliminar y Editar</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
    <tr>
        <td><?= $usuario['id'] ?></td>
        <td><?= $usuario['nombre'] ?></td>
        <td><?= $usuario['apellidos'] ?></td>
        <td><?= $usuario['email'] ?></td>
        <td><?= $usuario['rol'] ?></td>
        <td>
            <a href="<?=BASE_URL?>usuario/eliminarUsuario&id=<?= $usuario['id'] ?>">Eliminar</a>
            <a href="<?=BASE_URL?>usuario/editar&id=<?= $usuario['id'] ?>">Editar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>