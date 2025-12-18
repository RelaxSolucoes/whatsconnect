<?php
/**
 * Evolution API Integration Class
 */

class EvolutionAPI {
    private $baseUrl;
    private $apiKey;
    
    public function __construct($baseUrl, $apiKey) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
    }
    
    /**
     * Create a new WhatsApp instance
     */
    public function createInstance($instanceName, $integration = 'WHATSAPP-BAILEYS') {
        $endpoint = '/instance/create';
        
        $data = [
            'instanceName' => $instanceName,
            'qrcode' => true,
            'integration' => $integration
        ];
        
        return $this->request('POST', $endpoint, $data);
    }
    
    /**
     * Get instance info
     */
    public function getInstance($instanceName) {
        $endpoint = "/instance/fetchInstances?instanceName={$instanceName}";
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Get QR Code for instance
     */
    public function getQRCode($instanceName) {
        $endpoint = "/instance/connect/{$instanceName}";
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Delete instance
     */
    public function deleteInstance($instanceName) {
        $endpoint = "/instance/delete/{$instanceName}";
        return $this->request('DELETE', $endpoint);
    }
    
    /**
     * Set Chatwoot integration for instance
     */
    public function setChatwootIntegration($instanceName, $accountId, $token, $chatwootUrl) {
        $endpoint = "/chatwoot/set/{$instanceName}";
        
        $data = [
            'enabled' => true,
            'accountId' => (string)$accountId,
            'token' => $token,
            'url' => $chatwootUrl,
            'signMsg' => true,
            'reopenConversation' => true,
            'conversationPending' => false,
            'nameInbox' => 'WhatsApp',
            'mergeBrazilContacts' => true,
            'importContacts' => false,
            'importMessages' => false,
            'daysLimitImportMessages' => 7,
            'signDelimiter' => "\n",
            'autoCreate' => true,
            'organization' => 'Gerador de QRCode',
            'logo' => '',
            'ignoreJids' => []
        ];
        
        return $this->request('POST', $endpoint, $data, true);
    }
    
    /**
     * Send text message
     */
    public function sendText($instanceName, $number, $text) {
        $endpoint = "/message/sendText/{$instanceName}";
        
        $data = [
            'number' => $number,
            'text' => $text
        ];
        
        return $this->request('POST', $endpoint, $data);
    }
    
    /**
     * Get connection status
     */
    public function getConnectionStatus($instanceName) {
        $endpoint = "/instance/connectionState/{$instanceName}";
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Logout instance
     */
    public function logout($instanceName) {
        $endpoint = "/instance/logout/{$instanceName}";
        return $this->request('DELETE', $endpoint);
    }
    
    /**
     * Restart instance
     */
    public function restart($instanceName) {
        $endpoint = "/instance/restart/{$instanceName}";
        return $this->request('PUT', $endpoint);
    }
    
    /**
     * Make HTTP request to Evolution API
     */
    private function request($method, $endpoint, $data = null, $jsonBody = false) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'apikey: ' . $this->apiKey
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
            error_log("Evolution API Error: $error");
            return null;
        }
        
        if ($httpCode >= 400) {
            error_log("Evolution API HTTP Error $httpCode: $response");
            return ['error' => true, 'httpCode' => $httpCode, 'response' => $response];
        }
        
        $decoded = json_decode($response, true);
        error_log("Evolution API Response: " . $response);
        return $decoded;
    }
}
