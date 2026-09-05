<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvitationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Invitation extends BaseController
{
    protected $invitationModel;

    public function __construct()
    {
        $this->invitationModel = new InvitationModel();
    }

    public function index()
    {
        try {
            $search = $this->request->getGet('search');
            $status = $this->request->getGet('status');
            $sort = $this->request->getGet('sort') ?? 'id';
            $order = $this->request->getGet('order') ?? 'desc';
            $page = (int)($this->request->getGet('page') ?? 1);

            $builder = $this->invitationModel;

            if ($search) {
                $builder->groupStart()
                    ->like('title', $search)
                    ->orLike('slug', $search)
                    ->groupEnd();
            }

            if ($status && in_array($status, ['published', 'draft'])) {
                $builder->where('status', $status);
            }

            $allowedSorts = ['id', 'title', 'slug', 'status', 'views_count', 'created_at'];
            if (in_array($sort, $allowedSorts)) {
                $builder->orderBy($sort, $order);
            }

            $invitations = $builder->paginate(15, 'default', $page);
            $pager = $this->invitationModel->pager;

            // Get template preview images
            $templateModel = new \App\Models\TemplateModel();
            $templates = [];
            foreach ($invitations as $inv) {
                if (!empty($inv['template_id'])) {
                    if (!isset($templates[$inv['template_id']])) {
                        $template = $templateModel->find($inv['template_id']);
                        if ($template) {
                            $templates[$inv['template_id']] = $template;
                        }
                    }
                }
            }

            $data = [
                'invitations' => $invitations,
                'pager' => $pager,
                'search' => $search,
                'status' => $status,
                'sort' => $sort,
                'order' => $order,
                'templates' => $templates,
            ];

            return view('admin/invitation/index', $data);
        } catch (\Exception $e) {
            // Log error dengan detail lengkap
            $errorMessage = 'Error in index(): ' . $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            $errorFile = $e->getFile();
            $errorLine = $e->getLine();
            
            // Log ke file
            log_message('error', $errorMessage);
            log_message('error', 'File: ' . $errorFile . ' Line: ' . $errorLine);
            log_message('error', 'Stack trace: ' . $errorTrace);
            
            // Output ke console/terminal (akan muncul di browser console atau terminal)
            $consoleOutput = "
==========================================
ERROR in Invitation::index()
Message: " . $e->getMessage() . "
File: " . $errorFile . "
Line: " . $errorLine . "
Stack trace:
" . $errorTrace . "
==========================================";
            
            error_log($consoleOutput);
            
            // Output ke terminal jika CLI
            if (is_cli()) {
                echo $consoleOutput . "\n\n";
            }
            
            // Re-throw exception agar CodeIgniter error handler menampilkannya dengan detail
            throw $e;
        }
    }

    public function dashboard()
    {
        $data = [
            'total_invitations' => $this->invitationModel->getTotalInvitations(),
            'total_views' => $this->invitationModel->getTotalViews(),
            'published_count' => $this->invitationModel->getPublishedCount(),
            'draft_count' => $this->invitationModel->getDraftCount(),
        ];

        return view('admin/dashboard', $data);
    }

    public function create()
    {
        $templateModel = new \App\Models\TemplateModel();
        $templates = $templateModel->getActiveTemplates();
        return view('admin/invitation/form', [
            'invitation' => null,
            'templates' => $templates ?: []
        ]);
    }

    public function store()
    {
        try {
            $rules = [
                'title' => 'required|min_length[3]|max_length[255]',
                'slug' => 'required|min_length[3]|max_length[255]|is_unique[invitations.slug]',
                'template_id' => 'required|integer',
                'status' => 'in_list[published,draft]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Handle file uploads for images
            $imageFields = ['cover_image_file', 'groom_image_file', 'bride_image_file', 'dress_code_image_file', 'og_image_file'];
            $imageMapping = [
                'cover_image_file' => 'cover_image',
                'groom_image_file' => 'groom_image',
                'bride_image_file' => 'bride_image',
                'dress_code_image_file' => 'dress_code_image',
                'og_image_file' => 'og_image'
            ];
            
            foreach ($imageFields as $fileField) {
                $file = $this->request->getFile($fileField);
                if ($file && $file->isValid()) {
                    $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $newName = $file->getRandomName();
                    if ($file->move($uploadPath, $newName)) {
                        $_POST[$imageMapping[$fileField]] = base_url('assets/images/templates/' . $newName);
                    }
                }
            }
            
            // Handle gallery_images file uploads
            $galleryImages = [];
            $galleryFiles = $this->request->getFiles();
            if (!empty($galleryFiles['gallery_images_files'])) {
                $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($galleryFiles['gallery_images_files'] as $file) {
                    if ($file && $file->isValid()) {
                        $newName = $file->getRandomName();
                        if ($file->move($uploadPath, $newName)) {
                            $galleryImages[] = base_url('assets/images/templates/' . $newName);
                        }
                    }
                }
            }
            
            // Also check for existing gallery_images from textarea
            if (empty($galleryImages) && $this->request->getPost('gallery_images')) {
                $galleryText = $this->request->getPost('gallery_images');
                $galleryImages = array_filter(array_map('trim', explode("\n", $galleryText)));
            }

            $contentData = $this->buildContentData($galleryImages);
            
            $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'content_data' => json_encode($contentData),
            'content_json' => json_encode([]),
            'theme_config' => '{}',
            'status' => $this->request->getPost('status') ?? 'draft',
            'cover_image' => $this->request->getPost('cover_image'),
            'groom_name' => $this->request->getPost('groom_name'),
            'bride_name' => $this->request->getPost('bride_name'),
            'groom_parents' => $this->request->getPost('groom_parents'),
            'bride_parents' => $this->request->getPost('bride_parents'),
            'groom_image' => $this->request->getPost('groom_image'),
            'bride_image' => $this->request->getPost('bride_image'),
            'groom_instagram' => $this->request->getPost('groom_instagram'),
            'bride_instagram' => $this->request->getPost('bride_instagram'),
            'wedding_date' => $this->request->getPost('wedding_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('wedding_date'))) : null,
            'reception_date' => $this->request->getPost('reception_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('reception_date'))) : null,
            'reception_end_time' => $this->request->getPost('reception_end_time'),
            'countdown_date' => $this->request->getPost('countdown_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('countdown_date'))) : null,
            'wedding_location' => $this->request->getPost('wedding_location'),
            'wedding_address' => $this->request->getPost('wedding_address'),
            'location_map_url' => $this->request->getPost('location_map_url'),
            'location_map_search' => $this->request->getPost('location_map_search'),
            'calendar_url' => $this->request->getPost('calendar_url'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_whatsapp' => $this->request->getPost('contact_whatsapp'),
            'music_url' => $this->request->getPost('music_url'),
            'video_url' => $this->request->getPost('video_url'),
            'video_id' => $this->request->getPost('video_id'),
            'livestream_url' => $this->request->getPost('livestream_url'),
            'livestream_id' => $this->request->getPost('livestream_id'),
            'livestream_schedule' => $this->request->getPost('livestream_schedule'),
            'livestream_description' => $this->request->getPost('livestream_description'),
            'livestream_enabled' => (int)($this->request->getPost('livestream_enabled') ?? 0),
            'bank_name' => $this->request->getPost('bank_name'),
            'bank_account_number' => $this->request->getPost('bank_account_number'),
            'bank_account_name' => $this->request->getPost('bank_account_name'),
            'donation_enabled' => (int)($this->request->getPost('donation_enabled') ?? 0),
            'gallery_images' => json_encode($galleryImages),
            'story_text' => $this->request->getPost('story_text'),
            'cover_message' => $this->request->getPost('cover_message'),
            'couple_description' => $this->request->getPost('couple_description'),
            'venue_message' => $this->request->getPost('venue_message'),
            'apology_text' => $this->request->getPost('apology_text'),
            'thank_you_text' => $this->request->getPost('thank_you_text'),
            'event_name_1' => $this->request->getPost('event_name_1'),
            'event_name_2' => $this->request->getPost('event_name_2'),
            'event_date_1' => $this->request->getPost('event_date_1') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('event_date_1'))) : null,
            'event_date_2' => $this->request->getPost('event_date_2') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('event_date_2'))) : null,
            'event_time_1' => $this->request->getPost('event_time_1'),
            'event_time_2' => $this->request->getPost('event_time_2'),
            'dress_code' => $this->request->getPost('dress_code'),
            'dress_code_image' => $this->request->getPost('dress_code_image'),
            'is_featured' => (int)($this->request->getPost('is_featured') ?? 0),
            'tags' => $this->request->getPost('tags'),
            'category' => $this->request->getPost('category'),
            'meta_description' => $this->request->getPost('meta_description'),
            'meta_keywords' => $this->request->getPost('meta_keywords'),
            'og_image' => $this->request->getPost('og_image'),
            'custom_css' => $this->request->getPost('custom_css'),
            'custom_js' => $this->request->getPost('custom_js'),
            'published_at' => $this->request->getPost('published_at') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('published_at'))) : null,
            'expires_at' => $this->request->getPost('expires_at') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('expires_at'))) : null,
            'password' => $this->request->getPost('password'),
            'max_views' => (int)($this->request->getPost('max_views') ?? 0),
            'rsvp_enabled' => (int)($this->request->getPost('rsvp_enabled') ?? 0),
            'analytics_enabled' => (int)($this->request->getPost('analytics_enabled') ?? 0),
            'social_sharing_enabled' => (int)($this->request->getPost('social_sharing_enabled') ?? 0),
            'print_enabled' => (int)($this->request->getPost('print_enabled') ?? 0),
            'qr_code_enabled' => (int)($this->request->getPost('qr_code_enabled') ?? 0),
            'guestbook_enabled' => (int)($this->request->getPost('guestbook_enabled') ?? 0),
            'template_id' => (int)$this->request->getPost('template_id'),
            'language' => $this->request->getPost('language') ?? 'id',
            'timezone' => $this->request->getPost('timezone') ?? 'Asia/Jakarta',
            'views_count' => 0,
            'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($invitationId = $this->invitationModel->insert($data)) {
                // Handle Our Story
                $this->saveOurStory($invitationId);
                
                // Auto-generate QR code jika check-in card enabled
                if (!empty($data['check_in_card_enabled']) && $data['check_in_card_enabled'] == 1) {
                    $this->generateCheckInQrCode($invitationId);
                }
                
                return redirect()->to('/admin/invitation')->with('success', 'Undangan berhasil dibuat');
            }

            $error = $this->invitationModel->errors();
            log_message('error', 'Failed to insert invitation: ' . json_encode($error));
            return redirect()->back()->withInput()->with('error', 'Gagal membuat undangan: ' . implode(', ', $error));
        } catch (\Exception $e) {
            log_message('error', 'Exception in store(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return redirect()->to('/admin/invitation')->with('error', 'Undangan tidak ditemukan');
        }

        $templateModel = new \App\Models\TemplateModel();
        $templates = $templateModel->getActiveTemplates();
        
        $ourStoryModel = new \App\Models\OurStoryModel();
        $ourStories = $ourStoryModel->getByInvitationId($id);
        
        return view('admin/invitation/form', [
            'invitation' => $invitation,
            'templates' => $templates ?: [],
            'ourStories' => $ourStories,
        ]);
    }

    public function update($id)
    {
        try {
            $invitation = $this->invitationModel->find($id);
            if (!$invitation) {
                return redirect()->to('/admin/invitation')->with('error', 'Undangan tidak ditemukan');
            }

            $rules = [
                'title' => 'required|min_length[3]|max_length[255]',
                'slug' => "required|min_length[3]|max_length[255]|is_unique[invitations.slug,id,{$id}]",
                'template_id' => 'required|integer',
                'status' => 'in_list[published,draft]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

        // Handle file uploads for images
        $imageFields = ['cover_image_file', 'groom_image_file', 'bride_image_file', 'dress_code_image_file', 'og_image_file'];
        $imageMapping = [
            'cover_image_file' => 'cover_image',
            'groom_image_file' => 'groom_image',
            'bride_image_file' => 'bride_image',
            'dress_code_image_file' => 'dress_code_image',
            'og_image_file' => 'og_image'
        ];
        
        foreach ($imageFields as $fileField) {
            $file = $this->request->getFile($fileField);
            if ($file && $file->isValid()) {
                $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    $_POST[$imageMapping[$fileField]] = base_url('assets/images/templates/' . $newName);
                }
            }
        }
        
        // Handle gallery_images - preserve existing dan tambahkan yang baru
        // Ambil existing gallery images terlebih dahulu
        $existingGallery = [];
        if (!empty($invitation['gallery_images'])) {
            if (is_array($invitation['gallery_images'])) {
                $existingGallery = $invitation['gallery_images'];
            } else {
                $decoded = json_decode($invitation['gallery_images'], true);
                if (is_array($decoded) && !empty($decoded)) {
                    $existingGallery = $decoded;
                } elseif (is_string($invitation['gallery_images']) && !empty(trim($invitation['gallery_images']))) {
                    // Jika string biasa (bukan JSON), split dengan newline atau koma
                    $existingGallery = array_filter(array_map('trim', preg_split('/[\n\r,]+/', $invitation['gallery_images'])));
                }
            }
        }
        
        // Cek apakah ada data dari hidden input (dari form JavaScript)
        // Hidden input ini berisi semua URL gambar yang ada di preview (existing + yang baru diupload)
        // Ini adalah source of truth - jika ada, gunakan ini (bisa jadi existing yang sudah diupdate atau kosong jika user hapus semua)
        $galleryFromForm = [];
        $galleryText = $this->request->getPost('gallery_images');
        if (!empty($galleryText) && trim($galleryText) !== '') {
            $galleryFromForm = array_filter(array_map(function($url) {
                $decoded = trim($url);
                // Decode URL jika ter-encode
                if (strpos($decoded, '%') !== false || strpos($decoded, '&amp;') !== false) {
                    $decoded = html_entity_decode(urldecode($decoded), ENT_QUOTES, 'UTF-8');
                }
                // Jika masih ter-encode dengan format http/x3A/x2F, decode lagi
                if (strpos($decoded, 'http/x3A') !== false || strpos($decoded, 'x2F') !== false) {
                    $decoded = rawurldecode(str_replace(['x3A', 'x2F'], [':', '/'], $decoded));
                }
                return $decoded;
            }, explode("\n", trim($galleryText))));
        }
        
        // Prioritas: 
        // 1. Jika ada data dari form (hidden input), gunakan itu (bisa existing yang diupdate atau kosong)
        // 2. Jika form kosong/tidak ada tapi ada existing, tetap preserve existing
        // 3. Jika tidak ada keduanya, array kosong
        if ($this->request->getPost('gallery_images') !== null) {
            // Form dikirim (bisa kosong jika user hapus semua)
            $galleryImages = $galleryFromForm;
        } else {
            // Form tidak dikirim, preserve existing
            $galleryImages = $existingGallery;
        }
        
        // Handle file uploads baru (tambahkan ke gallery yang sudah ada)
        $galleryFiles = $this->request->getFiles();
        if (!empty($galleryFiles['gallery_images_files'])) {
            $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($galleryFiles['gallery_images_files'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    if ($file->move($uploadPath, $newName)) {
                        $newImageUrl = base_url('assets/images/templates/' . $newName);
                        // Tambahkan ke gallery jika belum ada
                        if (!in_array($newImageUrl, $galleryImages)) {
                            $galleryImages[] = $newImageUrl;
                        }
                    }
                }
            }
        }
        
        // Pastikan tidak ada duplikat dan filter yang kosong
        $galleryImages = array_values(array_unique(array_filter($galleryImages)));

        $contentData = $this->buildContentData($galleryImages);

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'content_data' => json_encode($contentData),
            'content_json' => $invitation['content_json'] ?? json_encode([]),
            'theme_config' => $invitation['theme_config'] ?? '{}',
            'status' => $this->request->getPost('status') ?? 'draft',
            'cover_image' => $this->request->getPost('cover_image'),
            'groom_name' => $this->request->getPost('groom_name'),
            'bride_name' => $this->request->getPost('bride_name'),
            'groom_parents' => $this->request->getPost('groom_parents'),
            'bride_parents' => $this->request->getPost('bride_parents'),
            'groom_image' => $this->request->getPost('groom_image'),
            'bride_image' => $this->request->getPost('bride_image'),
            'groom_instagram' => $this->request->getPost('groom_instagram'),
            'bride_instagram' => $this->request->getPost('bride_instagram'),
            'wedding_date' => $this->request->getPost('wedding_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('wedding_date'))) : null,
            'reception_date' => $this->request->getPost('reception_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('reception_date'))) : null,
            'reception_end_time' => $this->request->getPost('reception_end_time'),
            'countdown_date' => $this->request->getPost('countdown_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('countdown_date'))) : null,
            'wedding_location' => $this->request->getPost('wedding_location'),
            'wedding_address' => $this->request->getPost('wedding_address'),
            'location_map_url' => $this->request->getPost('location_map_url'),
            'location_map_search' => $this->request->getPost('location_map_search'),
            'calendar_url' => $this->request->getPost('calendar_url'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_whatsapp' => $this->request->getPost('contact_whatsapp'),
            'music_url' => $this->request->getPost('music_url'),
            'video_url' => $this->request->getPost('video_url'),
            'video_id' => $this->request->getPost('video_id'),
            'livestream_url' => $this->request->getPost('livestream_url'),
            'livestream_id' => $this->request->getPost('livestream_id'),
            'livestream_schedule' => $this->request->getPost('livestream_schedule'),
            'livestream_description' => $this->request->getPost('livestream_description'),
            'livestream_enabled' => (int)($this->request->getPost('livestream_enabled') ?? 0),
            'bank_name' => $this->request->getPost('bank_name'),
            'bank_account_number' => $this->request->getPost('bank_account_number'),
            'bank_account_name' => $this->request->getPost('bank_account_name'),
            'donation_enabled' => (int)($this->request->getPost('donation_enabled') ?? 0),
            'gallery_images' => json_encode($galleryImages),
            'story_text' => $this->request->getPost('story_text'),
            'cover_message' => $this->request->getPost('cover_message'),
            'couple_description' => $this->request->getPost('couple_description'),
            'venue_message' => $this->request->getPost('venue_message'),
            'apology_text' => $this->request->getPost('apology_text'),
            'thank_you_text' => $this->request->getPost('thank_you_text'),
            'event_name_1' => $this->request->getPost('event_name_1'),
            'event_name_2' => $this->request->getPost('event_name_2'),
            'event_date_1' => $this->request->getPost('event_date_1') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('event_date_1'))) : null,
            'event_date_2' => $this->request->getPost('event_date_2') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('event_date_2'))) : null,
            'event_time_1' => $this->request->getPost('event_time_1'),
            'event_time_2' => $this->request->getPost('event_time_2'),
            'dress_code' => $this->request->getPost('dress_code'),
            'dress_code_image' => $this->request->getPost('dress_code_image'),
            'is_featured' => (int)($this->request->getPost('is_featured') ?? 0),
            'tags' => $this->request->getPost('tags'),
            'category' => $this->request->getPost('category'),
            'meta_description' => $this->request->getPost('meta_description'),
            'meta_keywords' => $this->request->getPost('meta_keywords'),
            'og_image' => $this->request->getPost('og_image'),
            'custom_css' => $this->request->getPost('custom_css'),
            'custom_js' => $this->request->getPost('custom_js'),
            'published_at' => $this->request->getPost('published_at') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('published_at'))) : null,
            'expires_at' => $this->request->getPost('expires_at') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('expires_at'))) : null,
            'password' => $this->request->getPost('password'),
            'max_views' => (int)($this->request->getPost('max_views') ?? 0),
            'rsvp_enabled' => (int)($this->request->getPost('rsvp_enabled') ?? 0),
            'analytics_enabled' => (int)($this->request->getPost('analytics_enabled') ?? 0),
            'social_sharing_enabled' => (int)($this->request->getPost('social_sharing_enabled') ?? 0),
            'print_enabled' => (int)($this->request->getPost('print_enabled') ?? 0),
            'qr_code_enabled' => (int)($this->request->getPost('qr_code_enabled') ?? 0),
            'guestbook_enabled' => (int)($this->request->getPost('guestbook_enabled') ?? 0),
            'check_in_card_enabled' => (int)($this->request->getPost('check_in_card_enabled') ?? 0),
            'check_in_card_instructions' => $this->request->getPost('check_in_card_instructions'),
            'template_id' => (int)$this->request->getPost('template_id'),
            'language' => $this->request->getPost('language') ?? 'id',
            'timezone' => $this->request->getPost('timezone') ?? 'Asia/Jakarta',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

            if ($this->invitationModel->update($id, $data)) {
                // Handle Our Story
                $this->saveOurStory($id);
                
                // Auto-generate QR code jika check-in card enabled dan belum ada QR code
                if (!empty($data['check_in_card_enabled']) && $data['check_in_card_enabled'] == 1) {
                    $invitation = $this->invitationModel->find($id);
                    if (empty($invitation['check_in_qr_code'])) {
                        $this->generateCheckInQrCode($id);
                    }
                }
                
                return redirect()->to('/admin/invitation')->with('success', 'Undangan berhasil diperbarui');
            }

            $error = $this->invitationModel->errors();
            log_message('error', 'Failed to update invitation: ' . json_encode($error));
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui undangan: ' . implode(', ', $error));
        } catch (\Exception $e) {
            log_message('error', 'Exception in update(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return redirect()->to('/admin/invitation')->with('error', 'Undangan tidak ditemukan');
        }

        if ($this->invitationModel->delete($id)) {
            return redirect()->to('/admin/invitation')->with('success', 'Undangan berhasil dihapus');
        }

        return redirect()->to('/admin/invitation')->with('error', 'Gagal menghapus undangan');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        $deleted = 0;
        foreach ($ids as $id) {
            if ($this->invitationModel->delete($id)) {
                $deleted++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Berhasil menghapus {$deleted} undangan"
        ]);
    }

    public function duplicate($id)
    {
        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return redirect()->to('/admin/invitation')->with('error', 'Undangan tidak ditemukan');
        }

        $newData = [
            'title' => $invitation['title'] . ' (Copy)',
            'slug' => $invitation['slug'] . '-copy-' . time(),
            'content_json' => $invitation['content_json'],
            'theme_config' => $invitation['theme_config'],
            'status' => 'draft',
            'views_count' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->invitationModel->insert($newData)) {
            return redirect()->to('/admin/invitation')->with('success', 'Undangan berhasil diduplikasi');
        }

        return redirect()->to('/admin/invitation')->with('error', 'Gagal menduplikasi undangan');
    }

    public function toggleStatus($id)
    {
        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Undangan tidak ditemukan']);
        }

        $newStatus = $invitation['status'] === 'published' ? 'draft' : 'published';
        $this->invitationModel->update($id, [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Status berhasil diubah'
        ]);
    }

    public function resetViews($id)
    {
        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return redirect()->to('/admin/invitation')->with('error', 'Undangan tidak ditemukan');
        }

        $this->invitationModel->update($id, [
            'views_count' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/invitation')->with('success', 'View count berhasil direset');
    }


    public function guide()
    {
        return view('admin/invitation/guide');
    }

    public function previewRealtime($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Only AJAX requests allowed'
            ]);
        }

        // Jika ada ID, ambil dari database
        if ($id) {
            $invitation = $this->invitationModel->find($id);
            if (!$invitation) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Undangan tidak ditemukan'
                ]);
            }

            $contentData = json_decode($this->request->getPost('content_data') ?? $invitation['content_data'] ?? '{}', true);
            
            $tempInvitation = $invitation;
            $tempInvitation['content_data'] = json_encode($contentData);
            
            $previewController = new \App\Controllers\Preview();
            $reflection = new \ReflectionClass($previewController);
            $method = $reflection->getMethod('renderTemplate');
            $method->setAccessible(true);
            
            try {
                $html = $method->invoke($previewController, $tempInvitation);
                return $this->response->setJSON([
                    'status' => 'success',
                    'html' => $html->getBody()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        // Ambil data dari POST
        $data = $this->request->getPost();
        
        // Handle gallery_images
        $galleryImages = [];
        if (!empty($data['gallery_images'])) {
            if (is_array($data['gallery_images'])) {
                $galleryImages = $data['gallery_images'];
            } elseif (is_string($data['gallery_images'])) {
                $galleryImages = array_filter(array_map('trim', explode("\n", $data['gallery_images'])));
            }
        }
        
        // Buat array invitation dari data form
        $invitation = [
            'title' => $data['title'] ?? 'Preview Undangan',
            'slug' => $data['slug'] ?? 'preview',
            'groom_name' => $data['groom_name'] ?? '',
            'bride_name' => $data['bride_name'] ?? '',
            'groom_parents' => $data['groom_parents'] ?? '',
            'bride_parents' => $data['bride_parents'] ?? '',
            'groom_image' => $data['groom_image'] ?? '',
            'bride_image' => $data['bride_image'] ?? '',
            'groom_instagram' => $data['groom_instagram'] ?? '',
            'bride_instagram' => $data['bride_instagram'] ?? '',
            'wedding_date' => $data['wedding_date'] ?? date('Y-m-d H:i:s'),
            'reception_date' => $data['reception_date'] ?? '',
            'reception_end_time' => $data['reception_end_time'] ?? '',
            'countdown_date' => $data['countdown_date'] ?? '',
            'wedding_location' => $data['wedding_location'] ?? '',
            'wedding_address' => $data['wedding_address'] ?? '',
            'location_map_url' => $data['location_map_url'] ?? '',
            'location_map_search' => $data['location_map_search'] ?? '',
            'calendar_url' => $data['calendar_url'] ?? '',
            'contact_phone' => $data['contact_phone'] ?? '',
            'contact_email' => $data['contact_email'] ?? '',
            'contact_whatsapp' => $data['contact_whatsapp'] ?? '',
            'music_url' => $data['music_url'] ?? '',
            'video_url' => $data['video_url'] ?? '',
            'video_id' => $data['video_id'] ?? '',
            'livestream_url' => $data['livestream_url'] ?? '',
            'livestream_id' => $data['livestream_id'] ?? '',
            'livestream_schedule' => $data['livestream_schedule'] ?? '',
            'livestream_description' => $data['livestream_description'] ?? '',
            'gallery_images' => $galleryImages,
            'story_text' => $data['story_text'] ?? '',
            'cover_message' => $data['cover_message'] ?? '',
            'couple_description' => $data['couple_description'] ?? '',
            'venue_message' => $data['venue_message'] ?? '',
            'apology_text' => $data['apology_text'] ?? '',
            'thank_you_text' => $data['thank_you_text'] ?? '',
            'event_name_1' => $data['event_name_1'] ?? '',
            'event_name_2' => $data['event_name_2'] ?? '',
            'event_date_1' => $data['event_date_1'] ?? '',
            'event_date_2' => $data['event_date_2'] ?? '',
            'event_time_1' => $data['event_time_1'] ?? '',
            'event_time_2' => $data['event_time_2'] ?? '',
            'dress_code' => $data['dress_code'] ?? '',
            'dress_code_image' => $data['dress_code_image'] ?? '',
            'cover_image' => $data['cover_image'] ?? '',
            'og_image' => $data['og_image'] ?? '',
            'template_id' => !empty($data['template_id']) ? (int)$data['template_id'] : null,
            'content_json' => $data['content_json'] ?? '[]',
            'theme_config' => $data['theme_config'] ?? '{}',
            'custom_css' => $data['custom_css'] ?? '',
            'custom_js' => $data['custom_js'] ?? '',
            'language' => $data['language'] ?? 'id',
            'lang' => $data['language'] ?? 'id', // Alias untuk template
            'meta_description' => $data['meta_description'] ?? '',
            'invitation_url' => base_url($data['slug'] ?? 'preview'),
        ];

        // Jika ada template_id, render menggunakan template
        if (!empty($invitation['template_id']) && $invitation['template_id'] > 0) {
            $templateModel = new \App\Models\TemplateModel();
            $template = $templateModel->find($invitation['template_id']);
            
            if ($template) {
                // Cek template_path dari database
                $templatePath = $template['template_path'] ?? '';
                
                // Jika template_path kosong, coba cari berdasarkan slug di templates_undangan
                if (empty($templatePath) || !is_dir($templatePath)) {
                    $templateSlug = $template['slug'] ?? '';
                    $fallbackPath = FCPATH . 'templates_undangan' . DIRECTORY_SEPARATOR . $templateSlug . DIRECTORY_SEPARATOR;
                    
                    if (is_dir($fallbackPath)) {
                        $templatePath = $fallbackPath;
                    } else {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Template path tidak ditemukan. Template ID: ' . $invitation['template_id'] . ', Slug: ' . $templateSlug
                        ]);
                    }
                }
                
                // Pastikan template_path berakhir dengan DIRECTORY_SEPARATOR
                if (substr($templatePath, -1) !== DIRECTORY_SEPARATOR) {
                    $templatePath .= DIRECTORY_SEPARATOR;
                }
                
                $indexFile = $templatePath . 'index.php';
                
                if (file_exists($indexFile)) {
                    ob_start();
                    $templateContent = file_get_contents($indexFile);
                    
                    if (!preg_match('/function\s+esc\s*\(/i', $templateContent)) {
                        // Set invitation variable untuk template
                        include $indexFile;
                        $html = ob_get_clean();
                        
                        return $this->response->setJSON([
                            'status' => 'success',
                            'html' => $html
                        ]);
                    } else {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'Template mengandung function esc() yang tidak diizinkan'
                        ]);
                    }
                } else {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'File index.php tidak ditemukan: ' . $indexFile
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Template tidak ditemukan dengan ID: ' . $invitation['template_id']
                ]);
            }
        }

        // Jika tidak ada template, gunakan view default
        $contentJson = json_decode($invitation['content_json'], true);
        $themeConfig = json_decode($invitation['theme_config'] ?? '{}', true);

        $viewData = [
            'invitation' => $invitation,
            'content' => $contentJson ?? [],
            'theme' => $themeConfig,
        ];

        $html = view('preview/index', $viewData);
        
        return $this->response->setJSON([
            'status' => 'success',
            'html' => $html
        ]);
    }

    private function isValidJson($string)
    {
        if (empty($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function saveOurStory($invitationId)
    {
        $ourStoryModel = new \App\Models\OurStoryModel();
        $ourStories = $this->request->getPost('our_story');
        
        if (empty($ourStories) || !is_array($ourStories)) {
            // Hapus semua our story jika tidak ada data
            $ourStoryModel->deleteByInvitationId($invitationId);
            return;
        }
        
        // Get existing stories untuk preserve images
        $existingStories = [];
        if (!empty($invitationId)) {
            $existingStories = $ourStoryModel->getByInvitationId($invitationId);
        }
        
        // Handle file uploads untuk story images
        $files = $this->request->getFiles();
        foreach ($ourStories as $index => $story) {
            // Cek file upload dengan struktur array
            $file = null;
            if (isset($files['our_story'][$index]['story_image_file'])) {
                $file = $files['our_story'][$index]['story_image_file'];
            }
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    $ourStories[$index]['story_image'] = base_url('assets/images/templates/' . $newName);
                }
            } elseif (empty($ourStories[$index]['story_image']) && !empty($story['story_image'])) {
                // Keep existing image from form
                $ourStories[$index]['story_image'] = $story['story_image'];
            } elseif (!empty($story['id'])) {
                // Keep existing image from database
                foreach ($existingStories as $existing) {
                    if ($existing['id'] == $story['id'] && !empty($existing['story_image'])) {
                        $ourStories[$index]['story_image'] = $existing['story_image'];
                        break;
                    }
                }
            }
        }
        
        // Hapus existing stories yang tidak ada di form
        $storyIdsToKeep = [];
        foreach ($ourStories as $story) {
            if (!empty($story['id'])) {
                $storyIdsToKeep[] = $story['id'];
            }
        }
        
        // Hapus stories yang tidak ada di form
        foreach ($existingStories as $existing) {
            if (!in_array($existing['id'], $storyIdsToKeep)) {
                $ourStoryModel->delete($existing['id']);
            }
        }
        
        // Insert/Update stories
        foreach ($ourStories as $story) {
            if (empty($story['year']) && empty($story['title']) && empty($story['story_text'])) {
                continue; // Skip empty stories
            }
            
            $storyData = [
                'invitation_id' => $invitationId,
                'year' => $story['year'] ?? null,
                'title' => $story['title'] ?? null,
                'story_text' => $story['story_text'] ?? null,
                'story_image' => $story['story_image'] ?? null,
                'display_order' => (int)($story['display_order'] ?? 0),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            // Update jika ada ID
            if (!empty($story['id'])) {
                $existingStory = $ourStoryModel->find($story['id']);
                if ($existingStory && $existingStory['invitation_id'] == $invitationId) {
                    $ourStoryModel->update($story['id'], $storyData);
                    continue;
                }
            }
            
            // Insert new
            $storyData['created_at'] = date('Y-m-d H:i:s');
            $ourStoryModel->insert($storyData);
        }
    }

    public function generateCheckInCard()
    {
        // Set response type to JSON
        $this->response->setContentType('application/json');
        
        // Get data from JSON or POST
        $invitationId = null;
        $guestName = 'Tamu Undangan';
        
        // Try to get from JSON first
        $json = $this->request->getJSON(true);
        if ($json && !empty($json)) {
            $invitationId = $json['invitation_id'] ?? null;
            $guestName = $json['guest_name'] ?? 'Tamu Undangan';
        } else {
            // Fallback to POST data
            $invitationId = $this->request->getPost('invitation_id');
            $guestName = $this->request->getPost('guest_name') ?? 'Tamu Undangan';
        }
        
        if (!$invitationId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invitation ID required'
            ]);
        }
        
        // Verify invitation exists
        $invitation = $this->invitationModel->find($invitationId);
        if (!$invitation) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Undangan tidak ditemukan'
            ]);
        }
        
        $checkInCardModel = new \App\Models\CheckInCardModel();
        
        try {
            $qrCode = $checkInCardModel->generateQrCode($invitationId, $guestName);
            
            // Generate QR code image
            $qrCodeImagePath = $this->generateQrCodeImage($qrCode, $invitationId);
            
            $cardData = [
                'invitation_id' => (int)$invitationId,
                'guest_name' => $guestName,
                'qr_code' => $qrCode,
                'qr_code_image' => $qrCodeImagePath,
                'checked_in' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            if ($checkInCardModel->insert($cardData)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'QR Code berhasil dibuat',
                    'data' => $cardData
                ]);
            } else {
                $errors = $checkInCardModel->errors();
                log_message('error', 'Failed to insert check-in card: ' . json_encode($errors));
                log_message('error', 'Card data: ' . json_encode($cardData));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan check-in card: ' . (is_array($errors) ? implode(', ', $errors) : 'Unknown error')
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error generating QR code: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal generate QR code: ' . $e->getMessage()
            ]);
        }
    }

    private function generateQrCodeImage($qrCode, $invitationId)
    {
        // Buat folder qrcodes jika belum ada
        $qrImageDir = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'qrcodes';
        if (!is_dir($qrImageDir)) {
            mkdir($qrImageDir, 0755, true);
        }
        
        // Generate filename
        $filename = $invitationId . '_' . md5($qrCode) . '.png';
        $qrImagePath = 'assets/qrcodes/' . $filename;
        $qrImageFullPath = FCPATH . $qrImagePath;
        
        // Gunakan library endroid/qr-code (versi 6.x)
        try {
            if (class_exists('\Endroid\QrCode\Builder\Builder')) {
                // Versi 6.x menggunakan Builder pattern
                $builder = new \Endroid\QrCode\Builder\Builder();
                $result = $builder->build(
                    writer: new \Endroid\QrCode\Writer\PngWriter(),
                    data: $qrCode,
                    size: 300,
                    margin: 10
                );
                
                file_put_contents($qrImageFullPath, $result->getString());
                
                return base_url($qrImagePath);
            } elseif (class_exists('\Endroid\QrCode\QrCode')) {
                // Versi 5.x atau lebih lama
                $qrCodeObj = new \Endroid\QrCode\QrCode($qrCode);
                $qrCodeObj->setSize(300);
                $qrCodeObj->setMargin(10);
                
                $writer = new \Endroid\QrCode\Writer\PngWriter();
                $result = $writer->write($qrCodeObj);
                $result->saveToFile($qrImageFullPath);
                
                return base_url($qrImagePath);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error using QR code library: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        }
        
        // Fallback: gunakan API online jika library tidak tersedia
        try {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrCode);
            
            // Use cURL if available, otherwise file_get_contents
            if (function_exists('curl_init')) {
                $ch = curl_init($qrUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $qrImageData = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode === 200 && $qrImageData) {
                    file_put_contents($qrImageFullPath, $qrImageData);
                    return base_url($qrImagePath);
                }
            } else {
                // Fallback to file_get_contents
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'follow_location' => true
                    ]
                ]);
                $qrImageData = @file_get_contents($qrUrl, false, $context);
                
                if ($qrImageData && strlen($qrImageData) > 100) { // Valid image should be > 100 bytes
                    file_put_contents($qrImageFullPath, $qrImageData);
                    return base_url($qrImagePath);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error generating QR code via API: ' . $e->getMessage());
        }
        
        // Jika semua gagal, return path yang akan dibuat nanti
        return base_url($qrImagePath);
    }

    private function generateCheckInQrCode($invitationId)
    {
        $invitation = $this->invitationModel->find($invitationId);
        if (!$invitation) {
            return false;
        }
        
        // Generate unique QR code untuk undangan ini
        $qrCode = 'CHECKIN-' . $invitationId . '-' . md5($invitation['slug'] . time());
        
        // Generate QR code image
        $qrCodeImagePath = $this->generateQrCodeImage($qrCode, $invitationId);
        
        // Update invitation dengan QR code
        $this->invitationModel->update($invitationId, [
            'check_in_qr_code' => $qrCode,
            'check_in_qr_code_image' => $qrCodeImagePath
        ]);
        
        return true;
    }

    public function getQrCodes($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Undangan tidak ditemukan'
            ]);
        }

        $checkInCardModel = new \App\Models\CheckInCardModel();
        $qrCodes = $checkInCardModel->getByInvitationId($id);

        return $this->response->setJSON([
            'status' => 'success',
            'qr_codes' => $qrCodes
        ]);
    }

    protected function buildContentData($galleryImages = [])
    {
        $weddingDate = $this->request->getPost('wedding_date');
        $receptionDate = $this->request->getPost('reception_date');
        $countdownDate = $this->request->getPost('countdown_date') ?: $weddingDate;
        
        $weddingDayName = '';
        $weddingDay = '';
        $weddingMonthName = '';
        $weddingYear = '';
        $weddingDateShort = '';
        
        if ($weddingDate) {
            $timestamp = strtotime($weddingDate);
            $weddingDayName = $this->getDayNameId(date('l', $timestamp));
            $weddingDay = date('d', $timestamp);
            $weddingMonthName = $this->getMonthNameId(date('F', $timestamp));
            $weddingYear = date('Y', $timestamp);
            $weddingDateShort = date('d', $timestamp) . ' ' . $this->getMonthNameId(date('F', $timestamp)) . ' ' . date('Y', $timestamp);
        }
        
        $receptionDayName = '';
        $receptionDay = '';
        $receptionMonthName = '';
        $receptionYear = '';
        
        if ($receptionDate) {
            $timestamp = strtotime($receptionDate);
            $receptionDayName = $this->getDayNameId(date('l', $timestamp));
            $receptionDay = date('d', $timestamp);
            $receptionMonthName = $this->getMonthNameId(date('F', $timestamp));
            $receptionYear = date('Y', $timestamp);
        }
        
        $countdownDateJs = '';
        if ($countdownDate) {
            $countdownDateJs = date('m/d/Y H:i:s', strtotime($countdownDate));
        }
        
        $groomParents = $this->request->getPost('groom_parents') ?? '';
        $groomFatherName = '';
        $groomMotherName = '';
        if ($groomParents) {
            $parts = preg_split('/\s*(?:&|dan)\s*/i', $groomParents);
            $groomFatherName = trim($parts[0] ?? '');
            $groomMotherName = trim($parts[1] ?? '');
        }
        
        $brideParents = $this->request->getPost('bride_parents') ?? '';
        $brideFatherName = '';
        $brideMotherName = '';
        if ($brideParents) {
            $parts = preg_split('/\s*(?:&|dan)\s*/i', $brideParents);
            $brideFatherName = trim($parts[0] ?? '');
            $brideMotherName = trim($parts[1] ?? '');
        }
        
        if (empty($galleryImages)) {
            $galleryText = $this->request->getPost('gallery_images');
            if ($galleryText) {
                $galleryImages = array_filter(array_map('trim', explode("\n", $galleryText)));
            }
        }
        
        return [
            'lang' => $this->request->getPost('language') ?? 'id',
            'title' => $this->request->getPost('title') ?? '',
            'meta_description' => $this->request->getPost('meta_description') ?? '',
            'groom_name' => $this->request->getPost('groom_name') ?? '',
            'bride_name' => $this->request->getPost('bride_name') ?? '',
            'groom_image' => $this->request->getPost('groom_image') ?? '',
            'bride_image' => $this->request->getPost('bride_image') ?? '',
            'cover_image' => $this->request->getPost('cover_image') ?? '',
            'dress_code_image' => $this->request->getPost('dress_code_image') ?? '',
            'music_url' => $this->request->getPost('music_url') ?? '',
            'wedding_location' => $this->request->getPost('wedding_location') ?? '',
            'event_name_1' => $this->request->getPost('event_name_1') ?? 'Akad Nikah',
            'event_name_2' => $this->request->getPost('event_name_2') ?? 'Resepsi',
            'event_time_1' => $this->request->getPost('event_time_1') ?? '',
            'event_time_2' => $this->request->getPost('event_time_2') ?? '',
            'wedding_day_name' => $weddingDayName,
            'wedding_day' => $weddingDay,
            'wedding_month_name' => $weddingMonthName,
            'wedding_year' => $weddingYear,
            'wedding_date_short' => $weddingDateShort,
            'reception_day_name' => $receptionDayName,
            'reception_day' => $receptionDay,
            'reception_month_name' => $receptionMonthName,
            'reception_year' => $receptionYear,
            'groom_father_name' => $groomFatherName,
            'groom_mother_name' => $groomMotherName,
            'bride_father_name' => $brideFatherName,
            'bride_mother_name' => $brideMotherName,
            'groom_instagram' => $this->request->getPost('groom_instagram') ?? '',
            'bride_instagram' => $this->request->getPost('bride_instagram') ?? '',
            'location_map_url' => $this->request->getPost('location_map_url') ?? '',
            'location_map_search' => $this->request->getPost('location_map_search') ?? '',
            'livestream_url' => $this->request->getPost('livestream_url') ?? '',
            'livestream_enabled' => !empty($this->request->getPost('livestream_url')),
            'video_id' => $this->request->getPost('video_id') ?? '',
            'bank_name' => $this->request->getPost('bank_name') ?? '',
            'bank_account_number' => $this->request->getPost('bank_account_number') ?? '',
            'bank_account_name' => $this->request->getPost('bank_account_name') ?? '',
            'check_in_qr_code_image' => $this->request->getPost('check_in_qr_code_image') ?? '',
            'calendar_url' => $this->request->getPost('calendar_url') ?? '',
            'countdown_date_js' => $countdownDateJs,
            'gallery_images' => $galleryImages,
            'og_image' => $this->request->getPost('og_image') ?? '',
        ];
    }

    protected function getDayNameId($dayName)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        return $days[$dayName] ?? 'Sabtu';
    }

    protected function getMonthNameId($monthName)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        return $months[$monthName] ?? 'Mei';
    }

    public function exportJson($id)
    {
        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return redirect()->to('/admin/invitation')->with('error', 'Undangan tidak ditemukan');
        }

        $contentData = json_decode($invitation['content_data'] ?? '{}', true);
        if (empty($contentData)) {
            $contentData = $this->buildContentDataFromInvitation($invitation);
        }

        $filename = 'invitation_' . ($invitation['slug'] ?? $id) . '_' . date('Y-m-d') . '.json';
        
        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(json_encode($contentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function importJson($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $invitation = $this->invitationModel->find($id);
        if (!$invitation) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Undangan tidak ditemukan'
            ]);
        }

        $file = $this->request->getFile('json_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File tidak valid'
            ]);
        }

        if ($file->getExtension() !== 'json') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File harus berformat JSON'
            ]);
        }

        $content = file_get_contents($file->getTempName());
        $contentData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'JSON tidak valid: ' . json_last_error_msg()
            ]);
        }

        $this->invitationModel->update($id, [
            'content_data' => json_encode($contentData),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'JSON berhasil diimport',
            'data' => $contentData
        ]);
    }


    protected function buildContentDataFromInvitation($invitation)
    {
        $weddingDate = $invitation['wedding_date'] ?? null;
        $receptionDate = $invitation['reception_date'] ?? null;
        $countdownDate = $invitation['countdown_date'] ?? $weddingDate;
        
        $weddingDayName = '';
        $weddingDay = '';
        $weddingMonthName = '';
        $weddingYear = '';
        $weddingDateShort = '';
        
        if ($weddingDate) {
            $timestamp = strtotime($weddingDate);
            $weddingDayName = $this->getDayNameId(date('l', $timestamp));
            $weddingDay = date('d', $timestamp);
            $weddingMonthName = $this->getMonthNameId(date('F', $timestamp));
            $weddingYear = date('Y', $timestamp);
            $weddingDateShort = date('d', $timestamp) . ' ' . $this->getMonthNameId(date('F', $timestamp)) . ' ' . date('Y', $timestamp);
        }
        
        $receptionDayName = '';
        $receptionDay = '';
        $receptionMonthName = '';
        $receptionYear = '';
        
        if ($receptionDate) {
            $timestamp = strtotime($receptionDate);
            $receptionDayName = $this->getDayNameId(date('l', $timestamp));
            $receptionDay = date('d', $timestamp);
            $receptionMonthName = $this->getMonthNameId(date('F', $timestamp));
            $receptionYear = date('Y', $timestamp);
        }
        
        $countdownDateJs = '';
        if ($countdownDate) {
            $countdownDateJs = date('m/d/Y H:i:s', strtotime($countdownDate));
        }
        
        $groomParents = $invitation['groom_parents'] ?? '';
        $groomFatherName = '';
        $groomMotherName = '';
        if ($groomParents) {
            $parts = preg_split('/\s*(?:&|dan)\s*/i', $groomParents);
            $groomFatherName = trim($parts[0] ?? '');
            $groomMotherName = trim($parts[1] ?? '');
        }
        
        $brideParents = $invitation['bride_parents'] ?? '';
        $brideFatherName = '';
        $brideMotherName = '';
        if ($brideParents) {
            $parts = preg_split('/\s*(?:&|dan)\s*/i', $brideParents);
            $brideFatherName = trim($parts[0] ?? '');
            $brideMotherName = trim($parts[1] ?? '');
        }
        
        $galleryImages = [];
        if (!empty($invitation['gallery_images'])) {
            if (is_array($invitation['gallery_images'])) {
                $galleryImages = $invitation['gallery_images'];
            } else {
                $decoded = json_decode($invitation['gallery_images'], true);
                if (is_array($decoded)) {
                    $galleryImages = $decoded;
                } else {
                    $galleryImages = array_filter(array_map('trim', explode("\n", $invitation['gallery_images'])));
                }
            }
        }
        
        return [
            'lang' => $invitation['language'] ?? 'id',
            'title' => $invitation['title'] ?? '',
            'meta_description' => $invitation['meta_description'] ?? '',
            'groom_name' => $invitation['groom_name'] ?? '',
            'bride_name' => $invitation['bride_name'] ?? '',
            'groom_image' => $invitation['groom_image'] ?? '',
            'bride_image' => $invitation['bride_image'] ?? '',
            'cover_image' => $invitation['cover_image'] ?? '',
            'dress_code_image' => $invitation['dress_code_image'] ?? '',
            'music_url' => $invitation['music_url'] ?? '',
            'wedding_location' => $invitation['wedding_location'] ?? '',
            'event_name_1' => $invitation['event_name_1'] ?? 'Akad Nikah',
            'event_name_2' => $invitation['event_name_2'] ?? 'Resepsi',
            'event_time_1' => $invitation['event_time_1'] ?? '',
            'event_time_2' => $invitation['event_time_2'] ?? '',
            'wedding_day_name' => $weddingDayName,
            'wedding_day' => $weddingDay,
            'wedding_month_name' => $weddingMonthName,
            'wedding_year' => $weddingYear,
            'wedding_date_short' => $weddingDateShort,
            'reception_day_name' => $receptionDayName,
            'reception_day' => $receptionDay,
            'reception_month_name' => $receptionMonthName,
            'reception_year' => $receptionYear,
            'groom_father_name' => $groomFatherName,
            'groom_mother_name' => $groomMotherName,
            'bride_father_name' => $brideFatherName,
            'bride_mother_name' => $brideMotherName,
            'groom_instagram' => $invitation['groom_instagram'] ?? '',
            'bride_instagram' => $invitation['bride_instagram'] ?? '',
            'location_map_url' => $invitation['location_map_url'] ?? '',
            'location_map_search' => $invitation['location_map_search'] ?? '',
            'livestream_url' => $invitation['livestream_url'] ?? '',
            'livestream_enabled' => !empty($invitation['livestream_url']),
            'video_id' => $invitation['video_id'] ?? '',
            'bank_name' => $invitation['bank_name'] ?? '',
            'bank_account_number' => $invitation['bank_account_number'] ?? '',
            'bank_account_name' => $invitation['bank_account_name'] ?? '',
            'check_in_qr_code_image' => $invitation['check_in_qr_code_image'] ?? '',
            'calendar_url' => $invitation['calendar_url'] ?? '',
            'countdown_date_js' => $countdownDateJs,
            'gallery_images' => $galleryImages,
            'og_image' => $invitation['og_image'] ?? '',
        ];
    }
}

