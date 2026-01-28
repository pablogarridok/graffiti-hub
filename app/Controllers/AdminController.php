<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Models/Comment.php';

class AdminController {
    private $db;
    private $user;
    private $post;
    private $comment;

    public function __construct() {
        // Verificar que sea admin
        if(!isAdmin()) {
            $_SESSION['error'] = 'Acceso denegado';
            redirect('/');
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
        $this->post = new Post($this->db);
        $this->comment = new Comment($this->db);
    }

    // Dashboard principal
    public function index() {
        $users = $this->user->getAll();
        $posts = $this->post->getAll();
        $comments = $this->comment->getAll();
        
        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    // Gestión de usuarios
    public function users() {
        $users = $this->user->getAll();
        require_once __DIR__ . '/../Views/admin/users.php';
    }

    // Eliminar usuario
    public function deleteUser($id) {
        if($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'No puedes eliminarte a ti mismo';
            redirect('/admin/users');
        }

        if($this->user->delete($id)) {
            $_SESSION['success'] = 'Usuario eliminado';
        } else {
            $_SESSION['error'] = 'Error al eliminar usuario';
        }
        redirect('/admin/users');
    }

    // Cambiar rol de usuario
    public function changeUserRole($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role = clean($_POST['role']);

            if($id == $_SESSION['user_id']) {
                $_SESSION['error'] = 'No puedes cambiar tu propio rol';
                redirect('/admin/users');
            }

            if($this->user->changeRole($id, $role)) {
                $_SESSION['success'] = 'Rol actualizado';
            } else {
                $_SESSION['error'] = 'Error al actualizar rol';
            }
            redirect('/admin/users');
        }
    }

    // Gestión de posts
    public function posts() {
        $posts = $this->post->getAll();
        require_once __DIR__ . '/../Views/admin/posts.php';
    }

    // Cambiar estado de post
    public function changePostStatus($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = clean($_POST['status']);

            if($this->post->changeStatus($id, $status)) {
                $_SESSION['success'] = 'Estado actualizado';
            } else {
                $_SESSION['error'] = 'Error al actualizar estado';
            }
            redirect('/admin/posts');
        }
    }

    // Eliminar post desde admin
    public function deletePost($id) {
        $post = $this->post->getById($id);

        // Eliminar imagen
        if($post['image'] && file_exists(UPLOAD_PATH . $post['image'])) {
            unlink(UPLOAD_PATH . $post['image']);
        }

        if($this->post->delete($id)) {
            $_SESSION['success'] = 'Post eliminado';
        } else {
            $_SESSION['error'] = 'Error al eliminar post';
        }
        redirect('/admin/posts');
    }

    // Gestión de comentarios
    public function comments() {
        $comments = $this->comment->getAll();
        require_once __DIR__ . '/../Views/admin/comments.php';
    }

    // Eliminar comentario
    public function deleteComment($id) {
        if($this->comment->delete($id)) {
            $_SESSION['success'] = 'Comentario eliminado';
        } else {
            $_SESSION['error'] = 'Error al eliminar comentario';
        }
        redirect('/admin/comments');
    }
}
?>
