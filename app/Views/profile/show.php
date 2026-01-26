<?php 
$pageTitle = '@' . htmlspecialchars($user['username']) . ' - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php'; 

// Helper function
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
?>

<!-- Header del perfil -->
<div class="profile-header">
    <div class="profile-avatar-large" style="background: <?= generateAvatarColor($user['username']) ?>">
        <?= strtoupper(substr($user['username'], 0, 2)) ?>
    </div>
    
    <div class="profile-info">
        <h1 class="profile-username"><?= htmlspecialchars($user['nombre']) ?></h1>
        <p class="profile-handle">@<?= htmlspecialchars($user['username']) ?></p>
        
        <?php if ($user['bio']): ?>
            <p class="profile-bio"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
        <?php endif; ?>
        
        <div class="profile-meta">
            <div class="profile-stat">
                <strong><?= $stats['pieces_count'] ?></strong>
                Piezas
            </div>
            <div class="profile-stat">
                <strong><?= $stats['total_likes'] ?></strong>
                Likes
            </div>
            <?php if ($user['ciudad']): ?>
                <div class="profile-stat">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; vertical-align: middle;">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?= htmlspecialchars($user['ciudad']) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($user['instagram']): ?>
            <p style="margin-top: 10px; color: #a0a0a0;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px; vertical-align: middle;">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="#000"/>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="#000" stroke-width="2"/>
                </svg>
                <a href="https://instagram.com/<?= htmlspecialchars($user['instagram']) ?>" 
                   target="_blank" 
                   style="color: #667eea; text-decoration: none;">
                    @<?= htmlspecialchars($user['instagram']) ?>
                </a>
            </p>
        <?php endif; ?>
        
        <?php if ($isOwnProfile): ?>
            <a href="/profile/edit" class="btn" style="margin-top: 15px; display: inline-block;">
                Editar perfil
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Piezas del usuario -->
<div style="margin-top: 40px;">
    <h2 style="color: #fff; margin-bottom: 20px;">Piezas</h2>
    
    <?php if (empty($pieces)): ?>
        <div class="empty-state">
            <p>Este usuario no ha subido piezas todavía</p>
            <?php if ($isOwnProfile): ?>
                <a href="/piece/upload" class="btn btn-primary" style="margin-top: 15px;">
                    Subir tu primera pieza
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="feed-grid">
            <?php foreach ($pieces as $piece): ?>
                <article class="piece-card">
                    <a href="/piece/<?= $piece['id'] ?>">
                        <div class="piece-image-container">
                            <img src="<?= htmlspecialchars($piece['imagen']) ?>" 
                                 alt="<?= htmlspecialchars($piece['titulo'] ?? 'Pieza') ?>"
                                 loading="lazy">
                            
                            <?php if ($piece['estilo']): ?>
                                <span class="piece-style-tag"><?= htmlspecialchars($piece['estilo']) ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div class="piece-content">
                        <?php if ($piece['titulo']): ?>
                            <h3 style="margin-bottom: 8px;">
                                <a href="/piece/<?= $piece['id'] ?>" style="color: #fff; text-decoration: none;">
                                    <?= htmlspecialchars($piece['titulo']) ?>
                                </a>
                            </h3>
                        <?php endif; ?>
                        
                        <div class="piece-actions">
                            <div class="action-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                                <span><?= $piece['likes_count'] ?? 0 ?></span>
                            </div>
                            
                            <div class="action-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <span><?= $piece['comments_count'] ?? 0 ?></span>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>