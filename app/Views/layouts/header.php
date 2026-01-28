<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Wet+Paint&family=Bebas+Neue&family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title><?= isset($title) ? $title : 'Graffiti-Hub' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#0a0a0a',
                        'dark-carbon': '#1a1a1a',
                        'dark-alt': '#141414',
                        'gray-light': '#e0e0e0',
                        'gray-medium': '#2d2d2d',
                        'yellow-hub': '#ffdd00',
                        'yellow-gold': '#ffd700',
                        'neon-green': '#00ff88',
                        'neon-magenta': '#ff006e',
                    },
                    fontFamily: {
                        'street': ['Bebas Neue', 'Impact', 'Arial Black', 'sans-serif'],
                        'urban': ['Oswald', 'Arial Narrow', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Oswald', Arial Narrow, sans-serif;
            letter-spacing: 0.5px;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Bebas Neue', Impact, sans-serif;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="bg-dark-bg min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="bg-dark-carbon text-gray-light p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?= BASE_URL ?>/" 
               class="text-3xl text-gray-light tracking-wide font-graffiti">
               Graffiti-<span class="text-yellow-hub">Hub</span>
            </a>
            
            <div class="flex gap-4 font-street text-lg">
                <a href="<?= BASE_URL ?>/" class="hover:text-yellow-hub transition-colors">INICIO</a>
                
                <?php if(isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/posts/create" class="hover:text-yellow-hub transition-colors">CREAR POST</a>
                    
                    <?php if(isAdmin()): ?>
                        <a href="<?= BASE_URL ?>/admin" class="hover:text-yellow-hub transition-colors">ADMIN</a>
                    <?php endif; ?>
                    
                    <span class="text-gray-400">HOLA, <?= strtoupper($_SESSION['username']) ?></span>
                    <a href="<?= BASE_URL ?>/logout" class="hover:text-neon-magenta transition-colors">SALIR</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="hover:text-yellow-hub transition-colors">LOGIN</a>
                    <a href="<?= BASE_URL ?>/register" class="hover:text-yellow-hub transition-colors">REGISTRO</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Mensajes flash -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="container mx-auto mt-4">
            <div class="bg-neon-green bg-opacity-20 border border-neon-green text-neon-green px-4 py-3 rounded font-street">
                <?= $_SESSION['success'] ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="container mx-auto mt-4">
            <div class="bg-neon-magenta bg-opacity-20 border border-neon-magenta text-neon-magenta px-4 py-3 rounded font-street">
                <?= $_SESSION['error'] ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <main class="container mx-auto mt-6 mb-6 flex-grow">