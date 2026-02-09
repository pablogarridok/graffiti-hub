<?php $title = 'Crear Post'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto bg-gray-medium p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-light">Crear Nuevo Post</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/posts/store" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block text-gray-light mb-2">Título *</label>
            <input type="text" name="title" required 
                   class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light focus:outline-none focus:border-yellow-hub">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-light mb-2">Contenido *</label>
            <textarea name="content" rows="10" required 
                      class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light focus:outline-none focus:border-yellow-hub"></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-light mb-2">Imagen</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light focus:outline-none focus:border-yellow-hub">
            <small class="text-gray-400">Formatos permitidos: JPG, PNG, GIF (máx. 5MB)</small>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-light mb-2">Estado *</label>
            <select name="status" 
                    class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light focus:outline-none focus:border-yellow-hub">
                <option value="draft">Borrador</option>
                <option value="published">Publicado</option>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" 
                    class="w-full bg-dark-alt text-gray-light py-2 rounded hover:bg-yellow-hub transition-colors font-semibold">
                Crear Post
            </button>
            <a href="<?= BASE_URL ?>/" 
               class="bg-gray-500 text-gray-light px-6 py-2 rounded hover:bg-gray-600 transition-colors inline-block text-center">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>