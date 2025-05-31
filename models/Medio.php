<?php

namespace models;

use lib\BaseDatos;
use PDO;

class Medio {
    private $id;
    private $nombre;
    private $db;

    public function __construct() {
        $this->db = new BaseDatos();
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Database methods
    public function save() {
        $sql = "INSERT INTO medios (nombre) VALUES (:nombre)";
        $params = [":nombre" => $this->getNombre()];

        try {
            $this->db->ejecutar($sql, $params);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            echo "Error al guardar el medio: " . $e->getMessage();
            return false;
        }
    }

    public function delete() {
        $sql = "DELETE FROM medios WHERE id = :id";
        $params = [":id" => $this->id];

        try {
            $this->db->ejecutar($sql, $params);
            return true;
        } catch (\PDOException $e) {
            echo "Error al eliminar el medio: " . $e->getMessage();
            return false;
        }
    }

    public function update() {
        $sql = "UPDATE medios SET nombre = :nombre WHERE id = :id";
        $params = [
            ":nombre" => $this->getNombre(),
            ":id" => $this->getId()
        ];

        try {
            $this->db->ejecutar($sql, $params);
            return true;
        } catch (\PDOException $e) {
            echo "Error al actualizar el medio: " . $e->getMessage();
            return false;
        }
    }

    public static function findById($id) {
        $db = new BaseDatos();
        $sql = "SELECT * FROM medios WHERE id = :id";
        $params = [":id" => $id];

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al buscar el medio: " . $e->getMessage();
            return false;
        }
    }

    public static function findAll() {
        $db = new BaseDatos();
        $sql = "SELECT * FROM medios ORDER BY nombre ASC";

        try {
            $medios = $db->query($sql);
            return $medios->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error al obtener los medios: " . $e->getMessage();
            return false;
        }
    }
}
?>
