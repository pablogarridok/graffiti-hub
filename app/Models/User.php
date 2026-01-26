<?php
// app/Models/User.php

namespace App\Models;

use Config\Database;
use PDO;

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create($data) {
        $sql = "INSERT INTO users (username, nombre, email, password, created_at) 
                VALUES (:username, :nombre, :email, :password, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', $data['password']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Buscar usuario por ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Buscar usuario por username
     */
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id, $data) {
        $sql = "UPDATE users 
                SET nombre = :nombre,
                    bio = :bio,
                    ciudad = :ciudad,
                    instagram = :instagram,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':bio', $data['bio']);
        $stmt->bindValue(':ciudad', $data['ciudad']);
        $stmt->bindValue(':instagram', $data['instagram']);
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar usuario
     */
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener total de likes recibidos por un usuario
     */
    public function getTotalLikes($userId) {
        $sql = "SELECT COUNT(*) as total 
                FROM likes l
                INNER JOIN pieces p ON l.piece_id = p.id
                WHERE p.user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    /**
     * Obtener todos los usuarios (admin)
     */
    public function getAll($limit = 20, $offset = 0) {
        $sql = "SELECT id, username, nombre, email, ciudad, role, status, created_at 
                FROM users 
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar total de usuarios
     */
    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    /**
     * Contar usuarios bloqueados
     */
    public function getBlockedCount() {
        $sql = "SELECT COUNT(*) as total FROM users WHERE status = 'blocked'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    /**
     * Buscar usuarios por término
     */
    public function search($term, $limit = 10) {
        $sql = "SELECT id, username, nombre, email, ciudad, role, status, bio, created_at
                FROM users 
                WHERE username LIKE :term 
                   OR nombre LIKE :term
                   OR email LIKE :term
                   OR ciudad LIKE :term
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $searchTerm = '%' . $term . '%';
        $stmt->bindValue(':term', $searchTerm);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualizar estado del usuario (active/blocked)
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);
        
        return $stmt->execute();
    }
    
    /**
     * Actualizar rol del usuario (user/admin)
     */
    public function updateRole($id, $role) {
        $sql = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':role', $role);
        
        return $stmt->execute();
    }
}