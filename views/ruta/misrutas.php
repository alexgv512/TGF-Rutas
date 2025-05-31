<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\misrutas.php

require_once __DIR__ . '/../../models/Ruta.php';
require_once __DIR__ . '/../../models/Valoracion.php';

use models\Ruta;
use models\Valoracion;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Rutas</h2>
        <a href="<?= BASE_URL ?>ruta/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nueva Ruta
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['ruta_creada'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['ruta_creada'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['ruta_creada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['ruta_actualizada'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['ruta_actualizada'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['ruta_actualizada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['ruta_eliminada'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['ruta_eliminada'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['ruta_eliminada']); ?>
    <?php endif; ?>

    <?php if (empty($rutas)): ?>
        <div class="alert alert-info text-center">
            <h4>¡Aún no has creado ninguna ruta!</h4>
            <p>Comparte tus aventuras y experiencias creando tu primera ruta.</p>
            <a href="<?= BASE_URL ?>ruta/crear" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Mi Primera Ruta
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($rutas as $ruta): ?>
                <?php
                // Obtener valoración promedio y número de valoraciones
                $promedio = Valoracion::getPromedioRuta($ruta['id']);
                $numValoraciones = Valoracion::getNumeroValoracionesRuta($ruta['id']);
                $medios = Ruta::getMediosRuta($ruta['id']);
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($ruta['nombre']) ?></h5>
                            <p class="card-text">
                                <?= substr(htmlspecialchars($ruta['descripcion']), 0, 100) . (strlen($ruta['descripcion']) > 100 ? '...' : '') ?>
                            </p>
                            
                            <div class="mb-2">
                                <span class="badge badge-primary"><?= htmlspecialchars($ruta['dificultad']) ?></span>
                                <span class="badge badge-info"><?= number_format($ruta['longitud'], 2) ?> km</span>
                                <?php if ($promedio > 0): ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-star"></i> <?= number_format($promedio, 1) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="mb-2">
                                <?php foreach ($medios as $medio): ?>
                                    <span class="badge badge-secondary"><?= htmlspecialchars($medio['nombre']) ?></span>
                                <?php endforeach; ?>
                            </div>

                            <div class="mb-2">
                                <?php if ($promedio > 0): ?>
                                    <div class="text-warning">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $promedio) {
                                                echo '<i class="fas fa-star"></i>';
                                            } elseif ($i - 0.5 <= $promedio) {
                                                echo '<i class="fas fa-star-half-alt"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                        <small class="text-muted ml-1">(<?= $numValoraciones ?> valoraciones)</small>
                                    </div>
                                <?php else: ?>
                                    <small class="text-muted">Sin valoraciones aún</small>
                                <?php endif; ?>
                            </div>

                            <small class="text-muted">
                                Creada el <?= date('d/m/Y', strtotime($ruta['fecha'])) ?>
                            </small>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" 
                                   class="btn btn-outline-primary" title="Ver detalles">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="<?= BASE_URL ?>ruta/editar&id=<?= $ruta['id'] ?>" 
                                   class="btn btn-outline-warning" title="Editar ruta">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= BASE_URL ?>ruta/exportarGPX&id=<?= $ruta['id'] ?>" 
                                   class="btn btn-outline-success" title="Exportar GPX">
                                    <i class="fas fa-download"></i> GPX
                                </a>
                                <a href="<?= BASE_URL ?>ruta/eliminar&id=<?= $ruta['id'] ?>&redirect=perfil" 
                                   class="btn btn-outline-danger" title="Eliminar ruta"
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar esta ruta? Esta acción no se puede deshacer.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas de mis rutas</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary"><?= count($rutas) ?></h4>
                                <small>Rutas creadas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <?php 
                                $totalKm = array_sum(array_column($rutas, 'longitud'));
                                ?>
                                <h4 class="text-info"><?= number_format($totalKm, 2) ?></h4>
                                <small>Kilómetros totales</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <?php 
                                $totalValoraciones = 0;
                                foreach ($rutas as $ruta) {
                                    $totalValoraciones += Valoracion::getNumeroValoracionesRuta($ruta['id']);
                                }
                                ?>
                                <h4 class="text-warning"><?= $totalValoraciones ?></h4>
                                <small>Valoraciones recibidas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <?php 
                                $promedioGeneral = 0;
                                $rutasConValoracion = 0;
                                foreach ($rutas as $ruta) {
                                    $promedio = Valoracion::getPromedioRuta($ruta['id']);
                                    if ($promedio > 0) {
                                        $promedioGeneral += $promedio;
                                        $rutasConValoracion++;
                                    }
                                }
                                $promedioGeneral = $rutasConValoracion > 0 ? $promedioGeneral / $rutasConValoracion : 0;
                                ?>
                                <h4 class="text-success"><?= $promedioGeneral > 0 ? number_format($promedioGeneral, 1) : '-' ?></h4>
                                <small>Valoración promedio</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
