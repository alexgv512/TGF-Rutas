<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\ruta\gestion.php

require_once __DIR__ . '/../../models/Ruta.php';
require_once __DIR__ . '/../../models/Valoracion.php';
require_once __DIR__ . '/../../models/Usuario.php';

use models\Ruta;
use models\Valoracion;
use models\Usuario;
?>

<div class="container mt-4">
    <h1>Gestión de Rutas</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Listado de Rutas</h5>
        </div>
        <div class="card-body">
            <?php if (empty($rutas)): ?>
                <div class="alert alert-info">
                    No hay rutas registradas en el sistema.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Longitud</th>
                                <th>Dificultad</th>
                                <th>Fecha</th>
                                <th>Valoración</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rutas as $ruta): ?>
                                <?php 
                                $valoracionInfo = Valoracion::getPromedioRuta($ruta['id']);
                                $valoracion = isset($valoracionInfo['promedio']) ? round($valoracionInfo['promedio'], 1) : 0;
                                $usuario = Usuario::findById($ruta['usuario_id']);
                                ?>
                                <tr>
                                    <td><?= $ruta['id'] ?></td>
                                    <td><?= htmlspecialchars($ruta['nombre']) ?></td>
                                    <td>
                                        <?php if ($usuario): ?>
                                            <a href="<?= BASE_URL ?>usuario/perfil&id=<?= $usuario['id'] ?>">
                                                <?= htmlspecialchars($usuario['nombre']) ?>
                                            </a>
                                        <?php else: ?>
                                            <em>Usuario eliminado</em>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($ruta['longitud'], 2) ?> km</td>
                                    <td><?= htmlspecialchars($ruta['dificultad']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($ruta['fecha'])) ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa<?= ($i <= $valoracion) ? 's' : 'r' ?> fa-star text-warning small"></i>
                                        <?php endfor; ?>
                                        <small>(<?= $valoracion ?>)</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= BASE_URL ?>ruta/detalle&id=<?= $ruta['id'] ?>" class="btn btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>ruta/editar&id=<?= $ruta['id'] ?>" class="btn btn-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>ruta/eliminar&id=<?= $ruta['id'] ?>" class="btn btn-danger" 
                                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta ruta?')" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
