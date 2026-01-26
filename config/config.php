<?php

// Cargar variables de entorno
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Cargar .env desde la raíz del proyecto
loadEnv(__DIR__ . '/../.env');

// Configuración de base de datos
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'blog_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Blog');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

// Configuración de sesión
define('SESSION_LIFETIME', $_ENV['SESSION_LIFETIME'] ?? 7200);

// Rutas
define('BASE_PATH', dirname(__DIR__));
define('ROOT_PATH', BASE_PATH); // Añadida la constante ROOT_PATH
define('PUBLIC_PATH', BASE_PATH . '/public');
define('VIEWS_PATH', BASE_PATH . '/app/Views');

// Cargar Database
require_once __DIR__ . '/Database.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurar zona horaria
date_default_timezone_set('Europe/Madrid');

// Mostrar errores en desarrollo
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}