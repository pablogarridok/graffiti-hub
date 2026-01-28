<?php $title = 'Panel de Administración'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold mb-2">Panel de Administración</h1>
    <p class="text-gray-600">Gestiona usuarios, posts y comentarios</p>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
        <div class="text-3xl font-bold mb-2"><?= count($users) ?></div>
        <div class="text-blue-100">Usuarios Registrados</div>
    </div>

    <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
        <div class="text-3xl font-bold mb-2"><?= count($posts) ?></div>
        <div class="text-green-100">Posts Totales</div>
    </div>

    <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
        <div class="text-3xl font-bold mb-2"><?= count($comments) ?></div>
        <div class="text-purple-100">Comentarios Totales</div>
    </div>
</div>

<!-- Menú de navegación -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="<?= BASE_URL ?>/admin/users" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
        <div class="text-2xl mb-2">👥</div>
        <h3 class="text-xl font-bold mb-2">Gestionar Usuarios</h3>
        <p class="text-gray-600">Ver, editar y eliminar usuarios</p>
    </a>

    <a href="<?= BASE_URL ?>/admin/posts" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
        <div class="text-2xl mb-2">📝</div>
        <h3 class="text-xl font-bold mb-2">Gestionar Posts</h3>
        <p class="text-gray-600">Moderar y administrar publicaciones</p>
    </a>

    <a href="<?= BASE_URL ?>/admin/comments" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
        <div class="text-2xl mb-2">💬</div>
        <h3 class="text-xl font-bold mb-2">Gestionar Comentarios</h3>
        <p class="text-gray-600">Moderar comentarios de usuarios</p>
    </a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
