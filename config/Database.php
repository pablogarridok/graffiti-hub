<?php

namespace Config;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    
    /**
     * Constructor privado para Singleton
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener instancia única de Database (Singleton)
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        
        return self::$instance;
    }
    
    /**
     * Obtener conexión PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    /**
     * Cerrar conexión
     */
    public function closeConnection(): void {
        $this->connection = null;
        self::$instance = null;
    }
    
    /**
     * Prevenir clonación
     */
    private function __clone() {}
    
    /**
     * Prevenir deserialización
     */
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}