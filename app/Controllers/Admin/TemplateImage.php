<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TemplateImage extends BaseController
{
    protected $uploadPath;
    protected $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']; // Semua format gambar
    protected $maxSize = 1024; // 1MB in KB

    public function __construct()
    {
        $this->uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        
        // Create directory if not exists
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
        
        // Create index.html for security
        $indexFile = $this->uploadPath . 'index.html';
        if (!file_exists($indexFile)) {
            file_put_contents($indexFile, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory access is forbidden.</h1></body></html>');
        }
    }

    /**
     * Upload image untuk template
     */
    public function upload()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Only AJAX requests allowed'
            ]);
        }

        // Check PHP upload limits before processing
        $postMaxSize = $this->parseSize(ini_get('post_max_size'));
        $contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int)$_SERVER['CONTENT_LENGTH'] : 0;
        
        if ($contentLength > 0 && $contentLength > $postMaxSize) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File terlalu besar. Ukuran maksimal: ' . ini_get('post_max_size') . '. Ukuran file Anda: ' . $this->formatBytes($contentLength),
                'php_limits' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'current_content_length' => $this->formatBytes($contentLength)
                ]
            ]);
        }

        // Cek apakah upload image, music, atau video
        $file = $this->request->getFile('image');
        $fileType = 'image';
        
        if (!$file || !$file->isValid()) {
            // Coba music
            $file = $this->request->getFile('music');
            $fileType = 'music';
        }
        
        if (!$file || !$file->isValid()) {
            // Coba video
            $file = $this->request->getFile('video');
            $fileType = 'video';
        }
        
        if (!$file || !$file->isValid()) {
            $errorMessage = 'No file uploaded or file is invalid';
            
            // Check for specific upload errors
            if ($file) {
                $errorCode = $file->getError();
                switch ($errorCode) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'File terlalu besar. Ukuran maksimal: ' . ini_get('upload_max_filesize');
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'File hanya ter-upload sebagian';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'Tidak ada file yang diupload';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = 'Folder temporary tidak ditemukan';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = 'Gagal menulis file ke disk';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = 'Upload dihentikan oleh extension PHP';
                        break;
                }
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $errorMessage
            ]);
        }

        // Validate file berdasarkan tipe
        $rules = [];
        $maxSize = $this->maxSize; // Default 1MB untuk image
        
        if ($fileType === 'image') {
            // Validasi untuk image - semua format gambar, max 1MB
            $rules = [
                'image' => [
                    'uploaded[image]',
                    'max_size[image,' . $maxSize . ']',
                    'ext_in[image,' . implode(',', $this->allowedTypes) . ']',
                    'mime_in[image,image/jpeg,image/png,image/gif,image/webp,image/svg+xml]'
                ]
            ];
        } elseif ($fileType === 'music') {
            // Validasi untuk music - MP3, WAV, OGG, max 5MB
            $maxSize = 5120; // 5MB
            $rules = [
                'music' => [
                    'uploaded[music]',
                    'max_size[music,' . $maxSize . ']',
                    'ext_in[music,mp3,wav,ogg]',
                    'mime_in[music,audio/mpeg,audio/wav,audio/ogg,audio/mp3]'
                ]
            ];
        } elseif ($fileType === 'video') {
            // Validasi untuk video - MP4, WebM, max 50MB
            $maxSize = 51200; // 50MB
            $rules = [
                'video' => [
                    'uploaded[video]',
                    'max_size[video,' . $maxSize . ']',
                    'ext_in[video,mp4,webm]',
                    'mime_in[video,video/mp4,video/webm]'
                ]
            ];
        }

        if (!empty($rules) && !$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Generate unique filename
        $originalName = $file->getName();
        $extension = $file->getClientExtension();
        $newName = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
        
        // Tentukan path upload berdasarkan tipe file
        $uploadPath = $this->uploadPath;
        if ($fileType === 'music') {
            $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'music' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
        } elseif ($fileType === 'video') {
            $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
        }
        
        // Move file
        if ($file->move($uploadPath, $newName)) {
            // Generate URL berdasarkan tipe file
            if ($fileType === 'image') {
                $fileUrl = base_url('assets/images/templates/' . $newName);
            } elseif ($fileType === 'music') {
                $fileUrl = base_url('assets/media/music/' . $newName);
            } elseif ($fileType === 'video') {
                $fileUrl = base_url('assets/media/video/' . $newName);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => ucfirst($fileType) . ' uploaded successfully',
                'url' => $fileUrl,
                'data' => [
                    'url' => $fileUrl,
                    'filename' => $newName,
                    'original_name' => $originalName,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType()
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to move uploaded file',
                'errors' => $file->getErrorString()
            ]);
        }
    }

    /**
     * Delete image
     */
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Only AJAX requests allowed'
            ]);
        }

        $filename = $this->request->getPost('filename');
        
        if (empty($filename)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Filename is required'
            ]);
        }

        // Security: prevent directory traversal
        $filename = basename($filename);
        $filePath = $this->uploadPath . $filename;

        if (!file_exists($filePath)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File not found'
            ]);
        }

        // Security: ensure file is in upload directory
        $realPath = realpath($filePath);
        $realUploadPath = realpath($this->uploadPath);
        
        if (strpos($realPath, $realUploadPath) !== 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid file path'
            ]);
        }

        if (unlink($filePath)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Image deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete file'
            ]);
        }
    }

    /**
     * List all uploaded images
     */
    public function list()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Only AJAX requests allowed'
            ]);
        }

        $images = [];
        $files = glob($this->uploadPath . '*');
        
        foreach ($files as $file) {
            if (is_file($file) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $this->allowedTypes)) {
                $filename = basename($file);
                $images[] = [
                    'filename' => $filename,
                    'url' => base_url('assets/images/templates/' . $filename),
                    'size' => filesize($file),
                    'modified' => date('Y-m-d H:i:s', filemtime($file))
                ];
            }
        }

        // Sort by modified date (newest first)
        usort($images, function($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $images,
            'count' => count($images)
        ]);
    }

    /**
     * Parse PHP size string to bytes
     * Example: "100M" -> 104857600
     */
    private function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        
        return round($size);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

