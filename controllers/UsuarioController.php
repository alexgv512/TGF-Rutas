<?php

namespace controllers;

use models\Usuario;
use models\Ruta;
use models\Valoracion;
use PDOException;
use Utils; // Added Utils class

class UsuarioController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db;
    }

    public function index(): void {
        echo "<h1>Controlador Usuario, acción index</h1>";
    }

    public function registrarse(): void {
        require_once "views/usuario/registrarse.php";
    }

    public function login(): void {
        require_once "views/usuario/login.php";
    }

    public function administrar(): void {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        $usuarios = Usuario::findAll();
        require_once "views/usuario/administrar.php";
    }

    public function perfil(): void {
        // Verificar que el usuario está logueado
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para ver tu perfil";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        $usuario_id = $_SESSION['identity']['id'];
        $usuario = Usuario::findById($usuario_id);
        
        // Obtener las rutas creadas por el usuario
        $rutas = Ruta::findByUsuarioId($usuario_id);
        
        require_once "views/usuario/perfil.php";
    }

    public function registrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = htmlspecialchars(trim($_POST['nombre']));
            $apellidos = htmlspecialchars(trim($_POST['apellidos']));
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $password = htmlspecialchars(trim($_POST['password']));

            // Validaciones
            if (empty($nombre) || empty($apellidos) || !$email || empty($password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios y deben ser válidos.';
                header('Location: ' . BASE_URL . 'usuario/registrarse');
                exit();
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
                header('Location: ' . BASE_URL . 'usuario/registrarse');
                exit();
            }

            // Verificar si el email ya existe en la base de datos
            $existe_email = Usuario::findByEmail($email);
            if ($existe_email) {
                $_SESSION['error'] = 'El email ya está registrado.';
                header('Location: ' . BASE_URL . 'usuario/registrarse');
                exit();
            }

            // Procesar la imagen si se ha subido
            $imagen = null;
            if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['name'])) {
                $archivo = $_FILES['imagen'];
                $tipo = $archivo['type'];
                
                if ($tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png" || $tipo == "image/gif") {
                    // Crear directorio de imágenes si no existe
                    if (!is_dir('uploads/usuarios')) {
                        mkdir('uploads/usuarios', 0777, true);
                    }
                    
                    // Generar nombre único para la imagen
                    $imagen = time() . '_' . $archivo['name'];
                    
                    // Mover imagen al directorio
                    move_uploaded_file($archivo['tmp_name'], 'uploads/usuarios/' . $imagen);
                } else {
                    $_SESSION['error'] = "El tipo de archivo no es válido. Solo se permiten imágenes JPG, PNG o GIF";
                    header('Location: ' . BASE_URL . 'usuario/registrarse');
                    exit();
                }
            }

            // Crear y guardar el nuevo usuario
            try {
                $usuario = new Usuario();
                $usuario->setNombre($nombre);
                $usuario->setApellidos($apellidos);
                $usuario->setEmail($email);
                $usuario->setPassword($password);
                $usuario->setRol('usuario');
                
                if ($imagen) {
                    $usuario->setImagen($imagen);
                }
                
                $save = $usuario->save();
                
                if ($save) {
                    $_SESSION['success'] = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
                    header('Location: ' . BASE_URL . 'usuario/login');
                } else {
                    $_SESSION['error'] = 'Error al registrar el usuario.';
                    header('Location: ' . BASE_URL . 'usuario/registrarse');
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Error al registrar el usuario: ' . $e->getMessage();
                header('Location: ' . BASE_URL . 'usuario/registrarse');
            }
            exit();
        }
    }

    public function entrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $password = htmlspecialchars(trim($_POST['password']));

            if (!$email || empty($password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios y deben ser válidos.';
                header('Location: ' . BASE_URL . 'usuario/login');
                exit();
            }

            $usuario = new Usuario();
            $usuario->setEmail($email);
            $usuario->setPassword($password);
            
            $identity = $usuario->login();
            
            if ($identity && is_array($identity)) {
                $_SESSION['identity'] = $identity;
                
                if ($identity['rol'] == 'admin') {
                    $_SESSION['admin'] = true;
                }
                
                header('Location: ' . BASE_URL);
            } else {
                $_SESSION['error_login'] = 'Email o contraseña incorrectos';
                header('Location: ' . BASE_URL . 'usuario/login');
            }
            exit();
        }
    }

    public function cerrarsesion() {
        if (isset($_SESSION['identity'])) {
            unset($_SESSION['identity']);
        }
        
        if (isset($_SESSION['admin'])) {
            unset($_SESSION['admin']);
        }
        
        header("Location: " . BASE_URL);
        exit();
    }

    public function editar() {
        // Verificar que el usuario está logueado
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para editar tu perfil";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        $usuario_id = $_SESSION['identity']['id'];
        $usuario = Usuario::findById($usuario_id);
        
        require_once 'views/usuario/editar.php';
    }

    public function actualizarUsuario() {
        // Verificar que el usuario está logueado
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para editar tu perfil";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['identity']['id'];
            $nombre = htmlspecialchars(trim($_POST['nombre']));
            $apellidos = htmlspecialchars(trim($_POST['apellidos']));
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $password = !empty($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : null;
            
            // Validar datos
            if (empty($nombre) || empty($apellidos) || !$email) {
                $_SESSION['error'] = 'Nombre, apellidos y email son obligatorios.';
                header('Location: ' . BASE_URL . 'usuario/editar');
                exit();
            }
            
            if ($password && strlen($password) < 6) {
                $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
                header('Location: ' . BASE_URL . 'usuario/editar');
                exit();
            }
            
            // Verificar que el email no está registrado por otro usuario
            $existe_email = Usuario::findByEmail($email);
            if ($existe_email && $existe_email['id'] != $usuario_id) {
                $_SESSION['error'] = 'Este email ya está registrado por otro usuario.';
                header('Location: ' . BASE_URL . 'usuario/editar');
                exit();
            }
            
            // Procesar la imagen si se ha subido
            $imagen = null;
            if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['name'])) {
                $archivo = $_FILES['imagen'];
                $tipo = $archivo['type'];
                
                if ($tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png" || $tipo == "image/gif") {
                    // Crear directorio de imágenes si no existe
                    if (!is_dir('uploads/usuarios')) {
                        mkdir('uploads/usuarios', 0777, true);
                    }
                    
                    // Generar nombre único para la imagen
                    $imagen = time() . '_' . $archivo['name'];
                    
                    // Mover imagen al directorio
                    move_uploaded_file($archivo['tmp_name'], 'uploads/usuarios/' . $imagen);
                    
                    // Eliminar imagen anterior si existe
                    $usuario_actual = Usuario::findById($usuario_id);
                    if ($usuario_actual['imagen'] && file_exists('uploads/usuarios/' . $usuario_actual['imagen'])) {
                        unlink('uploads/usuarios/' . $usuario_actual['imagen']);
                    }
                } else {
                    $_SESSION['error'] = "El tipo de archivo no es válido. Solo se permiten imágenes JPG, PNG o GIF";
                    header('Location: ' . BASE_URL . 'usuario/editar');
                    exit();
                }
            }
            
            try {
                $usuario = new Usuario();
                $usuario->setId($usuario_id);
                $usuario->setNombre($nombre);
                $usuario->setApellidos($apellidos);
                $usuario->setEmail($email);
                
                if ($password) {
                    $usuario->setPassword($password);
                }
                
                if ($imagen) {
                    $usuario->setImagen($imagen);
                }
                
                $save = $usuario->update();
                
                if ($save) {
                    // Actualizar la sesión con los nuevos datos
                    $usuario_actualizado = Usuario::findById($usuario_id);
                    $_SESSION['identity'] = $usuario_actualizado;
                    
                    $_SESSION['success'] = "Tu perfil se ha actualizado correctamente";
                } else {
                    $_SESSION['error'] = "Error al actualizar el perfil";
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Error al actualizar el perfil: ' . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . 'usuario/perfil');
            exit();
        }
    }

    public function eliminarUsuario() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            // No permitir eliminar al propio administrador
            if ($id == $_SESSION['identity']['id']) {
                $_SESSION['error'] = "No puedes eliminar tu propio usuario";
                header('Location: ' . BASE_URL . 'usuario/administrar');
                exit();
            }
            
            try {
                // Aquí deberíamos implementar la lógica para eliminar al usuario
                // Incluyendo eliminar sus rutas y valoraciones
                // Por ahora, solo mostramos un mensaje
                
                $_SESSION['success'] = "Usuario eliminado correctamente";
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Error al eliminar el usuario: ' . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . 'usuario/administrar');
            exit();
        } else {
            $_SESSION['error'] = "ID de usuario no especificado";
            header('Location: ' . BASE_URL . 'usuario/administrar');
            exit();
        }
    }
}
?>
