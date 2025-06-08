
    <?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\usuario\registrarse.php
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card modern-card">
                <div class="card-header-modern">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Registro de Usuario
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
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showSuccessAlert('<?= addslashes($_SESSION['success']) ?>');
                            });
                        </script>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                      <form action="<?= BASE_URL ?>usuario/registrarUsuario" method="POST" enctype="multipart/form-data" id="registerForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="nombre">Nombre *</label>
                                    <input type="text" class="form-control form-control-modern" id="nombre" name="nombre" required
                                           placeholder="Tu nombre">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" class="form-control form-control-modern" id="apellidos" name="apellidos"
                                           placeholder="Tus apellidos">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control form-control-modern" id="email" name="email" required
                                   placeholder="ejemplo@correo.com">                            <small class="form-text text-muted">Tu correo electrónico será tu nombre de usuario</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="password">Contraseña *</label>
                                    <input type="password" class="form-control form-control-modern" id="password" name="password" required
                                           placeholder="Contraseña">
                                    <small class="form-text text-muted">Mínimo 6 caracteres</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="password2">Confirmar Contraseña *</label>
                                    <input type="password" class="form-control form-control-modern" id="password2" name="password2" required
                                           placeholder="Confirma tu contraseña">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="imagen">Foto de Perfil</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="imagen" name="imagen" accept="image/*">
                                <label class="custom-file-label" for="imagen">Seleccionar imagen</label>
                            </div>
                            <small class="form-text text-muted">Imagen opcional para tu perfil (máx. 2MB)</small>
                        </div>
                        
                        <div class="form-group-modern">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="terminos" name="terminos" required>
                                <label class="custom-control-label" for="terminos">
                                    Acepto los <a href="#" data-toggle="modal" data-target="#terminosModal">términos y condiciones</a> *
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group-modern">
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="fas fa-user-plus mr-2"></i>Registrarse
                            </button>
                        </div>
                    </form>                </div>
                <div class="card-footer-modern">
                    <div class="text-center">
                        ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>usuario/login" class="link-modern">Inicia sesión aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="terminosModal" tabindex="-1" role="dialog" aria-labelledby="terminosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="terminosModalLabel">Términos y Condiciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>1. Aceptación de los términos</h6>
                <p>Al registrarte en RutasMotor, aceptas los siguientes términos y condiciones que rigen el uso de nuestra plataforma.</p>
                
                <h6>2. Uso de la plataforma</h6>
                <p>RutasMotor es una plataforma para compartir y descubrir rutas para vehículos. Los usuarios son responsables de la información que comparten.</p>
                
                <h6>3. Contenido del usuario</h6>
                <p>Al subir contenido a RutasMotor, garantizas que tienes los derechos necesarios sobre dicho contenido y otorgas a RutasMotor una licencia no exclusiva para utilizarlo.</p>
                
                <h6>4. Seguridad en carretera</h6>
                <p>RutasMotor no se hace responsable de accidentes o incidentes que puedan ocurrir durante el uso de las rutas compartidas. Los usuarios deben respetar las normas de tráfico y conducir de manera segura en todo momento.</p>
                
                <h6>5. Privacidad</h6>
                <p>Consulta nuestra política de privacidad para obtener información sobre cómo recopilamos, utilizamos y protegemos tus datos personales.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Script para mostrar el nombre del archivo seleccionado en el input file
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var fileName = this.files[0].name;
            var nextSibling = this.nextElementSibling;
            nextSibling.innerText = fileName;
        });
        
        // Validación del formulario con SweetAlert2
        const registerForm = document.getElementById('registerForm');
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const password2 = document.getElementById('password2').value;
            const terminos = document.getElementById('terminos').checked;
            
            // Validar contraseñas
            if (password !== password2) {
                showErrorAlert('Las contraseñas no coinciden');
                return;
            }
            
            if (password.length < 6) {
                showErrorAlert('La contraseña debe tener al menos 6 caracteres');
                return;
            }
            
            // Validar términos
            if (!terminos) {
                showErrorAlert('Debes aceptar los términos y condiciones');
                return;
            }
            
            // Confirmar registro
            confirmAction(
                'Confirmar Registro',
                '¿Estás seguro de que quieres crear esta cuenta?',
                'Sí, registrarme',
                () => {
                    showLoadingAlert('Registrando usuario...');
                    registerForm.submit();
                }
            );
        });
    });
</script>

