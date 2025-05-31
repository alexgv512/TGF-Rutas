<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\medio\crear.php
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?= isset($medio) ? 'Editar Medio de Transporte' : 'Nuevo Medio de Transporte' ?></h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>medio/guardar" method="POST">
                        <?php if (isset($medio) && $medio): ?>
                            <input type="hidden" name="id" value="<?= $medio['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                   value="<?= isset($medio) ? htmlspecialchars($medio['nombre']) : '' ?>">
                            <small class="form-text text-muted">Ejemplo: Coche deportivo, Moto de carretera, SUV, etc.</small>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <?= isset($medio) ? 'Actualizar' : 'Guardar' ?>
                            </button>
                            <a href="<?= BASE_URL ?>medio/index" class="btn btn-secondary ml-2">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
