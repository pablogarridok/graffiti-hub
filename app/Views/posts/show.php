<?php $title = $post['title']; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <!-- Encabezado del post -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($post['title']) ?></h1>
        
        <div class="flex justify-between items-center text-gray-600 text-sm mb-4">
            <span>Por <?= htmlspecialchars($post['username']) ?></span>
            <span><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
        </div>

        <?php if($post['status'] == 'draft'): ?>
            <span class="inline-block bg-yellow-200 text-yellow-800 px-3 py-1 rounded text-sm">
                Borrador
            </span>
        <?php endif; ?>
    </div>

    <!-- Imagen del post -->
    <?php if($post['image']): ?>
        <img src="<?= UPLOAD_URL . $post['image'] ?>" 
             alt="<?= htmlspecialchars($post['title']) ?>" 
             class="w-full max-h-[600px] object-cover rounded mb-6">
    <?php endif; ?>

    <!-- Contenido del post -->
    <div class="prose max-w-none mb-6">
        <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($post['content']) ?></p>
    </div>

    <!-- Botones de acción -->
    <?php if(isLoggedIn() && ($_SESSION['user_id'] == $post['user_id'] || isAdmin())): ?>
        <div class="flex gap-2 mb-6">
            <a href="<?= BASE_URL ?>/posts/edit/<?= $post['id'] ?>" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Editar
            </a>
            <a href="<?= BASE_URL ?>/posts/delete/<?= $post['id'] ?>" 
               onclick="return confirm('¿Estás seguro de eliminar este post?')"
               class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Eliminar
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Sección de comentarios -->
<div class="bg-white p-6 rounded-lg shadow-md mt-6">
    <h2 class="text-2xl font-bold mb-4">Comentarios (<?= count($comments) ?>)</h2>

    <!-- Formulario para agregar comentario -->
    <?php if(isLoggedIn()): ?>
        <form method="POST" action="<?= BASE_URL ?>/posts/<?= $post['id'] ?>/comment" class="mb-6">
            <textarea name="content" rows="3" required placeholder="Escribe tu comentario..."
                      class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"></textarea>
            <button type="submit" 
                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Comentar
            </button>
        </form>
    <?php else: ?>
        <p class="text-gray-600 mb-6">
            <a href="<?= BASE_URL ?>/login" class="text-blue-500 hover:underline">Inicia sesión</a> para comentar
        </p>
    <?php endif; ?>

    <!-- Lista de comentarios -->
    <?php if(empty($comments)): ?>
        <p class="text-gray-500">No hay comentarios aún. ¡Sé el primero en comentar!</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach($comments as $comment): ?>
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold"><?= htmlspecialchars($comment['username']) ?></span>
                        <span class="text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></span>
                    </div>
                    <p class="text-gray-700"><?= htmlspecialchars($comment['content']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<a href="<?= BASE_URL ?>/" class="inline-block mt-6 text-blue-500 hover:underline">
    ← Volver al inicio
</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
