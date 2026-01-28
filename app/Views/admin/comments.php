<?php $title = 'Gestión de Comentarios'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-medium p-6 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-light">Gestión de Comentarios</h1>
        <a href="<?= BASE_URL ?>/admin" class="text-yellow-hub hover:text-yellow-gold transition-colors">← Volver al panel</a>
    </div>
</div>

<div class="bg-gray-medium rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-alt">
            <tr>
                <th class="px-4 py-3 text-left text-gray-light">ID</th>
                <th class="px-4 py-3 text-left text-gray-light">Usuario</th>
                <th class="px-4 py-3 text-left text-gray-light">Post</th>
                <th class="px-4 py-3 text-left text-gray-light">Comentario</th>
                <th class="px-4 py-3 text-left text-gray-light">Fecha</th>
                <th class="px-4 py-3 text-center text-gray-light">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($comments)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                        No hay comentarios registrados
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($comments as $comment): ?>
                    <tr class="border-b border-dark-alt hover:bg-dark-carbon transition-colors">
                        <td class="px-4 py-3 text-gray-light"><?= $comment['id'] ?></td>
                        <td class="px-4 py-3 text-gray-light"><?= htmlspecialchars($comment['username']) ?></td>
                        <td class="px-4 py-3">
                            <a href="<?= BASE_URL ?>/posts/<?= $comment['post_id'] ?>" 
                               class="text-yellow-hub hover:text-yellow-gold transition-colors text-sm">
                                <?= htmlspecialchars(substr($comment['post_title'], 0, 30)) ?>...
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-light">
                            <?= htmlspecialchars(substr($comment['content'], 0, 50)) ?>...
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-400">
                            <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="<?= BASE_URL ?>/admin/comments/delete/<?= $comment['id'] ?>" 
                               onclick="return confirm('¿Eliminar comentario?')"
                               class="text-neon-magenta hover:text-red-400 transition-colors text-sm">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>