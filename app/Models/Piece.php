<?php

namespace App\Models;

use Config\Database;
use PDO;

class Piece {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nueva pieza
     */
    public function create($data) {
        $sql = "INSERT INTO pieces (user_id, titulo, descripcion, imagen, ciudad, estilo_id, created_at) 
                VALUES (:user_id, :titulo, :descripcion, :imagen, :ciudad, :estilo_id, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':descripcion', $data['descripcion']);
        $stmt->bindValue(':imagen', $data['imagen']);
        $stmt->bindValue(':ciudad', $data['ciudad']);
        $stmt->bindValue(':estilo_id', $data['estilo_id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Obtener pieza por ID con información relacionada
     */
    public function getById($id) {
        $sql = "SELECT 
                    p.*,
                    u.username,
                    u.nombre as user_nombre,
                    u.avatar,
                    s.nombre as estilo_nombre,
                    s.slug as estilo_slug,
                    (SELECT COUNT(*) FROM likes WHERE piece_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE piece_id = p.id) as comments_count
                FROM pieces p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN styles s ON p.estilo_id = s.id
                WHERE p.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las piezas con paginación
     */
    public function getAll($limit = 12, $offset = 0) {
        $sql = "SELECT 
                    p.*,
                    u.username,
                    u.nombre as user_nombre,
                    u.avatar,
                    s.nombre as estilo_nombre,
                    s.slug as estilo_slug,
                    (SELECT COUNT(*) FROM likes WHERE piece_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE piece_id = p.id) as comments_count
                FROM pieces p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN styles s ON p.estilo_id = s.id
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener piezas de un usuario
     */
    public function getByUserId($userId, $limit = 12, $offset = 0) {
        $sql = "SELECT 
                    p.*,
                    u.username,
                    u.nombre as user_nombre,
                    s.nombre as estilo_nombre,
                    s.slug as estilo_slug,
                    (SELECT COUNT(*) FROM likes WHERE piece_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE piece_id = p.id) as comments_count
                FROM pieces p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN styles s ON p.estilo_id = s.id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener piezas por estilo
     */
    public function getByStyleId($styleId, $limit = 12, $offset = 0) {
        $sql = "SELECT 
                    p.*,
                    u.username,
                    u.nombre as user_nombre,
                    s.nombre as estilo_nombre,
                    s.slug as estilo_slug,
                    (SELECT COUNT(*) FROM likes WHERE piece_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE piece_id = p.id) as comments_count
                FROM pieces p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN styles s ON p.estilo_id = s.id
                WHERE p.estilo_id = :estilo_id
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estilo_id', $styleId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualizar pieza
     */
    public function update($id, $data) {
        $sql = "UPDATE pieces 
                SET titulo = :titulo,
                    descripcion = :descripcion,
                    ciudad = :ciudad,
                    estilo_id = :estilo_id,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':descripcion', $data['descripcion']);
        $stmt->bindValue(':ciudad', $data['ciudad']);
        $stmt->bindValue(':estilo_id', $data['estilo_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar pieza
     */
    public function delete($id) {
        $sql = "DELETE FROM pieces WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Contar total de piezas
     */
    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as total FROM pieces";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    /**
     * Contar piezas de un usuario
     */
    public function countByUserId($userId) {
        $sql = "SELECT COUNT(*) as total FROM pieces WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    /**
     * Buscar piezas por término
     */
    public function search($term, $limit = 20) {
        $sql = "SELECT 
                    p.*,
                    u.username,
                    u.nombre as user_nombre,
                    s.nombre as estilo_nombre,
                    s.slug as estilo_slug,
                    (SELECT COUNT(*) FROM likes WHERE piece_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE piece_id = p.id) as comments_count
                FROM pieces p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN styles s ON p.estilo_id = s.id
                WHERE p.titulo LIKE :term 
                   OR p.descripcion LIKE :term
                   OR p.ciudad LIKE :term
                   OR u.username LIKE :term
                ORDER BY p.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $searchTerm = '%' . $term . '%';
        $stmt->bindValue(':term', $searchTerm);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener piezas trending (más likes recientes)
     */
    public function getTrending($limit = 12) {
        $sql = "SELECT 
                    p.*,
                    u.username,
                    u.nombre as user_nombre,
                    s.nombre as estilo_nombre,
                    s.slug as estilo_slug,
                    (SELECT COUNT(*) FROM likes WHERE piece_id = p.id) as likes_count,
                    (SELECT COUNT(*) FROM comments WHERE piece_id = p.id) as comments_count
                FROM pieces p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN styles s ON p.estilo_id = s.id
                WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                ORDER BY likes_count DESC, p.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}