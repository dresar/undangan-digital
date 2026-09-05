<?php

namespace App\Models;

use CodeIgniter\Model;

class RsvpModel extends Model
{
    protected $table = 'rsvps';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invitation_id',
        'name',
        'email',
        'phone',
        'attendance',
        'guest_count',
        'message',
        'ip_address',
        'user_agent',
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

    public function getByInvitationId($invitationId)
    {
        return $this->where('invitation_id', $invitationId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getAttendanceStats($invitationId)
    {
        $total = $this->where('invitation_id', $invitationId)->countAllResults();
        
        // Handle both '1'/'0' and 'yes'/'no' for attendance
        $attending = $this->where('invitation_id', $invitationId)
            ->groupStart()
                ->where('attendance', '1')
                ->orWhere('attendance', 'yes')
            ->groupEnd()
            ->countAllResults();
            
        $notAttending = $this->where('invitation_id', $invitationId)
            ->groupStart()
                ->where('attendance', '0')
                ->orWhere('attendance', 'no')
            ->groupEnd()
            ->countAllResults();
            
        $totalGuests = $this->where('invitation_id', $invitationId)
            ->groupStart()
                ->where('attendance', '1')
                ->orWhere('attendance', 'yes')
            ->groupEnd()
            ->selectSum('guest_count')
            ->first();
        $totalGuests = !empty($totalGuests['guest_count']) ? (int)$totalGuests['guest_count'] : 0;

        return [
            'total' => $total,
            'attending' => $attending,
            'not_attending' => $notAttending,
            'total_guests' => $totalGuests,
        ];
    }
}

