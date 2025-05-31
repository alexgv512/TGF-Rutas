<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RutasMotor - La plataforma para aficionados al motor</title>    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/styles.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?=BASE_URL?>">
                    <i class="fas fa-road mr-2"></i>RutasMotor
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>ruta/destacadas">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>ruta/explorar">Explorar Rutas</a>
                        </li>
                        <?php if (isset($_SESSION['identity'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL?>ruta/crear">Crear Ruta</a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <ul class="navbar-nav ml-auto">
                        <?php if (!isset($_SESSION['identity'])): ?>
                            <!-- Si la sesión no está iniciada mostramos los enlaces de registrarse y login -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL?>usuario/login">
                                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL?>usuario/registrarse">
                                    <i class="fas fa-user-plus"></i> Registrarse
                                </a>
                            </li>
                        <?php else: ?>
                            <!-- Si la sesión está iniciada -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                    <?= htmlspecialchars($_SESSION['identity']['nombre']) ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="<?=BASE_URL?>usuario/perfil">
                                        <i class="fas fa-id-card"></i> Mi Perfil
                                    </a>
                                    <a class="dropdown-item" href="<?=BASE_URL?>usuario/mis_rutas">
                                        <i class="fas fa-route"></i> Mis Rutas
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <?php if (isset($_SESSION['identity']) && $_SESSION['identity']['rol'] == 'admin'): ?>
                                        <a class="dropdown-item" href="<?=BASE_URL?>ruta/gestion">
                                            <i class="fas fa-tasks"></i> Gestión de Rutas
                                        </a>
                                        <a class="dropdown-item" href="<?=BASE_URL?>medio/index">
                                            <i class="fas fa-car"></i> Gestión de Medios
                                        </a>
                                        <a class="dropdown-item" href="<?=BASE_URL?>usuario/administrar">
                                            <i class="fas fa-users-cog"></i> Gestión de Usuarios
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <a class="dropdown-item" href="<?=BASE_URL?>usuario/cerrarsesion">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="py-4">