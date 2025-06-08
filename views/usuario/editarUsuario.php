<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card modern-card">
                <div class="card-header-modern">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Editar Usuario
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>usuario/actualizarUsuario" method="POST" id="editUserForm">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        
                        <div class="form-group-modern">
                            <label for="nombre">Nombre *</label>
                            <input type="text" class="form-control form-control-modern" id="nombre" name="nombre" 
                                   value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="apellidos">Apellidos *</label>
                            <input type="text" class="form-control form-control-modern" id="apellidos" name="apellidos" 
                                   value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control form-control-modern" id="email" name="email" 
                                   value="<?= htmlspecialchars($usuario['email']) ?>" required>
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="rol">Rol *</label>
                            <select class="form-control form-control-modern" id="rol" name="rol" required>
                                <option value="usuario" <?= $usuario['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>
                        
                        <div class="form-group-modern">
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="fas fa-save mr-2"></i>Actualizar Usuario
                            </button>
                            <a href="<?= BASE_URL ?>usuario/administrarAdmin" class="btn btn-modern-secondary ml-2">
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
    const editUserForm = document.getElementById('editUserForm');
    
    editUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validaciones básicas
        const nombre = document.getElementById('nombre').value.trim();
        const apellidos = document.getElementById('apellidos').value.trim();
        const email = document.getElementById('email').value.trim();
        const rol = document.getElementById('rol').value;
        
        if (!nombre) {
            showErrorAlert('El nombre es obligatorio');
            return;
        }
        
        if (!apellidos) {
            showErrorAlert('Los apellidos son obligatorios');
            return;
        }
        
        if (!email) {
            showErrorAlert('El email es obligatorio');
            return;
        }
        
        if (!rol) {
            showErrorAlert('Debes seleccionar un rol');
            return;
        }
        
        // Confirmar actualización
        confirmAction(
            'Actualizar Usuario',
            '¿Estás seguro de que quieres actualizar este usuario?',
            'Sí, actualizar',
            () => {
                showLoadingAlert('Actualizando usuario...');
                editUserForm.submit();
            }
        );
    });
});
</script>