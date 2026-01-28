<?php $title = 'Gestión de Usuarios'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Gestión de Usuarios</h1>
        <a href="<?= BASE_URL ?>/admin" class="text-blue-500 hover:underline">← Volver al panel</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-3 text-left">ID</th>
                <th class="px-4 py-3 text-left">Usuario</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Rol</th>
                <th class="px-4 py-3 text-left">Fecha Registro</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3"><?= $user['id'] ?></td>
                    <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($user['username']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="px-4 py-3">
                        <form method="POST" action="<?= BASE_URL ?>/admin/users/role/<?= $user['id'] ?>" class="inline">
                            <select name="role" onchange="this.form.submit()" 
                                    class="border border-gray-300 rounded px-2 py-1 text-sm"
                                    <?= $user['id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>>
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Usuario</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($user['id'] != $_SESSION['user_id']): ?>
                            <a href="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" 
                               onclick="return confirm('¿Eliminar usuario?')"
                               class="text-red-500 hover:underline text-sm">
                                Eliminar
                            </a>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">Tú</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
