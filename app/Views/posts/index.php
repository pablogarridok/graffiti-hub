<?php $title = 'Inicio'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-medium p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold text-gray-light">Bienvenido al Blog de Graffiti puntero en España</h1>
    <p class="text-gray-400 mt-2">Comparte y descubre arte urbano</p>
</div>

<?php if(empty($posts)): ?>
    <div class="bg-gray-medium p-6 rounded-lg shadow-md text-center">
        <p class="text-gray-400">No hay posts publicados aún.</p>
        <?php if(isLoggedIn()): ?>
            <a href="<?= BASE_URL ?>/posts/create" class="inline-block mt-4 bg-neon-green text-dark-bg px-6 py-2 rounded hover:bg-yellow-hub transition-colors">
                Crear el primer post
            </a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($posts as $post): ?>
            <div class="bg-gray-medium rounded-lg shadow-md overflow-hidden hover:border-2 hover:border-yellow-hub transition-all">
                <?php if($post['image']): ?>
                    <img src="<?= UPLOAD_URL . $post['image'] ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>" 
                         class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-dark-alt flex items-center justify-center">
                        <span class="text-yellow-hub text-4xl">🎨</span>
                    </div>
                <?php endif; ?>
                
                <div class="p-4">
                    <h3 class="text-xl font-bold mb-2 text-gray-light">
                        <a href="<?= BASE_URL ?>/posts/<?= $post['id'] ?>" class="hover:text-yellow-hub transition-colors">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    
                    <p class="text-gray-400 mb-3">
                        <?= substr(htmlspecialchars($post['content']), 0, 100) ?>...
                    </p>
                    
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>Por <?= htmlspecialchars($post['username']) ?></span>
                        <span><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>/posts/<?= $post['id'] ?>" 
                       class="inline-block mt-3 text-yellow-hub hover:text-yellow-gold transition-colors">
                        Leer más →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>