# Blog de Graffiti

Blog de graffiti desarrollado con PHP, MySQL y TailwindCSS.

## Características

- ✅ Sistema completo de autenticación (registro y login)
- ✅ Gestión de sesiones y roles (admin/usuario)
- ✅ CRUD completo de posts
- ✅ Sistema de comentarios
- ✅ Panel de administración
- ✅ Validaciones del lado del servidor
- ✅ Subida y gestión de imágenes
- ✅ Cifrado de contraseñas con password_hash/password_verify
- ✅ Diseño con TailwindCSS
- ✅ Rutas protegidas según roles

## Requisitos

- Docker
- Docker Compose

## Instalación

1. Descomprimir el proyecto
2. Abrir terminal en la carpeta del proyecto
3. Ejecutar Docker:
```bash
docker-compose down  # Por si hay contenedores previos
docker-compose up -d --build
```

5. Esperar 15-20 segundos para que MySQL inicialice
6. Acceder a:
   - Blog: http://localhost:8080
   - PhpMyAdmin: http://localhost:8081



## Usuarios de Prueba

### Administrador
- Email: admin@gmail.com
- Password: password

### Usuarios normales
- Email: pablo@gmail.com
- Password: password

- Email: xavi@gmail.com
- Password: password

## Estructura del Proyecto

```
.
├── app/
│   ├── Controllers/       # Controladores (MVC)
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── PostController.php
│   ├── Models/           # Modelos (MVC)
│   │   ├── Comment.php
│   │   ├── Post.php
│   │   └── User.php
│   └── Views/            # Vistas (MVC)
│       ├── admin/        # Vistas del panel admin
│       ├── auth/         # Login y registro
│       ├── posts/        # CRUD de posts
│       └── layouts/      # Header y footer
├── config/
│   ├── config.php        # Configuración general
│   └── database.php      # Conexión a BD
├── public/
│   ├── uploads/posts/    # Imágenes subidas
│   ├── .htaccess         # Rutas limpias
│   └── index.php         # Router principal
├── sql/
│   └── schema.sql        # Estructura de la BD
├── docker-compose.yml    # Configuración Docker
└── Dockerfile           # Imagen PHP+Apache
```

## Funcionalidades Implementadas

### Autenticación
- Registro de usuarios con validaciones
- Login con verificación de contraseñas hasheadas
- Cierre de sesión
- Protección de rutas según estado de login

### Gestión de Posts
- Crear posts con título, contenido e imagen
- Editar posts propios
- Eliminar posts propios
- Listar posts publicados
- Estados: borrador/publicado
- Solo el autor o admin puede editar/eliminar

### Sistema de Comentarios
- Agregar comentarios en posts
- Ver todos los comentarios de un post
- Los admins pueden eliminar cualquier comentario

### Panel de Administración
- Dashboard con estadísticas
- Gestión de usuarios (cambiar rol, eliminar)
- Gestión de posts (cambiar estado, eliminar)
- Gestión de comentarios (eliminar)
- Solo accesible para usuarios con rol admin

### Validaciones
- Campos obligatorios
- Formato de email
- Longitud mínima de contraseñas (6 caracteres)
- Longitud mínima de username (3 caracteres)
- Validación de tipos de archivo (imágenes)
- Tamaño máximo de archivos (5MB)

### Seguridad
- Contraseñas hasheadas con password_hash()
- Sanitización de datos con htmlspecialchars()
- Protección contra SQL injection con PDO prepared statements
- Verificación de permisos en cada acción
- Sesiones seguras

## Base de Datos

### Tablas
- **users**: Usuarios del sistema con roles
- **posts**: Publicaciones del blog
- **comments**: Comentarios en los posts

### Relaciones
- Un usuario puede tener muchos posts (1:N)
- Un post pertenece a un usuario (N:1)
- Un post puede tener muchos comentarios (1:N)
- Un usuario puede hacer muchos comentarios (1:N)

## Tecnologías Utilizadas

- **Backend**: PHP 8.1
- **Base de datos**: MySQL 8.0
- **Frontend**: HTML5, TailwindCSS
- **Contenedores**: Docker, Docker Compose
- **Servidor web**: Apache
- **Patrón de diseño**: MVC

## Comandos Útiles

```bash
# Iniciar contenedores
docker-compose up -d

# Ver logs
docker-compose logs -f

# Detener contenedores
docker-compose down

# Reiniciar contenedores
docker-compose restart

# Eliminar todo (incluyendo volúmenes)
docker-compose down -v
```

