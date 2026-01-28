<?php $title = 'Login'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-md mx-auto bg-gray-medium p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-light">Iniciar Sesión</h2>
    
    <form method="POST" action="<?= BASE_URL ?>/login">
        <div class="mb-4">
            <label class="block text-gray-light mb-2">Email</label>
            <input type="email" name="email" required 
                   class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light focus:outline-none focus:border-yellow-hub">
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-light mb-2">Contraseña</label>
            <input type="password" name="password" required 
                   class="w-full px-3 py-2 bg-dark-alt border border-gray-500 rounded text-gray-light focus:outline-none focus:border-yellow-hub">
        </div>
        
        <button type="submit" 
                class="w-full bg-dark-alt text-gray-light py-2 rounded hover:bg-yellow-hub transition-colors font-semibold">
            Entrar
        </button>
    </form>
    
    <p class="mt-4 text-center text-gray-400">
        ¿No tienes cuenta? <a href="<?= BASE_URL ?>/register" class="text-yellow-hub hover:text-yellow-gold transition-colors">Regístrate</a>
    </p>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>