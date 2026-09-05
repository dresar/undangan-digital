<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class InvitationEntity extends Entity
{
    protected $dates = ['wedding_date', 'reception_date', 'countdown_date', 'published_at', 'expires_at', 'created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'int',
        'template_id' => 'int',
        'views_count' => 'int',
        'max_views' => 'int',
        'livestream_enabled' => 'bool',
        'donation_enabled' => 'bool',
        'rsvp_enabled' => 'bool',
        'guestbook_enabled' => 'bool',
        'analytics_enabled' => 'bool',
        'social_sharing_enabled' => 'bool',
        'print_enabled' => 'bool',
        'qr_code_enabled' => 'bool',
        'check_in_card_enabled' => 'bool',
        'is_featured' => 'bool',
    ];

    /**
     * Mapping nama hari dalam bahasa Indonesia
     */
    protected function getDayNameId($dayName)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        return $days[$dayName] ?? 'Sabtu';
    }

    /**
     * Mapping nama bulan dalam bahasa Indonesia
     */
    protected function getMonthNameId($monthName)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        return $months[$monthName] ?? 'Februari';
    }

    /**
     * Format tanggal pernikahan: d M Y (contoh: 05 Mei 2025)
     */
    public function getWeddingDateFormatted()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('d M Y', strtotime($date));
    }

    /**
     * Format tanggal pernikahan lengkap: l, d F Y (contoh: Monday, 05 May 2025)
     */
    public function getWeddingDateFull()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('l, d F Y', strtotime($date));
    }

    /**
     * Nama hari pernikahan (English)
     */
    public function getWeddingDayName()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('l', strtotime($date));
    }

    /**
     * Nama hari pernikahan (Bahasa Indonesia)
     */
    public function getWeddingDayNameId()
    {
        return $this->getDayNameId($this->getWeddingDayName());
    }

    /**
     * Tanggal pernikahan (hari saja, contoh: 05)
     */
    public function getWeddingDay()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('d', strtotime($date));
    }

    /**
     * Bulan pernikahan (angka, contoh: 05)
     */
    public function getWeddingMonth()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('m', strtotime($date));
    }

    /**
     * Nama bulan pernikahan (English)
     */
    public function getWeddingMonthName()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('F', strtotime($date));
    }

    /**
     * Nama bulan pernikahan (Bahasa Indonesia)
     */
    public function getWeddingMonthNameId()
    {
        return $this->getMonthNameId($this->getWeddingMonthName());
    }

    /**
     * Tahun pernikahan
     */
    public function getWeddingYear()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('Y', strtotime($date));
    }

    /**
     * Format tanggal pendek: d.m.Y (contoh: 05.05.2025)
     */
    public function getWeddingDateShort()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('d.m.Y', strtotime($date));
    }

    /**
     * Waktu pernikahan: H:i (contoh: 08:00)
     */
    public function getWeddingTime()
    {
        $date = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('H:i', strtotime($date));
    }

    /**
     * Waktu resepsi: H:i
     */
    public function getReceptionTime()
    {
        $date = $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('H:i', strtotime($date));
    }

    /**
     * Nama hari resepsi (English)
     */
    public function getReceptionDayName()
    {
        $date = $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('l', strtotime($date));
    }

    /**
     * Nama hari resepsi (Bahasa Indonesia)
     */
    public function getReceptionDayNameId()
    {
        return $this->getDayNameId($this->getReceptionDayName());
    }

    /**
     * Tanggal resepsi (hari saja)
     */
    public function getReceptionDay()
    {
        $date = $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('d', strtotime($date));
    }

    /**
     * Nama bulan resepsi (English)
     */
    public function getReceptionMonthName()
    {
        $date = $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('F', strtotime($date));
    }

    /**
     * Nama bulan resepsi (Bahasa Indonesia)
     */
    public function getReceptionMonthNameId()
    {
        return $this->getMonthNameId($this->getReceptionMonthName());
    }

    /**
     * Tahun resepsi
     */
    public function getReceptionYear()
    {
        $date = $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('Y', strtotime($date));
    }

    /**
     * Format countdown date untuk JavaScript
     */
    public function getCountdownDateFormatted()
    {
        $date = $this->attributes['countdown_date'] ?? $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('m/d/Y H:i:s', strtotime($date)) . ' UTC';
    }

    /**
     * Format countdown date untuk JavaScript (tanpa UTC)
     */
    public function getCountdownDateJs()
    {
        $date = $this->attributes['countdown_date'] ?? $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('m/d/Y H:i:s', strtotime($date));
    }

    /**
     * Parse nama orang tua mempelai pria menjadi array
     */
    public function getGroomParentsList()
    {
        $raw = $this->attributes['groom_parents'] ?? 'Tjipto Gunawan & Felicia Susanto';
        
        if (is_string($raw)) {
            if (strpos($raw, '&') !== false) {
                return array_map('trim', explode('&', $raw));
            } elseif (stripos($raw, 'dan') !== false) {
                return array_map('trim', explode('dan', $raw));
            } else {
                return [$raw];
            }
        }
        
        return is_array($raw) ? $raw : ['Tjipto Gunawan', 'Felicia Susanto'];
    }

    /**
     * Parse nama orang tua mempelai wanita menjadi array
     */
    public function getBrideParentsList()
    {
        $raw = $this->attributes['bride_parents'] ?? 'Budiman Thamrin & Sarah Erawati';
        
        if (is_string($raw)) {
            if (strpos($raw, '&') !== false) {
                return array_map('trim', explode('&', $raw));
            } elseif (stripos($raw, 'dan') !== false) {
                return array_map('trim', explode('dan', $raw));
            } else {
                return [$raw];
            }
        }
        
        return is_array($raw) ? $raw : ['Budiman Thamrin', 'Sarah Erawati'];
    }

    /**
     * Nama pasangan (groom & bride)
     */
    public function getCoupleNames()
    {
        $groom = $this->attributes['groom_name'] ?? 'Vidi Dwi Saputra';
        $bride = $this->attributes['bride_name'] ?? 'Hening Tyas Saputri';
        return $groom . ' & ' . $bride;
    }

    /**
     * Memastikan URL gambar valid dengan fallback
     */
    protected function ensureImageUrl($url, $fallback = '')
    {
        if (empty($url)) {
            return $fallback;
        }
        
        $url = html_entity_decode(urldecode($url), ENT_QUOTES, 'UTF-8');
        
        if (strpos($url, 'http/x3A') !== false || strpos($url, 'x2F') !== false) {
            $url = rawurldecode(str_replace(['x3A', 'x2F'], [':', '/'], $url));
        }
        
        $url = trim($url);
        
        if (filter_var($url, FILTER_VALIDATE_URL) || strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            return $url;
        }
        
        $currentBaseUrl = base_url();
        if (strpos($url, $currentBaseUrl) !== false) {
            return $url;
        }
        
        if (strpos($url, 'assets/') === 0) {
            return base_url($url);
        }
        
        if (strpos($url, '/assets/') === 0) {
            return base_url(ltrim($url, '/'));
        }
        
        if (strpos($url, '/') === false && strpos($url, '\\') === false) {
            return base_url('assets/images/templates/' . $url);
        }
        
        return base_url('assets/images/templates/' . basename($url));
    }

    public function getGroomImage()
    {
        return $this->ensureImageUrl($this->attributes['groom_image'] ?? '', '');
    }

    public function getBrideImage()
    {
        return $this->ensureImageUrl($this->attributes['bride_image'] ?? '', '');
    }

    public function getCoverImage()
    {
        return $this->ensureImageUrl($this->attributes['cover_image'] ?? '', '');
    }

    public function getDressCodeImage()
    {
        return $this->ensureImageUrl($this->attributes['dress_code_image'] ?? '', '');
    }

    public function getOgImage()
    {
        return $this->ensureImageUrl($this->attributes['og_image'] ?? '', '');
    }

    /**
     * Parse gallery images menjadi array
     */
    public function getGalleryImages()
    {
        $gallery = $this->attributes['gallery_images'] ?? [];
        
        if (is_string($gallery)) {
            // Coba decode JSON
            $decoded = json_decode($gallery, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $gallery = $decoded;
            } else {
                // Jika bukan JSON, coba split dengan newline atau koma
                $gallery = array_filter(array_map('trim', preg_split('/[\n\r,]+/', $gallery)));
            }
        }
        
        if (!is_array($gallery)) {
            $gallery = [];
        }
        
        // Validasi dan normalisasi URL setiap gambar
        $validated = [];
        foreach ($gallery as $img) {
            if (!empty($img)) {
                $decodedImg = html_entity_decode(urldecode($img), ENT_QUOTES, 'UTF-8');
                $validated[] = $this->ensureImageUrl($decodedImg, '');
            }
        }
        
        $validated = array_filter($validated);
        return $validated;
    }

    /**
     * Parse music URL dengan validasi
     */
    public function getMusicUrl()
    {
        $url = $this->attributes['music_url'] ?? '';
        
        if (empty($url)) {
            return '';
        }
        
        $url = trim($url);
        
        if (filter_var($url, FILTER_VALIDATE_URL) || strpos($url, 'http') === 0) {
            return $url;
        }
        
        if (strpos($url, base_url()) !== false) {
            return $url;
        }
        
        if (strpos($url, 'assets/') === 0) {
            return base_url($url);
        }
        
        if (strpos($url, '/assets/') === 0) {
            return base_url(ltrim($url, '/'));
        }
        
        return base_url('assets/media/' . basename($url));
    }

    /**
     * Cek apakah livestream enabled dan valid
     */
    public function isLivestreamEnabled()
    {
        $enabled = !empty($this->attributes['livestream_enabled']) && $this->attributes['livestream_enabled'] == 1;
        $hasUrl = !empty($this->attributes['livestream_url']);
        $hasId = !empty($this->attributes['livestream_id']);
        
        return $enabled && $hasUrl && $hasId;
    }

    /**
     * URL check-in QR code image dengan validasi
     */
    public function getCheckInQrCodeImage()
    {
        $url = $this->attributes['check_in_qr_code_image'] ?? '';
        if (empty($url)) {
            return '';
        }
        return $this->ensureImageUrl($url, '');
    }

    /**
     * Event date 1 (hari saja)
     */
    public function getEventDate1()
    {
        $date = $this->attributes['event_date_1'] ?? $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('d', strtotime($date));
    }

    /**
     * Event month 1 (bulan dan tahun)
     */
    public function getEventMonth1()
    {
        $date = $this->attributes['event_date_1'] ?? $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        return date('F Y', strtotime($date));
    }

    /**
     * Event time 1
     */
    public function getEventTime1()
    {
        if (!empty($this->attributes['event_time_1'])) {
            return $this->attributes['event_time_1'];
        }
        $weddingTime = $this->getWeddingTime();
        $weddingDate = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        $endTime = date('H:i', strtotime($weddingDate . ' +2 hours'));
        return $weddingTime . ' - ' . $endTime;
    }

    /**
     * Event date 2 (hari saja)
     */
    public function getEventDate2()
    {
        $date = $this->attributes['event_date_2'] ?? $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('d', strtotime($date));
    }

    /**
     * Event month 2 (bulan dan tahun)
     */
    public function getEventMonth2()
    {
        $date = $this->attributes['event_date_2'] ?? $this->attributes['reception_date'] ?? '2025-05-05 15:00:00';
        return date('F Y', strtotime($date));
    }

    /**
     * Event time 2
     */
    public function getEventTime2()
    {
        if (!empty($this->attributes['event_time_2'])) {
            return $this->attributes['event_time_2'];
        }
        $receptionTime = $this->getReceptionTime();
        $receptionEndTime = $this->attributes['reception_end_time'] ?? '17:00';
        return $receptionTime . ' - ' . $receptionEndTime;
    }

    /**
     * Calendar URL untuk Google Calendar
     */
    public function getCalendarUrl()
    {
        if (!empty($this->attributes['calendar_url'])) {
            return $this->attributes['calendar_url'];
        }
        
        $coupleNames = $this->getCoupleNames();
        $weddingDate = $this->attributes['wedding_date'] ?? '2025-05-05 08:00:00';
        $startDate = date('Ymd\THis', strtotime($weddingDate));
        $endDate = date('Ymd\THis', strtotime($weddingDate . ' +2 hours'));
        
        return 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . urlencode('The Wedding of ' . $coupleNames) . 
               '&dates=' . $startDate . '/' . $endDate . '&ctz=Asia/Jakarta';
    }

    /**
     * Guestbook list URL
     */
    public function getGuestbookListUrl()
    {
        $invitationId = $this->attributes['id'] ?? 0;
        $invitationSlug = $this->attributes['slug'] ?? '';
        
        $param = '';
        if (!empty($invitationId)) {
            $param = '?invitation_id=' . $invitationId;
        } elseif (!empty($invitationSlug)) {
            $param = '?slug=' . $invitationSlug;
        }
        
        return base_url('guestbook' . $param);
    }

    /**
     * Invitation URL
     */
    public function getInvitationUrl()
    {
        if (!empty($this->attributes['invitation_url'])) {
            return $this->attributes['invitation_url'];
        }
        
        $slug = $this->attributes['slug'] ?? '';
        if (!empty($slug)) {
            return base_url($slug);
        }
        
        // Fallback ke current URL
        if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
            return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        
        return base_url();
    }

    /**
     * Language code (default: id)
     */
    public function getLang()
    {
        return $this->attributes['lang'] ?? $this->attributes['language'] ?? 'id';
    }

    /**
     * Meta description dengan fallback
     */
    public function getMetaDescription()
    {
        if (!empty($this->attributes['meta_description'])) {
            return $this->attributes['meta_description'];
        }
        return $this->getCoupleNames() . ' Digital Invitation';
    }

    /**
     * Escape URL untuk HTML attributes
     */
    public function escUrl($url)
    {
        if (empty($url)) {
            return '';
        }
        // Decode HTML entities terlebih dahulu jika ada
        $url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');
        // Decode URL encoding jika ada
        $url = urldecode($url);
        // htmlspecialchars dengan ENT_QUOTES tidak akan meng-encode slash (/)
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Tentukan waktu sapaan berdasarkan jam saat ini
     */
    public function getGreeting()
    {
        $hour = (int)date('H');
        
        if ($hour >= 5 && $hour < 12) {
            return 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 19) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }

    /**
     * Get Our Stories data
     */
    public function getOurStories()
    {
        return $this->attributes['our_stories'] ?? [];
    }

    /**
     * Magic method untuk akses property - fallback ke attributes
     * CodeIgniter 4 Entity sudah mendukung akses property langsung
     * Method ini memastikan akses ke attributes berfungsi dengan baik
     */
    public function __get($key)
    {
        // Coba akses via parent (untuk magic getter seperti getTitle(), dll)
        try {
            $result = parent::__get($key);
            // Jika parent mengembalikan null tapi attribute ada, gunakan attribute
            if ($result === null && isset($this->attributes[$key])) {
                return $this->attributes[$key];
            }
            return $result;
        } catch (\Error | \Exception $e) {
            // Jika tidak ada, coba akses langsung ke attributes
            if (isset($this->attributes[$key])) {
                return $this->attributes[$key];
            }
            return null;
        }
    }

    /**
     * Magic method untuk isset check
     */
    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]) || parent::__isset($key);
    }
}

