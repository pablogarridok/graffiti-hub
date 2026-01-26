<?php 
$pageTitle = '@' . htmlspecialchars($user['username']) . ' - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php'; 
?>

<div class="main-container">
    <!-- Header del perfil -->
    <div class="profile-header">
        <div class="profile-info">
            <h1 class="profile-username"><?= htmlspecialchars($user['nombre']) ?></h1>
            <p class="profile-handle">@<?= htmlspecialchars($user['username']) ?></p>
            
            <?php if (!empty($user['bio'])): ?>
                <p class="profile-bio"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
            <?php endif; ?>
            
            <div class="profile-meta">
                <div class="profile-stat">
                    <strong><?= $stats['pieces_count'] ?></strong>
                    <span>Piezas</span>
                </div>
                <div class="profile-stat">
                    <strong><?= $stats['total_likes'] ?></strong>
                    <span>Likes</span>
                </div>
                <?php if (!empty($user['ciudad'])): ?>
                    <div class="profile-stat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span><?= htmlspecialchars($user['ciudad']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($user['instagram'])): ?>
                <div class="profile-instagram">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="#1a1a1a"/>
                        <circle cx="17.5" cy="6.5" r="1" fill="#1a1a1a"/>
                    </svg>
                    <a href="https://instagram.com/<?= ltrim(htmlspecialchars($user['instagram']), '@') ?>" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <?= htmlspecialchars($user['instagram']) ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($isOwnProfile): ?>
                <a href="/profile/edit" class="btn btn-secondary">
                    Editar perfil
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Piezas del usuario -->
    <div class="profile-pieces-section">
        <h2>Piezas de <?= htmlspecialchars($user['nombre']) ?></h2>
        
        <?php if (empty($pieces)): ?>
            <div class="empty-state">
                <p>Este usuario no ha subido piezas todavía</p>
                <?php if ($isOwnProfile): ?>
                    <a href="/piece/upload" class="btn btn-primary">
                        Subir tu primera pieza
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="feed-grid">
                <?php foreach ($pieces as $piece): ?>
                    <div class="piece-card">
                        <!-- Imagen -->
                        <a href="/piece/<?= $piece['id'] ?>">
                            <img src="/uploads/pieces/<?= htmlspecialchars($piece['imagen']) ?>" 
                                 alt="<?= htmlspecialchars($piece['titulo'] ?? 'Pieza') ?>"
                                 class="piece-card-image"
                                 loading="lazy">
                        </a>
                        
                        <!-- Info -->
                        <div class="piece-card-info">
                            <?php if (!empty($piece['titulo'])): ?>
                                <div class="piece-card-title">
                                    <a href="/piece/<?= $piece['id'] ?>">
                                        <?= htmlspecialchars($piece['titulo']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($piece['estilo_nombre'])): ?>
                                <div class="piece-card-style">
                                    <?= htmlspecialchars($piece['estilo_nombre']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="piece-card-actions">
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>