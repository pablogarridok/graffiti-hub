<?php

namespace App\Models;

use Config\Database;
use PDO;

class Style {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener todos los estilos
     */
    public function getAll() {
        $sql = "SELECT * FROM styles ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estilo por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM styles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estilo por slug
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM styles WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear nuevo estilo (admin)
     */
    public function create($data) {
        $sql = "INSERT INTO styles (nombre, slug, created_at) 
                VALUES (:nombre, :slug, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':slug', $data['slug']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
}