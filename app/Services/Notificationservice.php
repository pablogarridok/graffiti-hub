<?php
/**
 * Servicio de Notificación via n8n Webhook
 * Envía los datos del post al workflow de n8n para que envíe el email
 */

class NotificationService {
    private $webhook_url;
    
    public function __construct() {
        $this->webhook_url = N8N_WEBHOOK_URL;
    }
    
    /**
     * Enviar notificación de nuevo post al webhook de n8n
     * 
     * @param array $post - Datos del post
     * @param string $author - Nombre del autor
     * @return bool
     */
    public function sendNewPostNotification($post, $author) {
        // Log inicial
        error_log("=== INICIANDO ENVÍO DE NOTIFICACIÓN ===");
        error_log("Post ID: " . ($post['id'] ?? 'N/A'));
        error_log("Título: " . ($post['title'] ?? 'N/A'));
        error_log("Autor: " . $author);
        error_log("Webhook URL: " . $this->webhook_url);
        
        try {
            // Verificar que el webhook esté configurado
            if (empty($this->webhook_url) || $this->webhook_url === 'URL_DE_TU_WEBHOOK_N8N') {
                error_log("❌ ERROR: Webhook URL no está configurada");
                return false;
            }
            
            // Preparar los datos en el formato que espera n8n
            $data = [
                'title' => $post['title'] ?? 'Sin título',
                'content' => $post['content'] ?? '',
                'author' => $post['username'] ?? $author ?? 'Anónimo',
                'post_url' => BASE_URL . '/posts/' . ($post['id'] ?? ''),
                'image_url' => !empty($post['image']) ? UPLOAD_URL . $post['image'] : '',
                'created_at' => $post['created_at'] ?? date('Y-m-d H:i:s')
            ];

            
            error_log("📦 Datos a enviar: " . json_encode($data, JSON_UNESCAPED_UNICODE));
            
            // Convertir a JSON
            $json_data = json_encode($data);
            
            if ($json_data === false) {
                error_log("❌ ERROR: No se pudo convertir los datos a JSON");
                return false;
            }
            
            // Configurar la petición cURL
            $ch = curl_init($this->webhook_url);
            
            if ($ch === false) {
                error_log("❌ ERROR: No se pudo inicializar cURL");
                return false;
            }
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_data)
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            
            // Ejecutar la petición
            error_log("📡 Enviando petición al webhook...");
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            $curl_errno = curl_errno($ch);
            
            curl_close($ch);
            
            // Log de la respuesta
            error_log("📊 Código HTTP: " . $http_code);
            
            if ($curl_errno !== 0) {
                error_log("❌ ERROR cURL (#" . $curl_errno . "): " . $curl_error);
                return false;
            }
            
            if ($response !== false) {
                error_log("📄 Respuesta: " . substr($response, 0, 500));
            }
            
            // Verificar si fue exitoso
            if ($http_code >= 200 && $http_code < 300) {
                error_log("✅ NOTIFICACIÓN ENVIADA EXITOSAMENTE");
                error_log("=== FIN DE NOTIFICACIÓN ===");
                return true;
            } else {
                error_log("⚠️ ERROR: Código HTTP no exitoso: " . $http_code);
                error_log("Respuesta completa: " . $response);
                error_log("=== FIN DE NOTIFICACIÓN (CON ERROR) ===");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("❌ EXCEPCIÓN al enviar notificación: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("=== FIN DE NOTIFICACIÓN (CON EXCEPCIÓN) ===");
            return false;
        }
    }
    
    /**
     * Verificar que el webhook está configurado
     */
    public function isConfigured() {
        return !empty($this->webhook_url) && $this->webhook_url !== 'URL_DE_TU_WEBHOOK_N8N';
    }
    
    /**
     * Probar la conexión al webhook
     * 
     * @return array Con 'success' (bool) y 'message' (string)
     */
    public function testConnection() {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Webhook URL no está configurada'
            ];
        }
        
        $test_data = json_encode([
            'title' => 'Test de Conexión',
            'content' => 'Este es un mensaje de prueba',
            'author' => 'Sistema',
            'post_url' => BASE_URL . '/test',
            'image_url' => '',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $ch = curl_init($this->webhook_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $test_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        if ($curl_error) {
            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $curl_error
            ];
        }
        
        if ($http_code >= 200 && $http_code < 300) {
            return [
                'success' => true,
                'message' => 'Conexión exitosa (HTTP ' . $http_code . ')'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Webhook respondió con código HTTP ' . $http_code
        ];
    }
}
?>