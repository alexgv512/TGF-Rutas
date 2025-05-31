<?php

    namespace models;

    use lib\BaseDatos;
    use PDO;
    
    class Usuario {
        private $id;
        private $nombre;
        private $apellidos;
        private $email;
        private $password;
        private $rol;
        private $imagen;
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
    
        public function getApellidos() {
            return $this->apellidos;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function getPassword() {
            return $this->password;
        }
        
        public function getRol() {
            return $this->rol;
        }
        
        public function getImagen() {
            return $this->imagen;
        }
        
        // Setters
        public function setId($id) {
            $this->id = $id;
        }
        
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
    
        public function setApellidos($apellidos) {
            $this->apellidos = $apellidos;
        }
    
        public function setEmail($email) {
            $this->email = $email;
        }
    
        public function setPassword($password) {
            $this->password = $password;
        }
        
        public function setRol($rol) {
            $this->rol = $rol;
        }
        
        public function setImagen($imagen) {
            $this->imagen = $imagen;
        }
        
        // Database methods
        public function save() {
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol, imagen) 
                    VALUES (:nombre, :apellidos, :email, :password, :rol, :imagen)";
            
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 4]);
            
            $params = [
                ":nombre" => $this->nombre,
                ":apellidos" => $this->apellidos,
                ":email" => $this->email,
                ":password" => $password_hash,
                ":rol" => $this->rol ? $this->rol : 'usuario',
                ":imagen" => $this->imagen
            ];
            
            try {
                $this->db->ejecutar($sql, $params);
                return $this->db->lastInsertId();
            } catch (\PDOException $e) {
                echo "Error al guardar usuario: " . $e->getMessage();
                return false;
            }
        }
        
        public function login() {
            $result = false;
            $email = $this->email;
            $password = $this->password;
            
            // Comprobar si existe el usuario
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $params = [":email" => $email];
            
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($usuario && password_verify($password, $usuario['password'])) {
                    $result = $usuario;
                }
                
                return $result;
            } catch (\PDOException $e) {
                echo "Error en el login: " . $e->getMessage();
                return false;
            }
        }
        
        public function update() {
            $sql = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    apellidos = :apellidos, 
                    email = :email";
            
            $params = [
                ":nombre" => $this->nombre,
                ":apellidos" => $this->apellidos,
                ":email" => $this->email,
                ":id" => $this->id
            ];
            
            // Si hay una nueva contraseña, la actualizamos
            if (!empty($this->password)) {
                $sql .= ", password = :password";
                $params[":password"] = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 4]);
            }
            
            // Si hay una nueva imagen, la actualizamos
            if (!empty($this->imagen)) {
                $sql .= ", imagen = :imagen";
                $params[":imagen"] = $this->imagen;
            }
            
            $sql .= " WHERE id = :id";
            
            try {
                $this->db->ejecutar($sql, $params);
                return true;
            } catch (\PDOException $e) {
                echo "Error al actualizar usuario: " . $e->getMessage();
                return false;
            }
        }
        
        public static function findById($id) {
            $db = new BaseDatos();
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $params = [":id" => $id];
            
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                echo "Error al buscar usuario: " . $e->getMessage();
                return false;
            }
        }
        
        public static function findByEmail($email) {
            $db = new BaseDatos();
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $params = [":email" => $email];
            
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                echo "Error al buscar usuario por email: " . $e->getMessage();
                return false;
            }
        }
        
        public static function findAll() {
            $db = new BaseDatos();
            $sql = "SELECT * FROM usuarios ORDER BY id DESC";
            
            try {
                $usuarios = $db->query($sql);
                return $usuarios->fetchAll(PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                echo "Error al obtener todos los usuarios: " . $e->getMessage();
                return false;
            }        }
    }