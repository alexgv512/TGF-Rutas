<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\destacadas.php

require_once __DIR__ . '/../../models/Ruta.php';

use models\Ruta;
?>

<div class="container mt-5">
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="display-4">Encuentra tu próxima aventura</h1>
            <p class="lead">Descubre las mejores rutas para viajar en coche o moto, comparte tus experiencias y conecta con otros entusiastas.</p>
            <?php if (!isset($_SESSION['identity'])): ?>
                <a href="<?= BASE_URL ?>usuario/registrarse" class="btn btn-modern-primary mr-2">
                    <i class="fas fa-user-plus mr-2"></i>Regístrate
                </a>
                <a href="<?= BASE_URL ?>usuario/login" class="btn btn-modern-secondary">
                    <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>ruta/crear" class="btn btn-modern-primary mr-2">
                    <i class="fas fa-plus mr-2"></i>Crear Ruta
                </a>
                <a href="<?= BASE_URL ?>ruta/explorar" class="btn btn-modern-secondary">
                    <i class="fas fa-compass mr-2"></i>Explorar Rutas
                </a>
            <?php endif; ?>
        </div>
    </div>

    <h2 class="mb-4">Rutas Destacadas</h2>
    
    <?php if (empty($rutas)): ?>
        <div class="alert alert-info">
            No hay rutas destacadas en este momento. ¡Sé el primero en crear una!
        </div>
    <?php else: ?>        <div class="row">
            <?php foreach ($rutas as $ruta): ?>
                <div class="col-md-4 mb-4">
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
                                </div>                                <div>
                                    <?php
                                    $valoracion = isset($ruta['promedio_valoracion']) ? round($ruta['promedio_valoracion'], 1) : 0;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $valoracion) {
                                            echo '<i class="fas fa-star text-warning"></i>';
                                        } elseif ($i - 0.5 <= $valoracion) {
                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                        } else {
                                            echo '<i class="far fa-star text-warning"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>                        <div class="card-footer bg-transparent">
                            <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" class="btn btn-sm btn-modern-primary">
                                <i class="fas fa-eye mr-1"></i>Ver Detalles
                            </a>
                            <?php 
                            $medios = Ruta::getMediosRuta($ruta['id']);
                            foreach ($medios as $medio): ?>
                                <span class="badge badge-secondary"><?= htmlspecialchars($medio['nombre']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>    <div class="text-center mt-4 mb-5">
        <a href="<?= BASE_URL ?>ruta/explorar" class="btn btn-lg btn-modern-primary">
            <i class="fas fa-compass mr-2"></i>Ver Todas las Rutas
        </a>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card modern-card text-center mb-4">
                <div class="card-body">
                    <i class="fas fa-map-marked-alt fa-3x mb-3" style="color: var(--primary-color);"></i>
                    <h5 class="card-title">Crea y comparte rutas</h5>
                    <p class="card-text">Diseña tus propios recorridos, añade puntos de interés y compártelos con la comunidad.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card modern-card text-center mb-4">
                <div class="card-body">
                    <i class="fas fa-car fa-3x mb-3" style="color: var(--primary-color);"></i>
                    <h5 class="card-title">Para coche y moto</h5>
                    <p class="card-text">Encuentra rutas específicas para tu tipo de vehículo y disfruta al máximo de cada viaje.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card modern-card text-center mb-4">
                <div class="card-body">
                    <i class="fas fa-download fa-3x mb-3" style="color: var(--primary-color);"></i>
                    <h5 class="card-title">Exporta en GPX/KML</h5>
                    <p class="card-text">Descarga las rutas en formatos compatibles con dispositivos GPS y aplicaciones de navegación.</p>
                </div>
            </div>
        </div>
    </div>
</div>
