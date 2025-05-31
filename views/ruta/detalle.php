<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\detalle.php

require_once __DIR__ . '/../../models/Ruta.php';
require_once __DIR__ . '/../../models/Valoracion.php';
require_once __DIR__ . '/../../models/Usuario.php';

use models\Ruta;
use models\Valoracion;
use models\Usuario;
?>

<div class="container mt-4">
    <?php if (isset($ruta) && $ruta): ?>
        <div class="row">
            <div class="col-md-8">
                <h1><?= htmlspecialchars($ruta['nombre']) ?></h1>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="badge badge-primary"><?= htmlspecialchars($ruta['dificultad']) ?></span>
                        <span class="badge badge-info"><?= number_format($ruta['longitud'], 2) ?> km</span>
                    </div>
                    
                    <div class="mr-3">
                        <?php
                        $valoracionInfo = Valoracion::getPromedioRuta($ruta['id']);
                        $valoracion = isset($valoracionInfo['promedio']) ? round($valoracionInfo['promedio'], 1) : 0;
                        $totalValoraciones = isset($valoracionInfo['total']) ? $valoracionInfo['total'] : 0;
                        
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $valoracion) {
                                echo '<i class="fas fa-star text-warning"></i>';
                            } elseif ($i - 0.5 <= $valoracion) {
                                echo '<i class="fas fa-star-half-alt text-warning"></i>';
                            } else {
                                echo '<i class="far fa-star text-warning"></i>';
                            }
                        }
                        echo " <small>($totalValoraciones valoraciones)</small>";
                        ?>
                    </div>
                    
                    <div>
                        <?php
                        $medios = Ruta::getMediosRuta($ruta['id']);
                        foreach ($medios as $medio): ?>
                            <span class="badge badge-secondary"><?= htmlspecialchars($medio['nombre']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Descripción</h5>
                    </div>
                    <div class="card-body">
                        <p><?= nl2br(htmlspecialchars($ruta['descripcion'])) ?></p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Mapa de la Ruta</h5>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                        <div class="mt-3">
                            <a href="#" class="btn btn-sm btn-outline-secondary mr-2">
                                <i class="fas fa-download"></i> Descargar GPX
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download"></i> Descargar KML
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de valoraciones -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Valoraciones y Comentarios</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['identity'])): ?>
                            <div class="mb-4">
                                <h6>Deja tu valoración</h6>
                                <form action="<?= BASE_URL ?>valoracion/guardar" method="POST">
                                    <input type="hidden" name="ruta_id" value="<?= $ruta['id'] ?>">
                                    
                                    <div class="form-group">
                                        <label>Puntuación:</label>
                                        <div class="rating">
                                            <input type="radio" id="star5" name="valoracion" value="5" required>
                                            <label for="star5" title="5 estrellas"></label>
                                            <input type="radio" id="star4" name="valoracion" value="4">
                                            <label for="star4" title="4 estrellas"></label>
                                            <input type="radio" id="star3" name="valoracion" value="3">
                                            <label for="star3" title="3 estrellas"></label>
                                            <input type="radio" id="star2" name="valoracion" value="2">
                                            <label for="star2" title="2 estrellas"></label>
                                            <input type="radio" id="star1" name="valoracion" value="1">
                                            <label for="star1" title="1 estrella"></label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="comentario">Comentario:</label>
                                        <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Enviar valoración</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-4">
                                <a href="<?= BASE_URL ?>usuario/login">Inicia sesión</a> para dejar tu valoración y comentario.
                            </div>
                        <?php endif; ?>
                        
                        <h6>Comentarios de la comunidad</h6>
                        <?php
                        $valoraciones = Valoracion::findByRutaId($ruta['id']);
                        if ($valoraciones && count($valoraciones) > 0):
                            foreach ($valoraciones as $valoracion):
                        ?>
                            <div class="media mb-3 p-3 border-bottom">
                                <img src="<?= !empty($valoracion['imagen']) ? BASE_URL . 'uploads/' . $valoracion['imagen'] : BASE_URL . 'assets/img/user-default.png' ?>" 
                                     class="mr-3 rounded-circle" alt="Usuario" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mt-0 mb-0"><?= htmlspecialchars($valoracion['nombre']) ?></h6>
                                        <div>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa<?= ($i <= $valoracion['valoracion']) ? 's' : 'r' ?> fa-star text-warning"></i>
                                            <?php endfor; ?>
                                            <small class="text-muted ml-2">
                                                <?= date('d/m/Y', strtotime($valoracion['fecha'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                    <p><?= nl2br(htmlspecialchars($valoracion['comentario'])) ?></p>
                                </div>
                            </div>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <div class="alert alert-light">
                                Aún no hay valoraciones para esta ruta. ¡Sé el primero en opinar!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Información adicional</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Fecha de creación:</strong> <?= date('d/m/Y', strtotime($ruta['fecha'])) ?></p>
                        
                        <?php 
                        $autor = Usuario::findById($ruta['usuario_id']);
                        if ($autor): 
                        ?>
                            <p><strong>Creada por:</strong> 
                                <a href="<?= BASE_URL ?>usuario/perfil&id=<?= $autor['id'] ?>">
                                    <?= htmlspecialchars($autor['nombre']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['identity']) && $_SESSION['identity']['id'] == $ruta['usuario_id']): ?>
                            <div class="mt-3">
                                <a href="<?= BASE_URL ?>ruta/editar&id=<?= $ruta['id'] ?>" class="btn btn-sm btn-primary mr-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= BASE_URL ?>ruta/eliminar&id=<?= $ruta['id'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta ruta?')">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Rutas similares</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($rutasSimilares) && !empty($rutasSimilares)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($rutasSimilares as $rutaSimilar): ?>
                                    <li class="list-group-item px-0">
                                        <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $rutaSimilar['id'] ?>">
                                            <?= htmlspecialchars($rutaSimilar['nombre']) ?>
                                        </a>
                                        <div class="small">
                                            <span class="text-muted"><?= number_format($rutaSimilar['longitud'], 2) ?> km</span>
                                            <span class="mx-1">·</span>
                                            <span class="text-muted"><?= htmlspecialchars($rutaSimilar['dificultad']) ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="mb-0">No hay rutas similares disponibles.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            La ruta solicitada no existe o ha sido eliminada.
            <a href="<?= BASE_URL ?>ruta/destacadas" class="alert-link">Volver a rutas destacadas</a>
        </div>
    <?php endif; ?>
</div>

<script>
    // Código para inicializar el mapa (se puede usar Google Maps o Leaflet)
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el mapa aquí cuando se implemente la API de mapas
        console.log('Mapa cargado');
    });
</script>

<style>
    /* Estilos para el sistema de valoración con estrellas */
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating input {
        display: none;
    }
    .rating label {
        color: #ddd;
        font-size: 24px;
        padding: 0 2px;
        cursor: pointer;
    }
    .rating label:before {
        content: '\f005';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
    }
    .rating input:checked ~ label {
        color: #ffc107;
    }
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
</style>
