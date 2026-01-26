<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <meta name="description" content="Red social de graffiti - Comparte tus piezas con la comunidad">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-logo"><?= APP_NAME ?></a>
            <ul class="navbar-menu">
                <li><a href="/">Home</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="/admin/users">Panel Admin</a></li>
                    <?php endif; ?>
                    <li><a href="/profile/<?= htmlspecialchars($_SESSION['username']) ?>">Perfil</a></li>
                    <li><a href="/piece/upload" class="btn-upload">Subir Pieza</a></li>
                    <li><a href="/logout">Salir</a></li>
                <?php else: ?>
                    <li><a href="/login">Iniciar sesión</a></li>
                    <li><a href="/register" class="btn-upload">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="main-container">
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="main-container">
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <main>