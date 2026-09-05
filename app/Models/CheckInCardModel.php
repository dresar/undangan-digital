<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckInCardModel extends Model
{
    protected $table = 'check_in_cards';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invitation_id',
        'guest_name',
        'qr_code',
        'qr_code_image',
        'checked_in',
        'checked_in_at',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getByInvitationId($invitationId)
    {
        return $this->where('invitation_id', $invitationId)->findAll();
    }

    public function findByQrCode($qrCode)
    {
        return $this->where('qr_code', $qrCode)->first();
    }

    public function checkIn($qrCode)
    {
        $card = $this->findByQrCode($qrCode);
        if ($card && !$card['checked_in']) {
            return $this->update($card['id'], [
                'checked_in' => 1,
                'checked_in_at' => date('Y-m-d H:i:s'),
            ]);
        }
        return false;
    }

    public function generateQrCode($invitationId, $guestName)
    {
        // Generate unique QR code
        $qrCode = 'CHECKIN-' . $invitationId . '-' . time() . '-' . bin2hex(random_bytes(4));
        
        // Generate QR code image (akan dibuat di controller dengan library QR code)
        return $qrCode;
    }
}

