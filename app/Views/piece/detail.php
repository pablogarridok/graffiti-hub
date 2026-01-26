<?php 
$pageTitle = ($piece['titulo'] ?? 'Pieza') . ' - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php'; 

// Helper functions
function generateAvatarColor($username) {
    $colors = [
        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
        'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
        'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
    ];
    $hash = crc32($username);
    return $colors[$hash % count($colors)];
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'Ahora';
    if ($diff < 3600) return floor($diff / 60) . 'm';
    if ($diff < 86400) return floor($diff / 3600) . 'h';
    if ($diff < 604800) return floor($diff / 86400) . 'd';
    if ($diff < 2592000) return floor($diff / 604800) . 'sem';
    return date('d M Y', $time);
}
?>

<div class="main-container">
    <div class="piece-detail">
        <!-- Imagen -->
        <div class="piece-detail-image">
            <img src="/uploads/pieces/<?= htmlspecialchars($piece['imagen']) ?>" 
                 alt="<?= htmlspecialchars($piece['titulo'] ?? 'Pieza') ?>">
        </div>
        
        <div class="piece-detail-content">
            <!-- Header con autor -->
            <div class="piece-header">
                <a href="/profile/<?= htmlspecialchars($piece['username']) ?>" class="piece-author">
                    <div class="piece-author-info">
                        <span class="username">@<?= htmlspecialchars($piece['username']) ?></span>
                        <span class="piece-time"><?= timeAgo($piece['created_at']) ?></span>
                    </div>
                </a>
                
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $piece['user_id']): ?>
                    <div class="piece-actions-owner">
                        <a href="/piece/<?= $piece['id'] ?>/edit" class="btn btn-secondary">Editar</a>
                        <a href="/piece/<?= $piece['id'] ?>/delete" 
                           onclick="return confirm('¿Seguro que quieres eliminar esta pieza?')"
                           class="btn btn-danger">
                            Eliminar
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($piece['titulo'])): ?>
                <h1 class="piece-title">
                    <?= htmlspecialchars($piece['titulo']) ?>
                </h1>
            <?php endif; ?>
            
            <?php if (!empty($piece['descripcion'])): ?>
                <p class="piece-description">
                    <?= nl2br(htmlspecialchars($piece['descripcion'])) ?>
                </p>
            <?php endif; ?>
            
            <!-- Metadata -->
            <div class="piece-metadata">
                <?php if (!empty($piece['ciudad'])): ?>
                    <div class="piece-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span><?= htmlspecialchars($piece['ciudad']) ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($piece['estilo_nombre'])): ?>
                    <a href="/style/<?= htmlspecialchars($piece['estilo_slug'] ?? '') ?>" class="piece-style">
                        <?= htmlspecialchars($piece['estilo_nombre']) ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Acciones -->
            <div class="piece-actions-bar">
                <button class="action-btn like-btn <?= ($userLiked ?? false) ? 'liked' : '' ?>" 
                        data-piece-id="<?= $piece['id'] ?>"
                        onclick="toggleLike(<?= $piece['id'] ?>)"
                        <?= !isset($_SESSION['user_id']) ? 'disabled' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="<?= ($userLiked ?? false) ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span class="likes-count"><?= $piece['likes_count'] ?? 0 ?></span>
                </button>
                
                <div class="action-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span><?= $piece['comments_count'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Comentarios -->
    <div class="comments-section">
        <h3>Comentarios (<?= $piece['comments_count'] ?? 0 ?>)</h3>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="/piece/<?= $piece['id'] ?>/comment" method="POST" class="comment-form">
                <textarea name="contenido" class="form-control" placeholder="Añade un comentario..." required rows="3"></textarea>
                <button type="submit" class="btn btn-primary">Comentar</button>
            </form>
        <?php else: ?>
            <p class="text-muted" style="padding: 20px 0;">
                <a href="/login" style="color: var(--accent-color);">Inicia sesión</a> para comentar
            </p>
        <?php endif; ?>
        
        <div class="comments-list">
            <?php if (empty($comments)): ?>
                <p class="empty-state-text">
                    No hay comentarios todavía. ¡Sé el primero!
                </p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <div>
                                <div class="comment-author">@<?= htmlspecialchars($comment['username']) ?></div>
                                <div class="comment-date"><?= timeAgo($comment['created_at']) ?></div>
                            </div>
                        </div>
                        <div class="comment-content"><?= nl2br(htmlspecialchars($comment['contenido'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>