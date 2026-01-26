<?php

namespace App\Controllers;

use App\Models\Piece;
use App\Models\Style;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;

class PieceController {
    
    private $pieceModel;
    private $styleModel;
    private $commentModel;
    private $likeModel;
    private $userModel;
    
    public function __construct() {
        $this->pieceModel = new Piece();
        $this->styleModel = new Style();
        $this->commentModel = new Comment();
        $this->likeModel = new Like();
        $this->userModel = new User();
    }
    
    /**
     * Mostrar feed principal
     */
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $pieces = $this->pieceModel->getAll($limit, $offset);
        $totalPieces = $this->pieceModel->getTotalCount();
        $totalPages = ceil($totalPieces / $limit);
        $styles = $this->styleModel->getAll();
        
        require VIEWS_PATH . '/layouts/header.php';
        require BASE_PATH . '/app/home.php';
        require VIEWS_PATH . '/layouts/footer.php';
    }
    
    /**
     * Mostrar una pieza individual
     */
    public function show($id) {
        $piece = $this->pieceModel->getById($id);
        
        if (!$piece) {
            $_SESSION['error'] = 'Pieza no encontrada';
            header('Location: /');
            exit;
        }
        
        // Obtener comentarios
        $comments = $this->commentModel->getByPieceId($id);
        
        // Verificar si el usuario actual dio like
        $userLiked = false;
        if (isset($_SESSION['user_id'])) {
            $userLiked = $this->likeModel->hasLiked($_SESSION['user_id'], $id);
        }
        
        // Verificar si es el propietario
        $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $piece['user_id'];
        
        require VIEWS_PATH . '/piece/detail.php';
    }
    
    /**
     * Mostrar formulario de subida
     */
    public function upload() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión para subir piezas';
            header('Location: /login');
            exit;
        }
        
        $styles = $this->styleModel->getAll();
        require VIEWS_PATH . '/piece/upload.php';
    }
    
    /**
     * Procesar subida de pieza
     */
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /piece/upload');
            exit;
        }
        
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $estilo_id = !empty($_POST['estilo_id']) ? (int)$_POST['estilo_id'] : null;
        
        // Validaciones
        if (empty($titulo)) {
            $_SESSION['error'] = 'El título es obligatorio';
            header('Location: /piece/upload');
            exit;
        }
        
        // Procesar imagen
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Debes subir una imagen';
            header('Location: /piece/upload');
            exit;
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['imagen']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = 'Formato de imagen no válido';
            header('Location: /piece/upload');
            exit;
        }
        
        // Crear nombre único para la imagen
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('piece_') . '.' . $extension;
        $uploadPath = PUBLIC_PATH . '/uploads/pieces/' . $filename;
        
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
            $_SESSION['error'] = 'Error al subir la imagen';
            header('Location: /piece/upload');
            exit;
        }
        
        // Guardar en base de datos
        $data = [
            'user_id' => $_SESSION['user_id'],
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $filename,
            'ciudad' => $ciudad,
            'estilo_id' => $estilo_id
        ];
        
        $pieceId = $this->pieceModel->create($data);
        
        if ($pieceId) {
            $_SESSION['success'] = '¡Pieza subida con éxito!';
            header('Location: /piece/' . $pieceId);
        } else {
            $_SESSION['error'] = 'Error al guardar la pieza';
            header('Location: /piece/upload');
        }
        exit;
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        $piece = $this->pieceModel->getById($id);
        
        if (!$piece) {
            $_SESSION['error'] = 'Pieza no encontrada';
            header('Location: /');
            exit;
        }
        
        // Verificar que sea el propietario
        if ($piece['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'No tienes permiso para editar esta pieza';
            header('Location: /piece/' . $id);
            exit;
        }
        
        $styles = $this->styleModel->getAll();
        require VIEWS_PATH . '/piece/edit.php';
    }
    
    /**
     * Actualizar pieza
     */
    public function update($id) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        $piece = $this->pieceModel->getById($id);
        
        if (!$piece || $piece['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'No tienes permiso';
            header('Location: /');
            exit;
        }
        
        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'ciudad' => trim($_POST['ciudad'] ?? ''),
            'estilo_id' => !empty($_POST['estilo_id']) ? (int)$_POST['estilo_id'] : null
        ];
        
        if (empty($data['titulo'])) {
            $_SESSION['error'] = 'El título es obligatorio';
            header('Location: /piece/' . $id . '/edit');
            exit;
        }
        
        if ($this->pieceModel->update($id, $data)) {
            $_SESSION['success'] = 'Pieza actualizada correctamente';
            header('Location: /piece/' . $id);
        } else {
            $_SESSION['error'] = 'Error al actualizar la pieza';
            header('Location: /piece/' . $id . '/edit');
        }
        exit;
    }
    
    /**
     * Eliminar pieza
     */
    public function delete($id) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        $piece = $this->pieceModel->getById($id);
        
        if (!$piece || $piece['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'No tienes permiso';
            header('Location: /');
            exit;
        }
        
        // Eliminar archivo de imagen
        $imagePath = PUBLIC_PATH . '/uploads/pieces/' . $piece['imagen'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        if ($this->pieceModel->delete($id)) {
            $_SESSION['success'] = 'Pieza eliminada correctamente';
            header('Location: /profile/' . $_SESSION['username']);
        } else {
            $_SESSION['error'] = 'Error al eliminar la pieza';
            header('Location: /piece/' . $id);
        }
        exit;
    }
    
    /**
     * Toggle like (API endpoint)
     */
    public function toggleLike() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $pieceId = (int)($data['piece_id'] ?? 0);
        
        if (!$pieceId) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }
        
        $result = $this->likeModel->toggle($_SESSION['user_id'], $pieceId);
        
        if ($result !== false) {
            $likesCount = $this->likeModel->countByPieceId($pieceId);
            echo json_encode([
                'success' => true,
                'liked' => $result,
                'likes_count' => $likesCount
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al procesar like']);
        }
        exit;
    }
    
    /**
     * Añadir comentario
     */
    public function addComment($pieceId) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /piece/' . $pieceId);
            exit;
        }
        
        $contenido = trim($_POST['contenido'] ?? '');
        
        if (empty($contenido)) {
            $_SESSION['error'] = 'El comentario no puede estar vacío';
            header('Location: /piece/' . $pieceId);
            exit;
        }
        
        $data = [
            'piece_id' => $pieceId,
            'user_id' => $_SESSION['user_id'],
            'contenido' => $contenido
        ];
        
        if ($this->commentModel->create($data)) {
            $_SESSION['success'] = 'Comentario añadido';
        } else {
            $_SESSION['error'] = 'Error al añadir comentario';
        }
        
        header('Location: /piece/' . $pieceId);
        exit;
    }
    
    /**
     * Buscar piezas
     */
    public function search() {
        $term = trim($_GET['q'] ?? '');
        $pieces = [];
        $styles = $this->styleModel->getAll();
        
        if (!empty($term)) {
            $pieces = $this->pieceModel->search($term);
        }
        
        require VIEWS_PATH . '/layouts/header.php';
        require BASE_PATH . '/app/home.php';
        require VIEWS_PATH . '/layouts/footer.php';
    }
    
    /**
     * Filtrar por estilo
     */
    public function filterByStyle($styleSlug) {
        $style = $this->styleModel->getBySlug($styleSlug);
        
        if (!$style) {
            $_SESSION['error'] = 'Estilo no encontrado';
            header('Location: /');
            exit;
        }
        
        $pieces = $this->pieceModel->getByStyleId($style['id']);
        $styles = $this->styleModel->getAll();
        
        require VIEWS_PATH . '/layouts/header.php';
        require BASE_PATH . '/app/home.php';
        require VIEWS_PATH . '/layouts/footer.php';
    }
}