<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Graffiti-Hub' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Wet+Paint&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    

    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?= BASE_URL ?>/" class="flex items-center ml-20">
    <img src="<?= BASE_URL ?>/assets/logo.png"
         alt="Graffiti Hub"
         class="h-20 w-35 hover:opacity-90 transition">
</a>


            
            <div class="flex gap-4">
                <a href="<?= BASE_URL ?>/" class="hover:text-gray-300">Inicio</a>
                
                <?php if(isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/posts/create" class="hover:text-gray-300">Crear Post</a>
                    
                    <?php if(isAdmin()): ?>
                        <a href="<?= BASE_URL ?>/admin" class="hover:text-gray-300">Admin Panel</a>
                    <?php endif; ?>
                    
                    <span class="text-gray-400">Hola, <?= $_SESSION['username'] ?></span>
                    <a href="<?= BASE_URL ?>/logout" class="hover:text-red-400">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="hover:text-gray-300">Login</a>
                    <a href="<?= BASE_URL ?>/register" class="hover:text-gray-300">Registro</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Mensajes flash -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="container mx-auto mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?= $_SESSION['success'] ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="container mx-auto mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= $_SESSION['error'] ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <main class="container mx-auto mt-6 mb-6 flex-grow">
