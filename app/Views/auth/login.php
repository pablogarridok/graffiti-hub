<?php $title = 'Login'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Iniciar Sesión</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/login">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Contraseña</label>
            <input type="password" name="password" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <button type="submit" 
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
            Entrar
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">
        ¿No tienes cuenta? <a href="<?= BASE_URL ?>/register" class="text-blue-500 hover:underline">Regístrate</a>
    </p>


</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
