<?php
$pageTitle = 'Editar Pieza - ' . APP_NAME;
?>

<div class="form-container">
    <h1 class="form-title">Editar Pieza</h1>
    
    <form action="/piece/<?= $piece['id'] ?>/edit" method="POST">
        <div class="form-group">
            <label for="titulo" class="form-label">Título *</label>
            <input type="text" 
                   id="titulo" 
                   name="titulo" 
                   class="form-input" 
                   value="<?= htmlspecialchars($piece['titulo']) ?>"
                   required 
                   maxlength="100">
        </div>
        
        <div class="form-group">
            <label for="descripcion" class="form-label">Descripción *</label>
            <textarea id="descripcion" 
                      name="descripcion" 
                      class="form-textarea" 
                      required 
                      maxlength="500"><?= htmlspecialchars($piece['descripcion']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" 
                   id="ciudad" 
                   name="ciudad" 
                   class="form-input" 
                   value="<?= htmlspecialchars($piece['ciudad'] ?? '') ?>"
                   maxlength="100">
        </div>
        
        <div class="form-group">
            <label for="estilo_id" class="form-label">Estilo</label>
            <select id="estilo_id" name="estilo_id" class="form-select">
                <option value="">-- Selecciona un estilo --</option>
                <?php foreach ($styles as $style): ?>
                    <option value="<?= $style['id'] ?>" <?= $piece['estilo_id'] == $style['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($style['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Actualizar Pieza</button>
        </div>
        
        <div class="form-group text-center">
            <a href="/piece/<?= $piece['id'] ?>" class="btn-link">Cancelar</a>
        </div>
    </form>
</div>