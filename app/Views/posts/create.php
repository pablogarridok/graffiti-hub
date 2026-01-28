<?php $title = 'Crear Post'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Crear Nuevo Post</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/posts/store" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Título *</label>
            <input type="text" name="title" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Contenido *</label>
            <textarea name="content" rows="10" required 
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Imagen</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            <small class="text-gray-500">Formatos permitidos: JPG, PNG, GIF (máx. 5MB)</small>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Estado *</label>
            <select name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <option value="draft">Borrador</option>
                <option value="published">Publicado</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" 
                    class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                Crear Post
            </button>
            <a href="<?= BASE_URL ?>/" 
               class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
