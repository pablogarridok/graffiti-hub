<?php $title = 'Gestión de Usuarios'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-medium p-6 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-light">Gestión de Usuarios</h1>
        <a href="<?= BASE_URL ?>/admin" class="text-yellow-hub hover:text-yellow-gold transition-colors">← Volver al panel</a>
    </div>
</div>

<div class="bg-gray-medium rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-alt">
            <tr>
                <th class="px-4 py-3 text-left text-gray-light">ID</th>
                <th class="px-4 py-3 text-left text-gray-light">Usuario</th>
                <th class="px-4 py-3 text-left text-gray-light">Email</th>
                <th class="px-4 py-3 text-left text-gray-light">Rol</th>
                <th class="px-4 py-3 text-left text-gray-light">Fecha Registro</th>
                <th class="px-4 py-3 text-center text-gray-light">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr class="border-b border-dark-alt hover:bg-dark-carbon transition-colors">
                    <td class="px-4 py-3 text-gray-light"><?= $user['id'] ?></td>
                    <td class="px-4 py-3 font-semibold text-gray-light"><?= htmlspecialchars($user['username']) ?></td>
                    <td class="px-4 py-3 text-gray-light"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="px-4 py-3">
                        <form method="POST" action="<?= BASE_URL ?>/admin/users/role/<?= $user['id'] ?>" class="inline">
                            <select name="role" onchange="this.form.submit()" 
                                    class="bg-dark-alt border border-gray-500 rounded px-2 py-1 text-sm text-gray-light focus:border-yellow-hub focus:outline-none"
                                    <?= $user['id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>>
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Usuario</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">
                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($user['id'] != $_SESSION['user_id']): ?>
                            <a href="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" 
                               onclick="return confirm('¿Eliminar usuario?')"
                               class="text-neon-magenta hover:text-red-400 transition-colors text-sm">
                                Eliminar
                            </a>
                        <?php else: ?>
                            <span class="text-gray-500 text-sm">Tú</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>