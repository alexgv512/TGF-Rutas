

    </main>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><i class="fas fa-road mr-2"></i>RutasMotor</h5>
                <p class="text-muted">La plataforma para los aficionados al motor que buscan experiencias de conducción memorables.</p>
                <div class="social-icons">
                    <a href="#" class="text-white mr-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white mr-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white mr-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="<?=BASE_URL?>ruta/destacadas" class="text-light">Inicio</a></li>
                    <li><a href="<?=BASE_URL?>ruta/explorar" class="text-light">Explorar Rutas</a></li>
                    <li><a href="<?=BASE_URL?>ruta/crear" class="text-light">Crear Ruta</a></li>
                    <li><a href="#" class="text-light">Acerca de Nosotros</a></li>
                    <li><a href="#" class="text-light">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contacto</h5>
                <address class="mb-0 text-muted">
                    <p><i class="fas fa-map-marker-alt mr-2"></i> Cádiz, España</p>
                    <p><i class="fas fa-envelope mr-2"></i> info@rutasmotor.com</p>
                    <p><i class="fas fa-phone mr-2"></i> +34 956 123 456</p>
                </address>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> RutasMotor - Todos los derechos reservados | Desarrollado por Alejandro Galán Varo</p>
        </div>
    </div>
</footer>

<!-- JavaScript Bootstrap y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<!-- Scripts personalizados -->
<script src="<?=BASE_URL?>assets/js/main.js"></script>
<script src="<?=BASE_URL?>assets/js/maps.js"></script>
</body>
</html>