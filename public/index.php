<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Obtener la URL solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$request_path = parse_url($request_uri, PHP_URL_PATH);

// Eliminar el script name si está presente
if (isset($_SERVER['SCRIPT_NAME'])) {
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    if ($script_dir !== '/') {
        $request_path = str_replace($script_dir, '', $request_path);
    }
}

$path = trim($request_path, '/');

// Dividir la ruta en segmentos
$segments = $path ? explode('/', $path) : [];
$action = $segments[0] ?? 'home';

// Router simple
switch($action) {
    case '':
    case 'home':
        require_once __DIR__ . '/../app/Controllers/PostController.php';
        $controller = new PostController();
        $controller->index();
        break;

    case 'login':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'register':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'logout':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'posts':
        require_once __DIR__ . '/../app/Controllers/PostController.php';
        $controller = new PostController();
        
        $subaction = $segments[1] ?? null;
        $id = $segments[2] ?? null;

        if($subaction == 'create') {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $controller->store();
            } else {
                $controller->create();
            }
        } elseif($subaction == 'edit' && $id) {
            $controller->edit($id);
        } elseif($subaction == 'update' && $id) {
            $controller->update($id);
        } elseif($subaction == 'delete' && $id) {
            $controller->delete($id);
        } elseif($subaction == 'store') {
            $controller->store();
        } elseif(is_numeric($subaction)) {
            // Ver detalle del post
            if(isset($segments[2]) && $segments[2] == 'comment') {
                $controller->addComment($subaction);
            } else {
                $controller->show($subaction);
            }
        } else {
            $controller->index();
        }
        break;

    case 'admin':
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new AdminController();
        
        $subaction = $segments[1] ?? null;
        $subsubaction = $segments[2] ?? null;
        $id = $segments[3] ?? null;

        if($subaction == 'users') {
            if($subsubaction == 'delete' && $id) {
                $controller->deleteUser($id);
            } elseif($subsubaction == 'role' && $id) {
                $controller->changeUserRole($id);
            } else {
                $controller->users();
            }
        } elseif($subaction == 'posts') {
            if($subsubaction == 'delete' && $id) {
                $controller->deletePost($id);
            } elseif($subsubaction == 'status' && $id) {
                $controller->changePostStatus($id);
            } else {
                $controller->posts();
            }
        } elseif($subaction == 'comments') {
            if($subsubaction == 'delete' && $id) {
                $controller->deleteComment($id);
            } else {
                $controller->comments();
            }
        } else {
            $controller->index();
        }
        break;

    default:
        // 404 - Página no encontrada
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<a href='" . BASE_URL . "'>Volver al inicio</a>";
        break;
}
?>
