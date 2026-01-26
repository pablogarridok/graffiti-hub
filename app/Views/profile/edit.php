<?php
$pageTitle = 'Editar Perfil - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="form-container">
    <h1 class="form-title">Editar Perfil</h1>
    
    <form action="/profile/edit" method="POST">
        <div class="form-group">
            <label for="nombre" class="form-label">Nombre Completo *</label>
            <input type="text" 
                   id="nombre" 
                   name="nombre" 
                   class="form-input" 
                   value="<?= htmlspecialchars($user['nombre']) ?>"
                   required>
        </div>
        
        <div class="form-group">
            <label for="bio" class="form-label">Biografía</label>
            <textarea id="bio" 
                      name="bio" 
                      class="form-textarea" 
                      maxlength="300"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            <small class="text-muted">Máximo 300 caracteres</small>
        </div>
        
        <div class="form-group">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" 
                   id="ciudad" 
                   name="ciudad" 
                   class="form-input" 
                   value="<?= htmlspecialchars($user['ciudad'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="instagram" class="form-label">Instagram (sin @)</label>
            <input type="text" 
                   id="instagram" 
                   name="instagram" 
                   class="form-input" 
                   value="<?= htmlspecialchars($user['instagram'] ?? '') ?>"
                   placeholder="username">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
        </div>
        
        <div class="form-group text-center">
            <a href="/profile/<?= htmlspecialchars($user['username']) ?>" class="btn-link">Cancelar</a>
        </div>
    </form>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>