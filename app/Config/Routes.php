<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');

$routes->get('admin', 'Admin\Invitation::dashboard');
$routes->group('admin', function($routes) {
    $routes->get('invitation/dashboard', 'Admin\Invitation::dashboard');
    $routes->get('invitation/guide', 'Admin\Invitation::guide');
    $routes->get('invitation', 'Admin\Invitation::index');
    $routes->get('invitation/create', 'Admin\Invitation::create');
    $routes->post('invitation/store', 'Admin\Invitation::store');
    $routes->get('invitation/edit/(:num)', 'Admin\Invitation::edit/$1');
    $routes->post('invitation/update/(:num)', 'Admin\Invitation::update/$1');
    $routes->get('invitation/delete/(:num)', 'Admin\Invitation::delete/$1');
    $routes->post('invitation/bulk-delete', 'Admin\Invitation::bulkDelete');
    $routes->get('invitation/duplicate/(:num)', 'Admin\Invitation::duplicate/$1');
    $routes->post('invitation/toggle-status/(:num)', 'Admin\Invitation::toggleStatus/$1');
    $routes->get('invitation/reset-views/(:num)', 'Admin\Invitation::resetViews/$1');
    $routes->get('invitation/export-json/(:num)', 'Admin\Invitation::exportJson/$1');
    $routes->post('invitation/import-json/(:num)', 'Admin\Invitation::importJson/$1');
    $routes->post('invitation/generate-checkin-card', 'Admin\Invitation::generateCheckInCard');
    $routes->get('invitation/get-qr-codes/(:num)', 'Admin\Invitation::getQrCodes/$1');
    
    // Template Management
    $routes->get('template', 'Admin\Template::index');
    $routes->post('template/create-folder', 'Admin\Template::createFolder');
    $routes->get('template/browse/(:segment)', 'Admin\Template::browse/$1');
    $routes->get('template/preview/(:segment)', 'Admin\Template::preview/$1');
    $routes->get('template/delete/(:segment)', 'Admin\Template::delete/$1');
    $routes->post('template/toggle-active/(:segment)', 'Admin\Template::toggleActive/$1');
    $routes->get('template/assets/(:segment)/(:any)', 'Admin\Template::serveAsset/$1/$2');
    
    $routes->get('asset', 'Admin\Asset::index');
    $routes->get('asset/create', 'Admin\Asset::create');
    $routes->post('asset/store', 'Admin\Asset::store');
    $routes->get('asset/edit/(:num)', 'Admin\Asset::edit/$1');
    $routes->post('asset/update/(:num)', 'Admin\Asset::update/$1');
    $routes->get('asset/delete/(:num)', 'Admin\Asset::delete/$1');
    $routes->post('asset/toggle-status/(:num)', 'Admin\Asset::toggleStatus/$1');
    
    $routes->get('prompt', 'Admin\Prompt::index');
    
    // RSVP Management (termasuk Guestbook/Ucapan)
    $routes->get('rsvp', 'Admin\Rsvp::index');
    $routes->get('rsvp/detail/(:num)', 'Admin\Rsvp::detail/$1');
    $routes->get('rsvp/delete/(:num)', 'Admin\Rsvp::delete/$1');
    $routes->get('rsvp/delete-guestbook/(:num)', 'Admin\Rsvp::deleteGuestbook/$1');
    
    // Template Image Upload
    $routes->post('template-image/upload', 'Admin\TemplateImage::upload');
    $routes->post('template-image/delete', 'Admin\TemplateImage::delete');
    $routes->get('template-image/list', 'Admin\TemplateImage::list');
    
    // Invitation Preview Realtime
    $routes->post('invitation/preview-realtime', 'Admin\Invitation::previewRealtime');
    $routes->post('invitation/preview-realtime/(:num)', 'Admin\Invitation::previewRealtime/$1');
});

// Route untuk guestbook/wishes
$routes->get('guestbook', 'Guestbook::index');
$routes->post('guestbook', 'Guestbook::store');

// Route untuk RSVP
$routes->post('rsvp', 'Rsvp::store');

// Route untuk user panel (tanpa login)
$routes->group('user', function($routes) {
    $routes->get('(:segment)', 'User\Invitation::index/$1');
    $routes->post('(:segment)/scan-qr', 'User\Invitation::scanQrCode/$1');
    $routes->post('(:segment)/update', 'User\Invitation::updateInvitation/$1');
});

// Route untuk check-in page
$routes->get('checkin/(:segment)', 'User\CheckIn::index/$1');
$routes->post('checkin/(:segment)', 'User\CheckIn::submit/$1');

// Debug route untuk cek assets template templates1
$routes->get('debug/templates/templates1', 'TemplateCheck::templates1');

$routes->get('templates/(:segment)/(:any)', function($slug, $path) {
    $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
    $filePath = ROOTPATH . 'templates' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $path;
    
    if (!file_exists($filePath) || !is_file($filePath)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
    
    $mimeType = mime_content_type($filePath);
    
    $response = service('response');
    $response->setHeader('Content-Type', $mimeType);
    $response->setHeader('Cache-Control', 'public, max-age=31536000');
    $response->setBody(file_get_contents($filePath));
    
    return $response;
});

// Route untuk preview undangan (harus di akhir agar tidak konflik dengan route admin)
// URL: domain/slug (bukan domain/preview/slug)
$routes->get('(:segment)', 'Preview::index/$1', ['as' => 'invitation_view']);
