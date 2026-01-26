
/**
 * Toggle like en una pieza
 */
async function toggleLike(pieceId) {
    try {
        const response = await fetch('/api/like', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ piece_id: pieceId })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }
            throw new Error(data.message || 'Error al dar like');
        }
        
        if (data.success) {
            // Actualizar UI
            const btn = document.querySelector(`[data-piece-id="${pieceId}"].like-btn`);
            if (!btn) return;
            
            const svg = btn.querySelector('svg');
            const count = btn.querySelector('.likes-count');
            
            if (data.liked) {
                btn.classList.add('liked');
                svg.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove('liked');
                svg.setAttribute('fill', 'none');
            }
            
            if (count) {
                count.textContent = data.likes_count;
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al dar like. Por favor, intenta de nuevo.');
    }
}

/**
 * Compartir pieza
 */
function sharePiece(pieceId) {
    const url = `${window.location.origin}/piece/${pieceId}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Check out this piece!',
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: copiar al portapapeles
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copiado al portapapeles!');
        }).catch(err => {
            console.error('Error copying:', err);
        });
    }
}

/**
 * Confirmación antes de eliminar
 */
function confirmDelete(message) {
    return confirm(message || '¿Estás seguro de que quieres eliminar esto?');
}

/**
 * Preview de imagen antes de subir
 */
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imagen');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Validar tamaño
            if (file.size > 5 * 1024 * 1024) {
                alert('La imagen es demasiado grande. Máximo 5MB.');
                this.value = '';
                return;
            }
            
            // Validar tipo
            if (!file.type.match('image.*')) {
                alert('Por favor selecciona una imagen válida.');
                this.value = '';
                return;
            }
            
            // Mostrar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('previewImg');
                const previewContainer = document.getElementById('imagePreview');
                
                if (previewImg && previewContainer) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
            }
            reader.readAsDataURL(file);
        });
    }
});

/**
 * Auto-hide alerts después de 5 segundos
 */
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});