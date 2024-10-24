<?php
namespace JurateVilima\MvcCore;

class Request {
    public function getPath() {
        return isset($_GET['url']) ? "/{$_GET['url']}" : '/';
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isPost() {
        return $this->getMethod() === 'POST';
    }

    public function isGet() {
        return $this->getMethod() === 'GET';
    }

    public function fetchSanitizedData() {
        // Determine the request method
        $method = $this->getMethod();
        
        // Select the appropriate superglobal array
        $data = ($method === 'POST') ? $_POST : $_GET;

        // Remove 'url' parameter out of the array
        if ($method === 'GET' && isset($data['url'])) {
            unset($data['url']);
        }
        
        // Define a function to sanitize input values
        $sanitizedArr = function($value) {
            $value = trim($value);
            return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        };
    
        // Apply sanitization to the selected data
        $sanitizedData = array_map($sanitizedArr, $data);
        
        return $sanitizedData;
    }
    
    
}