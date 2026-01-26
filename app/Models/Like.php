<?php

namespace App\Models;

use Config\Database;
use PDO;

class Like {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Dar like a una pieza
     */
    public function create($userId, $pieceId) {
        $sql = "INSERT INTO likes (user_id, piece_id, created_at) 
                VALUES (:user_id, :piece_id, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        
        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Si ya existe el like (UNIQUE constraint)
            return false;
        }
    }
    
    /**
     * Quitar like de una pieza
     */
    public function delete($userId, $pieceId) {
        $sql = "DELETE FROM likes 
                WHERE user_id = :user_id AND piece_id = :piece_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Toggle like (dar o quitar)
     */
    public function toggle($userId, $pieceId) {
        if ($this->hasLiked($userId, $pieceId)) {
            $this->delete($userId, $pieceId);
            return false; // Se quitó el like
        } else {
            $this->create($userId, $pieceId);
            return true; // Se dio like
        }
    }
    
    /**
     * Verificar si un usuario dio like a una pieza
     */
    public function hasLiked($userId, $pieceId) {
        $sql = "SELECT COUNT(*) as count 
                FROM likes 
                WHERE user_id = :user_id AND piece_id = :piece_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'] > 0;
    }
    
    /**
     * Contar likes de una pieza
     */
    public function countByPieceId($pieceId) {
        $sql = "SELECT COUNT(*) as total FROM likes WHERE piece_id = :piece_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    /**
     * Obtener likes de un usuario
     */
    public function getByUserId($userId, $limit = 20, $offset = 0) {
        $sql = "SELECT 
                    l.*,
                    p.titulo,
                    p.imagen,
                    p.user_id as piece_user_id,
                    u.username
                FROM likes l
                INNER JOIN pieces p ON l.piece_id = p.id
                INNER JOIN users u ON p.user_id = u.id
                WHERE l.user_id = :user_id
                ORDER BY l.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener usuarios que dieron like a una pieza
     */
    public function getUsersByPieceId($pieceId, $limit = 20) {
        $sql = "SELECT 
                    u.id,
                    u.username,
                    u.nombre,
                    u.avatar,
                    l.created_at as liked_at
                FROM likes l
                INNER JOIN users u ON l.user_id = u.id
                WHERE l.piece_id = :piece_id
                ORDER BY l.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':piece_id', $pieceId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}