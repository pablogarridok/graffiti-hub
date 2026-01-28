<?php $title = 'Gestión de Posts'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Gestión de Posts</h1>
        <a href="<?= BASE_URL ?>/admin" class="text-blue-500 hover:underline">← Volver al panel</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-3 text-left">ID</th>
                <th class="px-4 py-3 text-left">Título</th>
                <th class="px-4 py-3 text-left">Autor</th>
                <th class="px-4 py-3 text-left">Estado</th>
                <th class="px-4 py-3 text-left">Fecha</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($posts as $post): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3"><?= $post['id'] ?></td>
                    <td class="px-4 py-3">
                        <a href="<?= BASE_URL ?>/posts/<?= $post['id'] ?>" class="text-blue-600 hover:underline">
                            <?= htmlspecialchars(substr($post['title'], 0, 50)) ?>
                        </a>
                    </td>
                    <td class="px-4 py-3"><?= htmlspecialchars($post['username']) ?></td>
                    <td class="px-4 py-3">
                        <form method="POST" action="<?= BASE_URL ?>/admin/posts/status/<?= $post['id'] ?>" class="inline">
                            <select name="status" onchange="this.form.submit()" 
                                    class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="draft" <?= $post['status'] == 'draft' ? 'selected' : '' ?>>Borrador</option>
                                <option value="published" <?= $post['status'] == 'published' ? 'selected' : '' ?>>Publicado</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-center space-x-3">
    <a href="<?= BASE_URL ?>/posts/edit/<?= $post['id'] ?>"
       class="text-blue-500 hover:text-blue-700 transition text-lg"
       title="Editar post">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <a href="<?= BASE_URL ?>/admin/posts/delete/<?= $post['id'] ?>"
       onclick="return confirm('¿Eliminar post?')"
       class="text-red-500 hover:text-red-700 transition text-lg"
       title="Eliminar post">
        <i class="fa-solid fa-trash"></i>
    </a>
</td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
