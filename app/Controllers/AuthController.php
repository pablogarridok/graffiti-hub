<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Piece;

class AuthController {
    
    private $userModel;
    private $pieceModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->pieceModel = new Piece();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        require VIEWS_PATH . '/auth/login.php';
    }
    
    /**
     * Procesar login
     */
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validaciones
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor completa todos los campos';
            header('Location: /login');
            exit;
        }
        
        // Buscar usuario por email
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Credenciales incorrectas';
            header('Location: /login');
            exit;
        }
        
        // Verificar si el usuario está bloqueado
        if (isset($user['status']) && $user['status'] === 'blocked') {
            $_SESSION['error'] = 'Tu cuenta ha sido bloqueada. Contacta al administrador.';
            header('Location: /login');
            exit;
        }
        
        // Login exitoso
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['user_role'] = $user['role'] ?? 'user'; // Guardar el rol en sesión
        
        $_SESSION['success'] = '¡Bienvenido de vuelta, ' . $user['nombre'] . '!';
        header('Location: /');
        exit;
    }

    
    /**
     * Mostrar formulario de registro
     */
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        require VIEWS_PATH . '/auth/register.php';
    }
    
    /**
     * Procesar registro
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        // Validaciones
        if (empty($username) || empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor completa todos los campos';
            header('Location: /register');
            exit;
        }
        
        // Validar username (solo letras, números y guión bajo)
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $_SESSION['error'] = 'El username debe tener entre 3 y 20 caracteres (solo letras, números y _)';
            header('Location: /register');
            exit;
        }
        
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email no válido';
            header('Location: /register');
            exit;
        }
        
        // Validar contraseña
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: /register');
            exit;
        }
        
        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            header('Location: /register');
            exit;
        }
        
        // Verificar si el username ya existe
        if ($this->userModel->findByUsername($username)) {
            $_SESSION['error'] = 'El username ya está en uso';
            header('Location: /register');
            exit;
        }
        
        // Verificar si el email ya existe
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            header('Location: /register');
            exit;
        }
        
        // Crear usuario
        $data = [
            'username' => $username,
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            // Auto-login después del registro
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['nombre'] = $nombre;
            
            $_SESSION['success'] = '¡Cuenta creada con éxito! Bienvenido a GraffitiHub';
            header('Location: /');
        } else {
            $_SESSION['error'] = 'Error al crear la cuenta. Inténtalo de nuevo.';
            header('Location: /register');
        }
        exit;
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
    
    /**
     * Mostrar perfil de usuario
     */
    public function profile($username) {
        $user = $this->userModel->findByUsername($username);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: /');
            exit;
        }
        
        // Obtener piezas del usuario
        $pieces = $this->pieceModel->getByUserId($user['id'], 12, 0);
        
        // Contar estadísticas
        $stats = [
            'pieces_count' => $this->pieceModel->countByUserId($user['id']),
            'total_likes' => $this->userModel->getTotalLikes($user['id'])
        ];
        
        // Verificar si el usuario logueado está viendo su propio perfil
        $isOwnProfile = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $user['id'];
        
        require VIEWS_PATH . '/profile/show.php';
    }
    
    /**
     * Mostrar formulario de edición de perfil
     */
    public function editProfile() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        $user = $this->userModel->findById($_SESSION['user_id']);
        require VIEWS_PATH . '/profile/edit.php';
    }
    
    /**
     * Actualizar perfil
     */
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile/edit');
            exit;
        }
        
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'ciudad' => trim($_POST['ciudad'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? '')
        ];
        
        // Validaciones básicas
        if (empty($data['nombre'])) {
            $_SESSION['error'] = 'El nombre es obligatorio';
            header('Location: /profile/edit');
            exit;
        }
        
        if ($this->userModel->update($_SESSION['user_id'], $data)) {
            $_SESSION['nombre'] = $data['nombre'];
            $_SESSION['success'] = 'Perfil actualizado correctamente';
            
            $user = $this->userModel->findById($_SESSION['user_id']);
            header('Location: /profile/' . $user['username']);
        } else {
            $_SESSION['error'] = 'Error al actualizar el perfil';
            header('Location: /profile/edit');
        }
        exit;
    }
}