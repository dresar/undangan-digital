<?php

namespace App\Libraries;

use Config\Gemini;
use CodeIgniter\HTTP\CURLRequest;

class GeminiAI
{
    protected $config;
    protected $client;

    public function __construct()
    {
        $this->config = new Gemini();
        $this->client = \Config\Services::curlrequest();
    }

    public function generateCode($prompt, $context = '')
    {
        if (empty($this->config->apiKey)) {
            return [
                'success' => false,
                'error' => 'API Key tidak ditemukan. Silakan set gemini.apiKey di file .env'
            ];
        }

        $systemPrompt = $this->getSystemPrompt($context);
        $fullPrompt = $systemPrompt . "\n\nUser Request: " . $prompt;

        try {
            $url = $this->config->apiUrl . '?key=' . urlencode($this->config->apiKey);
            
            $response = $this->client->request('POST', $url, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'http_errors' => false,
                'timeout' => 30,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            if ($statusCode === 200) {
                $data = json_decode($body, true);
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $generatedText = $data['candidates'][0]['content']['parts'][0]['text'];
                    $extracted = $this->extractCode($generatedText);
                    
                    if ($context === 'php' && (!empty($extracted['css']) || !empty($extracted['js']))) {
                        return [
                            'success' => true,
                            'code' => $extracted['php'],
                            'css' => $extracted['css'] ?? '',
                            'js' => $extracted['js'] ?? '',
                            'has_multiple' => true,
                            'full_response' => $generatedText
                        ];
                    } else {
                        $code = $extracted['php'] ?: ($extracted['css'] ?: $extracted['js']);
                        return [
                            'success' => true,
                            'code' => $code,
                            'full_response' => $generatedText
                        ];
                    }
                } elseif (isset($data['error'])) {
                    return [
                        'success' => false,
                        'error' => $data['error']['message'] ?? 'API Error: ' . json_encode($data['error'])
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Format response tidak valid: ' . json_encode($data)
                    ];
                }
            } else {
                $errorData = json_decode($body, true);
                $errorMsg = isset($errorData['error']['message']) ? $errorData['error']['message'] : $body;
                return [
                    'success' => false,
                    'error' => 'API Error: ' . $statusCode . ' - ' . $errorMsg
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function getSystemPrompt($context)
    {
        $basePrompt = "Anda adalah asisten AI untuk CodeIgniter 4 Template Editor. ";
        $basePrompt .= "Anda membantu developer membuat template undangan digital dengan PHP.\n\n";
        
        $basePrompt .= "VARIABLE YANG TERSEDIA:\n";
        $basePrompt .= "- \$invitation['title'] - Judul undangan\n";
        $basePrompt .= "- \$invitation['groom_name'] - Nama mempelai pria\n";
        $basePrompt .= "- \$invitation['bride_name'] - Nama mempelai wanita\n";
        $basePrompt .= "- \$invitation['groom_parents'] - Orang tua mempelai pria\n";
        $basePrompt .= "- \$invitation['bride_parents'] - Orang tua mempelai wanita\n";
        $basePrompt .= "- \$invitation['wedding_date'] - Tanggal acara (format: Y-m-d H:i:s)\n";
        $basePrompt .= "- \$invitation['wedding_location'] - Lokasi acara\n";
        $basePrompt .= "- \$invitation['wedding_address'] - Alamat lengkap\n";
        $basePrompt .= "- \$invitation['contact_phone'] - No. telepon\n";
        $basePrompt .= "- \$invitation['contact_email'] - Email\n";
        $basePrompt .= "- \$invitation['contact_whatsapp'] - WhatsApp\n";
        $basePrompt .= "- \$invitation['music_url'] - URL musik\n";
        $basePrompt .= "- \$invitation['video_url'] - URL video\n";
        $basePrompt .= "- \$invitation['cover_image'] - Cover image\n";
        $basePrompt .= "- \$invitation['category'] - Kategori\n";
        $basePrompt .= "- \$invitation['tags'] - Tags\n\n";
        
        $basePrompt .= "HELPER FUNCTIONS:\n";
        $basePrompt .= "- esc(\$string) - Escape HTML\n";
        $basePrompt .= "- base_url(\$path) - Generate base URL\n";
        $basePrompt .= "- date('format', strtotime(\$date)) - Format tanggal\n\n";
        
        $basePrompt .= "LIBRARY YANG SUDAH TERSEDIA (CDN):\n";
        $basePrompt .= "- Bootstrap 5 (local)\n";
        $basePrompt .= "- Bootstrap Icons (local)\n";
        $basePrompt .= "- jQuery (local)\n";
        $basePrompt .= "- Moment.js (CDN) - untuk format tanggal\n";
        $basePrompt .= "- FancyBox (CDN) - untuk lightbox gallery\n";
        $basePrompt .= "- Clipboard.js (CDN) - untuk copy to clipboard\n";
        $basePrompt .= "- jQuery UI (CDN) - untuk UI components\n";
        $basePrompt .= "- Three.js (CDN) - untuk 3D graphics\n";
        $basePrompt .= "- Particles.js (CDN) - untuk particle effects\n\n";
        
        $basePrompt .= "GAMBAR:\n";
        $basePrompt .= "- Untuk gambar latar belakang dan animasi bunga: gunakan CDN (contoh: unsplash.com, pixabay.com)\n";
        $basePrompt .= "- Untuk foto pengantin: gunakan default placeholder atau \$invitation['cover_image']\n\n";
        
        $basePrompt .= "RULES:\n";
        $basePrompt .= "1. Selalu gunakan esc() untuk output data user\n";
        $basePrompt .= "2. Bootstrap 5, jQuery sudah include otomatis (local)\n";
        $basePrompt .= "3. CSS dan JS template sudah terhubung otomatis, JANGAN tambahkan <link> atau <script> untuk file template\n";
        $basePrompt .= "4. Untuk library CDN (Moment.js, FancyBox, dll), gunakan CDN link\n";
        $basePrompt .= "5. Output hanya kode PHP/HTML/CSS/JS yang diminta\n";
        $basePrompt .= "6. Jangan tambahkan komentar yang tidak perlu\n";
        $basePrompt .= "7. Kode harus clean dan production-ready\n\n";

        if ($context === 'php') {
            $basePrompt .= "KONTEKS: Anda sedang mengedit file index.php template.\n";
            $basePrompt .= "File ini adalah file PHP yang akan di-include oleh sistem.\n";
            $basePrompt .= "Variable \$invitation sudah tersedia.\n";
            $basePrompt .= "CSS dan JS template (style.css, script.js) sudah terhubung otomatis oleh sistem.\n";
            $basePrompt .= "PENTING: Jika user meminta fitur yang memerlukan CSS atau JS, GENERATE JUGA kode CSS dan JS yang diperlukan.\n";
            $basePrompt .= "Format output: Berikan 3 bagian terpisah dengan label [PHP], [CSS], [JS]\n";
        } elseif ($context === 'css') {
            $basePrompt .= "KONTEKS: Anda sedang mengedit file CSS template.\n";
            $basePrompt .= "Gunakan Bootstrap 5 classes jika memungkinkan.\n";
            $basePrompt .= "File CSS sudah terhubung otomatis, tidak perlu <link> tag.\n";
        } elseif ($context === 'js') {
            $basePrompt .= "KONTEKS: Anda sedang mengedit file JavaScript template.\n";
            $basePrompt .= "jQuery, Moment.js, FancyBox, Clipboard.js, jQuery UI, Three.js, Particles.js sudah tersedia.\n";
            $basePrompt .= "File JS sudah terhubung otomatis, tidak perlu <script> tag untuk file template.\n";
        }

        return $basePrompt;
    }

    private function extractCode($text)
    {
        $result = [
            'php' => '',
            'css' => '',
            'js' => ''
        ];

        if (preg_match('/\[PHP\](.*?)(?=\[CSS\]|\[JS\]|$)/s', $text, $matches)) {
            $phpCode = trim($matches[1]);
            if (preg_match('/```(?:php|html)?\s*(.*?)```/s', $phpCode, $codeMatch)) {
                $result['php'] = trim($codeMatch[1]);
            } else {
                $result['php'] = trim($phpCode);
            }
        }

        if (preg_match('/\[CSS\](.*?)(?=\[JS\]|$)/s', $text, $matches)) {
            $cssCode = trim($matches[1]);
            if (preg_match('/```css\s*(.*?)```/s', $cssCode, $codeMatch)) {
                $result['css'] = trim($codeMatch[1]);
            } else {
                $result['css'] = trim($cssCode);
            }
        }

        if (preg_match('/\[JS\](.*?)$/s', $text, $matches)) {
            $jsCode = trim($matches[1]);
            if (preg_match('/```(?:javascript|js)?\s*(.*?)```/s', $jsCode, $codeMatch)) {
                $result['js'] = trim($codeMatch[1]);
            } else {
                $result['js'] = trim($jsCode);
            }
        }

        if (empty($result['php']) && empty($result['css']) && empty($result['js'])) {
            if (preg_match('/```(?:php|html|css|javascript|js)?\s*(.*?)```/s', $text, $matches)) {
                $result['php'] = trim($matches[1]);
            } else {
                $result['php'] = trim($text);
            }
        }

        return $result;
    }

    public function testApiKey()
    {
        if (empty($this->config->apiKey)) {
            return [
                'success' => false,
                'error' => 'API Key tidak ditemukan'
            ];
        }

        try {
            $url = $this->config->apiUrl . '?key=' . urlencode($this->config->apiKey);
            
            $response = $this->client->request('POST', $url, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'Test connection. Reply with "OK" if you can read this.']
                            ]
                        ]
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'http_errors' => false,
                'timeout' => 10,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            
            if ($statusCode === 200) {
                $data = json_decode($body, true);
                $responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'OK';
                return [
                    'success' => true,
                    'message' => 'API Key valid dan terhubung dengan Gemini 2.5 Flash! Response: ' . $responseText
                ];
            } else {
                $errorData = json_decode($body, true);
                $errorMsg = $errorData['error']['message'] ?? $body;
                return [
                    'success' => false,
                    'error' => 'API Error: ' . $statusCode . ' - ' . $errorMsg
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}

