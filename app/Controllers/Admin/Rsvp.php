<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RsvpModel;
use App\Models\GuestbookModel;
use App\Models\InvitationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Rsvp extends BaseController
{
    protected $rsvpModel;
    protected $guestbookModel;
    protected $invitationModel;

    public function __construct()
    {
        $this->rsvpModel = new RsvpModel();
        $this->guestbookModel = new GuestbookModel();
        $this->invitationModel = new InvitationModel();
    }

    /**
     * Menampilkan daftar semua undangan dengan statistik RSVP dan Ucapan
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $page = (int)($this->request->getGet('page') ?? 1);
        
        // Ambil semua undangan (seperti di Daftar Undangan)
        $db = \Config\Database::connect();
        $builder = $db->table('invitations')
            ->select('invitations.*');

        // Search filter
        if ($search && $search !== '') {
            $builder->groupStart()
                ->like('invitations.title', $search)
                ->orLike('invitations.slug', $search)
                ->orLike('invitations.groom_name', $search)
                ->orLike('invitations.bride_name', $search)
                ->groupEnd();
        }

        // Get data dengan pagination manual
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Hitung total
        $countBuilder = $db->table('invitations');
        if ($search && $search !== '') {
            $countBuilder->groupStart()
                ->like('title', $search)
                ->orLike('slug', $search)
                ->orLike('groom_name', $search)
                ->orLike('bride_name', $search)
                ->groupEnd();
        }
        $total = $countBuilder->countAllResults(false);
        
        // Get invitations
        $invitations = $builder->orderBy('invitations.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();
        
        // Untuk setiap invitation, ambil statistik RSVP dan Guestbook
        $invitationsWithStats = [];
        foreach ($invitations as $inv) {
            $invId = $inv['id'];
            
            // Get RSVP stats
            $rsvpStats = $this->rsvpModel->getAttendanceStats($invId);
            
            // Get Guestbook count
            $guestbookCount = $this->guestbookModel->where('invitation_id', $invId)
                ->where('is_approved', 1)
                ->countAllResults(false);
            
            $invitationsWithStats[] = [
                'id' => $inv['id'],
                'title' => $inv['title'],
                'slug' => $inv['slug'],
                'groom_name' => $inv['groom_name'] ?? '',
                'bride_name' => $inv['bride_name'] ?? '',
                'status' => $inv['status'] ?? 'draft',
                'views_count' => $inv['views_count'] ?? 0,
                'created_at' => $inv['created_at'],
                'rsvp_total' => $rsvpStats['total'],
                'rsvp_attending' => $rsvpStats['attending'],
                'rsvp_not_attending' => $rsvpStats['not_attending'],
                'rsvp_total_guests' => $rsvpStats['total_guests'],
                'guestbook_count' => $guestbookCount,
            ];
        }
        
        // Buat pager object untuk pagination
        $pager = \Config\Services::pager();
        $pager->store('default', $page, $perPage, $total);
        
        // Pass pagination info ke view
        $paginationInfo = [
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => ceil($total / $perPage)
        ];

        // Get overall statistics
        $totalBuilder = $this->rsvpModel->builder();
        $attendingBuilder = $this->rsvpModel->builder();
        $notAttendingBuilder = $this->rsvpModel->builder();
        
        $stats = [
            'total' => $totalBuilder->countAllResults(false),
            'attending' => $attendingBuilder->groupStart()
                ->where('attendance', '1')
                ->orWhere('attendance', 'yes')
                ->groupEnd()
                ->countAllResults(false),
            'not_attending' => $notAttendingBuilder->groupStart()
                ->where('attendance', '0')
                ->orWhere('attendance', 'no')
                ->groupEnd()
                ->countAllResults(false),
            'total_guestbook' => $this->guestbookModel->where('is_approved', 1)->countAllResults(false),
        ];

        $data = [
            'invitations' => $invitationsWithStats,
            'pager' => $pager,
            'paginationInfo' => $paginationInfo,
            'stats' => $stats,
            'search' => $search,
        ];

        return view('admin/rsvp/index', $data);
    }

    /**
     * Menampilkan detail RSVP untuk undangan tertentu
     */
    public function detail($invitationId = null)
    {
        if (empty($invitationId)) {
            $invitationId = $this->request->getGet('invitation_id');
        }

        if (empty($invitationId)) {
            return redirect()->to(base_url('admin/rsvp'))->with('error', 'ID Undangan tidak ditemukan');
        }

        $invitation = $this->invitationModel->find($invitationId);
        if (!$invitation) {
            return redirect()->to(base_url('admin/rsvp'))->with('error', 'Undangan tidak ditemukan');
        }

        $search = $this->request->getGet('search');
        $attendance = $this->request->getGet('attendance');
        $page = (int)($this->request->getGet('page') ?? 1);

        $builder = $this->rsvpModel->where('invitation_id', $invitationId);

        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }

        if ($attendance !== null && $attendance !== '') {
            if ($attendance === '1' || $attendance === 'yes') {
                $builder->groupStart()
                    ->where('attendance', '1')
                    ->orWhere('attendance', 'yes')
                    ->groupEnd();
            } elseif ($attendance === '0' || $attendance === 'no') {
                $builder->groupStart()
                    ->where('attendance', '0')
                    ->orWhere('attendance', 'no')
                    ->groupEnd();
            }
        }

        // Hitung total dulu
        $total = $builder->countAllResults(false);
        
        // Get data dengan pagination manual
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Rebuild query untuk get data
        $dataBuilder = $this->rsvpModel->where('invitation_id', $invitationId);
        
        if ($search) {
            $dataBuilder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }
        
        if ($attendance !== null && $attendance !== '') {
            if ($attendance === '1' || $attendance === 'yes') {
                $dataBuilder->groupStart()
                    ->where('attendance', '1')
                    ->orWhere('attendance', 'yes')
                    ->groupEnd();
            } elseif ($attendance === '0' || $attendance === 'no') {
                $dataBuilder->groupStart()
                    ->where('attendance', '0')
                    ->orWhere('attendance', 'no')
                    ->groupEnd();
            }
        }
        
        $rsvps = $dataBuilder->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();
        
        // Buat pager object untuk pagination
        $pager = \Config\Services::pager();
        $pager->store('default', $page, $perPage, $total);
        
        // Pass pagination info ke view
        $paginationInfo = [
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => ceil($total / $perPage)
        ];

        // Get statistics for this invitation
        $stats = $this->rsvpModel->getAttendanceStats($invitationId);
        
        // Get guestbook/wishes untuk undangan ini dengan filter jika ada search
        $guestbookBuilder = $this->guestbookModel->where('invitation_id', $invitationId)->where('is_approved', 1);
        if ($search && $search !== '') {
            $guestbookBuilder->groupStart()
                ->like('name', $search)
                ->orLike('message', $search)
                ->orLike('address', $search)
                ->groupEnd();
        }
        $guestbooks = $guestbookBuilder->orderBy('created_at', 'DESC')->findAll();
        
        // Gabungkan RSVP dan Guestbook menjadi satu list
        // Format: [type, data, created_at] untuk sorting
        $combinedList = [];
        
        // Buat array untuk tracking RSVP yang sudah punya message (untuk menghindari duplikat)
        // Key: name|message, Value: created_at timestamp
        $rsvpWithMessages = [];
        foreach ($rsvps as $rsvp) {
            if (!empty($rsvp['message'])) {
                // Simpan key berdasarkan name + message untuk deteksi duplikat
                $key = strtolower(trim($rsvp['name'])) . '|' . strtolower(trim($rsvp['message']));
                $rsvpWithMessages[$key] = strtotime($rsvp['created_at']);
            }
            $combinedList[] = [
                'type' => 'rsvp',
                'data' => $rsvp,
                'created_at' => $rsvp['created_at'],
            ];
        }
        
        // Tambahkan Guestbook/Ucapan, tapi skip jika duplikat dengan RSVP yang sudah punya message
        foreach ($guestbooks as $gb) {
            $key = strtolower(trim($gb['name'])) . '|' . strtolower(trim($gb['message']));
            $gbTimestamp = strtotime($gb['created_at']);
            
            // Skip jika name dan message sama dengan RSVP yang sudah ada message-nya
            // Dan waktu dibuatnya dalam rentang 5 menit (untuk menghindari duplikat dari form yang sama)
            $isDuplicate = false;
            if (isset($rsvpWithMessages[$key])) {
                $timeDiff = abs($gbTimestamp - $rsvpWithMessages[$key]);
                // Jika waktu berbeda kurang dari 5 menit (300 detik), anggap duplikat
                if ($timeDiff < 300) {
                    $isDuplicate = true;
                }
            }
            
            if (!$isDuplicate) {
                $combinedList[] = [
                    'type' => 'guestbook',
                    'data' => $gb,
                    'created_at' => $gb['created_at'],
                ];
            }
        }
        
        // Sort by created_at DESC
        usort($combinedList, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Pagination untuk combined list
        $combinedPerPage = 20;
        $combinedPage = (int)($this->request->getGet('page') ?? 1);
        $combinedOffset = ($combinedPage - 1) * $combinedPerPage;
        $combinedTotal = count($combinedList);
        $combinedPaginated = array_slice($combinedList, $combinedOffset, $combinedPerPage);
        
        // Update pagination info untuk combined list
        $paginationInfo = [
            'total' => $combinedTotal,
            'perPage' => $combinedPerPage,
            'currentPage' => $combinedPage,
            'totalPages' => ceil($combinedTotal / $combinedPerPage)
        ];

        $data = [
            'invitation' => $invitation,
            'rsvps' => $rsvps, // Keep for stats
            'guestbooks' => $guestbooks, // Keep for reference
            'combinedList' => $combinedPaginated, // New combined list
            'pager' => $pager,
            'paginationInfo' => $paginationInfo,
            'stats' => $stats,
            'search' => $search,
            'attendance' => $attendance,
        ];

        return view('admin/rsvp/detail', $data);
    }

    /**
     * Delete RSVP
     */
    public function delete($id)
    {
        $rsvp = $this->rsvpModel->find($id);
        if (!$rsvp) {
            return redirect()->back()->with('error', 'RSVP tidak ditemukan');
        }

        $this->rsvpModel->delete($id);
        return redirect()->back()->with('success', 'RSVP berhasil dihapus');
    }

    /**
     * Delete Guestbook/Ucapan
     */
    public function deleteGuestbook($id)
    {
        $guestbook = $this->guestbookModel->find($id);
        if (!$guestbook) {
            return redirect()->back()->with('error', 'Ucapan tidak ditemukan');
        }

        $this->guestbookModel->delete($id);
        return redirect()->back()->with('success', 'Ucapan berhasil dihapus');
    }
}

