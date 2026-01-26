-- schema.sql - Base de datos GraffitiHub con Admin y Usuarios de Ejemplo

USE blog_db;

-- Eliminar tablas si existen (para empezar limpio)
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS pieces;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS styles;

-- ============================================
-- Tabla de usuarios CON roles y estados
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user' NOT NULL,
    status ENUM('active', 'blocked') DEFAULT 'active' NOT NULL,
    bio TEXT,
    ciudad VARCHAR(100),
    instagram VARCHAR(50),
    avatar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla de estilos de graffiti
-- ============================================
CREATE TABLE styles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar estilos predefinidos
INSERT INTO styles (nombre, slug) VALUES
('Wildstyle', 'wildstyle'),
('Bubble', 'bubble'),
('Throw-up', 'throw-up'),
('Blockbuster', 'blockbuster'),
('3D', '3d'),
('Stencil', 'stencil'),
('Tag', 'tag'),
('Piece', 'piece'),
('Mural', 'mural'),
('Character', 'character');

-- ============================================
-- Tabla de piezas
-- ============================================
CREATE TABLE pieces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100),
    estilo_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (estilo_id) REFERENCES styles(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_estilo_id (estilo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla de likes
-- ============================================
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    piece_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (piece_id) REFERENCES pieces(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (user_id, piece_id),
    INDEX idx_piece_id (piece_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla de comentarios
-- ============================================
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    piece_id INT NOT NULL,
    user_id INT NOT NULL,
    contenido TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (piece_id) REFERENCES pieces(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_piece_id (piece_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- USUARIOS DE EJEMPLO
-- Contraseña para todos: "password123"
-- ============================================

-- ADMINISTRADOR
INSERT INTO users (username, nombre, email, password, role, status, bio, ciudad, instagram, created_at) VALUES
('admin', 'Administrador', 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', 'Administrador del sistema GraffitiHub', 'Madrid', '@graffitihub', NOW());

-- USUARIOS NORMALES
INSERT INTO users (username, nombre, email, password, role, status, bio, ciudad, instagram, created_at) VALUES
('wildstyle_king', 'Carlos Martínez', 'carlos@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', '20 años pintando muros. Especialista en Wildstyle y 3D. Barcelona es mi canvas.', 'Barcelona', '@wildstyle_king', DATE_SUB(NOW(), INTERVAL 30 DAY)),
('bubble_master', 'Ana García', 'ana@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Bubble letters y colores vibrantes 🎨 Pintando desde 2015', 'Madrid', '@bubble_master', DATE_SUB(NOW(), INTERVAL 25 DAY)),
('throwup_beast', 'Miguel Torres', 'miguel@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Throw-ups rápidos y limpios. La calle es mi galería.', 'Valencia', '@throwup_beast', DATE_SUB(NOW(), INTERVAL 20 DAY)),
('stencil_art', 'Laura Sánchez', 'laura@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Arte urbano con plantillas. Mensajes que importan 💭', 'Sevilla', '@stencil_art', DATE_SUB(NOW(), INTERVAL 18 DAY)),
('character_crew', 'David Ruiz', 'david@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Personajes cartoon y realistas. Crew: TNT Writers', 'Bilbao', '@character_crew', DATE_SUB(NOW(), INTERVAL 15 DAY)),
('mural_pro', 'Sara López', 'sara@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Murales a gran escala. Disponible para proyectos comerciales.', 'Málaga', '@mural_pro', DATE_SUB(NOW(), INTERVAL 12 DAY)),
('tag_runner', 'Pablo Díaz', 'pablo@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Tags everywhere. Style is everything 🔥', 'Zaragoza', '@tag_runner', DATE_SUB(NOW(), INTERVAL 10 DAY)),
('blockbuster_king', 'Javier Moreno', 'javier@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Letras grandes y bloques sólidos. Visibilidad máxima.', 'Granada', '@blockbuster_king', DATE_SUB(NOW(), INTERVAL 8 DAY));

-- Usuario bloqueado de ejemplo
INSERT INTO users (username, nombre, email, password, role, status, bio, ciudad, created_at) VALUES
('banned_user', 'Usuario Bloqueado', 'banned@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'blocked', 'Este usuario fue bloqueado por el admin', 'Barcelona', DATE_SUB(NOW(), INTERVAL 5 DAY));

-- ============================================
-- PIEZAS DE EJEMPLO
-- ============================================
INSERT INTO pieces (user_id, titulo, descripcion, imagen, ciudad, estilo_id, created_at) VALUES
(2, 'Wildstyle Evolution', 'Mi última pieza en el barrio Gótico. 6 horas de trabajo con Montana Gold.', 'wildstyle_1.jpg', 'Barcelona', 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, '3D Letters Downtown', 'Letras 3D con sombras profundas. Técnica de degradado.', '3d_1.jpg', 'Barcelona', 5, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 'Bubble Love', 'Bubble letters con colores pastel. Inspired by the 80s NYC scene.', 'bubble_1.jpg', 'Madrid', 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'Candy Colors Wall', 'Muro completo con estilo bubble. Rosa, azul y amarillo.', 'bubble_2.jpg', 'Madrid', 2, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(4, 'Quick Throwup Express', 'Throw-up de 15 minutos. Plata y negro, clean and fast.', 'throwup_1.jpg', 'Valencia', 3, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(5, 'Freedom Fighter', 'Stencil de 4 capas. Mensaje sobre la libertad de expresión.', 'stencil_1.jpg', 'Sevilla', 6, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(6, 'Cartoon Gang', 'Personajes estilo cartoon con mi crew TNT. 3 días de trabajo.', 'character_1.jpg', 'Bilbao', 10, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(7, 'Mural Comunitario', 'Proyecto encargado por el ayuntamiento. 50m² de pared.', 'mural_1.jpg', 'Málaga', 9, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(8, 'Signature Style', 'Mi tag personal evolucionado. 3 años perfeccionando.', 'tag_1.jpg', 'Zaragoza', 7, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(9, 'Blockbuster Highway', 'Letras de 6 metros de alto. Visible desde la autopista.', 'blockbuster_1.jpg', 'Granada', 4, DATE_SUB(NOW(), INTERVAL 3 DAY));

-- ============================================
-- LIKES Y COMENTARIOS
-- ============================================
INSERT INTO likes (user_id, piece_id, created_at) VALUES
(3, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(4, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 3, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(4, 3, DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO comments (piece_id, user_id, contenido, created_at) VALUES
(1, 3, 'Brutal hermano!', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 4, 'Ese estilo es una locura!', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 2, 'Es el numero 1 tt!', DATE_SUB(NOW(), INTERVAL 1 DAY));