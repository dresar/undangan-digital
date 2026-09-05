<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\InvitationModel;
use App\Models\CheckInCardModel;

class CheckIn extends BaseController
{
    protected $invitationModel;
    protected $checkInCardModel;

    public function __construct()
    {
        $this->invitationModel = new InvitationModel();
        $this->checkInCardModel = new CheckInCardModel();
    }

    public function index($slug)
    {
        $invitation = $this->invitationModel->findBySlug($slug);
        
        if (!$invitation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Cek apakah check-in card enabled
        if (empty($invitation['check_in_card_enabled']) || $invitation['check_in_card_enabled'] != 1) {
            return redirect()->to(base_url('preview/' . $slug))->with('error', 'Check-in card tidak aktif untuk undangan ini');
        }
        
        // Cek apakah QR code ada
        if (empty($invitation['check_in_qr_code'])) {
            return redirect()->to(base_url('preview/' . $slug))->with('error', 'QR code check-in belum tersedia');
        }
        
        $data = [
            'title' => 'Check-In - ' . ($invitation['title'] ?? 'Undangan'),
            'invitation' => $invitation,
        ];
        
        return view('user/checkin/index', $data);
    }

    public function submit($slug)
    {
        $invitation = $this->invitationModel->findBySlug($slug);
        
        if (!$invitation) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Undangan tidak ditemukan'
            ]);
        }
        
        // Cek apakah check-in card enabled
        if (empty($invitation['check_in_card_enabled']) || $invitation['check_in_card_enabled'] != 1) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Check-in card tidak aktif'
            ]);
        }
        
        $guestName = $this->request->getPost('guest_name');
        if (empty($guestName) || trim($guestName) === '') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama tamu harus diisi'
            ]);
        }
        
        // Cek apakah sudah check-in sebelumnya (berdasarkan nama)
        $existingCheckIn = $this->checkInCardModel->where('invitation_id', $invitation['id'])
            ->where('guest_name', trim($guestName))
            ->first();
        
        if ($existingCheckIn) {
            // Update check-in jika sudah ada
            $this->checkInCardModel->update($existingCheckIn['id'], [
                'checked_in' => 1,
                'checked_in_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            // Insert baru
            $this->checkInCardModel->insert([
                'invitation_id' => $invitation['id'],
                'guest_name' => trim($guestName),
                'qr_code' => $invitation['check_in_qr_code'],
                'qr_code_image' => $invitation['check_in_qr_code_image'] ?? null,
                'checked_in' => 1,
                'checked_in_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Check-in berhasil! Terima kasih atas kehadiran Anda.'
        ]);
    }
}
