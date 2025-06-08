<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\usuario\perfil.php
?>

<div class="container mt-4">
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
    
    <?php if (isset($_SESSION['identity'])): ?>
        <div class="row profile-header align-items-center">
            <div class="col-md-3 text-center">
                <img src="<?= !empty($_SESSION['identity']['imagen']) ? BASE_URL . 'uploads/' . $_SESSION['identity']['imagen'] : BASE_URL . 'assets/img/user-default.png' ?>" 
                     alt="Foto de perfil" class="profile-avatar">
            </div>
            <div class="col-md-9">                <h2><?= htmlspecialchars($_SESSION['identity']['nombre'] . ' ' . $_SESSION['identity']['apellidos']) ?></h2>
                <p class="text-muted">
                    <i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($_SESSION['identity']['email']) ?>
                </p>
                <a href="<?= BASE_URL ?>usuario/editar" class="btn btn-modern-secondary">
                    <i class="fas fa-edit mr-2"></i>Editar Perfil
                </a>
            </div>
        </div>
        
        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="rutas-tab" data-toggle="tab" href="#rutas" role="tab">
                    <i class="fas fa-route mr-2"></i>Mis Rutas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="valoraciones-tab" data-toggle="tab" href="#valoraciones" role="tab">
                    <i class="fas fa-star mr-2"></i>Mis Valoraciones
                </a>
            </li>
        </ul>
        
        <div class="tab-content" id="profileTabsContent">
            <div class="tab-pane fade show active" id="rutas" role="tabpanel">                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Mis Rutas</h3>
                    <a href="<?= BASE_URL ?>ruta/crear" class="btn btn-modern-primary">
                        <i class="fas fa-plus mr-2"></i>Nueva Ruta
                    </a>
                </div>
                
                <?php if (empty($rutas)): ?>
                    <div class="alert alert-info">
                        Aún no has creado ninguna ruta. ¡Crea tu primera ruta con el botón de arriba!
                    </div>
                <?php else: ?>                    <div class="row">
                        <?php foreach ($rutas as $ruta): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card modern-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($ruta['nombre']) ?></h5>
                                        <p class="card-text">
                                            <?= substr(htmlspecialchars($ruta['descripcion']), 0, 100) . (strlen($ruta['descripcion']) > 100 ? '...' : '') ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge badge-primary"><?= htmlspecialchars($ruta['dificultad']) ?></span>
                                                <span class="badge badge-info"><?= number_format($ruta['longitud'], 2) ?> km</span>
                                            </div>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($ruta['fecha'])) ?></small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" class="btn btn-sm btn-modern-primary">Ver Detalles</a>
                                        <a href="<?= BASE_URL ?>ruta/editar&id=<?= $ruta['id'] ?>" class="btn btn-sm btn-modern-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteRoute(<?= $ruta['id'] ?>)" class="btn btn-sm btn-modern-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade" id="valoraciones" role="tabpanel">
                <h3 class="mb-3">Mis Valoraciones</h3>
                
                <?php if (empty($valoraciones)): ?>
                    <div class="alert alert-info">
                        Aún no has valorado ninguna ruta. <a href="<?= BASE_URL ?>ruta/explorar" class="alert-link">¡Explora rutas para valorar!</a>
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($valoraciones as $valoracion): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h5 class="mb-1">
                                        <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $valoracion['ruta_id'] ?>">
                                            <?= htmlspecialchars($valoracion['nombre_ruta']) ?>
                                        </a>
                                    </h5>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa<?= ($i <= $valoracion['valoracion']) ? 's' : 'r' ?> fa-star text-warning"></i>
                                        <?php endfor; ?>
                                        <small class="text-muted ml-2"><?= date('d/m/Y', strtotime($valoracion['fecha'])) ?></small>
                                    </div>
                                </div>
                                <?php if (!empty($valoracion['comentario'])): ?>
                                    <p class="mb-1 mt-2"><?= nl2br(htmlspecialchars($valoracion['comentario'])) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>        <div class="alert alert-danger">
            No has iniciado sesión.
            <a href="<?= BASE_URL ?>usuario/login" class="alert-link">Inicia sesión</a> para ver tu perfil.
        </div>
    <?php endif; ?>
</div>

<script>
function deleteRoute(routeId) {
    confirmAction(
        'Eliminar Ruta',
        '¿Estás seguro de que quieres eliminar esta ruta? Esta acción no se puede deshacer.',
        'Sí, eliminar',
        () => {
            showLoadingAlert('Eliminando ruta...');
            window.location.href = `<?= BASE_URL ?>ruta/eliminar&id=${routeId}`;
        },
        'question'
    );
}
</script>