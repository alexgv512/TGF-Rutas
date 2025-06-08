<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card modern-card">
                <div class="card-header-modern">
                    <h4 class="mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Crear Categoría
                    </h4>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($mensaje)): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showSuccessAlert('<?= addslashes($mensaje) ?>');
                            });
                        </script>
                    <?php endif; ?>
                    
                    <form action="<?=BASE_URL?>categoria/guardar" method="POST" id="categoryForm">
                        <div class="form-group-modern">
                            <label for="nombre">Nombre de la categoría *</label>
                            <input type="text" class="form-control form-control-modern" id="nombre" name="nombre" 
                                   placeholder="Ej: Turismo, Aventura, etc." required>
                        </div>
                        
                        <div class="form-group-modern">
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="fas fa-save mr-2"></i>Crear Categoría
                            </button>
                            <a href="<?= BASE_URL ?>categoria/administrar" class="btn btn-modern-secondary ml-2">
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
    const categoryForm = document.getElementById('categoryForm');
    
    categoryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nombre = document.getElementById('nombre').value.trim();
        
        if (!nombre) {
            showErrorAlert('El nombre de la categoría es obligatorio');
            return;
        }
        
        if (nombre.length < 2) {
            showErrorAlert('El nombre debe tener al menos 2 caracteres');
            return;
        }
        
        // Confirmar creación
        confirmAction(
            'Crear Categoría',
            `¿Estás seguro de que quieres crear la categoría "${nombre}"?`,
            'Sí, crear',
            () => {
                showLoadingAlert('Creando categoría...');
                categoryForm.submit();
            }
        );
    });
});
</script>