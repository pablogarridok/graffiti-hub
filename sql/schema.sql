-- schema.sql - Base de datos GraffitiHub

-- Usar la base de datos que ya está configurada en Docker
USE blog_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    ciudad VARCHAR(100),
    instagram VARCHAR(50),
    avatar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de estilos de graffiti
CREATE TABLE IF NOT EXISTS styles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar estilos predefinidos (solo si no existen)
INSERT IGNORE INTO styles (nombre, slug) VALUES
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

-- Tabla de piezas
CREATE TABLE IF NOT EXISTS pieces (
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

-- Tabla de likes
CREATE TABLE IF NOT EXISTS likes (
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

-- Tabla de comentarios
CREATE TABLE IF NOT EXISTS comments (
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