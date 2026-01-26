<?php

?>

<div class="main-container">
    <?php if (empty($pieces)): ?>
        <div class="empty-state">
            <h2>No hay piezas aún</h2>
            <p>Sé el primero en compartir tu arte</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/piece/upload" class="btn btn-primary">Subir tu primera pieza</a>
            <?php else: ?>
                <a href="/register" class="btn btn-primary">Únete ahora</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="feed-grid">
            <?php foreach ($pieces as $piece): ?>
                <div class="piece-card">
                    <!-- Header -->
                    <div class="piece-card-header">
                        <div class="piece-card-user-info">
                            <a href="/profile/<?= htmlspecialchars($piece['username']) ?>" class="piece-card-username">
                                <?= htmlspecialchars($piece['username']) ?>
                            </a>
                            <?php if (!empty($piece['ciudad'])): ?>
                                <div class="piece-card-location"><?= htmlspecialchars($piece['ciudad']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Imagen -->
                    <a href="/piece/<?= $piece['id'] ?>">
                        <img src="/uploads/pieces/<?= htmlspecialchars($piece['imagen']) ?>" 
                             alt="<?= htmlspecialchars($piece['titulo']) ?>" 
                             class="piece-card-image">
                    </a>
                    
                    <!-- Acciones -->
                    <div class="piece-card-actions">
                        <button class="action-btn like-btn <?= isset($piece['user_liked']) && $piece['user_liked'] ? 'liked' : '' ?>" 
                                data-piece-id="<?= $piece['id'] ?>"
                                onclick="toggleLike(<?= $piece['id'] ?>)">
                            <svg viewBox="0 0 24 24">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span class="likes-count"><?= $piece['likes_count'] ?? 0 ?></span>
                        </button>
                        
                        <a href="/piece/<?= $piece['id'] ?>#comments" class="action-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                            </svg>
                            <span><?= $piece['comments_count'] ?? 0 ?></span>
                        </a>
                    </div>
                    
                    <!-- Info -->
                    <div class="piece-card-info">
                        <?php if ($piece['likes_count'] > 0): ?>
                            <div class="piece-card-likes">
                                <?= $piece['likes_count'] ?> <?= $piece['likes_count'] == 1 ? 'like' : 'likes' ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="piece-card-title"><?= htmlspecialchars($piece['titulo']) ?></div>
                        
                        <?php if (!empty($piece['descripcion'])): ?>
                            <div class="piece-card-description"><?= htmlspecialchars($piece['descripcion']) ?></div>
                        <?php endif; ?>
                        
                        <div class="piece-card-date">
                            <?php 
                            $date = new DateTime($piece['created_at']);
                            echo $date->format('d M Y');
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>