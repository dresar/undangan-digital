<?php

namespace App\Models;

use CodeIgniter\Model;

class OurStoryModel extends Model
{
    protected $table = 'our_story';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invitation_id',
        'year',
        'title',
        'story_text',
        'story_image',
        'display_order',
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
        return $this->where('invitation_id', $invitationId)
            ->orderBy('display_order', 'ASC')
            ->orderBy('year', 'ASC')
            ->findAll();
    }

    public function deleteByInvitationId($invitationId)
    {
        return $this->where('invitation_id', $invitationId)->delete();
    }
}

