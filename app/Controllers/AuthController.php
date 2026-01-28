<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    // Mostrar formulario de login
    public function showLogin() {
        if(isLoggedIn()) {
            redirect('/');
        }
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // Procesar login
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = clean($_POST['email']);
            $password = $_POST['password'];

            // Validaciones básicas
            if(empty($email) || empty($password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
                redirect('/login');
            }

            // Validar email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email inválido';
                redirect('/login');
            }

            // Intentar login
            $result = $this->user->login($email, $password);
            
            if($result) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['role'] = $result['role'];
                $_SESSION['success'] = '¡Bienvenido ' . $result['username'] . '!';
                
                // Redireccionar según el rol
                if($result['role'] == 'admin') {
                    redirect('/admin');
                } else {
                    redirect('/');
                }
            } else {
                $_SESSION['error'] = 'Email o contraseña incorrectos';
                redirect('/login');
            }
        }
    }

    // Mostrar formulario de registro
    public function showRegister() {
        if(isLoggedIn()) {
            redirect('/');
        }
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    // Procesar registro
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = clean($_POST['username']);
            $email = clean($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validaciones
            if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
                redirect('/register');
            }

            // Validar longitud de username
            if(strlen($username) < 3) {
                $_SESSION['error'] = 'El username debe tener al menos 3 caracteres';
                redirect('/register');
            }

            // Validar email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email inválido';
                redirect('/register');
            }

            // Validar longitud de contraseña
            if(strlen($password) < 6) {
                $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
                redirect('/register');
            }

            // Verificar que las contraseñas coincidan
            if($password !== $confirm_password) {
                $_SESSION['error'] = 'Las contraseñas no coinciden';
                redirect('/register');
            }

            // Intentar registro
            if($this->user->register($username, $email, $password)) {
                $_SESSION['success'] = 'Registro exitoso. Por favor inicia sesión';
                redirect('/login');
            } else {
                $_SESSION['error'] = 'Error en el registro. El usuario o email ya existe';
                redirect('/register');
            }
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        redirect('/login');
    }
}
?>
