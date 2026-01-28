<?php $title = 'Registro'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Crear Cuenta</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/register">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nombre de Usuario</label>
            <input type="text" name="username" required minlength="3"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            <small class="text-gray-500">Mínimo 3 caracteres</small>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Contraseña</label>
            <input type="password" name="password" required minlength="6"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            <small class="text-gray-500">Mínimo 6 caracteres</small>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Confirmar Contraseña</label>
            <input type="password" name="confirm_password" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <button type="submit" 
                class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
            Registrarse
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">
        ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/login" class="text-blue-500 hover:underline">Inicia sesión</a>
    </p>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
