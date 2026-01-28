<?php
class Comment {
    private $conn;
    private $table = 'comments';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo comentario
    public function create($post_id, $user_id, $content) {
        $query = "INSERT INTO " . $this->table . " (post_id, user_id, content) 
                  VALUES (:post_id, :user_id, :content)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        
        return $stmt->execute();
    }

    // Obtener comentarios por post
    public function getByPost($post_id) {
        $query = "SELECT c.*, u.username 
                  FROM " . $this->table . " c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  WHERE c.post_id = :post_id 
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los comentarios (admin)
    public function getAll() {
        $query = "SELECT c.*, u.username, p.title as post_title 
                  FROM " . $this->table . " c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  LEFT JOIN posts p ON c.post_id = p.id 
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar comentario
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
