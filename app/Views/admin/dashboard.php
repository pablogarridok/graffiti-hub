<?php $title = 'Panel de Administración'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-medium p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold mb-2 text-gray-light">Panel de Administración</h1>
    <p class="text-gray-400">Gestiona usuarios, posts y comentarios</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-neon-green text-dark-bg p-6 rounded-lg shadow-md">
        <div class="text-3xl font-bold mb-2"><?= count($users) ?></div>
        <div class="opacity-90">Usuarios Registrados</div>
    </div>
    
    <div class="bg-yellow-hub text-dark-bg p-6 rounded-lg shadow-md">
        <div class="text-3xl font-bold mb-2"><?= count($posts) ?></div>
        <div class="opacity-90">Posts Totales</div>
    </div>
    
    <div class="bg-neon-magenta text-white p-6 rounded-lg shadow-md">
        <div class="text-3xl font-bold mb-2"><?= count($comments) ?></div>
        <div class="opacity-90">Comentarios Totales</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="<?= BASE_URL ?>/admin/users" class="bg-gray-medium p-6 rounded-lg shadow-md hover:border-2 hover:border-yellow-hub transition">
        <div class="text-2xl mb-2">👥</div>
        <h3 class="text-xl font-bold mb-2 text-gray-light">Gestionar Usuarios</h3>
        <p class="text-gray-400">Ver, editar y eliminar usuarios</p>
    </a>
    
    <a href="<?= BASE_URL ?>/admin/posts" class="bg-gray-medium p-6 rounded-lg shadow-md hover:border-2 hover:border-yellow-hub transition">
        <div class="text-2xl mb-2">📝</div>
        <h3 class="text-xl font-bold mb-2 text-gray-light">Gestionar Posts</h3>
        <p class="text-gray-400">Moderar y administrar publicaciones</p>
    </a>
    
    <a href="<?= BASE_URL ?>/admin/comments" class="bg-gray-medium p-6 rounded-lg shadow-md hover:border-2 hover:border-yellow-hub transition">
        <div class="text-2xl mb-2">💬</div>
        <h3 class="text-xl font-bold mb-2 text-gray-light">Gestionar Comentarios</h3>
        <p class="text-gray-400">Moderar comentarios de usuarios</p>
    </a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>