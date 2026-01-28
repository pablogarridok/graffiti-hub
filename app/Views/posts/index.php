<?php $title = 'Inicio'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold">Bienvenido al Blog de Graffiti puntero en España</h1>
    <p class="text-gray-600 mt-2">Comparte y descubre arte urbano</p>
</div>

<?php if(empty($posts)): ?>
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <p class="text-gray-600">No hay posts publicados aún.</p>
        <?php if(isLoggedIn()): ?>
            <a href="<?= BASE_URL ?>/posts/create" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Crear el primer post
            </a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($posts as $post): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if($post['image']): ?>
                    <img src="<?= UPLOAD_URL . $post['image'] ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>" 
                         class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-500 text-4xl">🎨</span>
                    </div>
                <?php endif; ?>
                
                <div class="p-4">
                    <h3 class="text-xl font-bold mb-2">
                        <a href="<?= BASE_URL ?>/posts/<?= $post['id'] ?>" class="hover:text-blue-600">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 mb-3">
                        <?= substr(htmlspecialchars($post['content']), 0, 100) ?>...
                    </p>
                    
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>Por <?= htmlspecialchars($post['username']) ?></span>
                        <span><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>/posts/<?= $post['id'] ?>" 
                       class="inline-block mt-3 text-blue-500 hover:underline">
                        Leer más →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
