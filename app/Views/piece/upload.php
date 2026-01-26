<?php 
$pageTitle = 'Subir Pieza - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php'; 

// Obtener estilos para el select
use App\Models\Style;
$styleModel = new Style();
$styles = $styleModel->getAll();
?>

<div class="upload-container">
    <h2>Subir Nueva Pieza</h2>
    
    <form action="/piece/upload" method="POST" enctype="multipart/form-data" id="uploadForm">
        <div class="form-group">
            <label for="imagen">Imagen * (máx. 5MB)</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" required>
            <div id="imagePreview" class="image-preview" style="display: none;">
                <img id="previewImg" src="" alt="Preview">
            </div>
        </div>
        
        <div class="form-group">
            <label for="titulo">Título (opcional)</label>
            <input type="text" name="titulo" id="titulo" 
                   placeholder="Dale un título a tu pieza"
                   maxlength="255">
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción (opcional)</label>
            <textarea name="descripcion" id="descripcion" rows="4" 
                      placeholder="Cuenta algo sobre esta pieza, la técnica, el lugar..."></textarea>
        </div>
        
        <div class="form-group">
            <label for="ubicacion">Ubicación (opcional)</label>
            <input type="text" name="ubicacion" id="ubicacion" 
                   placeholder="Ej: Barcelona, Spain"
                   maxlength="255">
        </div>
        
        <div class="form-group">
            <label for="style_id">Estilo</label>
            <select name="style_id" id="style_id">
                <option value="">Selecciona un estilo...</option>
                <?php foreach ($styles as $style): ?>
                    <option value="<?= $style['id'] ?>">
                        <?= htmlspecialchars($style['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Subir Pieza</button>
        <a href="/" class="btn" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<script>
// Preview de imagen
document.getElementById('imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>