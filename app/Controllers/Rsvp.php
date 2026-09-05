<?php

namespace App\Controllers;

use App\Models\RsvpModel;
use App\Models\InvitationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Rsvp extends BaseController
{
    protected $rsvpModel;
    protected $invitationModel;

    public function __construct()
    {
        $this->rsvpModel = new RsvpModel();
        $this->invitationModel = new InvitationModel();
    }

    /**
     * Menyimpan RSVP dari form
     */
    public function store()
    {
        $invitationId = $this->request->getPost('invitation_id');
        $name = $this->request->getPost('name');
        $status = $this->request->getPost('status'); // '1' atau '0'
        $message = $this->request->getPost('comment') ?? $this->request->getPost('message');
        $guestCount = (int)($this->request->getPost('guest_count') ?? 1);

        // Validasi
        if (empty($invitationId) || empty($name) || $status === null || $status === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ])->setStatusCode(400);
        }

        // Cek apakah invitation ada
        $invitation = $this->invitationModel->find($invitationId);
        if (!$invitation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Undangan tidak ditemukan'
            ])->setStatusCode(404);
        }

        // Simpan RSVP
        $data = [
            'invitation_id' => $invitationId,
            'name' => $name,
            'email' => $this->request->getPost('email') ?? null,
            'phone' => $this->request->getPost('phone') ?? null,
            'attendance' => $status, // Simpan sebagai '1' atau '0'
            'guest_count' => $guestCount,
            'message' => $message ?? null,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->rsvpModel->insert($data);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Terima kasih! Konfirmasi kehadiran Anda telah diterima.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan RSVP: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}

