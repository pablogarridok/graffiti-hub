<?php 
$pageTitle = 'Panel de Usuarios - Admin';
require VIEWS_PATH . '/layouts/header.php'; 
?>

<div class="main-container">
    <div class="admin-panel">
        <div class="admin-header">
            <h1>Panel de Usuarios</h1>
            <div class="admin-stats">
                <span class="stat-badge">Total: <?= $totalUsers ?></span>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="admin-search">
            <form action="/admin/users" method="GET" class="search-form">
                <input type="text" 
                       name="search" 
                       placeholder="Buscar usuarios..." 
                       value="<?= htmlspecialchars($search ?? '') ?>"
                       class="search-input">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if (!empty($search)): ?>
                    <a href="/admin/users" class="btn btn-secondary">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabla de usuarios -->
        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Fecha Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="empty-message">No se encontraron usuarios</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="user-row <?= $user['status'] === 'blocked' ? 'blocked-user' : '' ?>">
                                <td class="user-info">
                                    <div class="user-main">
                                        <strong>@<?= htmlspecialchars($user['username']) ?></strong>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="badge badge-admin">ADMIN</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="user-name"><?= htmlspecialchars($user['nombre']) ?></div>
                                </td>
                                <td>
                                    <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $date = new DateTime($user['created_at']);
                                    echo $date->format('d/m/Y');
                                    ?>
                                </td>
                                <td>
                                    <?php if ($user['status'] === 'blocked'): ?>
                                        <span class="badge badge-blocked">Bloqueado</span>
                                    <?php else: ?>
                                        <span class="badge badge-active">Activo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <!-- Ver perfil -->
                                        <a href="/profile/<?= htmlspecialchars($user['username']) ?>" 
                                           class="action-btn action-view"
                                           title="Ver perfil">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Bloquear/Desbloquear -->
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <?php if ($user['status'] === 'active'): ?>
                                                <form action="/admin/users/<?= $user['id'] ?>/block" method="POST" class="inline-form">
                                                    <button type="submit" 
                                                            class="action-btn action-block"
                                                            title="Bloquear usuario"
                                                            onclick="return confirm('¿Seguro que quieres bloquear a este usuario?')">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="/admin/users/<?= $user['id'] ?>/unblock" method="POST" class="inline-form">
                                                    <button type="submit" 
                                                            class="action-btn action-unblock"
                                                            title="Desbloquear usuario">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                            <path d="M7 11V7a5 5 0 0 1 9.9-1"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <!-- Eliminar -->
                                            <form action="/admin/users/<?= $user['id'] ?>/delete" method="POST" class="inline-form">
                                                <button type="submit" 
                                                        class="action-btn action-delete"
                                                        title="Eliminar usuario"
                                                        onclick="return confirm('¿SEGURO que quieres ELIMINAR permanentemente a este usuario y todas sus piezas?')">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size: 0.85rem;">Tú</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/admin/users?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
                       class="<?= $page === $i ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>