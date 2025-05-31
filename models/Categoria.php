<?php

    namespace models;

    use lib\BaseDatos;
    use PDO;
    use PDOException;

    class Categoria{

        private int $id;
        private string $nombre;
        private BaseDatos $baseDatos;

        public function __construct(){
        }

        /* GETTERS Y SETTERS */
        
        public function getId(): int{
            return $this->id;
        }

        public function getNombre(): string{
            return $this->nombre;
        }

        public function setId(int $id): void{
            $this->id = $id;
        }

        public function setNombre(string $nombre): void{
            $this->nombre = $nombre;
        }
        
        

        /* MÉTODOS DINÁMICOS */
        public function save(): bool{
            $this->baseDatos = new BaseDatos();
            $stmt = $this->baseDatos->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
            $stmt->bindParam(":nombre", $this->nombre);
            return $stmt->execute();
        }

        public function update(): bool{
            $this->baseDatos = new BaseDatos();
            $stmt = $this->baseDatos->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        }

        public function delete(): bool{
            $this->baseDatos = new BaseDatos();
            $stmt = $this->baseDatos->prepare("DELETE FROM categorias WHERE id = :id");
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        }
        
        

        /* MÉTODOS ESTÁTICOS */
        public static function getAll(): array{
            $baseDatos = new BaseDatos();
            $stmt = $baseDatos->query("SELECT * FROM categorias");
            if ($stmt === false) {
                return [];
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public static function getById($id) {
            $baseDatos = new BaseDatos();
            $stmt = $baseDatos->prepare("SELECT * FROM categorias WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    }

?>