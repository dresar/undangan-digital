<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModel extends Model
{
    protected $table = 'analytics';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invitation_id',
        'event_type',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'created_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function trackEvent($invitationId, $eventType, $request)
    {
        $userAgent = $request->getUserAgent();
        $ipAddress = $request->getIPAddress();
        $referrer = $request->getServer('HTTP_REFERER');

        $data = [
            'invitation_id' => $invitationId,
            'event_type' => $eventType,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent->getAgentString(),
            'referrer' => $referrer,
            'device_type' => $this->detectDeviceType($userAgent),
            'browser' => $userAgent->getBrowser(),
            'os' => $userAgent->getPlatform(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }

    public function getStatsByInvitation($invitationId, $startDate = null, $endDate = null)
    {
        $builder = $this->where('invitation_id', $invitationId);
        
        if ($startDate) {
            $builder->where('created_at >=', $startDate);
        }
        if ($endDate) {
            $builder->where('created_at <=', $endDate);
        }

        $totalViews = $builder->where('event_type', 'view')->countAllResults(false);
        $uniqueViews = $builder->select('DISTINCT ip_address')->where('event_type', 'view')->countAllResults(false);
        
        return [
            'total_views' => $totalViews,
            'unique_views' => $uniqueViews,
            'by_device' => $this->getStatsByDevice($invitationId),
            'by_browser' => $this->getStatsByBrowser($invitationId),
            'by_country' => $this->getStatsByCountry($invitationId),
        ];
    }

    private function detectDeviceType($userAgent)
    {
        $agent = $userAgent->getAgentString();
        if (preg_match('/mobile|android|iphone|ipad/i', $agent)) {
            return 'mobile';
        }
        if (preg_match('/tablet|ipad/i', $agent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function getStatsByDevice($invitationId)
    {
        return $this->select('device_type, COUNT(*) as count')
            ->where('invitation_id', $invitationId)
            ->where('event_type', 'view')
            ->groupBy('device_type')
            ->findAll();
    }

    private function getStatsByBrowser($invitationId)
    {
        return $this->select('browser, COUNT(*) as count')
            ->where('invitation_id', $invitationId)
            ->where('event_type', 'view')
            ->groupBy('browser')
            ->findAll();
    }

    private function getStatsByCountry($invitationId)
    {
        return $this->select('country, COUNT(*) as count')
            ->where('invitation_id', $invitationId)
            ->where('event_type', 'view')
            ->where('country IS NOT NULL')
            ->groupBy('country')
            ->orderBy('count', 'DESC')
            ->findAll(10);
    }
}

