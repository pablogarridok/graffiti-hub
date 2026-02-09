<?php $title = $post['title']; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-medium p-6 rounded-lg shadow-md">
    <!-- Encabezado del post -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2 text-gray-light"><?= htmlspecialchars($post['title']) ?></h1>
        
        <div class="flex justify-between items-center text-gray-400 text-sm mb-4">
            <span>Por <?= htmlspecialchars($post['username']) ?></span>
            <span><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
        </div>
        
        <?php if($post['status'] == 'draft'): ?>
            <span class="inline-block bg-yellow-hub bg-opacity-20 text-yellow-hub border border-yellow-hub px-3 py-1 rounded text-sm">
                Borrador
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Imagen del post -->
    <?php if($post['image']): ?>
        <img src="<?= UPLOAD_URL . $post['image'] ?>" 
             alt="<?= htmlspecialchars($post['title']) ?>" 
             class="w-full max-h-[600px] object-cover rounded mb-6 border-2 border-dark-alt">
    <?php endif; ?>
    
    <!-- Contenido del post -->
    <div class="prose max-w-none mb-6">
        <p class="text-gray-light whitespace-pre-wrap"><?= htmlspecialchars($post['content']) ?></p>
    </div>
    
    <!-- Botones de acción -->
    <?php if(isLoggedIn() && ($_SESSION['user_id'] == $post['user_id'] || isAdmin())): ?>
        <div class="flex gap-2 mb-6">
            <a href="<?= BASE_URL ?>/posts/edit/<?= $post['id'] ?>" 
               class="bg-yellow-hub text-dark-bg px-4 py-2 rounded hover:bg-yellow-gold transition-colors font-semibold">
                Editar
            </a>
            <form action="<?= BASE_URL ?>/posts/delete/<?= $post['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este post?')">
                <?= csrf_field() ?>
                <button type="submit" 
                   class="bg-neon-magenta text-white px-4 py-2 rounded hover:bg-red-500 transition-colors font-semibold">
                    Eliminar
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Sección de comentarios -->
<div class="bg-gray-medium p-6 rounded-lg shadow-md mt-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-light">Comentarios (<?= count($comments) ?>)</h2>
    
    <!-- Formulario para agregar comentario -->
    <?php if(isLoggedIn()): ?>
        <form method="POST" action="<?= BASE_URL ?>/posts/<?= $post['id'] ?>/comment" class="mb-6">
            <?= csrf_field() ?>
            <textarea name="content" rows="3" required placeholder="Escribe tu comentario..."
                      class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light placeholder-gray-500 focus:outline-none focus:border-yellow-hub"></textarea>
            <button type="submit" 
                    class="mt-2 bg-neon-green text-dark-bg px-4 py-2 rounded hover:bg-yellow-hub transition-colors font-semibold">
                Comentar
            </button>
        </form>
    <?php else: ?>
        <p class="text-gray-400 mb-6">
            <a href="<?= BASE_URL ?>/login" class="text-yellow-hub hover:text-yellow-gold transition-colors">Inicia sesión</a> para comentar
        </p>
    <?php endif; ?>
    
    <!-- Lista de comentarios -->
    <?php if(empty($comments)): ?>
        <p class="text-gray-400">No hay comentarios aún. ¡Sé el primero en comentar!</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach($comments as $comment): ?>
                <div class="border-l-4 border-yellow-hub pl-4 py-2 bg-dark-alt rounded">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-gray-light"><?= htmlspecialchars($comment['username']) ?></span>
                        <span class="text-sm text-gray-400"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></span>
                    </div>
                    <p class="text-gray-light"><?= htmlspecialchars($comment['content']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<a href="<?= BASE_URL ?>/" class="inline-block mt-6 text-yellow-hub hover:text-yellow-gold transition-colors">
    ← Volver al inicio
</a>

<?php if(!empty($related_posts)): ?>
<div class="mt-8 mb-8">
    <h3 class="text-xl font-bold text-yellow-hub mb-4 font-street">TAMBIÉN TE PUEDE INTERESAR:</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php foreach($related_posts as $r): ?>
            <a href="<?= BASE_URL ?>/posts/<?= $r['id'] ?>" class="block bg-dark-alt p-3 rounded hover:border hover:border-yellow-hub transition-all">
                <?php if($r['image']): ?>
                    <img src="<?= UPLOAD_URL . $r['image'] ?>" class="w-full h-24 object-cover rounded mb-2">
                <?php endif; ?>
                <h4 class="text-gray-light font-bold text-sm truncate"><?= htmlspecialchars($r['title']) ?></h4>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>