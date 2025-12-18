<?php
/**
 * Database Class - Singleton Pattern
 * Handles MySQL database operations
 */

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        if (!USE_DATABASE) {
            return;
        }
        
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new Exception("Erro de conexão com o banco de dados");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get PDO instance
     */
    public function getPdo() {
        return $this->pdo;
    }
    
    /**
     * Save registration data
     */
    public function saveRegistration($data) {
        if (!USE_DATABASE || !$this->pdo) {
            return false;
        }
        
        $sql = "INSERT INTO registrations (
            full_name, email, whatsapp, company_name, 
            chatwoot_account_id, chatwoot_user_id, evolution_instance,
            created_at
        ) VALUES (
            :full_name, :email, :whatsapp, :company_name,
            :chatwoot_account_id, :chatwoot_user_id, :evolution_instance,
            NOW()
        )";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'whatsapp' => $data['whatsapp'],
                'company_name' => $data['company_name'],
                'chatwoot_account_id' => $data['chatwoot_account_id'],
                'chatwoot_user_id' => $data['chatwoot_user_id'],
                'evolution_instance' => $data['evolution_instance']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database insert error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get registration by email
     */
    public function getRegistrationByEmail($email) {
        if (!USE_DATABASE || !$this->pdo) {
            return null;
        }
        
        $sql = "SELECT * FROM registrations WHERE email = :email LIMIT 1";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Database select error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all registrations
     */
    public function getAllRegistrations($limit = 100, $offset = 0) {
        if (!USE_DATABASE || !$this->pdo) {
            return [];
        }
        
        $sql = "SELECT * FROM registrations ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database select error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get registration count
     */
    public function getRegistrationCount() {
        if (!USE_DATABASE || !$this->pdo) {
            return 0;
        }
        
        $sql = "SELECT COUNT(*) as total FROM registrations";
        
        try {
            $stmt = $this->pdo->query($sql);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    /**
     * Save admin settings
     */
    public function saveSetting($key, $value) {
        if (!USE_DATABASE || !$this->pdo) {
            return false;
        }
        
        $sql = "INSERT INTO settings (setting_key, setting_value, updated_at) 
                VALUES (:key, :value, NOW())
                ON DUPLICATE KEY UPDATE setting_value = :value, updated_at = NOW()";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['key' => $key, 'value' => $value]);
            return true;
        } catch (PDOException $e) {
            error_log("Database setting save error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get setting value
     */
    public function getSetting($key, $default = null) {
        if (!USE_DATABASE || !$this->pdo) {
            return $default;
        }
        
        $sql = "SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['key' => $key]);
            $result = $stmt->fetch();
            return $result ? $result['setting_value'] : $default;
        } catch (PDOException $e) {
            return $default;
        }
    }
    
    /**
     * Delete registration
     */
    public function deleteRegistration($id) {
        if (!USE_DATABASE || !$this->pdo) {
            return false;
        }
        
        $sql = "DELETE FROM registrations WHERE id = :id";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Database delete error: " . $e->getMessage());
            return false;
        }
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
