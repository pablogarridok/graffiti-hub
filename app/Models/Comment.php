<?php

namespace App\Models;

use Config\Database;
use PDO;

class Comment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nuevo comentario
     */
    public function create($data) {
        $sql = "INSERT INTO comments (piece_id, user_id, contenido, created_at) 
                VALUES (:piece_id, :user_id, :contenido, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':piece_id', $data['piece_id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':contenido', $data['contenido']);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener comentarios de una pieza
     */
    public function getByPieceId($pieceId) {
        $sql = "SELECT 
                    c.*,
                    u.username,
                    u.nombre as user_nombre,
                    u.avatar
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.piece_id = :piece_id
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener comentario por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Eliminar comentario
     */
    public function delete($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Contar comentarios de una pieza
     */
    public function getCount($pieceId) {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE piece_id = :piece_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    /**
     * Obtener comentarios de un usuario
     */
    public function getByUserId($userId, $limit = 10, $offset = 0) {
        $sql = "SELECT 
                    c.*,
                    p.titulo as piece_titulo,
                    p.imagen as piece_imagen
                FROM comments c
                INNER JOIN pieces p ON c.piece_id = p.id
                WHERE c.user_id = :user_id
                ORDER BY c.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}