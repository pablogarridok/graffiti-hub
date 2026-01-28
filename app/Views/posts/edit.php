<?php $title = 'Editar Post'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Editar Post</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/posts/update/<?= $post['id'] ?>" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Título *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Contenido *</label>
            <textarea name="content" rows="10" required 
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"><?= htmlspecialchars($post['content']) ?></textarea>
        </div>

        <!-- Imagen actual -->
        <?php if($post['image']): ?>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Imagen Actual</label>
                <img src="<?= UPLOAD_URL . $post['image'] ?>" alt="Current" class="w-48 h-32 object-cover rounded">
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nueva Imagen (opcional)</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            <small class="text-gray-500">Deja vacío para mantener la imagen actual</small>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Estado *</label>
            <select name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <option value="draft" <?= $post['status'] == 'draft' ? 'selected' : '' ?>>Borrador</option>
                <option value="published" <?= $post['status'] == 'published' ? 'selected' : '' ?>>Publicado</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" 
                    class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Actualizar Post
            </button>
            <a href="<?= BASE_URL ?>/posts/<?= $post['id'] ?>" 
               class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
