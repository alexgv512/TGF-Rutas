<?php

    namespace lib;

    use PDO;
    use PDOException;

    class BaseDatos  {
        private $pdo;
        private $host = DB_HOST;
        private $dbname = DB_NAME; 
        private $usuario = DB_USER;
        private $contraseña = DB_PASS;
        
        public function __construct() {
            try {
                $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->usuario, $this->contraseña);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error de conexión a la base de datos: " . $e->getMessage();
            }
        }
    
        public function getConexion() {
            return $this->pdo;
        }

        public function query($sql) {
            return $this->pdo->query($sql);
        }

        public function prepare($sql) {
            return $this->pdo->prepare($sql);
        }

        public function ejecutar($sql, $parametros) {
            $sentencia = $this->prepare($sql);
            $sentencia->execute($parametros);
        }
        
        public function lastInsertId() {
            return $this->pdo->lastInsertId();
        }

        

    }
?>