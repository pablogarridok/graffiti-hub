<?php $title = 'Gestión de Comentarios'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Gestión de Comentarios</h1>
        <a href="<?= BASE_URL ?>/admin" class="text-blue-500 hover:underline">← Volver al panel</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-3 text-left">ID</th>
                <th class="px-4 py-3 text-left">Usuario</th>
                <th class="px-4 py-3 text-left">Post</th>
                <th class="px-4 py-3 text-left">Comentario</th>
                <th class="px-4 py-3 text-left">Fecha</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($comments)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        No hay comentarios registrados
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($comments as $comment): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3"><?= $comment['id'] ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($comment['username']) ?></td>
                        <td class="px-4 py-3">
                            <a href="<?= BASE_URL ?>/posts/<?= $comment['post_id'] ?>" 
                               class="text-blue-600 hover:underline text-sm">
                                <?= htmlspecialchars(substr($comment['post_title'], 0, 30)) ?>...
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?= htmlspecialchars(substr($comment['content'], 0, 50)) ?>...
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="<?= BASE_URL ?>/admin/comments/delete/<?= $comment['id'] ?>" 
                               onclick="return confirm('¿Eliminar comentario?')"
                               class="text-red-500 hover:text-red-700 transition text-lg"
                               title="Eliminar comentario">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
