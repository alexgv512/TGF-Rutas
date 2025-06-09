<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\destacadas.php

require_once __DIR__ . '/../../models/Ruta.php';

use models\Ruta;
?>

<!-- Hero Section Principal -->
<div class="main-hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Bienvenido a nuestro<br>Sistema de Gestión<br>de Rutas</h1>
                    <p class="hero-subtitle">Explora y crea rutas de manera sencilla. Únete a nuestra comunidad y comparte tus experiencias.</p>
                    <div class="hero-buttons">
                        <?php if (!isset($_SESSION['identity'])): ?>
                            <a href="<?= BASE_URL ?>usuario/login" class="btn btn-hero-primary">Login</a>
                            <a href="<?= BASE_URL ?>usuario/registrarse" class="btn btn-hero-secondary">Registrarse</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>ruta/crear" class="btn btn-hero-primary">Crear Ruta</a>
                            <a href="<?= BASE_URL ?>ruta/explorar" class="btn btn-hero-secondary">Explorar Rutas</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="hero-placeholder">
                        <i class="fas fa-image"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Sección de Rutas Populares -->
<div class="popular-routes-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Descubre las rutas más populares del momento</h2>
            <p class="section-subtitle">Explora las rutas que han cautivado a nuestros usuarios. ¡Encuentra la aventura perfecta para ti!</p>
        </div>
        
        <div class="row">
            <?php if (!empty($rutas)): ?>
                <?php $count = 0; ?>
                <?php foreach ($rutas as $ruta): ?>
                    <?php if ($count >= 3) break; ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="route-card">
                            <div class="route-card-image">
                                <div class="route-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <div class="route-card-content">
                                <h5 class="route-card-title"><?= htmlspecialchars($ruta['nombre']) ?></h5>
                                <p class="route-card-description">
                                    <?= substr(htmlspecialchars($ruta['descripcion']), 0, 80) . (strlen($ruta['descripcion']) > 80 ? '...' : '') ?>
                                </p>
                                <div class="route-card-meta">
                                    <span class="route-difficulty"><?= htmlspecialchars($ruta['dificultad']) ?></span>
                                    <span class="route-distance"><?= number_format($ruta['longitud'], 1) ?> km</span>
                                </div>
                                <div class="route-card-rating">
                                    <?php
                                    $valoracion = isset($ruta['promedio_valoracion']) ? round($ruta['promedio_valoracion'], 1) : 0;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $valoracion) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i - 0.5 <= $valoracion) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                    <span class="rating-value"><?= $valoracion ?></span>
                                </div>
                                <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" class="btn btn-route-detail">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                    <?php $count++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mostrar cards de placeholder cuando no hay rutas -->
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="route-card">
                            <div class="route-card-image">
                                <div class="route-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <div class="route-card-content">
                                <h5 class="route-card-title">Rutas <?= $i ?></h5>
                                <p class="route-card-description">
                                    Próximamente encontrarás aquí increíbles rutas para explorar. ¡Mantente atento!
                                </p>
                                <div class="route-card-meta">
                                    <span class="route-difficulty">Próximamente</span>
                                    <span class="route-distance">-- km</span>
                                </div>
                                <div class="route-card-rating">
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <span class="rating-value">--</span>
                                </div>
                                <?php if (isset($_SESSION['identity'])): ?>
                                    <a href="<?= BASE_URL ?>ruta/crear" class="btn btn-route-detail">Crear Primera Ruta</a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>usuario/registrarse" class="btn btn-route-detail">Únete Ahora</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>ruta/explorar" class="btn btn-explore-all">
                <i class="fas fa-compass mr-2"></i>Explorar Todas las Rutas
            </a>
        </div>
    </div>
</div>
