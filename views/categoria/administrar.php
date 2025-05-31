<h1>Administar categorias</h1>

<a href="<?=BASE_URL?>categoria/crear">
    <button class="boton more-margin">
        Crear Categoría
    </button>
</a>

<h2>Listado de Categorías</h2>

<?php if (isset($categorias) && count($categorias) > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= htmlspecialchars($categoria['id']) ?></td>
                    <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay categorías para mostrar.</p>
<?php endif; ?>