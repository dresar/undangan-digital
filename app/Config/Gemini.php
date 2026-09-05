<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Gemini extends BaseConfig
{
    public string $apiKey = '';
    
    public string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    
    public function __construct()
    {
        parent::__construct();
        $this->apiKey = $_ENV['gemini.apiKey'] ?? getenv('gemini.apiKey') ?: '';
    }
}

