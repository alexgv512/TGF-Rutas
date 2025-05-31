<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\explorar.php

require_once __DIR__ . '/../../models/Ruta.php';
require_once __DIR__ . '/../../models/Medio.php';

use models\Ruta;
use models\Medio;
?>

<div class="container mt-4">
    <h1 class="mb-4">Explorar Rutas</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Filtros de búsqueda</h5>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>ruta/explorar" method="GET" class="row">
                <input type="hidden" name="buscar" value="1">
                
                <div class="form-group col-md-4">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '' ?>">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="dificultad">Dificultad</label>
                    <select class="form-control" id="dificultad" name="dificultad">
                        <option value="">Todas</option>
                        <option value="Fácil" <?= (isset($_GET['dificultad']) && $_GET['dificultad'] == 'Fácil') ? 'selected' : '' ?>>Fácil</option>
                        <option value="Media" <?= (isset($_GET['dificultad']) && $_GET['dificultad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                        <option value="Difícil" <?= (isset($_GET['dificultad']) && $_GET['dificultad'] == 'Difícil') ? 'selected' : '' ?>>Difícil</option>
                        <option value="Extrema" <?= (isset($_GET['dificultad']) && $_GET['dificultad'] == 'Extrema') ? 'selected' : '' ?>>Extrema</option>
                    </select>
                </div>
                
                <div class="form-group col-md-4">
                    <label for="medio_id">Medio de transporte</label>
                    <select class="form-control" id="medio_id" name="medio_id">
                        <option value="">Todos</option>
                        <?php foreach ($medios as $medio): ?>
                            <option value="<?= $medio['id'] ?>" <?= (isset($_GET['medio_id']) && $_GET['medio_id'] == $medio['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($medio['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group col-md-3">
                    <label for="longitud_min">Longitud mínima (km)</label>
                    <input type="number" class="form-control" id="longitud_min" name="longitud_min" min="0" step="0.1" 
                           value="<?= isset($_GET['longitud_min']) ? htmlspecialchars($_GET['longitud_min']) : '' ?>">
                </div>
                
                <div class="form-group col-md-3">
                    <label for="longitud_max">Longitud máxima (km)</label>
                    <input type="number" class="form-control" id="longitud_max" name="longitud_max" min="0" step="0.1" 
                           value="<?= isset($_GET['longitud_max']) ? htmlspecialchars($_GET['longitud_max']) : '' ?>">
                </div>
                
                <div class="form-group col-md-3">
                    <label for="valoracion_min">Valoración mínima</label>
                    <select class="form-control" id="valoracion_min" name="valoracion_min">
                        <option value="">Cualquiera</option>
                        <option value="1" <?= (isset($_GET['valoracion_min']) && $_GET['valoracion_min'] == '1') ? 'selected' : '' ?>>★</option>
                        <option value="2" <?= (isset($_GET['valoracion_min']) && $_GET['valoracion_min'] == '2') ? 'selected' : '' ?>>★★</option>
                        <option value="3" <?= (isset($_GET['valoracion_min']) && $_GET['valoracion_min'] == '3') ? 'selected' : '' ?>>★★★</option>
                        <option value="4" <?= (isset($_GET['valoracion_min']) && $_GET['valoracion_min'] == '4') ? 'selected' : '' ?>>★★★★</option>
                        <option value="5" <?= (isset($_GET['valoracion_min']) && $_GET['valoracion_min'] == '5') ? 'selected' : '' ?>>★★★★★</option>
                    </select>
                </div>
                
                <div class="form-group col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if (isset($_GET['buscar'])): ?>
        <h2 class="mb-3">Resultados de la búsqueda</h2>
        
        <?php if (empty($rutas)): ?>
            <div class="alert alert-info">
                No se encontraron rutas con los criterios especificados. Intenta con otros filtros.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($rutas as $ruta): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
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
                                    <div>
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
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" class="btn btn-sm btn-outline-primary">Ver Detalles</a>
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
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-secondary">
            Utiliza los filtros para encontrar rutas específicas o <a href="<?= BASE_URL ?>ruta/destacadas" class="alert-link">revisa las rutas destacadas</a>.
        </div>
    <?php endif; ?>
</div>
