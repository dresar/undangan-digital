<?php

namespace App\Models;

use CodeIgniter\Model;

class InvitationModel extends Model
{
    protected $table = 'invitations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'slug',
        'title',
        'content_json',
        'theme_config',
        'status',
        'views_count',
        'cover_image',
        'groom_name',
        'bride_name',
        'groom_parents',
        'bride_parents',
        'groom_image',
        'bride_image',
        'groom_instagram',
        'bride_instagram',
        'wedding_date',
        'reception_date',
        'reception_end_time',
        'countdown_date',
        'wedding_location',
        'wedding_address',
        'location_map_url',
        'location_map_search',
        'calendar_url',
        'contact_phone',
        'contact_email',
        'contact_whatsapp',
        'music_url',
        'video_url',
        'video_id',
        'livestream_url',
        'livestream_id',
        'livestream_schedule',
        'livestream_description',
        'livestream_enabled',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'donation_enabled',
        'gallery_images',
        'story_text',
        'cover_message',
        'couple_description',
        'venue_message',
        'apology_text',
        'thank_you_text',
        'thank_image',
        'event_name_1',
        'event_name_2',
        'event_date_1',
        'event_date_2',
        'event_time_1',
        'event_time_2',
        'dress_code',
        'dress_code_image',
        'is_featured',
        'tags',
        'category',
        'meta_description',
        'meta_keywords',
        'og_image',
        'custom_css',
        'custom_js',
        'published_at',
        'expires_at',
        'password',
        'max_views',
        'rsvp_enabled',
        'analytics_enabled',
        'social_sharing_enabled',
        'print_enabled',
        'qr_code_enabled',
        'guestbook_enabled',
        'check_in_card_enabled',
        'check_in_card_instructions',
        'check_in_qr_code',
        'check_in_qr_code_image',
        'template_id',
        'language',
        'timezone',
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

    public function findBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function incrementViews($id)
    {
        return $this->set('views_count', 'views_count + 1', false)
            ->where('id', $id)
            ->update();
    }

    public function getTotalInvitations()
    {
        return $this->countAllResults();
    }

    public function getTotalViews()
    {
        return $this->selectSum('views_count')->first()['views_count'] ?? 0;
    }

    public function getPublishedCount()
    {
        return $this->where('status', 'published')->countAllResults();
    }

    public function getDraftCount()
    {
        return $this->where('status', 'draft')->countAllResults();
    }

    public function getFeaturedInvitations($limit = 5)
    {
        return $this->where('is_featured', 1)
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function getByCategory($category, $limit = 10)
    {
        return $this->where('category', $category)
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function searchByTags($tags)
    {
        $tagArray = is_array($tags) ? $tags : explode(',', $tags);
        $builder = $this->where('status', 'published');
        
        foreach ($tagArray as $tag) {
            $builder->like('tags', trim($tag));
        }
        
        return $builder->findAll();
    }

    public function getExpiredInvitations()
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))
            ->where('expires_at !=', null)
            ->findAll();
    }

    public function getUpcomingWeddings($days = 30)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        
        return $this->where('wedding_date >=', $startDate)
            ->where('wedding_date <=', $endDate)
            ->where('status', 'published')
            ->orderBy('wedding_date', 'ASC')
            ->findAll();
    }

    public function checkPassword($id, $password)
    {
        $invitation = $this->find($id);
        if (!$invitation || empty($invitation['password'])) {
            return true;
        }
        return $invitation['password'] === $password;
    }

    public function checkMaxViews($id)
    {
        $invitation = $this->find($id);
        if (!$invitation || empty($invitation['max_views']) || $invitation['max_views'] == 0) {
            return true;
        }
        return $invitation['views_count'] < $invitation['max_views'];
    }

    public function isExpired($id)
    {
        $invitation = $this->find($id);
        if (!$invitation || empty($invitation['expires_at'])) {
            return false;
        }
        return strtotime($invitation['expires_at']) < time();
    }
}

