<?php
// public/index.php

require_once __DIR__ . '/../config/config.php';

// Autoload simple para clases
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Obtener la URI y limpiarla
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Routing de la aplicación
switch (true) {
    // ==================== HOME / FEED ====================
    case $uri === '' || $uri === 'home':
        $controller = new App\Controllers\PieceController();
        $controller->index();
        break;
    
    // ==================== ADMIN ====================
    
    // Panel de usuarios (admin)
    case $uri === 'admin/users':
        $controller = new App\Controllers\AdminController();
        $controller->users();
        break;
    
    // Bloquear usuario
    case preg_match('#^admin/users/(\d+)/block$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\AdminController();
        $controller->blockUser($matches[1]);
        break;
    
    // Desbloquear usuario
    case preg_match('#^admin/users/(\d+)/unblock$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\AdminController();
        $controller->unblockUser($matches[1]);
        break;
    
    // Eliminar usuario
    case preg_match('#^admin/users/(\d+)/delete$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\AdminController();
        $controller->deleteUser($matches[1]);
        break;
    
    // ==================== PIEZAS ====================
    
    // Ver pieza individual
    case preg_match('#^piece/(\d+)$#', $uri, $matches):
        $controller = new App\Controllers\PieceController();
        $controller->show($matches[1]);
        break;
    
    // Formulario para subir pieza (GET)
    case $uri === 'piece/upload' && $_SERVER['REQUEST_METHOD'] === 'GET':
        $controller = new App\Controllers\PieceController();
        $controller->upload();
        break;
    
    // Procesar subida de pieza (POST)
    case $uri === 'piece/upload' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\PieceController();
        $controller->store();
        break;
    
    // Eliminar pieza
    case preg_match('#^piece/(\d+)/delete$#', $uri, $matches):
        $controller = new App\Controllers\PieceController();
        $controller->delete($matches[1]);
        break;
    
    // Editar pieza (GET)
    case preg_match('#^piece/(\d+)/edit$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET':
        $controller = new App\Controllers\PieceController();
        $controller->edit($matches[1]);
        break;
    
    // Actualizar pieza (POST)
    case preg_match('#^piece/(\d+)/edit$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\PieceController();
        $controller->update($matches[1]);
        break;
    
    // ==================== API ENDPOINTS ====================
    
    // Toggle like (API)
    case $uri === 'api/like' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\PieceController();
        $controller->toggleLike();
        break;
    
    // Añadir comentario
    case preg_match('#^piece/(\d+)/comment$#', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\PieceController();
        $controller->addComment($matches[1]);
        break;
    
    // ==================== PERFIL ====================
    
    // Ver perfil de usuario
    case preg_match('#^profile/([a-zA-Z0-9_]+)$#', $uri, $matches):
        $controller = new App\Controllers\AuthController();
        $controller->profile($matches[1]);
        break;
    
    // Editar perfil
    case $uri === 'profile/edit' && $_SERVER['REQUEST_METHOD'] === 'GET':
        $controller = new App\Controllers\AuthController();
        $controller->editProfile();
        break;
    
    // Actualizar perfil
    case $uri === 'profile/edit' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\AuthController();
        $controller->updateProfile();
        break;
    
    // ==================== AUTENTICACIÓN ====================
    
    // Mostrar formulario de login
    case $uri === 'login' && $_SERVER['REQUEST_METHOD'] === 'GET':
        $controller = new App\Controllers\AuthController();
        $controller->showLogin();
        break;
    
    // Procesar login
    case $uri === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\AuthController();
        $controller->login();
        break;
    
    // Mostrar formulario de registro
    case $uri === 'register' && $_SERVER['REQUEST_METHOD'] === 'GET':
        $controller = new App\Controllers\AuthController();
        $controller->showRegister();
        break;
    
    // Procesar registro
    case $uri === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST':
        $controller = new App\Controllers\AuthController();
        $controller->register();
        break;
    
    // Cerrar sesión
    case $uri === 'logout':
        $controller = new App\Controllers\AuthController();
        $controller->logout();
        break;
    
    // ==================== BÚSQUEDA Y FILTROS ====================
    
    // Buscar piezas
    case $uri === 'search':
        $controller = new App\Controllers\PieceController();
        $controller->search();
        break;
    
    // Filtrar por estilo
    case preg_match('#^style/([a-z0-9-]+)$#', $uri, $matches):
        $controller = new App\Controllers\PieceController();
        $controller->filterByStyle($matches[1]);
        break;
    
    // ==================== 404 ====================
    default:
        http_response_code(404);
        require VIEWS_PATH . '/layouts/header.php';
        echo '<div class="main-container">';
        echo '<div class="empty-state">';
        echo '<h1 style="color: #fff;">404</h1>';
        echo '<p>Página no encontrada</p>';
        echo '<a href="/" class="btn btn-primary">Volver al inicio</a>';
        echo '</div>';
        echo '</div>';
        require VIEWS_PATH . '/layouts/footer.php';
        break;
}