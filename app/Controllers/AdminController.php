<?php
// app/Controllers/AdminController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Piece;

class AdminController {
    
    private $userModel;
    private $pieceModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->pieceModel = new Piece();
    }
    
    /**
     * Verificar si el usuario actual es admin
     */
    private function requireAdmin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        if (!$user || $user['role'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos de administrador';
            header('Location: /');
            exit;
        }
    }
    
    /**
     * Panel de administración principal
     */
    public function dashboard() {
        $this->requireAdmin();
        
        // Obtener estadísticas
        $stats = [
            'total_users' => $this->userModel->getTotalCount(),
            'total_pieces' => $this->pieceModel->getTotalCount(),
            'blocked_users' => $this->userModel->getBlockedCount(),
        ];
        
        require VIEWS_PATH . '/admin/dashboard.php';
    }
    
    /**
     * Listar todos los usuarios
     */
    public function users() {
        $this->requireAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        if (!empty($search)) {
            $users = $this->userModel->search($search, $limit);
            $totalUsers = count($users);
        } else {
            $users = $this->userModel->getAll($limit, $offset);
            $totalUsers = $this->userModel->getTotalCount();
        }
        
        $totalPages = ceil($totalUsers / $limit);
        
        require VIEWS_PATH . '/admin/users.php';
    }
    
    /**
     * Bloquear usuario
     */
    public function blockUser($userId) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // No permitir bloquear al propio admin
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'No puedes bloquearte a ti mismo';
            header('Location: /admin/users');
            exit;
        }
        
        if ($this->userModel->updateStatus($userId, 'blocked')) {
            $_SESSION['success'] = 'Usuario bloqueado correctamente';
        } else {
            $_SESSION['error'] = 'Error al bloquear el usuario';
        }
        
        header('Location: /admin/users');
        exit;
    }
    
    /**
     * Desbloquear usuario
     */
    public function unblockUser($userId) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        if ($this->userModel->updateStatus($userId, 'active')) {
            $_SESSION['success'] = 'Usuario desbloqueado correctamente';
        } else {
            $_SESSION['error'] = 'Error al desbloquear el usuario';
        }
        
        header('Location: /admin/users');
        exit;
    }
    
    /**
     * Eliminar usuario
     */
    public function deleteUser($userId) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // No permitir eliminar al propio admin
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'No puedes eliminarte a ti mismo';
            header('Location: /admin/users');
            exit;
        }
        
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: /admin/users');
            exit;
        }
        
        // Eliminar las imágenes de las piezas del usuario
        $pieces = $this->pieceModel->getByUserId($userId, 1000, 0);
        foreach ($pieces as $piece) {
            $imagePath = PUBLIC_PATH . '/uploads/pieces/' . $piece['imagen'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        if ($this->userModel->delete($userId)) {
            $_SESSION['success'] = 'Usuario eliminado correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        
        header('Location: /admin/users');
        exit;
    }
}