<?php

namespace controllers;

use models\Medio;
use Utils; // Added Utils class

class MedioController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db;
    }    public function index() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        $medios = Medio::findAll();
        require_once 'views/medio/index.php';
    }

    public function crear() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        require_once 'views/medio/crear.php';
    }

    public function guardar() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        if (isset($_POST['submit'])) {
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
            
            // Validar datos
            $errores = [];
            
            if (!$nombre || strlen($nombre) < 3) {
                $errores['nombre'] = "El nombre debe tener al menos 3 caracteres";
            }
            
            if (count($errores) == 0) {
                $medio = new Medio();
                $medio->setNombre($nombre);
                
                $save = $medio->save();
                
                if ($save) {
                    $_SESSION['medio_creado'] = "El medio se ha creado correctamente";
                    
                    // Actualizar los medios en la sesión
                    $rutaController = new RutaController();
                    $rutaController->cargarMedios();
                    
                    header("Location: " . BASE_URL . "medio/index");
                } else {
                    $_SESSION['error'] = "Error al guardar el medio";
                    header("Location: " . BASE_URL . "medio/crear");
                }
            } else {
                $_SESSION['errores'] = $errores;
                header("Location: " . BASE_URL . "medio/crear");
            }
        } else {
            header("Location: " . BASE_URL . "medio/index");
        }
    }

    public function editar() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $medio = Medio::findById($id);
            
            if ($medio) {
                require_once 'views/medio/editar.php';
            } else {
                $_SESSION['error'] = "El medio solicitado no existe";
                header("Location: " . BASE_URL . "medio/index");
            }
        } else {
            $_SESSION['error'] = "ID de medio no especificado";
            header("Location: " . BASE_URL . "medio/index");
        }
    }

    public function actualizar() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
            
            // Validar datos
            $errores = [];
            
            if (!$nombre || strlen($nombre) < 3) {
                $errores['nombre'] = "El nombre debe tener al menos 3 caracteres";
            }
            
            if (count($errores) == 0) {
                $medio = new Medio();
                $medio->setId($id);
                $medio->setNombre($nombre);
                
                $save = $medio->update();
                
                if ($save) {
                    $_SESSION['medio_actualizado'] = "El medio se ha actualizado correctamente";
                    
                    // Actualizar los medios en la sesión
                    $rutaController = new RutaController();
                    $rutaController->cargarMedios();
                    
                    header("Location: " . BASE_URL . "medio/index");
                } else {
                    $_SESSION['error'] = "Error al actualizar el medio";
                    header("Location: " . BASE_URL . "medio/editar&id=" . $id);
                }
            } else {
                $_SESSION['errores'] = $errores;
                header("Location: " . BASE_URL . "medio/editar&id=" . $id);
            }
        } else {
            header("Location: " . BASE_URL . "medio/index");
        }
    }

    public function eliminar() {
        // Verificar que el usuario es administrador
        if (!Utils::isAdmin()) {
            $_SESSION['error'] = "Acceso no autorizado";
            header("Location: " . BASE_URL);
            exit();
        }
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $medio = Medio::findById($id);
            
            if ($medio) {
                $medioObj = new Medio();
                $medioObj->setId($id);
                $delete = $medioObj->delete();
                
                if ($delete) {
                    $_SESSION['medio_eliminado'] = "El medio se ha eliminado correctamente";
                    
                    // Actualizar los medios en la sesión
                    $rutaController = new RutaController();
                    $rutaController->cargarMedios();
                } else {
                    $_SESSION['error'] = "Error al eliminar el medio. Puede que esté siendo utilizado por alguna ruta.";
                }
                
                header("Location: " . BASE_URL . "medio/index");
            } else {
                $_SESSION['error'] = "El medio solicitado no existe";
                header("Location: " . BASE_URL . "medio/index");
            }
        } else {
            $_SESSION['error'] = "ID de medio no especificado";
            header("Location: " . BASE_URL . "medio/index");
        }
    }
}
?>
