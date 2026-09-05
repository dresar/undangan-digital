<?php

namespace App\Models;

use CodeIgniter\Model;

class GuestbookModel extends Model
{
    protected $table = 'guestbooks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invitation_id',
        'name',
        'address',
        'message',
        'photo',
        'is_approved',
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

    public function getByInvitationId($invitationId, $approvedOnly = true, $limit = null)
    {
        $builder = $this->where('invitation_id', $invitationId);
        if ($approvedOnly) {
            $builder->where('is_approved', 1);
        }
        $builder->orderBy('created_at', 'DESC');
        if ($limit !== null && $limit > 0) {
            $builder->limit($limit);
        }
        return $builder->findAll();
    }

    public function getPendingApprovals()
    {
        return $this->where('is_approved', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function approve($id)
    {
        return $this->update($id, ['is_approved' => 1]);
    }
}

