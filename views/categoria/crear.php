<h1>Crear Categoría</h1>

<?php if (isset($mensaje)): ?>
    <p><?= htmlspecialchars($mensaje) ?></p>
<?php endif; ?>
<form action="<?=BASE_URL?>categoria/guardar" method="POST">
    <label for="nombre">Nombre de la categoría:</label>
    <input type="text" name="nombre" required>
    <input type="submit" value="Guardar">
</form>