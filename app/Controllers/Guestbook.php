<?php

namespace App\Controllers;

use App\Models\GuestbookModel;
use App\Models\InvitationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Guestbook extends BaseController
{
    protected $guestbookModel;
    protected $invitationModel;

    public function __construct()
    {
        $this->guestbookModel = new GuestbookModel();
        $this->invitationModel = new InvitationModel();
    }

    /**
     * Menampilkan daftar wishes/ucapan untuk invitation tertentu
     */
    public function index()
    {
        $invitationId = $this->request->getGet('invitation_id');
        $slug = $this->request->getGet('slug');
        
        // Jika ada slug, cari invitation_id dari slug
        if (!empty($slug) && empty($invitationId)) {
            $invitation = $this->invitationModel->findBySlug($slug);
            if ($invitation) {
                $invitationId = $invitation['id'];
            }
        }
        
        if (empty($invitationId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invitation ID tidak ditemukan'
            ])->setStatusCode(400);
        }

        // Ambil wishes yang sudah approved dengan limit default 5
        $limit = (int)($this->request->getGet('limit') ?? 5);
        $wishes = $this->guestbookModel->getByInvitationId($invitationId, true, $limit);
        
        // Format response untuk ditampilkan di template dengan card
        $html = '';
        if (empty($wishes)) {
            $html = '<div class="text-center py-4"><p class="mb-0 text-muted"><b>Belum ada ucapan. Jadilah yang pertama mengucapkan selamat!</b></p></div>';
        } else {
            foreach ($wishes as $wish) {
                $name = esc($wish['name'] ?? 'Anonymous');
                $address = !empty($wish['address']) ? esc($wish['address']) : '';
                $message = esc($wish['message'] ?? '');
                $createdAt = !empty($wish['created_at']) ? date('d M Y, H:i', strtotime($wish['created_at'])) : '';
                
                $html .= '<div class="card mb-3 shadow-sm border-0" style="background: #ffffff;">';
                $html .= '<div class="card-body p-3">';
                $html .= '<div class="d-flex justify-content-between align-items-start mb-2">';
                $html .= '<div class="flex-grow-1">';
                $html .= '<h6 class="mb-1" style="font-size: 0.95rem; font-weight: 600; color: #333;"><strong>' . $name . '</strong></h6>';
                if (!empty($address)) {
                    $html .= '<small class="text-muted d-block" style="font-size: 0.8rem;"><i class="fas fa-map-marker-alt me-1"></i>' . $address . '</small>';
                }
                $html .= '</div>';
                if (!empty($createdAt)) {
                    $html .= '<small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">' . $createdAt . '</small>';
                }
                $html .= '</div>';
                $html .= '<p class="mb-0" style="font-size: 0.9rem; color: #555; line-height: 1.6;">' . nl2br($message) . '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }
            
            // Tambahkan link untuk load more jika ada lebih dari 5
            $totalWishes = $this->guestbookModel->where('invitation_id', $invitationId)->where('is_approved', 1)->countAllResults();
            if ($totalWishes > $limit) {
                $html .= '<div class="text-center mt-3">';
                $html .= '<button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadMoreWishes(' . ($limit + 5) . ')">';
                $html .= '<i class="fas fa-chevron-down me-1"></i>Lihat Lebih Banyak (' . ($totalWishes - $limit) . ' ucapan lagi)';
                $html .= '</button>';
                $html .= '</div>';
            }
        }
        
        // Cek apakah request dari jQuery .load() (Accept header mengandung text/html)
        $acceptHeader = $this->request->getHeaderLine('Accept');
        $isLoadRequest = strpos($acceptHeader, 'text/html') !== false;
        
        // Jika request dari .load() atau bukan AJAX murni, return HTML langsung
        if ($isLoadRequest || !$this->request->isAJAX()) {
            return $this->response->setBody($html)->setContentType('text/html');
        }
        
        // Jika request AJAX murni (bukan .load()), return JSON
        return $this->response->setJSON([
            'success' => true,
            'html' => $html,
            'count' => count($wishes)
        ]);
    }

    /**
     * Menyimpan wish/ucapan baru
     */
    public function store()
    {
        $invitationId = $this->request->getPost('invitation_id');
        $slug = $this->request->getPost('slug');
        
        // Jika ada slug, cari invitation_id dari slug
        if (!empty($slug) && empty($invitationId)) {
            $invitation = $this->invitationModel->findBySlug($slug);
            if ($invitation) {
                $invitationId = $invitation['id'];
            }
        }
        
        if (empty($invitationId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invitation ID tidak ditemukan'
            ])->setStatusCode(400);
        }

        $name = $this->request->getPost('name');
        $address = $this->request->getPost('alamat') ?? $this->request->getPost('address');
        $message = $this->request->getPost('comment') ?? $this->request->getPost('message');
        
        // Validasi
        if (empty($name) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama dan pesan wajib diisi'
            ])->setStatusCode(400);
        }

        // Simpan ke database
        $data = [
            'invitation_id' => $invitationId,
            'name' => $name,
            'address' => $address ?? '',
            'message' => $message,
            'is_approved' => 1, // Auto approve untuk sekarang
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->guestbookModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ucapan berhasil dikirim. Terima kasih!'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengirim ucapan. Silakan coba lagi.'
        ])->setStatusCode(500);
    }
}

