<?php 
$pageTitle = 'Iniciar Sesión - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php'; 
?>

<div class="auth-container">
    <h2>Iniciar Sesión</h2>
    
    <form action="/login" method="POST" class="auth-form">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required 
                   placeholder="tu@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required 
                   placeholder="••••••••">
        </div>
        
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px; color: #808080;">
        ¿No tienes cuenta? <a href="/register" style="color: #667eea;">Regístrate aquí</a>
    </p>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>