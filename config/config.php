<?php
// Configuración general
define('BASE_URL', 'http://localhost:8080');
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/posts/');
define('UPLOAD_URL', BASE_URL . '/uploads/posts/');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Función para verificar si el usuario es admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Función para redireccionar
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Función para sanitizar datos
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
