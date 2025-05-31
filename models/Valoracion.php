<?php

namespace models;

use lib\BaseDatos;
use PDO;

class Valoracion {
    private $id;
    private $usuario_id;
    private $ruta_id;
    private $valoracion;
    private $comentario;
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

    public function getRutaId() {
        return $this->ruta_id;
    }

    public function getValoracion() {
        return $this->valoracion;
    }

    public function getComentario() {
        return $this->comentario;
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

    public function setRutaId($ruta_id) {
        $this->ruta_id = $ruta_id;
    }

    public function setValoracion($valoracion) {
        $this->valoracion = $valoracion;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    // Database methods
    public function save() {
        // Comprueba si ya existe una valoración de este usuario para esta ruta
        $existente = self::findByUsuarioRuta($this->usuario_id, $this->ruta_id);
        
        if ($existente) {
            // Actualizar la valoración existente
            $this->id = $existente['id'];
            return $this->update();
        } else {
            // Crear una nueva valoración
            $sql = "INSERT INTO valoraciones (usuario_id, ruta_id, valoracion, comentario, fecha) 
                    VALUES (:usuario_id, :ruta_id, :valoracion, :comentario, :fecha)";
            
            $params = [
                ":usuario_id" => $this->getUsuarioId(),
                ":ruta_id" => $this->getRutaId(),
                ":valoracion" => $this->getValoracion(),
                ":comentario" => $this->getComentario(),
                ":fecha" => date('Y-m-d H:i:s')
            ];

            try {
                $this->db->ejecutar($sql, $params);
                return $this->db->lastInsertId();
            } catch (\PDOException $e) {
                echo "Error al guardar la valoración: " . $e->getMessage();
                return false;
            }
        }
    }

    public function delete() {
        $sql = "DELETE FROM valoraciones WHERE id = :id";
        $params = [":id" => $this->id];

        try {
            $this->db->ejecutar($sql, $params);
            return true;
        } catch (\PDOException $e) {
            echo "Error al eliminar la valoración: " . $e->getMessage();
            return false;
        }
    }

    public function update() {
        $sql = "UPDATE valoraciones SET 
                valoracion = :valoracion, 
                comentario = :comentario, 
                fecha = :fecha 
                WHERE id = :id";
        
        $params = [
            ":valoracion" => $this->getValoracion(),
            ":comentario" => $this->getComentario(),
            ":fecha" => date('Y-m-d H:i:s'),
            ":id" => $this->getId()
        ];

        try {
            $this->db->ejecutar($sql, $params);
            return true;
        } catch (\PDOException $e) {
            echo "Error al actualizar la valoración: " . $e->getMessage();
            return false;
        }
    }

    public static function findById($id) {
        $db = new BaseDatos();
        $sql = "SELECT * FROM valoraciones WHERE id = :id";
        $params = [":id" => $id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al buscar la valoración: " . $e->getMessage();
            return false;
        }
    }

    public static function findByRutaId($ruta_id) {
        $db = new BaseDatos();
        $sql = "SELECT v.*, u.nombre, u.imagen FROM valoraciones v 
                INNER JOIN usuarios u ON v.usuario_id = u.id 
                WHERE v.ruta_id = :ruta_id 
                ORDER BY v.fecha DESC";
        
        $params = [":ruta_id" => $ruta_id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al obtener las valoraciones de la ruta: " . $e->getMessage();
            return false;
        }
    }

    public static function findByUsuarioRuta($usuario_id, $ruta_id) {
        $db = new BaseDatos();
        $sql = "SELECT * FROM valoraciones WHERE usuario_id = :usuario_id AND ruta_id = :ruta_id";
        
        $params = [
            ":usuario_id" => $usuario_id,
            ":ruta_id" => $ruta_id
        ];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al buscar la valoración del usuario: " . $e->getMessage();
            return false;
        }
    }    public static function getPromedioRuta($ruta_id) {
        $db = new BaseDatos();
        $sql = "SELECT AVG(valoracion) as promedio FROM valoraciones WHERE ruta_id = :ruta_id";
        
        $params = [":ruta_id" => $ruta_id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (float)$result['promedio'] : 0;
        } catch (\PDOException $e) {
            echo "Error al obtener el promedio de valoraciones: " . $e->getMessage();
            return 0;
        }
    }

    public static function getNumeroValoracionesRuta($ruta_id) {
        $db = new BaseDatos();
        $sql = "SELECT COUNT(id) as total FROM valoraciones WHERE ruta_id = :ruta_id";
        
        $params = [":ruta_id" => $ruta_id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (\PDOException $e) {
            echo "Error al obtener el número de valoraciones: " . $e->getMessage();
            return 0;
        }
    }
}
?>
