<?php

namespace models;

use lib\BaseDatos;
use PDO;

class Ruta {
    private $id;
    private $usuario_id;
    private $nombre;
    private $descripcion;
    private $longitud;
    private $dificultad;
    private $fecha;
    private $db;

    public function __construct() {
        $this->db = new BaseDatos();
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getLongitud() {
        return $this->longitud;
    }

    public function getDificultad() {
        return $this->dificultad;
    }

    public function getFecha() {
        return $this->fecha;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setLongitud($longitud) {
        $this->longitud = $longitud;
    }

    public function setDificultad($dificultad) {
        $this->dificultad = $dificultad;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    // Database methods
    public function save() {
        $sql = "INSERT INTO rutas (usuario_id, nombre, descripcion, longitud, dificultad, fecha) 
                VALUES (:usuario_id, :nombre, :descripcion, :longitud, :dificultad, :fecha)";
        
        $params = [
            ":usuario_id" => $this->getUsuarioId(),
            ":nombre" => $this->getNombre(),
            ":descripcion" => $this->getDescripcion(),
            ":longitud" => $this->getLongitud(),
            ":dificultad" => $this->getDificultad(),
            ":fecha" => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->ejecutar($sql, $params);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            echo "Error al guardar la ruta: " . $e->getMessage();
            return false;
        }
    }

    public function delete() {
        $sql = "DELETE FROM rutas WHERE id = :id";
        $params = [":id" => $this->id];

        try {
            $this->db->ejecutar($sql, $params);
            return true;
        } catch (\PDOException $e) {
            echo "Error al eliminar la ruta: " . $e->getMessage();
            return false;
        }
    }

    public function update() {
        $sql = "UPDATE rutas SET 
                nombre = :nombre, 
                descripcion = :descripcion, 
                longitud = :longitud, 
                dificultad = :dificultad 
                WHERE id = :id";
        
        $params = [
            ":nombre" => $this->getNombre(),
            ":descripcion" => $this->getDescripcion(),
            ":longitud" => $this->getLongitud(),
            ":dificultad" => $this->getDificultad(),
            ":id" => $this->getId()
        ];

        try {
            $this->db->ejecutar($sql, $params);
            return true;
        } catch (\PDOException $e) {
            echo "Error al actualizar la ruta: " . $e->getMessage();
            return false;
        }
    }

    public static function findById($id) {
        $db = new BaseDatos();
        $sql = "SELECT * FROM rutas WHERE id = :id";
        $params = [":id" => $id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al buscar la ruta: " . $e->getMessage();
            return false;
        }
    }

    public static function findAll() {
        $db = new BaseDatos();
        $sql = "SELECT * FROM rutas ORDER BY fecha DESC";

        try {
            $rutas = $db->query($sql);
            return $rutas->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al obtener las rutas: " . $e->getMessage();
            return false;
        }
    }

    public static function findDestacadas($limit = 6) {
        $db = new BaseDatos();
        $sql = "SELECT r.*, COALESCE(AVG(v.valoracion), 0) as promedio_valoracion, COUNT(v.id) as total_valoraciones 
                FROM rutas r 
                LEFT JOIN valoraciones v ON r.id = v.ruta_id 
                GROUP BY r.id 
                ORDER BY promedio_valoracion DESC, total_valoraciones DESC 
                LIMIT :limit";

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al obtener las rutas destacadas: " . $e->getMessage();
            return false;
        }
    }

    public static function findByUsuarioId($usuario_id) {
        $db = new BaseDatos();
        $sql = "SELECT * FROM rutas WHERE usuario_id = :usuario_id ORDER BY fecha DESC";
        $params = [":usuario_id" => $usuario_id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al obtener las rutas del usuario: " . $e->getMessage();
            return false;
        }
    }

    public static function buscarRutas($filtros) {
        $db = new BaseDatos();
        $sql = "SELECT r.*, COALESCE(AVG(v.valoracion), 0) as promedio_valoracion 
                FROM rutas r 
                LEFT JOIN valoraciones v ON r.id = v.ruta_id 
                LEFT JOIN medios_rutas mr ON r.id = mr.ruta_id 
                LEFT JOIN medios m ON mr.medio_id = m.id 
                WHERE 1=1";
        
        $params = [];
        
        if (isset($filtros['nombre']) && !empty($filtros['nombre'])) {
            $sql .= " AND r.nombre LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }
        
        if (isset($filtros['dificultad']) && !empty($filtros['dificultad'])) {
            $sql .= " AND r.dificultad = :dificultad";
            $params[':dificultad'] = $filtros['dificultad'];
        }
        
        if (isset($filtros['medio_id']) && !empty($filtros['medio_id'])) {
            $sql .= " AND mr.medio_id = :medio_id";
            $params[':medio_id'] = $filtros['medio_id'];
        }
        
        if (isset($filtros['longitud_min']) && !empty($filtros['longitud_min'])) {
            $sql .= " AND r.longitud >= :longitud_min";
            $params[':longitud_min'] = $filtros['longitud_min'];
        }
        
        if (isset($filtros['longitud_max']) && !empty($filtros['longitud_max'])) {
            $sql .= " AND r.longitud <= :longitud_max";
            $params[':longitud_max'] = $filtros['longitud_max'];
        }
        
        $sql .= " GROUP BY r.id";
        
        if (isset($filtros['valoracion_min']) && !empty($filtros['valoracion_min'])) {
            $sql .= " HAVING promedio_valoracion >= :valoracion_min";
            $params[':valoracion_min'] = $filtros['valoracion_min'];
        }
        
        $sql .= " ORDER BY promedio_valoracion DESC, r.fecha DESC";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al buscar rutas: " . $e->getMessage();
            return false;
        }
    }

    public static function getMediosRuta($ruta_id) {
        $db = new BaseDatos();
        $sql = "SELECT m.* FROM medios m 
                INNER JOIN medios_rutas mr ON m.id = mr.medio_id 
                WHERE mr.ruta_id = :ruta_id";
        
        $params = [":ruta_id" => $ruta_id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al obtener los medios de la ruta: " . $e->getMessage();
            return false;
        }
    }

    public function agregarMedios($medios_ids) {
        if (!$this->id) {
            return false;
        }

        try {
            // Primero eliminamos las relaciones existentes
            $sqlDelete = "DELETE FROM medios_rutas WHERE ruta_id = :ruta_id";
            $this->db->ejecutar($sqlDelete, [":ruta_id" => $this->id]);
            
            // Ahora agregamos las nuevas relaciones
            foreach ($medios_ids as $medio_id) {
                $sqlInsert = "INSERT INTO medios_rutas (medio_id, ruta_id) VALUES (:medio_id, :ruta_id)";
                $this->db->ejecutar($sqlInsert, [
                    ":medio_id" => $medio_id,
                    ":ruta_id" => $this->id
                ]);
            }
            return true;
        } catch (\PDOException $e) {
            echo "Error al agregar medios a la ruta: " . $e->getMessage();
            return false;
        }
    }
}
?>
