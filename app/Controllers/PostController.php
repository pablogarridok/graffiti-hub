<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/n8n.php';
require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Models/Comment.php';
require_once __DIR__ . '/../Services/NotificationService.php';

class PostController {
    private $db;
    private $post;
    private $comment;
    private $notificationService;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->post = new Post($this->db);
        $this->comment = new Comment($this->db);
        $this->notificationService = new NotificationService();
    }

    // Página principal
    public function index() {
        $posts = $this->post->getAllPublished();
        require_once __DIR__ . '/../Views/posts/index.php';
    }

    // Ver detalle de un post
    public function show($id) {
        $post = $this->post->getById($id);
        
        if(!$post) {
            $_SESSION['error'] = 'Post no encontrado';
            redirect('/');
        }

        if($post['status'] != 'published' && !isAdmin() && $_SESSION['user_id'] != $post['user_id']) {
            $_SESSION['error'] = 'No tienes permiso para ver este post';
            redirect('/');
        }

        //Obtener posts relacionados
        $related_posts = $this->post->getRelatedPosts($id, $post['title']);
        
        $comments = $this->comment->getByPost($id);
        require_once __DIR__ . '/../Views/posts/show.php';
    }
    
    // Mostrar formulario de crear post
    public function create() {
        if(!isLoggedIn()) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            redirect('/login');
        }
        require_once __DIR__ . '/../Views/posts/create.php';
    }

    // Guardar nuevo post
    public function store() {
        if(!isLoggedIn()) {
            redirect('/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            validateCsrf();
            $title = clean($_POST['title']);
            $content = clean($_POST['content']);
            $status = clean($_POST['status']);
            $image = null;

            // Validaciones
            if(empty($title) || empty($content)) {
                $_SESSION['error'] = 'El título y contenido son obligatorios';
                redirect('/posts/create');
            }

            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                // Validar tipo de archivo
                if(!in_array(strtolower($filetype), $allowed)) {
                    $_SESSION['error'] = 'Solo se permiten imágenes (jpg, jpeg, png, gif)';
                    redirect('/posts/create');
                }

                // Validar tamaño (max 5MB)
                if($_FILES['image']['size'] > 5242880) {
                    $_SESSION['error'] = 'La imagen no debe superar los 5MB';
                    redirect('/posts/create');
                }

                // Generar nombre único
                $image = uniqid() . '.' . $filetype;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $image);
            }

            // Crear post
            try {
                $post_id = $this->post->create($_SESSION['user_id'], $title, $content, $image, $status);
                
                if($post_id) {
                    //Enviar notificación si el post es publicado
                    if($status === 'published') {
                        $new_post = $this->post->getById($post_id);
                        
                        // Intentar enviar notificación
                        $notification_sent = $this->notificationService->sendNewPostNotification(
                            $new_post, 
                            $_SESSION['username']
                        );
                        
                        
                    }
                    
                    $_SESSION['success'] = 'Post creado exitosamente';
                    redirect('/');
                } else {
                    throw new Exception("Error al crear registro.");
                } 
            } catch (Exception $e) {
                error_log("Error en create post: " . $e->getMessage());
                $_SESSION['error'] = 'Error del sistema al crear el post.';
                redirect('/posts/create');
            }
        }
    }

    // Mostrar formulario de editar post
    public function edit($id) {
        if(!isLoggedIn()) {
            redirect('/login');
        }

        $post = $this->post->getById($id);

        if(!$post) {
            $_SESSION['error'] = 'Post no encontrado';
            redirect('/');
        }

        // Verificar permisos
        if($_SESSION['user_id'] != $post['user_id'] && !isAdmin()) {
            $_SESSION['error'] = 'No tienes permiso para editar este post';
            redirect('/');
        }

        require_once __DIR__ . '/../Views/posts/edit.php';
    }

    // Actualizar post
    public function update($id) {
        if(!isLoggedIn()) {
            redirect('/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            validateCsrf();
            $post = $this->post->getById($id);

            // Verificar permisos
            if($_SESSION['user_id'] != $post['user_id'] && !isAdmin()) {
                $_SESSION['error'] = 'No tienes permiso';
                redirect('/');
            }

            $title = clean($_POST['title']);
            $content = clean($_POST['content']);
            $status = clean($_POST['status']);
            $old_status = $post['status'];
            $image = null;

            // Procesar nueva imagen si existe
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                if(in_array(strtolower($filetype), $allowed) && $_FILES['image']['size'] <= 5242880) {
                    // Eliminar imagen anterior
                    if($post['image'] && file_exists(UPLOAD_PATH . $post['image'])) {
                        unlink(UPLOAD_PATH . $post['image']);
                    }

                    $image = uniqid() . '.' . $filetype;
                    move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $image);
                }
            }

            // Actualizar post
                if($this->post->update($id, $title, $content, $image, $status)) {
                    
                    // Enviar notificación si el post pasa de borrador a publicado
                    if($old_status !== 'published' && $status === 'published') {
                        $updated_post = $this->post->getById($id);
                        
                        // Enviar notificación
                        $this->notificationService->sendNewPostNotification(
                            $updated_post, 
                            $updated_post['username']
                        );
                        
                        error_log("Notificación enviada para el post actualizado ID: {$id}");
                    }
                    
                    $_SESSION['success'] = 'Post actualizado exitosamente';
                    redirect('/posts/' . $id);
                } else {
                $_SESSION['error'] = 'Error al actualizar el post';
                redirect('/posts/edit/' . $id);
            }
        }
    }

    // Eliminar post
    public function delete($id) {
        if(!isLoggedIn()) {
            redirect('/login');
        }

       
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            validateCsrf();
        } else {
             $_SESSION['error'] = 'Método no permitido. Use el botón eliminar.';
             redirect('/posts/' . $id);
        }

        $post = $this->post->getById($id);

        // Verificar permisos
        if($_SESSION['user_id'] != $post['user_id'] && !isAdmin()) {
            $_SESSION['error'] = 'No tienes permiso';
            redirect('/');
        }

        // Eliminar imagen
        if($post['image'] && file_exists(UPLOAD_PATH . $post['image'])) {
            unlink(UPLOAD_PATH . $post['image']);
        }

        try {
            if($this->post->delete($id)) {
                $_SESSION['success'] = 'Post eliminado';
                redirect('/');
            } else {
                 throw new Exception("Error al eliminar registro.");
            }
        } catch (Exception $e) {
            error_log("Error en delete post: " . $e->getMessage());
            $_SESSION['error'] = 'Error del sistema al eliminar.';
            redirect('/');
        }
    }

    // Agregar comentario
    public function addComment($post_id) {
        if(!isLoggedIn()) {
            redirect('/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            validateCsrf();
            $content = clean($_POST['content']);

            if(empty($content)) {
                $_SESSION['error'] = 'El comentario no puede estar vacío';
                redirect('/posts/' . $post_id);
            }

            if($this->comment->create($post_id, $_SESSION['user_id'], $content)) {
                $_SESSION['success'] = 'Comentario agregado';
                redirect('/posts/' . $post_id);
            } else {
                $_SESSION['error'] = 'Error al agregar comentario';
                redirect('/posts/' . $post_id);
            }
        }
    }
}
?>