
USE blog_db;

-- Eliminar tablas si existen
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

-- Tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de comentarios
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuario administrador
-- Password: password

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar usuarios de prueba
-- Password para todos: password
INSERT INTO users (username, email, password, role) VALUES
('pablin', 'pablo@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('xavi', 'xavi@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');


-- Insertar posts de ejemplo
INSERT INTO posts (user_id, title, content, image, status) VALUES
(2, 'Mi segundo graffiti', 'Esta es mi segunda pieza, creoque hya una mejoria', 'bubble_1.jpg', 'published'),
(2, 'Mi primer graffiti', 'Este es mi primer trabajo en el arte urbano. Lo hice en el barrio antiguo.', 'bubble_2.jpg', 'published'),
(3, 'Mural para mi tio', 'Mi tio  me pidio que le hiciese una pieza xuleta en el salon, y yo ni me lo pense dos veces ', 'mural_1.jpg', 'published');

-- Insertar comentarios de ejemplo
INSERT INTO comments (post_id, user_id, content) VALUES
(2, 3, 'Muy buen trabajo para ser el primero!'),
(2, 1, 'Me gusta el estilo, sigue asi'),
(1, 1, 'Vas mejorando tt!!!');
