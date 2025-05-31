<?php

namespace controllers;

use models\Ruta;
use models\Medio;
use models\Valoracion;
use models\Usuario;
use Utils; // Added Utils class

class RutaController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db;
    }

    public function destacadas() {
        $rutas = Ruta::findDestacadas();
        require_once 'views/ruta/destacadas.php';
    }

    public function explorar() {
        $medios = Medio::findAll();
        
        $filtros = [];
        if (isset($_GET['buscar'])) {
            if (!empty($_GET['nombre'])) $filtros['nombre'] = $_GET['nombre'];
            if (!empty($_GET['dificultad'])) $filtros['dificultad'] = $_GET['dificultad'];
            if (!empty($_GET['medio_id'])) $filtros['medio_id'] = $_GET['medio_id'];
            if (!empty($_GET['longitud_min'])) $filtros['longitud_min'] = $_GET['longitud_min'];
            if (!empty($_GET['longitud_max'])) $filtros['longitud_max'] = $_GET['longitud_max'];
            if (!empty($_GET['valoracion_min'])) $filtros['valoracion_min'] = $_GET['valoracion_min'];
            
            $rutas = Ruta::buscarRutas($filtros);
        } else {
            $rutas = Ruta::findAll();
        }

        require_once 'views/ruta/explorar.php';
    }

    public function detalle() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $ruta = Ruta::findById($id);
            
            if ($ruta) {
                // Obtener los medios de transporte para esta ruta
                $medios = Ruta::getMediosRuta($id);
                
                // Obtener valoraciones y promedio
                $valoraciones = Valoracion::findByRutaId($id);
                $promedio = Valoracion::getPromedioRuta($id);
                
                // Obtener información del creador
                $creador = Usuario::findById($ruta['usuario_id']);
                
                // Verificar si el usuario actual ya ha valorado esta ruta
                $valoracion_usuario = null;
                if (isset($_SESSION['identity'])) {
                    $valoracion_usuario = Valoracion::findByUsuarioRuta($_SESSION['identity']['id'], $id);
                }
                
                require_once 'views/ruta/detalle.php';
            } else {
                $_SESSION['error'] = "La ruta solicitada no existe";
                header("Location: " . BASE_URL);
            }
        } else {
            $_SESSION['error'] = "ID de ruta no especificado";
            header("Location: " . BASE_URL);
        }
    }    public function crear() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para crear una ruta";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        $medios = Medio::findAll();
        require_once 'views/ruta/crear.php';
    }    public function guardar() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para crear una ruta";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['identity']['id'];
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
            $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
            $longitud = isset($_POST['longitud']) ? (float)$_POST['longitud'] : false;
            $dificultad = isset($_POST['dificultad']) ? $_POST['dificultad'] : false;
            $medios = isset($_POST['medios']) ? $_POST['medios'] : [];
            
            // Validar datos
            $errores = [];
            
            if (!$nombre || strlen($nombre) < 3) {
                $errores['nombre'] = "El nombre debe tener al menos 3 caracteres";
            }
            
            if (!$longitud || $longitud <= 0) {
                $errores['longitud'] = "La longitud debe ser un número positivo";
            }
            
            if (!$dificultad || !in_array($dificultad, ['Fácil', 'Media', 'Difícil', 'Extrema'])) {
                $errores['dificultad'] = "Selecciona una dificultad válida";
            }
            
            if (empty($medios)) {
                $errores['medios'] = "Debes seleccionar al menos un medio de transporte";
            }
            
            if (count($errores) == 0) {
                $ruta = new Ruta();
                $ruta->setUsuarioId($usuario_id);
                $ruta->setNombre($nombre);
                $ruta->setDescripcion($descripcion);
                $ruta->setLongitud($longitud);
                $ruta->setDificultad($dificultad);
                $ruta->setFecha(date('Y-m-d H:i:s'));
                
                $save = $ruta->save();
                
                if ($save) {
                    $ruta->setId($save);
                    $ruta->agregarMedios($medios);
                    
                    $_SESSION['ruta_creada'] = "La ruta se ha creado correctamente";
                    header("Location: " . BASE_URL . "ruta/misrutas");
                } else {
                    $_SESSION['error'] = "Error al guardar la ruta";
                    header("Location: " . BASE_URL . "ruta/crear");
                }
            } else {
                $_SESSION['errores'] = $errores;
                header("Location: " . BASE_URL . "ruta/crear");
            }
        } else {
            header("Location: " . BASE_URL);
        }
    }

    public function editar() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para editar una ruta";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $ruta = Ruta::findById($id);
              if ($ruta) {
                // Verificar que el usuario actual es el propietario de la ruta o es administrador
                if ($this->canEditRuta($ruta['usuario_id'])) {
                    // Obtener los medios de esta ruta
                    $medios_ruta = Ruta::getMediosRuta($id);
                    $medios_seleccionados = array_map(function($medio) { return $medio['id']; }, $medios_ruta);
                    
                    // Obtener todos los medios disponibles
                    $medios = Medio::findAll();
                    
                    require_once 'views/ruta/editar.php';
                } else {
                    $_SESSION['error'] = "No tienes permiso para editar esta ruta";
                    header("Location: " . BASE_URL . "ruta/detalle&id=" . $id);
                }
            } else {
                $_SESSION['error'] = "La ruta solicitada no existe";
                header("Location: " . BASE_URL);
            }
        } else {
            $_SESSION['error'] = "ID de ruta no especificado";
            header("Location: " . BASE_URL);
        }
    }

    public function actualizar() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para editar una ruta";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $ruta_actual = Ruta::findById($id);
              if ($ruta_actual) {
                // Verificar que el usuario actual es el propietario de la ruta o es administrador
                if ($this->canEditRuta($ruta_actual['usuario_id'])) {
                    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
                    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
                    $longitud = isset($_POST['longitud']) ? (float)$_POST['longitud'] : false;
                    $dificultad = isset($_POST['dificultad']) ? $_POST['dificultad'] : false;
                    $medios = isset($_POST['medios']) ? $_POST['medios'] : [];
                    
                    // Validar datos
                    $errores = [];
                    
                    if (!$nombre || strlen($nombre) < 3) {
                        $errores['nombre'] = "El nombre debe tener al menos 3 caracteres";
                    }
                      if (!$longitud || $longitud <= 0) {
                        $errores['longitud'] = "La longitud debe ser un número positivo";
                    }
                    
                    if (!$dificultad || !in_array($dificultad, ['Fácil', 'Media', 'Difícil', 'Extrema'])) {
                        $errores['dificultad'] = "Selecciona una dificultad válida";
                    }
                    
                    if (empty($medios)) {
                        $errores['medios'] = "Debes seleccionar al menos un medio de transporte";
                    }
                    
                    if (count($errores) == 0) {
                        $ruta = new Ruta();
                        $ruta->setId($id);
                        $ruta->setUsuarioId($ruta_actual['usuario_id']);
                        $ruta->setNombre($nombre);
                        $ruta->setDescripcion($descripcion);
                        $ruta->setLongitud($longitud);
                        $ruta->setDificultad($dificultad);
                        
                        $save = $ruta->update();
                        
                        if ($save) {
                            $ruta->agregarMedios($medios);
                            
                            $_SESSION['ruta_actualizada'] = "La ruta se ha actualizado correctamente";
                            header("Location: " . BASE_URL . "ruta/detalle&id=" . $id);
                        } else {
                            $_SESSION['error'] = "Error al actualizar la ruta";
                            header("Location: " . BASE_URL . "ruta/editar&id=" . $id);
                        }
                    } else {
                        $_SESSION['errores'] = $errores;
                        header("Location: " . BASE_URL . "ruta/editar&id=" . $id);
                    }
                } else {
                    $_SESSION['error'] = "No tienes permiso para editar esta ruta";
                    header("Location: " . BASE_URL . "ruta/detalle&id=" . $id);
                }
            } else {
                $_SESSION['error'] = "La ruta solicitada no existe";
                header("Location: " . BASE_URL);
            }
        } else {
            header("Location: " . BASE_URL);
        }
    }

    public function eliminar() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para eliminar una ruta";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $ruta = Ruta::findById($id);
              if ($ruta) {
                // Verificar que el usuario actual es el propietario de la ruta o es administrador
                if ($this->canEditRuta($ruta['usuario_id'])) {
                    $rutaObj = new Ruta();
                    $rutaObj->setId($id);
                    $delete = $rutaObj->delete();
                    
                    if ($delete) {
                        $_SESSION['ruta_eliminada'] = "La ruta se ha eliminado correctamente";
                    } else {
                        $_SESSION['error'] = "Error al eliminar la ruta";
                    }
                } else {
                    $_SESSION['error'] = "No tienes permiso para eliminar esta ruta";
                }
                
                if (isset($_GET['redirect']) && $_GET['redirect'] == 'perfil') {
                    header("Location: " . BASE_URL . "usuario/perfil");
                } else {
                    header("Location: " . BASE_URL . "ruta/misrutas");
                }
            } else {
                $_SESSION['error'] = "La ruta solicitada no existe";
                header("Location: " . BASE_URL);
            }
        } else {
            $_SESSION['error'] = "ID de ruta no especificado";
            header("Location: " . BASE_URL);
        }
    }

    public function misrutas() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para ver tus rutas";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        $usuario_id = $_SESSION['identity']['id'];
        $rutas = Ruta::findByUsuarioId($usuario_id);
        
        require_once 'views/ruta/misrutas.php';
    }

    public function valorar() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error'] = "Debes iniciar sesión para valorar una ruta";
            header("Location: " . BASE_URL . "usuario/login");
            exit();
        }
        
        if (isset($_POST['submit']) && isset($_POST['ruta_id'])) {
            $usuario_id = $_SESSION['identity']['id'];
            $ruta_id = (int)$_POST['ruta_id'];
            $valoracion_num = isset($_POST['valoracion']) ? (int)$_POST['valoracion'] : false;
            $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
            
            // Validar datos
            $errores = [];
            
            if (!$valoracion_num || $valoracion_num < 1 || $valoracion_num > 5) {
                $errores['valoracion'] = "La valoración debe ser un número entre 1 y 5";
            }
            
            // Verificar que la ruta existe
            $ruta = Ruta::findById($ruta_id);
            if (!$ruta) {
                $errores['ruta'] = "La ruta no existe";
            }
            
            // Verificar que el usuario no es el propietario de la ruta
            if ($ruta && $ruta['usuario_id'] == $usuario_id) {
                $errores['usuario'] = "No puedes valorar tu propia ruta";
            }
            
            if (count($errores) == 0) {
                $valoracion = new Valoracion();
                $valoracion->setUsuarioId($usuario_id);
                $valoracion->setRutaId($ruta_id);
                $valoracion->setValoracion($valoracion_num);
                $valoracion->setComentario($comentario);
                $valoracion->setFecha(date('Y-m-d H:i:s'));
                
                $save = $valoracion->save();
                
                if ($save) {
                    $_SESSION['valoracion_creada'] = "Tu valoración se ha guardado correctamente";
                } else {
                    $_SESSION['error'] = "Error al guardar la valoración";
                }
            } else {
                $_SESSION['errores'] = $errores;
            }
            
            header("Location: " . BASE_URL . "ruta/detalle&id=" . $ruta_id);
        } else {
            header("Location: " . BASE_URL);
        }
    }

    public function cargarMedios() {
        // Cargar los medios en la sesión para tenerlos disponibles en toda la aplicación
        $medios = Medio::findAll();
        $_SESSION['medios'] = $medios;
    }

    // Método para exportar una ruta a formato GPX
    public function exportarGPX() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $ruta = Ruta::findById($id);
            
            if ($ruta) {
                // Implementar lógica para generar el archivo GPX
                // Esto es un ejemplo básico, habría que tener los puntos GPS de la ruta
                $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <gpx creator="RutasMotoras" version="1.1" xmlns="http://www.topografix.com/GPX/1/1">
                    <metadata>
                        <name>' . htmlspecialchars($ruta['nombre']) . '</name>
                        <desc>' . htmlspecialchars($ruta['descripcion']) . '</desc>
                    </metadata>
                    <trk>
                        <name>' . htmlspecialchars($ruta['nombre']) . '</name>
                        <desc>' . htmlspecialchars($ruta['descripcion']) . '</desc>
                        <trkseg>
                            <!-- Aquí irían los puntos de la ruta -->
                        </trkseg>
                    </trk>
                </gpx>';
                
                header('Content-Type: application/gpx+xml');
                header('Content-Disposition: attachment; filename="' . $ruta['nombre'] . '.gpx"');
                echo $xml;
                exit;
            } else {
                $_SESSION['error'] = "La ruta solicitada no existe";
                header("Location: " . BASE_URL);
            }
        } else {
            $_SESSION['error'] = "ID de ruta no especificado";
            header("Location: " . BASE_URL);
        }
    }

    // Helper method to check if user can edit a specific ruta
    private function canEditRuta($userId) {
        return (isset($_SESSION['identity']) && 
                ($_SESSION['identity']['id'] == $userId || $_SESSION['identity']['rol'] == 'admin'));
    }
}
?>
