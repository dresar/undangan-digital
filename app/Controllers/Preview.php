<?php

namespace App\Controllers;

use App\Models\InvitationModel;
use App\Models\TemplateModel;
use App\Entities\InvitationEntity;

class Preview extends BaseController
{
    protected $invitationModel;
    protected $templateModel;

    public function __construct()
    {
        $this->invitationModel = new InvitationModel();
        $this->templateModel = new TemplateModel();
    }

    public function index($slug)
    {
        // Cek apakah ini bukan route yang sudah ada (admin, assets, dll)
        $excludedRoutes = ['admin', 'assets', 'preview', 'api', 'writable', 'index.php', 'favicon.ico', 'robots.txt', '.well-known'];
        if (in_array(strtolower($slug), $excludedRoutes)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Cek apakah slug mengandung karakter yang tidak valid untuk URL
        if (preg_match('/[^a-z0-9\-]/i', $slug)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $invitation = $this->invitationModel->findBySlug($slug);

        if (!$invitation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($invitation['status'] !== 'published') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->invitationModel->incrementViews($invitation['id']);

        // Jika ada template_id, render menggunakan template
        if (!empty($invitation['template_id']) && $invitation['template_id'] > 0) {
            return $this->renderTemplate($invitation);
        }

        // Jika tidak ada template, gunakan view default
        $contentJson = json_decode($invitation['content_json'], true);
        $themeConfig = json_decode($invitation['theme_config'] ?? '{}', true);

        $data = [
            'invitation' => $invitation,
            'content' => $contentJson ?? [],
            'theme' => $themeConfig,
        ];

        return view('preview/index', $data);
    }

    protected function renderTemplate($invitation)
    {
        try {
            $template = $this->templateModel->find($invitation['template_id']);
            if (!$template) {
                throw new \Exception('Template tidak ditemukan. Template ID: ' . $invitation['template_id']);
            }

            $templateSlug = $template['slug'] ?? '';
            if (empty($templateSlug)) {
                throw new \Exception('Template slug tidak ditemukan. Template ID: ' . $invitation['template_id']);
            }
            
            $templatePath = ROOTPATH . 'templates' . DIRECTORY_SEPARATOR . $templateSlug . DIRECTORY_SEPARATOR;
            if (!is_dir($templatePath)) {
                throw new \Exception('Template path tidak ditemukan. Template Slug: ' . $templateSlug);
            }

            $indexFile = $templatePath . 'index.php';
            if (!file_exists($indexFile)) {
                throw new \Exception('File index.php tidak ditemukan di template: ' . $indexFile);
            }

            $parser = \Config\Services::parser();
            $contentData = $this->parseContentData($invitation['content_data'] ?? '{}');
            $templateBase = base_url('templates/' . $templateSlug . '/');
            
            $parserData = $this->prepareParserData($contentData, $invitation, $templateBase, $templateSlug);
            $parser->setData($parserData);
            
            $templateContent = file_get_contents($indexFile);
            
            if (preg_match('/<\?php/i', $templateContent)) {
                throw new \Exception('ERROR: Template tidak boleh mengandung PHP code. Gunakan Parser syntax {variable_name} saja.');
            }
            
            $rendered = $parser->renderString($templateContent);
            return $this->response->setBody($rendered);
            
        } catch (\Exception $e) {
            return $this->response->setBody($this->getErrorHtml($e->getMessage()));
        }
    }

    protected function parseContentData($jsonString)
    {
        if (empty($jsonString) || $jsonString === '{}' || $jsonString === '[]') {
            return [];
        }
        
        $contentData = json_decode($jsonString, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'JSON decode error in content_data: ' . json_last_error_msg());
            return [];
        }
        
        return is_array($contentData) ? $contentData : [];
    }

    protected function prepareParserData($contentData, $invitation, $templateBase, $templateSlug)
    {
        $defaults = $this->getDefaultValues($templateBase);
        
        $data = [
            'template_base' => $templateBase,
            'invitation_id' => $invitation['id'] ?? 0,
            'invitation_url' => base_url($invitation['slug'] ?? ''),
            'base_url_guestbook' => base_url('guestbook'),
            'base_url_rsvp' => base_url('rsvp'),
        ];

        $data = array_merge($data, $this->prepareBasicData($contentData, $defaults));
        $data = array_merge($data, $this->prepareDateData($contentData, $defaults));
        $data = array_merge($data, $this->prepareImageData($contentData, $templateBase, $defaults));
        $data = array_merge($data, $this->prepareHtmlComponents($contentData, $invitation, $templateBase));
        
        return $data;
    }

    protected function getDefaultValues($templateBase)
    {
        return [
            'lang' => 'id',
            'title' => 'The Wedding',
            'meta_description' => '',
            'groom_name' => 'John Doe',
            'bride_name' => 'Jane Smith',
            'wedding_location' => 'Rumah Panggung Darusalam Palembang',
            'event_name_1' => 'Akad Nikah',
            'event_name_2' => 'Resepsi',
            'event_time_1' => '08.00 WIB - Selesai',
            'event_time_2' => '11.00 WIB - Selesai',
            'wedding_day_name' => 'Sabtu',
            'wedding_day' => '14',
            'wedding_month_name' => 'Mei',
            'wedding_year' => '2024',
            'wedding_date_short' => '14 Mei 2024',
            'reception_day_name' => 'Sabtu',
            'reception_day' => '14',
            'reception_month_name' => 'Mei',
            'reception_year' => '2024',
            'groom_father_name' => '',
            'groom_mother_name' => '',
            'bride_father_name' => '',
            'bride_mother_name' => '',
            'gallery_images' => [
                $templateBase . 'images/0Q6epS1a8h4Y.jpg',
                $templateBase . 'images/ExagVBHvRYIK.jpg',
                $templateBase . 'images/qgsqr16v7qqV.jpg',
                $templateBase . 'images/atH4JI7TaJ6l.jpg',
                $templateBase . 'images/8OzjI2gJy4c0.jpg',
                $templateBase . 'images/QfxKVOONHMmN.jpg'
            ],
            'images' => [
                'og_image' => $templateBase . 'images/2nXhu6XABqFi.jpg',
                'groom_image' => $templateBase . 'images/0YbpLI0Yp4yP.jpg',
                'bride_image' => $templateBase . 'images/gEnMIFoo6DUI.jpg',
                'cover_image' => $templateBase . 'images/Cevt8jvKsmBE.jpg',
                'dress_code_image' => $templateBase . 'images/dImlTsDuBHpw.jpg',
                'check_in_qr_code_image' => $templateBase . 'images/Tq3YKZhig7F2.png',
            ],
            'music_url' => $templateBase . 'media/nyb9A4qcKkwc.mp3',
        ];
    }

    protected function prepareBasicData($contentData, $defaults)
    {
        return [
            'lang' => $contentData['lang'] ?? $defaults['lang'],
            'title' => $this->escape($contentData['title'] ?? $defaults['title']),
            'meta_description' => $this->escape($contentData['meta_description'] ?? $defaults['meta_description']),
            'groom_name' => $this->escape($contentData['groom_name'] ?? $defaults['groom_name']),
            'bride_name' => $this->escape($contentData['bride_name'] ?? $defaults['bride_name']),
            'wedding_location' => $this->escape($contentData['wedding_location'] ?? $defaults['wedding_location']),
            'event_name_1' => $this->escape($contentData['event_name_1'] ?? $defaults['event_name_1']),
            'event_name_2' => $this->escape($contentData['event_name_2'] ?? $defaults['event_name_2']),
            'event_time_1' => $this->escape($contentData['event_time_1'] ?? $defaults['event_time_1']),
            'event_time_2' => $this->escape($contentData['event_time_2'] ?? $defaults['event_time_2']),
            'groom_father_name' => $this->escape($contentData['groom_father_name'] ?? $defaults['groom_father_name']),
            'groom_mother_name' => $this->escape($contentData['groom_mother_name'] ?? $defaults['groom_mother_name']),
            'bride_father_name' => $this->escape($contentData['bride_father_name'] ?? $defaults['bride_father_name']),
            'bride_mother_name' => $this->escape($contentData['bride_mother_name'] ?? $defaults['bride_mother_name']),
            'calendar_url' => $contentData['calendar_url'] ?? '',
            'countdown_date_js' => $this->escape($contentData['countdown_date_js'] ?? ''),
        ];
    }

    protected function prepareDateData($contentData, $defaults)
    {
        return [
            'wedding_day_name' => $this->escape($contentData['wedding_day_name'] ?? $defaults['wedding_day_name']),
            'wedding_day' => $this->escape($contentData['wedding_day'] ?? $defaults['wedding_day']),
            'wedding_month_name' => $this->escape($contentData['wedding_month_name'] ?? $defaults['wedding_month_name']),
            'wedding_year' => $this->escape($contentData['wedding_year'] ?? $defaults['wedding_year']),
            'wedding_date_short' => $this->escape($contentData['wedding_date_short'] ?? $defaults['wedding_date_short']),
            'reception_day_name' => $this->escape($contentData['reception_day_name'] ?? $defaults['reception_day_name']),
            'reception_day' => $this->escape($contentData['reception_day'] ?? $defaults['reception_day']),
            'reception_month_name' => $this->escape($contentData['reception_month_name'] ?? $defaults['reception_month_name']),
            'reception_year' => $this->escape($contentData['reception_year'] ?? $defaults['reception_year']),
        ];
    }

    protected function prepareImageData($contentData, $templateBase, $defaults)
    {
        return [
            'og_image' => $contentData['og_image'] ?? $defaults['images']['og_image'],
            'groom_image' => $contentData['groom_image'] ?? $defaults['images']['groom_image'],
            'bride_image' => $contentData['bride_image'] ?? $defaults['images']['bride_image'],
            'cover_image' => $contentData['cover_image'] ?? $defaults['images']['cover_image'],
            'dress_code_image' => $contentData['dress_code_image'] ?? $defaults['images']['dress_code_image'],
            'check_in_qr_code_image' => $contentData['check_in_qr_code_image'] ?? $defaults['images']['check_in_qr_code_image'],
            'music_url' => $contentData['music_url'] ?? $defaults['music_url'],
        ];
    }

    protected function prepareHtmlComponents($contentData, $invitation, $templateBase)
    {
        $galleryImages = $contentData['gallery_images'] ?? [];
        if (empty($galleryImages)) {
            $galleryImages = [
                $templateBase . 'images/0Q6epS1a8h4Y.jpg',
                $templateBase . 'images/ExagVBHvRYIK.jpg',
                $templateBase . 'images/qgsqr16v7qqV.jpg',
                $templateBase . 'images/atH4JI7TaJ6l.jpg',
                $templateBase . 'images/8OzjI2gJy4c0.jpg',
                $templateBase . 'images/QfxKVOONHMmN.jpg'
            ];
        }

        $ourStoryModel = new \App\Models\OurStoryModel();
        $ourStories = $ourStoryModel->getByInvitationId($invitation['id']);

        return [
            'gallery_html' => $this->generateGalleryHtml($galleryImages),
            'video_html' => $this->generateVideoHtml($contentData['video_id'] ?? ''),
            'our_stories_html' => $this->generateOurStoriesHtml($ourStories),
            'groom_instagram_html' => $this->generateInstagramHtml($contentData['groom_instagram'] ?? '', 'groom'),
            'bride_instagram_html' => $this->generateInstagramHtml($contentData['bride_instagram'] ?? '', 'bride'),
            'livestream_html_1' => $this->generateLivestreamHtml($contentData['livestream_url'] ?? ''),
            'livestream_html_2' => $this->generateLivestreamHtml($contentData['livestream_url'] ?? ''),
            'location_map_html' => $this->generateLocationMapHtml($contentData['location_map_url'] ?? ''),
            'location_map_search_html' => $this->generateLocationMapSearchHtml($contentData['location_map_search'] ?? ''),
            'bank_info_html' => $this->generateBankInfoHtml($contentData, $templateBase),
            'wedding_filter_instagram_html' => $this->generateWeddingFilterInstagramHtml($contentData),
            'calendar_url_html' => $this->generateCalendarUrlHtml($contentData['calendar_url'] ?? ''),
        ];
    }

    protected function generateGalleryHtml($galleryImages)
    {
        if (empty($galleryImages)) {
            return '';
        }

        $gridClasses = [
            'col-lg-12 col-md-12 col-sm-12 col-12',
            'col-lg-6 col-md-6 col-sm-6 col-6',
            'col-lg-6 col-md-6 col-sm-6 col-6',
            'col-lg-12 col-md-12 col-sm-12 col-12',
            'col-lg-6 col-md-6 col-sm-6 col-6',
            'col-lg-6 col-md-6 col-sm-6 col-6'
        ];

        $html = '';
        foreach ($galleryImages as $idx => $imgUrl) {
            $gridClass = $gridClasses[$idx % 6] ?? 'col-lg-6 col-md-6 col-sm-6 col-6';
            $heightStyle = (($idx % 6) == 0 || ($idx % 6) == 3) ? 'height:200px!important;' : '';
            $escapedUrl = $this->escape($imgUrl, 'attr');
            
            $html .= '<li class="card-container m-b30 wow zoomIn p-1 ' . $gridClass . '" data-wow-duration="2s" data-wow-delay="0.2s" style="padding: 10px;margin-bottom: 0px;">';
            $html .= '<div class="dlab-box dlab-gallery-box" style="border: 2px solid #dd9f49;padding: 2px;">';
            $html .= '<div class="dlab-media dlab-img-overlay8 primary">';
            $html .= '<a href="javascript:void(0);"><img src="' . $escapedUrl . '" class="fit_pic" alt="Foto Galeri" style="' . $heightStyle . '"></a>';
            $html .= '<div class="overlay-bx"><div class="overlay-icon text-center">';
            $html .= '<div class="port-box"><span data-exthumbimage="' . $escapedUrl . '" data-src="' . $escapedUrl . '" class="check-km port-full la la-plus" title=""></span></div>';
            $html .= '</div></div></div></div></li>';
        }

        return $html;
    }

    protected function generateVideoHtml($videoId)
    {
        if (empty($videoId)) {
            return '';
        }

        $escaped = $this->escape($videoId, 'attr');
        $html = '<div class="col-lg-10 col-md-10 col-12 mb-4 videoPreWedding wow zoomIn" data-wow-duration="3s" data-wow-delay="0.3s" style="padding: 5px;">';
        $html .= '<div class="frameYoutube"><iframe width="560" height="315" src="https://www.youtube.com/embed/' . $escaped . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe></div></div>';
        
        return $html;
    }

    protected function generateOurStoriesHtml($ourStories)
    {
        if (empty($ourStories)) {
            return '';
        }

        $html = '';
        foreach ($ourStories as $story) {
            $year = $this->escape($story['year'] ?? '');
            $title = $this->escape($story['title'] ?? '');
            $text = $this->escape($story['story_text'] ?? '');
            $imageHtml = '';
            
            if (!empty($story['story_image'])) {
                $imageUrl = $this->escape($story['story_image'], 'attr');
                $imageHtml = '<img src="' . $imageUrl . '" style="max-height: 250px;max-width: 100%;width: 100%;margin-bottom: 20px;border: 2px solid #dd9f49;padding: 2px;object-fit: cover!important;">';
            }
            
            $html .= '<div class="timeline-row">';
            $html .= '<div class="timeline-content wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s">';
            $html .= '<div class="themeWebOrnThree" style="z-index: 2"><div class="ornThree" style="height: 40%;"></div></div>';
            $html .= '<div class="timeline-time w-100 text-left text-light" style="font-size: 15px!important;margin-bottom: 7px;">' . $year . '</div>';
            $html .= '<div class="timeline-time w-100 text-left text-light">' . $title . '</div>';
            $html .= $imageHtml;
            $html .= '<p class="text-left text-light" style="font-size: 15px;color:#282828;">' . $text . '</p>';
            $html .= '</div></div>';
        }

        return $html;
    }

    protected function generateInstagramHtml($instagram, $type = 'groom')
    {
        if (empty($instagram)) {
            return '';
        }

        $escaped = $this->escape($instagram, 'attr');
        $html = '<div class="wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.3s">';
        $html .= '<a href="https://www.instagram.com/' . $escaped . '/" class="mt-2 btn btn-outline btnColor btn-sm mb-2" style="border-radius: 30px;border:0px;padding: 10px;padding-top: 5px!important;font-size: 15px;" target="_blank">';
        $html .= '<i class="fa fa-instagram" style="position: relative;top:2px;"></i> ' . $this->escape($instagram) . '</a></div>';
        
        return $html;
    }

    protected function generateLivestreamHtml($livestreamUrl)
    {
        if (empty($livestreamUrl)) {
            return '';
        }

        $escaped = $this->escape($livestreamUrl, 'attr');
        $html = '<div class="wow fadeInUp" data-wow-duration="3s" data-wow-delay="0.3s">';
        $html .= '<a href="' . $escaped . '" class="btn btn-outline btnColor btn-sm mb-2" style="border-radius: 30px;border:0px;padding: 10px;padding-top:5px!important;font-size: 15px;" target="_blank">';
        $html .= '<i class="fa fa-video-camera"></i> Live Streaming</a></div>';
        
        return $html;
    }

    protected function generateLocationMapHtml($mapUrl)
    {
        if (empty($mapUrl)) {
            return '';
        }

        $escaped = $this->escape($mapUrl, 'attr');
        $html = '<div class="mapGoogle business-solution text-center">';
        $html .= '<iframe src="' . $escaped . '" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>';
        
        return $html;
    }

    protected function generateLocationMapSearchHtml($searchUrl)
    {
        if (empty($searchUrl)) {
            return '';
        }

        $escaped = $this->escape($searchUrl, 'attr');
        $html = '<div class="wow slideInUp" data-wow-duration="3s" data-wow-delay="0.3s">';
        $html .= '<div class="text-center" style="margin-top: 5px!important;">';
        $html .= '<a href="' . $escaped . '" class="btn btn-outline btn-block btnColor" style="border:0px;" target="_blank">';
        $html .= '<i class="fa fa-location-arrow"></i> Lihat Lokasi Acara</a></div></div>';
        
        return $html;
    }

    protected function generateBankInfoHtml($contentData, $templateBase)
    {
        if (empty($contentData['bank_name'])) {
            return '';
        }

        $accountNumber = $this->escape($contentData['bank_account_number'] ?? '');
        $accountName = $this->escape($contentData['bank_account_name'] ?? '');
        
        $html = '<div><img src="' . $templateBase . 'images/av69XVCB8D30.png" style="max-height:85px;"></div>';
        $html .= '<div class="mb-2" style="background: #f5f5f5;padding: 10px;max-width: 300px!important;margin-right: auto;margin-left: auto;border-radius: 7px;">';
        $html .= '<h6 class="mb-0">' . $accountNumber . '<br> a/n ' . $accountName . '</h6></div>';
        $html .= '<div class="text-center mb-4" style="padding-top: 0px;max-width: 300px!important;margin-right: auto;margin-left: auto;">';
        $html .= '<button class="btn btn-outline" onclick="copasLink(\'' . $accountNumber . '\')" style="background: #2d2222!important;border: 0px solid #2d2222;background-image: -webkit-linear-gradient(top, #dedede, #2d2222);background-image: linear-gradient(top, #dedede, #fff);border-radius: 10px;box-shadow: 0px 3px 10px 0px rgb(0 0 0 / 50%), inset 0px 4px 1px 1px #2d2222, inset 0px -3px 1px 1px rgb(183 180 180 / 50%);width: 100%;padding:10px;">';
        $html .= '<i class="fa fa-copy"></i> Salin Nomor Rekening</button></div>';
        
        return $html;
    }

    protected function generateWeddingFilterInstagramHtml($contentData)
    {
        $groomIg = '';
        $brideIg = '';

        if (!empty($contentData['groom_instagram'])) {
            $escaped = $this->escape($contentData['groom_instagram'], 'attr');
            $groomIg = '<span class="d-block mb-2"><a href="https://instagram.com/' . $escaped . '" target="_blank" style="border-bottom-width: 1px;border-bottom-style: solid;padding-bottom: 2px;" class="text-light"><i class="fa fa-instagram"></i> @' . $this->escape($contentData['groom_instagram']) . '</a></span>';
        }

        if (!empty($contentData['bride_instagram'])) {
            $escaped = $this->escape($contentData['bride_instagram'], 'attr');
            $brideIg = '<span class="d-block"><a href="https://instagram.com/' . $escaped . '" target="_blank" style="border-bottom-width: 1px;border-bottom-style: solid;padding-bottom: 2px;" class="text-light"><i class="fa fa-instagram"></i> @' . $this->escape($contentData['bride_instagram']) . '</a></span>';
        }

        return $groomIg . $brideIg;
    }

    protected function escape($value, $context = 'html')
    {
        if ($context === 'attr') {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    protected function generateCalendarUrlHtml($calendarUrl)
    {
        if (empty($calendarUrl)) {
            return '';
        }

        return '<div class="col-lg-12 text-center wow fadeInUp" data-wow-duration="3s"
                    data-wow-delay="0.4s" style="margin-top: 20px!important;"> <a
                        href="' . $this->escape($calendarUrl, 'html') . '"
                        class="btn btn-outline btnColor btn-sm"
                        style="border-radius: 30px;border:0px;padding: 10px;font-size: 15px;"
                        target="_blank"><i class="fa fa-calendar-check-o"></i> Ingatkan via Google
                        Kalender</a> </div>';
    }

    protected function getErrorHtml($message)
    {
        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Preview Undangan</title>
    <link href="' . base_url('assets/css/tailwind.css') . '" rel="stylesheet">
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto bg-red-50 border border-red-200 rounded-lg p-6">
        <h1 class="text-2xl font-bold text-red-600 mb-4">Error Loading Template</h1>
        <p class="text-gray-700">' . esc($message) . '</p>
    </div>
</body>
</html>';
    }
}

