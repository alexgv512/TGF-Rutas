<?php
// filepath: c:\xampp\htdocs\DWES2\ProyectoFinalPhp\views\medio\index.php
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Medios de Transporte</h1>
        <a href="<?= BASE_URL ?>medio/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Medio
        </a>
    </div>
    
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
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Listado de Medios de Transporte</h5>
        </div>
        <div class="card-body">
            <?php if (empty($medios)): ?>
                <div class="alert alert-info">
                    No hay medios de transporte registrados. Haz clic en "Nuevo Medio" para crear uno.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medios as $medio): ?>
                                <tr>
                                    <td><?= $medio['id'] ?></td>
                                    <td><?= htmlspecialchars($medio['nombre']) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= BASE_URL ?>medio/editar&id=<?= $medio['id'] ?>" class="btn btn-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>medio/eliminar&id=<?= $medio['id'] ?>" class="btn btn-danger" 
                                               onclick="return confirm('¿Estás seguro de que deseas eliminar este medio de transporte?')" title="Eliminar">
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
