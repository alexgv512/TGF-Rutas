<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RutasMotor - La plataforma para aficionados al motor</title>    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/styles.css">
</head>
<body>    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container">
                <a class="navbar-brand font-weight-bold" href="<?=BASE_URL?>">
                    Logo
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>ruta/destacadas">Inicio Rutas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>ruta/explorar">Mis Rutas</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="rutasDropdown" role="button" data-toggle="dropdown">
                                Crear Ruta
                            </a>
                            <div class="dropdown-menu">
                                <?php if (isset($_SESSION['identity'])): ?>
                                    <a class="dropdown-item" href="<?=BASE_URL?>ruta/crear">Nueva Ruta</a>
                                    <a class="dropdown-item" href="<?=BASE_URL?>ruta/explorar">Explorar Rutas</a>
                                <?php else: ?>
                                    <a class="dropdown-item" href="<?=BASE_URL?>usuario/login">Iniciar Sesión</a>
                                    <a class="dropdown-item" href="<?=BASE_URL?>usuario/registrarse">Registrarse</a>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <button class="btn btn-outline-dark btn-sm">Menú</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav></header>
    
    <main class="<?= (!isset($_GET['controller']) && !isset($_GET['action'])) ? 'homepage-main' : 'py-4' ?>"><?= (!isset($_GET['controller']) && !isset($_GET['action'])) ? '' : '' ?>