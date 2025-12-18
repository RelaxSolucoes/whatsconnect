<?php
/**
 * WhatsApp Message Sender Class
 * Uses Evolution API to send WhatsApp messages
 */

class WhatsAppSender {
    private $evolutionUrl;
    private $apiKey;
    private $instanceName;
    
    public function __construct() {
        $this->evolutionUrl = rtrim(EVOLUTION_URL, '/');
        $this->apiKey = EVOLUTION_APIKEY;
        $this->instanceName = EVOLUTION_INSTANCE;
    }
    
    /**
     * Send welcome message to new user
     */
    public function sendWelcomeMessage($phone, $name, $company, $email, $password, $chatwootUrl) {
        // Format phone number (add 55 if not present)
        $phone = $this->formatPhoneNumber($phone);
        
        // Get message template and replace shortcodes
        $message = $this->formatMessage($name, $company, $email, $password, $chatwootUrl);
        
        return $this->sendText($phone, $message);
    }
    
    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber($phone) {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // Add Brazil country code if not present
        if (strlen($phone) <= 11) {
            $phone = '55' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Format message with shortcodes
     */
    private function formatMessage($name, $company, $email, $password, $url) {
        $template = WELCOME_MESSAGE_TEMPLATE;
        
        // Replace shortcodes
        $replacements = [
            '{nome}' => $name,
            '{empresa}' => $company,
            '{email}' => $email,
            '{senha}' => $password,
            '{url}' => $url
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
    
    /**
     * Send text message via Evolution API
     */
    public function sendText($number, $text) {
        if (empty($this->instanceName)) {
            error_log("WhatsApp Sender: Instance name not configured");
            return false;
        }
        
        $endpoint = "/message/sendText/{$this->instanceName}";
        $url = $this->evolutionUrl . $endpoint;
        
        $data = [
            'number' => $number,
            'text' => $text
        ];
        
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apikey: ' . $this->apiKey
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("WhatsApp Sender Error: $error");
            return false;
        }
        
        if ($httpCode >= 400) {
            error_log("WhatsApp Sender HTTP Error $httpCode: $response");
            return false;
        }
        
        $result = json_decode($response, true);
        return isset($result['key']) || (isset($result['status']) && $result['status'] === 'PENDING');
    }
    
    /**
     * Send custom message
     */
    public function sendCustomMessage($phone, $message) {
        $phone = $this->formatPhoneNumber($phone);
        return $this->sendText($phone, $message);
    }
}
