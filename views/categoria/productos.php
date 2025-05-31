<h1>Productos de la Categoría</h1>

<?php if (isset($productos) && count($productos) > 0): ?>
    <div id="productos-recomendados">
        <?php foreach ($productos as $producto): ?>
            <div class="product">
                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
                <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                <p><?= htmlspecialchars($producto['precio']) ?>€</p>
                <a href="<?= BASE_URL ?>producto/detalle&id=<?= htmlspecialchars($producto['id']) ?>">Añadir al Carrito</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No hay productos para mostrar en esta categoría.</p>
<?php endif; ?>