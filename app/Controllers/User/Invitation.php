<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\InvitationModel;
use App\Models\CheckInCardModel;
use App\Models\GuestbookModel;
use App\Models\TemplateModel;
use CodeIgniter\HTTP\ResponseInterface;

class Invitation extends BaseController
{
    protected $invitationModel;
    protected $checkInCardModel;
    protected $guestbookModel;
    protected $templateModel;

    public function __construct()
    {
        $this->invitationModel = new InvitationModel();
        $this->checkInCardModel = new CheckInCardModel();
        $this->guestbookModel = new GuestbookModel();
        $this->templateModel = new TemplateModel();
    }

    public function index($slug)
    {
        $invitation = $this->invitationModel->findBySlug($slug);
        
        if (!$invitation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Undangan tidak ditemukan');
        }

        // Get check-in cards
        $checkInCards = $this->checkInCardModel->getByInvitationId($invitation['id']);
        
        // Get guestbook
        $guestbooks = $this->guestbookModel->where('invitation_id', $invitation['id'])
            ->orderBy('created_at', 'DESC')
            ->findAll(50);
        
        // Get templates
        $templates = $this->templateModel->getActiveTemplates();

        $data = [
            'invitation' => $invitation,
            'checkInCards' => $checkInCards,
            'guestbooks' => $guestbooks,
            'templates' => $templates,
        ];

        return view('user/invitation/index', $data);
    }

    public function scanQrCode($slug)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $qrCode = $this->request->getPost('qr_code');
        
        if (empty($qrCode)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'QR Code tidak boleh kosong'
            ]);
        }

        $card = $this->checkInCardModel->findByQrCode($qrCode);
        
        if (!$card) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'QR Code tidak valid'
            ]);
        }

        // Check if already checked in
        if ($card['checked_in']) {
            return $this->response->setJSON([
                'status' => 'warning',
                'message' => 'Tamu sudah check-in sebelumnya',
                'data' => [
                    'guest_name' => $card['guest_name'],
                    'checked_in_at' => $card['checked_in_at'],
                ]
            ]);
        }

        // Check in
        if ($this->checkInCardModel->checkIn($qrCode)) {
            $card = $this->checkInCardModel->findByQrCode($qrCode);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Check-in berhasil',
                'data' => [
                    'guest_name' => $card['guest_name'],
                    'checked_in_at' => $card['checked_in_at'],
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal melakukan check-in'
        ]);
    }

    public function updateInvitation($slug)
    {
        $invitation = $this->invitationModel->findBySlug($slug);
        
        if (!$invitation) {
            return redirect()->back()->with('error', 'Undangan tidak ditemukan');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'template_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file uploads for images
        $imageFields = ['cover_image_file', 'groom_image_file', 'bride_image_file'];
        $imageMapping = [
            'cover_image_file' => 'cover_image',
            'groom_image_file' => 'groom_image',
            'bride_image_file' => 'bride_image',
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

        $data = [
            'title' => $this->request->getPost('title'),
            'groom_name' => $this->request->getPost('groom_name'),
            'bride_name' => $this->request->getPost('bride_name'),
            'groom_parents' => $this->request->getPost('groom_parents'),
            'bride_parents' => $this->request->getPost('bride_parents'),
            'wedding_date' => $this->request->getPost('wedding_date') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('wedding_date'))) : null,
            'wedding_location' => $this->request->getPost('wedding_location'),
            'wedding_address' => $this->request->getPost('wedding_address'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_whatsapp' => $this->request->getPost('contact_whatsapp'),
            'music_url' => $this->request->getPost('music_url'),
            'video_url' => $this->request->getPost('video_url'),
            'template_id' => (int)$this->request->getPost('template_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Update images if uploaded
        foreach ($imageMapping as $fileField => $dbField) {
            if ($this->request->getPost($dbField)) {
                $data[$dbField] = $this->request->getPost($dbField);
            }
        }

        if ($this->invitationModel->update($invitation['id'], $data)) {
            return redirect()->to('/user/' . $slug)->with('success', 'Undangan berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui undangan');
    }
}

