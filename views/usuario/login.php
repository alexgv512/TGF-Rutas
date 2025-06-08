
    <?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\usuario\login.php
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0 text-center">
                        <i class="fas fa-sign-in-alt text-primary mr-2"></i>
                        Iniciar Sesión
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>usuario/entrar" method="POST" 
                          data-confirm="true" 
                          data-confirm-title="¿Iniciar sesión?" 
                          data-confirm-text="Verificaremos tus credenciales para acceder">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope text-primary mr-2"></i>Email
                            </label>
                            <input type="email" class="form-input" id="email" name="email" required
                                   placeholder="ejemplo@correo.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock text-primary mr-2"></i>Contraseña
                            </label>
                            <input type="password" class="form-input" id="password" name="password" required
                                   placeholder="Introduce tu contraseña">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <span class="text-muted">¿No tienes cuenta?</span>
                    <a href="<?= BASE_URL ?>usuario/registrarse" class="btn btn-secondary btn-sm ml-2">
                        <i class="fas fa-user-plus mr-1"></i>Regístrate aquí
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script específico para manejar mensajes de sesión en login
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['error'])): ?>
        showError('Error de autenticación', '<?= addslashes($_SESSION['error']) ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        showSuccess('¡Bienvenido!', '<?= addslashes($_SESSION['success']) ?>');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
});
</script>
    

