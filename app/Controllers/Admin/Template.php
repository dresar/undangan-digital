<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TemplateModel;

class Template extends BaseController
{
    protected $templateModel;
    protected $templatesPath;

    public function __construct()
    {
        $this->templateModel = new TemplateModel();
        $this->templatesPath = ROOTPATH . 'templates' . DIRECTORY_SEPARATOR;
        
        // Create templates directory if not exists
        if (!is_dir($this->templatesPath)) {
            mkdir($this->templatesPath, 0755, true);
        }
    }

    /**
     * Halaman utama - lihat semua templates
     */
    public function index()
    {
        // Scan folder templates
        $folders = $this->scanTemplatesFolders();
        
        // Get template info from database
        $dbTemplates = $this->templateModel->findAll();
        $dbTemplatesMap = [];
        foreach ($dbTemplates as $tpl) {
            $dbTemplatesMap[$tpl['slug']] = $tpl;
        }
        
        // Merge folder info with database info
        $templates = [];
        foreach ($folders as $folder) {
            $slug = $folder['name'];
            $templates[] = [
                'slug' => $slug,
                'path' => $folder['path'],
                'files' => $folder['files'],
                'has_index' => $folder['has_index'],
                'db_info' => $dbTemplatesMap[$slug] ?? null,
            ];
        }
        
        $data = [
            'templates' => $templates,
            'templatesPath' => $this->templatesPath,
        ];
        
        return view('admin/template/index', $data);
    }

    /**
     * Create folder baru
     */
    public function createFolder()
    {
        $folderName = $this->request->getPost('folder_name');
        
        if (empty($folderName)) {
            return redirect()->back()->with('error', 'Nama folder tidak boleh kosong');
        }
        
        // Sanitize folder name (hanya huruf, angka, dash, underscore)
        $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $folderName);
        
        if (empty($folderName)) {
            return redirect()->back()->with('error', 'Nama folder tidak valid');
        }
        
        $folderPath = $this->templatesPath . $folderName;
        
        if (is_dir($folderPath)) {
            return redirect()->back()->with('error', 'Folder sudah ada: ' . $folderName);
        }
        
        // Create folder (kosong, user yang akan isi sendiri)
        if (!mkdir($folderPath, 0755, true)) {
            return redirect()->back()->with('error', 'Gagal membuat folder');
        }
        
        // Create entry in database if not exists
        $existing = $this->templateModel->where('slug', $folderName)->first();
        if (!$existing) {
            $this->templateModel->insert([
                'name' => ucfirst($folderName),
                'slug' => $folderName,
                'description' => '',
                'preview_image' => '',
                'template_path' => $folderPath . DIRECTORY_SEPARATOR,
                'css_files' => '[]',
                'js_files' => '[]',
                'template_config' => '{}',
                'category' => '',
                'is_active' => 1,
                'is_premium' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        return redirect()->to('/admin/template')->with('success', 'Folder template berhasil dibuat: ' . $folderName);
    }

    /**
     * Browse isi folder
     */
    public function browse($slug)
    {
        $folderPath = $this->templatesPath . $slug . DIRECTORY_SEPARATOR;
        
        if (!is_dir($folderPath)) {
            return redirect()->to('/admin/template')->with('error', 'Folder tidak ditemukan');
        }
        
        $files = $this->scanFolderContents($folderPath);
        
        $data = [
            'slug' => $slug,
            'folderPath' => $folderPath,
            'files' => $files,
        ];
        
        return view('admin/template/browse', $data);
    }

    /**
     * Preview index.php di tab baru
     */
    public function preview($slug)
    {
        try {
            $folderPath = $this->templatesPath . $slug . DIRECTORY_SEPARATOR;
            $indexFile = $folderPath . 'index.php';
            
            if (!file_exists($indexFile)) {
                throw new \Exception('File index.php tidak ditemukan di template: ' . $slug);
            }
            
            // Get template from database
            $template = $this->templateModel->where('slug', $slug)->first();
            
            // Sample invitation data untuk preview
            $invitation = new \App\Entities\InvitationEntity([
                'id' => 1,
                'title' => 'Preview Template - ' . $slug,
                'groom_name' => 'John Doe',
                'bride_name' => 'Jane Smith',
                'groom_nickname' => 'John',
                'bride_nickname' => 'Jane',
                'cover_image' => base_url('assets/images/default-cover.jpg'),
                'groom_photo' => base_url('assets/images/default-groom.jpg'),
                'bride_photo' => base_url('assets/images/default-bride.jpg'),
                'recipient_name' => 'Bapak/Ibu/Saudara/i',
                'recipient_title' => 'Kepada Yth.',
                'recipient_address' => 'Di Tempat',
                'wedding_date' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'wedding_location' => 'Grand Ballroom Hotel',
                'wedding_address' => 'Jl. Contoh No. 123, Jakarta Selatan',
                'groom_father' => 'Bapak Father',
                'groom_mother' => 'Ibu Mother',
                'bride_father' => 'Bapak Father',
                'bride_mother' => 'Ibu Mother',
                'contact_phone' => '+62 812-3456-7890',
                'contact_email' => 'contact@example.com',
                'contact_whatsapp' => '+628123456789',
                'music_url' => base_url('assets/media/default-music.mp3'),
                'video_url' => '',
                'location_map_url' => 'https://maps.google.com',
                'location_map_embed' => '',
                'location_map_image' => '',
                'gallery_images' => json_encode([]),
                'invitation_text' => 'Dengan memohon Rahmat dan Ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami.',
                'event_akad_date' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'event_akad_location' => 'Masjid Al-Ikhlas',
                'event_akad_address' => 'Jl. Contoh No. 123',
                'event_resepsi_date' => date('Y-m-d H:i:s', strtotime('+30 days 3 hours')),
                'event_resepsi_location' => 'Grand Ballroom Hotel',
                'event_resepsi_address' => 'Jl. Contoh No. 123',
                'our_stories' => [],
                'quotes' => [],
                'slug' => 'preview-' . $slug,
                'status' => 'published',
            ]);
            
            // Include the template
            ob_start();
            include $indexFile;
            $content = ob_get_clean();
            
            return $this->response->setBody($content);
            
        } catch (\Exception $e) {
            return $this->response->setBody('
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Error Preview</title>
                    <link href="' . base_url('assets/css/tailwind.css') . '" rel="stylesheet">
                </head>
                <body class="p-8 bg-gray-50">
                    <div class="max-w-2xl mx-auto bg-red-50 border border-red-200 rounded-lg p-6">
                        <h1 class="text-2xl font-bold text-red-600 mb-4">Error Preview Template</h1>
                        <p class="text-gray-700 mb-4">' . esc($e->getMessage()) . '</p>
                        <a href="' . base_url('admin/template') . '" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Kembali ke Templates
                        </a>
                    </div>
                </body>
                </html>
            ');
        }
    }

    /**
     * Delete folder template
     */
    public function delete($slug)
    {
        $folderPath = $this->templatesPath . $slug . DIRECTORY_SEPARATOR;
        
        if (!is_dir($folderPath)) {
            return redirect()->to('/admin/template')->with('error', 'Folder tidak ditemukan');
        }
        
        // Delete folder recursively
        $this->deleteDirectory($folderPath);
        
        // Delete from database
        $this->templateModel->where('slug', $slug)->delete();
        
        return redirect()->to('/admin/template')->with('success', 'Template berhasil dihapus: ' . $slug);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($slug)
    {
        $template = $this->templateModel->where('slug', $slug)->first();
        
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template tidak ditemukan di database'
            ]);
        }
        
        $newStatus = $template['is_active'] == 1 ? 0 : 1;
        $this->templateModel->update($template['id'], [
            'is_active' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'is_active' => $newStatus,
            'message' => 'Status berhasil diubah'
        ]);
    }

    // ============= HELPER METHODS =============

    /**
     * Scan folder templates
     */
    private function scanTemplatesFolders()
    {
        $folders = [];
        
        if (!is_dir($this->templatesPath)) {
            return $folders;
        }
        
        $items = scandir($this->templatesPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $itemPath = $this->templatesPath . $item;
            
            if (is_dir($itemPath)) {
                $files = $this->scanFolderContents($itemPath);
                $hasIndex = file_exists($itemPath . DIRECTORY_SEPARATOR . 'index.php');
                
                $folders[] = [
                    'name' => $item,
                    'path' => $itemPath,
                    'files' => $files,
                    'has_index' => $hasIndex,
                ];
            }
        }
        
        return $folders;
    }

    /**
     * Scan folder contents
     */
    private function scanFolderContents($folderPath)
    {
        $files = [];
        
        if (!is_dir($folderPath)) {
            return $files;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            $relativePath = str_replace($folderPath, '', $file->getPathname());
            $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
            
            $files[] = [
                'name' => $file->getFilename(),
                'path' => $relativePath,
                'full_path' => $file->getPathname(),
                'is_dir' => $file->isDir(),
                'extension' => $file->isFile() ? $file->getExtension() : '',
                'size' => $file->isFile() ? $file->getSize() : 0,
            ];
        }
        
        return $files;
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

}


