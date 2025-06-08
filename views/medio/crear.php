<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\medio\crear.php
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card modern-card">
                <div class="card-header-modern">
                    <h4 class="mb-0">
                        <i class="fas fa-car me-2"></i>
                        <?= isset($medio) ? 'Editar Medio de Transporte' : 'Nuevo Medio de Transporte' ?>
                    </h4>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error'])): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showErrorAlert('<?= addslashes($_SESSION['error']) ?>');
                            });
                        </script>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>medio/guardar" method="POST" id="medioForm">
                        <?php if (isset($medio) && $medio): ?>
                            <input type="hidden" name="id" value="<?= $medio['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-group-modern">
                            <label for="nombre">Nombre *</label>
                            <input type="text" class="form-control form-control-modern" id="nombre" name="nombre" required
                                   value="<?= isset($medio) ? htmlspecialchars(string: $medio['nombre']) : '' ?>"
                                   placeholder="Ej: Coche deportivo, Moto de carretera, etc.">
                            <small class="form-text text-muted">Ejemplo: Coche deportivo, Moto de carretera, SUV, etc.</small>
                        </div>
                        
                        <div class="form-group-modern">
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="fas fa-save mr-2"></i>
                                <?= isset($medio) ? 'Actualizar' : 'Guardar' ?>
                            </button>
                            <a href="<?= BASE_URL ?>medio/index" class="btn btn-modern-secondary ml-2">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const medioForm = document.getElementById('medioForm');
    
    medioForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nombre = document.getElementById('nombre').value.trim();
        
        if (!nombre) {
            showErrorAlert('El nombre del medio de transporte es obligatorio');
            return;
        }
        
        if (nombre.length < 2) {
            showErrorAlert('El nombre debe tener al menos 2 caracteres');
            return;
        }
        
        // Confirmar acción
        const isEdit = <?= isset($medio) ? 'true' : 'false' ?>;
        const action = isEdit ? 'actualizar' : 'crear';
        const title = isEdit ? 'Actualizar Medio' : 'Crear Medio';
        const text = `¿Estás seguro de que quieres ${action} este medio de transporte?`;
        const confirmText = isEdit ? 'Sí, actualizar' : 'Sí, crear';
        
        confirmAction(
            title,
            text,
            confirmText,
            () => {
                showLoadingAlert(`${isEdit ? 'Actualizando' : 'Creando'} medio de transporte...`);
                medioForm.submit();
            }
        );
    });
});
</script>
