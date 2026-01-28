<?php
// Configuración de la base de datos
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Usar variables de entorno o valores por defecto
        $this->host = getenv('DB_HOST') ?: 'db';
        $this->db_name = getenv('DB_NAME') ?: 'blog_db';
        $this->username = getenv('DB_USER') ?: 'blog_user';
        $this->password = getenv('DB_PASS') ?: 'blog_password';
    }

    // Conectar a la base de datos
    public function connect() {
        $this->conn = null;

        // Reintentar conexión hasta 10 veces (para esperar a que MySQL esté listo)
        $max_attempts = 10;
        $attempt = 0;

        while ($attempt < $max_attempts) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    )
                );
                break; // Conexión exitosa, salir del loop
            } catch(PDOException $e) {
                $attempt++;
                if ($attempt >= $max_attempts) {
                    die("Error de conexión después de $max_attempts intentos: " . $e->getMessage() . 
                        "<br><br>Asegúrate de que los contenedores estén corriendo: <code>docker-compose up -d</code>");
                }
                // Esperar 1 segundo antes de reintentar
                sleep(1);
            }
        }

        return $this->conn;
    }
}
?>
