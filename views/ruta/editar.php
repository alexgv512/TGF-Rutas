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
            <div class="card modern-card">
                <div class="card-header-modern" style="background: linear-gradient(135deg, var(--warning-color), var(--secondary-color));">
                    <h4 class="mb-0 text-white">
                        <i class="fas fa-edit mr-2"></i>Editar Ruta: <?= htmlspecialchars($ruta['nombre']) ?>
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
                    
                    <?php if (isset($_SESSION['errores'])): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const errors = <?= json_encode($_SESSION['errores']) ?>;
                                showErrorAlert('Errores encontrados:<br>' + errors.join('<br>'));
                            });
                        </script>
                        <?php unset($_SESSION['errores']); ?>
                    <?php endif; ?>                      <form action="<?= BASE_URL ?>ruta/actualizar" method="POST" id="editRutaForm">
                        <input type="hidden" name="id" value="<?= $ruta['id'] ?>">
                        
                        <div class="form-group-modern">
                            <label for="nombre">Nombre de la ruta *</label>
                            <input type="text" class="form-control form-control-modern" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($ruta['nombre']) ?>">
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control form-control-modern" id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($ruta['descripcion']) ?></textarea>
                            <small class="form-text text-muted">Describe la ruta, puntos de interés, consejos, etc.</small>
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="longitud">Longitud (km) *</label>
                            <input type="number" class="form-control form-control-modern" id="longitud" name="longitud" step="0.01" min="0" required
                                   value="<?= htmlspecialchars($ruta['longitud']) ?>">
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="dificultad">Dificultad *</label>
                            <select class="form-control form-control-modern" id="dificultad" name="dificultad" required>
                                <option value="">Seleccionar dificultad</option>
                                <option value="Fácil" <?= ($ruta['dificultad'] == 'Fácil') ? 'selected' : '' ?>>Fácil</option>
                                <option value="Media" <?= ($ruta['dificultad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                                <option value="Difícil" <?= ($ruta['dificultad'] == 'Difícil') ? 'selected' : '' ?>>Difícil</option>
                                <option value="Extrema" <?= ($ruta['dificultad'] == 'Extrema') ? 'selected' : '' ?>>Extrema</option>
                            </select>
                        </div>
                        
                        <div class="form-group-modern"><label>Medios de transporte recomendados *</label>
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
                            </div>                            <small class="form-text text-muted">Selecciona al menos un medio de transporte</small>
                        </div>
                        
                        <div class="form-group-modern">
                            <label>Mapa de la ruta</label>
                            <div id="map" style="height: 400px; width: 100%;"></div>
                            <small class="form-text text-muted">Haz clic en el mapa para trazar la ruta</small>
                        </div>
                        
                        <div class="form-group-modern">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save mr-2"></i>Actualizar Ruta
                            </button>
                            <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" 
                               class="btn btn-modern-secondary ml-2">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="button" onclick="deleteRoute(<?= $ruta['id'] ?>)" 
                               class="btn btn-modern-danger ml-2">
                                <i class="fas fa-trash mr-2"></i>Eliminar Ruta
                            </button>
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
    
    // Form validation and submission with SweetAlert2
    const editRutaForm = document.getElementById('editRutaForm');
    
    editRutaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validaciones
        const nombre = document.getElementById('nombre').value.trim();
        const longitud = document.getElementById('longitud').value;
        const dificultad = document.getElementById('dificultad').value;
        const medios = document.querySelectorAll('input[name="medios[]"]:checked');
        
        if (!nombre) {
            showErrorAlert('El nombre de la ruta es obligatorio');
            return;
        }
        
        if (!longitud || longitud <= 0) {
            showErrorAlert('La longitud debe ser mayor a 0');
            return;
        }
        
        if (!dificultad) {
            showErrorAlert('Debes seleccionar una dificultad');
            return;
        }
        
        if (medios.length === 0) {
            showErrorAlert('Debes seleccionar al menos un medio de transporte');
            return;
        }
        
        // Confirmar actualización
        confirmAction(
            'Actualizar Ruta',
            '¿Estás seguro de que quieres actualizar esta ruta?',
            'Sí, actualizar',
            () => {
                showLoadingAlert('Actualizando ruta...');
                editRutaForm.submit();
            }
        );
    });
});

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
