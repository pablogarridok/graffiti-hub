<?php
class Post {
    private $conn;
    private $table = 'posts';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo post - ACTUALIZADO para retornar el ID
    public function create($user_id, $title, $content, $image, $status = 'draft') {
        $query = "INSERT INTO " . $this->table . " (user_id, title, content, image, status) 
                  VALUES (:user_id, :title, :content, :image, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':status', $status);
        
        if($stmt->execute()) {
            // ✅ CAMBIO IMPORTANTE: Retornar el ID del post recién creado
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    // Obtener todos los posts publicados
    public function getAllPublished() {
        $query = "SELECT p.*, u.username 
                  FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.status = 'published' 
                  ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los posts (admin)
    public function getAll() {
        $query = "SELECT p.*, u.username 
                  FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener posts por usuario
    public function getByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener post por ID
    public function getById($id) {
        $query = "SELECT p.*, u.username, u.email 
                  FROM " . $this->table . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar post
    public function update($id, $title, $content, $image = null, $status = null) {
        if($image) {
            $query = "UPDATE " . $this->table . " 
                      SET title = :title, content = :content, image = :image, status = :status 
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET title = :title, content = :content, status = :status 
                      WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':status', $status);
        
        if($image) {
            $stmt->bindParam(':image', $image);
        }
        
        return $stmt->execute();
    }

    // Eliminar post
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cambiar estado del post
    public function changeStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    // Obtener posts relacionados
    public function getRelatedPosts($id, $title, $limit = 3) {
        // Buscamos posts similares por título y contenido, excluyendo el post actual
        $query = "SELECT p.*, u.username, 
                  MATCH(title, content) AGAINST(:title) as relevance
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.user_id = u.id
                  WHERE p.id != :id AND p.status = 'published'
                  HAVING relevance > 0
                  ORDER BY relevance DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>