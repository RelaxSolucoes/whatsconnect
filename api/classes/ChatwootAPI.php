<?php
/**
 * Chatwoot API Integration Class
 */

class ChatwootAPI {
    private $baseUrl;
    private $token;
    
    public function __construct($baseUrl, $token) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->token = $token;
    }
    
    /**
     * Create a new account (company) in Chatwoot
     */
    public function createAccount($name, $locale = 'pt_BR') {
        $endpoint = '/platform/api/v1/accounts';
        
        $data = [
            'name' => $name,
            'locale' => $locale
        ];
        
        return $this->request('POST', $endpoint, $data);
    }
    
    /**
     * Create a new user in Chatwoot
     */
    public function createUser($name, $email, $password) {
        $endpoint = '/platform/api/v1/users';
        
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        
        return $this->request('POST', $endpoint, $data);
    }
    
    /**
     * Add user to account with role
     */
    public function addUserToAccount($accountId, $userId, $role = 'administrator') {
        $endpoint = "/platform/api/v1/accounts/{$accountId}/account_users";
        
        $data = [
            'user_id' => $userId,
            'role' => $role
        ];
        
        return $this->request('POST', $endpoint, $data);
    }
    
    /**
     * Get account details
     */
    public function getAccount($accountId) {
        $endpoint = "/platform/api/v1/accounts/{$accountId}";
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Get user details
     */
    public function getUser($userId) {
        $endpoint = "/platform/api/v1/users/{$userId}";
        return $this->request('GET', $endpoint);
    }
    
    /**
     * List all accounts
     */
    public function listAccounts() {
        $endpoint = '/platform/api/v1/accounts';
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Delete account
     */
    public function deleteAccount($accountId) {
        $endpoint = "/platform/api/v1/accounts/{$accountId}";
        return $this->request('DELETE', $endpoint);
    }
    
    /**
     * Make HTTP request to Chatwoot API
     */
    private function request($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'api_access_token: ' . $this->token
        ];
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("Chatwoot API Error: $error");
            return null;
        }
        
        if ($httpCode >= 400) {
            error_log("Chatwoot API HTTP Error $httpCode: $response");
            return null;
        }
        
        return json_decode($response, true);
    }
}
