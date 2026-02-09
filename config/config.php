<?php
// Configuración general
define('BASE_URL', 'http://localhost:8080');
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/posts/');
define('UPLOAD_URL', BASE_URL . '/uploads/posts/');

// inicio de sesion seguro
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

// Generar campo oculto CSRF
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Validar Token CSRF
function validateCsrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'Error de validación de seguridad (CSRF). Por favor recarga la página.';
        redirect('/'); // página anterior si es posible

    }
}
?>
