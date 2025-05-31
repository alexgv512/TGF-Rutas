<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\editar.php

require_once __DIR__ . '/../../models/Ruta.php';
require_once __DIR__ . '/../../models/Medio.php';

use models\Ruta;
use models\Medio;
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit mr-2"></i>Editar Ruta: <?= htmlspecialchars($ruta['nombre']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['errores'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errores'] as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php unset($_SESSION['errores']); ?>
                        </div>
                    <?php endif; ?>
                      <form action="<?= BASE_URL ?>ruta/actualizar" method="POST" id="rutaForm">
                        <input type="hidden" name="id" value="<?= $ruta['id'] ?>">
                        
                        <div class="form-group">
                            <label for="nombre">Nombre de la ruta *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($ruta['nombre']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($ruta['descripcion']) ?></textarea>
                            <small class="form-text text-muted">Describe la ruta, puntos de interés, consejos, etc.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="longitud">Longitud (km) *</label>
                            <input type="number" class="form-control" id="longitud" name="longitud" step="0.01" min="0" required
                                   value="<?= htmlspecialchars($ruta['longitud']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="dificultad">Dificultad *</label>
                            <select class="form-control" id="dificultad" name="dificultad" required>
                                <option value="">Seleccionar dificultad</option>
                                <option value="Fácil" <?= ($ruta['dificultad'] == 'Fácil') ? 'selected' : '' ?>>Fácil</option>
                                <option value="Media" <?= ($ruta['dificultad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                                <option value="Difícil" <?= ($ruta['dificultad'] == 'Difícil') ? 'selected' : '' ?>>Difícil</option>
                                <option value="Extrema" <?= ($ruta['dificultad'] == 'Extrema') ? 'selected' : '' ?>>Extrema</option>
                            </select>
                        </div>
                        
                        <div class="form-group">                            <label>Medios de transporte recomendados *</label>
                            <div class="row">
                                <?php 
                                // Verificar si tenemos medios disponibles
                                if (!isset($medios) || empty($medios)) {
                                    try {
                                        $medios = models\Medio::findAll();
                                    } catch (Exception $e) {
                                        $medios = [];
                                    }
                                }
                                
                                // Obtener medios de la ruta actual
                                $mediosRuta = models\Ruta::getMediosRuta($ruta['id']);
                                $mediosRutaIds = array_map(function($medio) {
                                    return $medio['id'];
                                }, $mediosRuta);

                                if (!empty($medios) && is_array($medios)):
                                    foreach ($medios as $medio): 
                                ?>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                   id="medio_<?= $medio['id'] ?>" 
                                                   name="medios[]" 
                                                   value="<?= $medio['id'] ?>"
                                                   <?= (in_array($medio['id'], $mediosRutaIds)) ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="medio_<?= $medio['id'] ?>">
                                                <?= htmlspecialchars($medio['nombre']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            No hay medios de transporte configurados. 
                                            <?php if (isset($_SESSION['identity']) && $_SESSION['identity']['rol'] == 'admin'): ?>
                                                <a href="<?= BASE_URL ?>medio/crear" class="alert-link">Crear medios de transporte</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <small class="form-text text-muted">Selecciona al menos un medio de transporte</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Mapa de la ruta</label>
                            <div id="map" style="height: 400px; width: 100%;"></div>
                            <small class="form-text text-muted">Haz clic en el mapa para trazar la ruta</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save mr-2"></i>Actualizar Ruta
                            </button>
                            <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" 
                               class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <a href="<?= BASE_URL ?>ruta/eliminar&id=<?= $ruta['id'] ?>" 
                               class="btn btn-danger ml-2" 
                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta ruta?')">
                                <i class="fas fa-trash mr-2"></i>Eliminar Ruta
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar ruta existente en el mapa cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    // Aquí se podría cargar la ruta existente si tuviéramos coordenadas guardadas
    // Por ahora, simplemente inicializar el mapa vacío para permitir edición
    setTimeout(function() {
        if (typeof initializeMap === 'function') {
            // El mapa ya está inicializado por maps.js
            console.log('Mapa listo para edición de ruta');
        }
    }, 500);
});
</script>
