<?php
$pageTitle = 'Registro - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>
 
<div class="auth-container1">
    <h2>Crear Cuenta</h2>
   
    <form action="/register" method="POST" class="auth-form">
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" name="username" id="username" required
                   placeholder="tu_username"
                   pattern="[a-zA-Z0-9_]{3,20}"
                   title="Solo letras, números y guión bajo. Entre 3 y 20 caracteres."
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <small style="color: #808080; font-size: 0.85rem;">Solo letras, números y _. Ejemplo: cool_writer</small>
        </div>
       
        <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" name="nombre" id="nombre" required
                   placeholder="Tu nombre"
                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
        </div>
       
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" required
                   placeholder="tu@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
       
        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" name="password" id="password" required
                   minlength="6"
                   placeholder="Mínimo 6 caracteres">
        </div>
       
        <div class="form-group">
            <label for="password_confirm">Confirmar contraseña *</label>
            <input type="password" name="password_confirm" id="password_confirm" required
                   minlength="6"
                   placeholder="Repite tu contraseña">
        </div>
       
        <button type="submit" class="btn btn-primary">Crear cuenta</button>
    </form>
   
    <p style="text-align: center; margin-top: 20px; color: #808080;">
        ¿Ya tienes cuenta? <a href="/login" style="color: #667eea;">Inicia sesión aquí</a>
    </p>
</div>
 
<?php require VIEWS_PATH . '/layouts/footer.php'; ?>