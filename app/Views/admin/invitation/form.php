<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= $invitation ? 'Edit' : 'Buat' ?> Undangan - Admin Panel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto p-6 pb-20">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800"><?= $invitation ? 'Edit' : 'Buat' ?> Undangan</h2>
        <a href="<?= base_url('admin/invitation') ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <div>
                <strong>Error:</strong> <?= esc(session()->getFlashdata('error')) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div>
                <strong>Validation Errors:</strong>
                <ul class="list-disc list-inside mt-2">
                    <?php foreach (session()->getFlashdata('errors') as $field => $error): ?>
                    <li><?= esc($field) ?>: <?= esc(is_array($error) ? implode(', ', $error) : $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    </div>
    <?php endif; ?>

    <form method="post" action="<?= $invitation ? base_url('admin/invitation/update/' . $invitation['id']) : base_url('admin/invitation/store') ?>" id="invitationForm" enctype="multipart/form-data">
        <!-- Template Selection -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-palette mr-2 text-blue-600"></i>Pilih Template
            </h3>
            <div class="mb-4">
                <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Template <span class="text-red-500">*</span>
                </label>
                <select class="w-full px-4 py-3 border-2 border-blue-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg" id="template_id" name="template_id" required>
                    <option value="">-- Pilih Template --</option>
                    <?php if (!empty($templates)): ?>
                    <?php foreach ($templates as $template): ?>
                    <option value="<?= $template['id'] ?>" <?= ($invitation['template_id'] ?? '') == $template['id'] ? 'selected' : '' ?>>
                        <?= esc($template['name']) ?>
                        <?php if ($template['is_premium'] == 1): ?>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded ml-1">Premium</span>
                        <?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Template menentukan tampilan undangan Anda. Pilih template terlebih dahulu sebelum mengisi data.
                </p>
            </div>
        </div>

        <!-- Informasi Dasar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informasi Dasar
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="title" name="title" value="<?= esc($invitation['title'] ?? '') ?>" required>
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="slug" name="slug" value="<?= esc($invitation['slug'] ?? '') ?>" required>
                    <p class="mt-1 text-sm text-gray-500">URL: <?= base_url('') ?><span id="slugPreview" class="font-mono"><?= esc($invitation['slug'] ?? 'slug') ?></span></p>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="status" name="status">
                        <option value="draft" <?= ($invitation['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($invitation['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="category" name="category" value="<?= esc($invitation['category'] ?? '') ?>" placeholder="Pernikahan, Khitanan, dll">
                </div>
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="tags" name="tags" value="<?= esc($invitation['tags'] ?? '') ?>" placeholder="tag1, tag2, tag3">
                    <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma</p>
                </div>
                <div>
                    <label for="is_featured" class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="is_featured" name="is_featured">
                        <option value="0" <?= ($invitation['is_featured'] ?? 0) == 0 ? 'selected' : '' ?>>Tidak</option>
                        <option value="1" <?= ($invitation['is_featured'] ?? 0) == 1 ? 'selected' : '' ?>>Ya</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pasangan & Acara -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-heart mr-2 text-blue-600"></i>Informasi Pasangan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="groom_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Mempelai Pria</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="groom_name" name="groom_name" value="<?= esc($invitation['groom_name'] ?? '') ?>">
                </div>
                <div>
                    <label for="bride_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Mempelai Wanita</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bride_name" name="bride_name" value="<?= esc($invitation['bride_name'] ?? '') ?>">
                </div>
                <div>
                    <label for="groom_parents" class="block text-sm font-medium text-gray-700 mb-2">Orang Tua Mempelai Pria</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="groom_parents" name="groom_parents" value="<?= esc($invitation['groom_parents'] ?? '') ?>" placeholder="Bapak ... & Ibu ...">
                </div>
                <div>
                    <label for="bride_parents" class="block text-sm font-medium text-gray-700 mb-2">Orang Tua Mempelai Wanita</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bride_parents" name="bride_parents" value="<?= esc($invitation['bride_parents'] ?? '') ?>" placeholder="Bapak ... & Ibu ...">
                </div>
                <div>
                    <label for="groom_instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram Mempelai Pria</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="groom_instagram" name="groom_instagram" value="<?= esc($invitation['groom_instagram'] ?? '') ?>" placeholder="https://instagram.com/username">
                </div>
                <div>
                    <label for="bride_instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram Mempelai Wanita</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bride_instagram" name="bride_instagram" value="<?= esc($invitation['bride_instagram'] ?? '') ?>" placeholder="https://instagram.com/username">
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Informasi Acara</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="wedding_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Acara Pernikahan</label>
                    <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="wedding_date" name="wedding_date" value="<?= !empty($invitation['wedding_date']) ? date('Y-m-d\TH:i', strtotime($invitation['wedding_date'])) : '' ?>">
                </div>
                <div>
                    <label for="reception_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Resepsi</label>
                    <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="reception_date" name="reception_date" value="<?= !empty($invitation['reception_date']) ? date('Y-m-d\TH:i', strtotime($invitation['reception_date'])) : '' ?>">
                </div>
                <div>
                    <label for="reception_end_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai Resepsi</label>
                    <input type="time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="reception_end_time" name="reception_end_time" value="<?= esc($invitation['reception_end_time'] ?? '') ?>">
                </div>
                <div>
                    <label for="countdown_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Countdown</label>
                    <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="countdown_date" name="countdown_date" value="<?= !empty($invitation['countdown_date']) ? date('Y-m-d\TH:i', strtotime($invitation['countdown_date'])) : '' ?>">
                    <p class="mt-1 text-sm text-gray-500">Kosongkan untuk menggunakan tanggal acara</p>
                </div>
                <div>
                    <label for="wedding_location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Acara</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="wedding_location" name="wedding_location" value="<?= esc($invitation['wedding_location'] ?? '') ?>" placeholder="Nama Venue">
                </div>
                <div class="md:col-span-2">
                    <label for="wedding_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="wedding_address" name="wedding_address" rows="3"><?= esc($invitation['wedding_address'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="location_map_url" class="block text-sm font-medium text-gray-700 mb-2">URL Google Maps (Embed)</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="location_map_url" name="location_map_url" value="<?= esc($invitation['location_map_url'] ?? '') ?>" placeholder="https://maps.google.com/embed?q=...">
                </div>
                <div>
                    <label for="location_map_search" class="block text-sm font-medium text-gray-700 mb-2">URL Google Maps (Search)</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="location_map_search" name="location_map_search" value="<?= esc($invitation['location_map_search'] ?? '') ?>" placeholder="https://www.google.com/maps/search/?api=1&query=...">
                </div>
                <div>
                    <label for="calendar_url" class="block text-sm font-medium text-gray-700 mb-2">URL Google Calendar</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="calendar_url" name="calendar_url" value="<?= esc($invitation['calendar_url'] ?? '') ?>" placeholder="https://www.google.com/calendar/render?action=TEMPLATE&...">
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Kontak</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="tel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="contact_phone" name="contact_phone" value="<?= esc($invitation['contact_phone'] ?? '') ?>">
                </div>
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="contact_email" name="contact_email" value="<?= esc($invitation['contact_email'] ?? '') ?>">
                </div>
                <div>
                    <label for="contact_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="contact_whatsapp" name="contact_whatsapp" value="<?= esc($invitation['contact_whatsapp'] ?? '') ?>" placeholder="6281234567890">
                </div>
            </div>
        </div>

        <!-- Gambar & Media -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-images mr-2 text-blue-600"></i>Upload Gambar
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cover_image_file" class="block text-sm font-medium text-gray-700 mb-2">Cover Image <span class="text-red-500">*</span></label>
                    <input type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="cover_image_file" name="cover_image_file" onchange="handleFileUpload(this, 'cover_image')">
                    <input type="hidden" id="cover_image" name="cover_image" value="<?= esc($invitation['cover_image'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: JPG, PNG, GIF, WebP, SVG | Maksimal: 1MB</p>
                    <div class="mt-2 <?= empty($invitation['cover_image']) ? 'hidden' : '' ?>" id="cover_image_preview_container">
                        <img src="<?= esc($invitation['cover_image'] ?? '', 'attr') ?>" alt="Cover" class="w-full h-32 object-cover rounded border border-gray-200" id="cover_image_preview">
                    </div>
                </div>
                <div>
                    <label for="groom_image_file" class="block text-sm font-medium text-gray-700 mb-2">Gambar Mempelai Pria</label>
                    <input type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="groom_image_file" name="groom_image_file" onchange="handleFileUpload(this, 'groom_image')">
                    <input type="hidden" id="groom_image" name="groom_image" value="<?= esc($invitation['groom_image'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: JPG, PNG, GIF, WebP, SVG | Maksimal: 1MB</p>
                    <div class="mt-2 <?= empty($invitation['groom_image']) ? 'hidden' : '' ?>" id="groom_image_preview_container">
                        <img src="<?= esc($invitation['groom_image'] ?? '', 'attr') ?>" alt="Groom" class="w-full h-32 object-cover rounded border border-gray-200" id="groom_image_preview">
                    </div>
                </div>
                <div>
                    <label for="bride_image_file" class="block text-sm font-medium text-gray-700 mb-2">Gambar Mempelai Wanita</label>
                    <input type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="bride_image_file" name="bride_image_file" onchange="handleFileUpload(this, 'bride_image')">
                    <input type="hidden" id="bride_image" name="bride_image" value="<?= esc($invitation['bride_image'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: JPG, PNG, GIF, WebP, SVG | Maksimal: 1MB</p>
                    <div class="mt-2 <?= empty($invitation['bride_image']) ? 'hidden' : '' ?>" id="bride_image_preview_container">
                        <img src="<?= esc($invitation['bride_image'] ?? '', 'attr') ?>" alt="Bride" class="w-full h-32 object-cover rounded border border-gray-200" id="bride_image_preview">
                    </div>
                </div>
                <div>
                    <label for="dress_code_image_file" class="block text-sm font-medium text-gray-700 mb-2">Gambar Dress Code</label>
                    <input type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="dress_code_image_file" name="dress_code_image_file" onchange="handleFileUpload(this, 'dress_code_image')">
                    <input type="hidden" id="dress_code_image" name="dress_code_image" value="<?= esc($invitation['dress_code_image'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: JPG, PNG, GIF, WebP, SVG | Maksimal: 1MB</p>
                    <div class="mt-2 <?= empty($invitation['dress_code_image']) ? 'hidden' : '' ?>" id="dress_code_image_preview_container">
                        <img src="<?= esc($invitation['dress_code_image'] ?? '', 'attr') ?>" alt="Dress Code" class="w-full h-32 object-cover rounded border border-gray-200" id="dress_code_image_preview">
                    </div>
                </div>
                <div>
                    <label for="og_image_file" class="block text-sm font-medium text-gray-700 mb-2">OG Image (Social Media)</label>
                    <input type="file" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="og_image_file" name="og_image_file" onchange="handleFileUpload(this, 'og_image')">
                    <input type="hidden" id="og_image" name="og_image" value="<?= esc($invitation['og_image'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: JPG, PNG, GIF, WebP, SVG | Maksimal: 1MB</p>
                    <div class="mt-2 <?= empty($invitation['og_image']) ? 'hidden' : '' ?>" id="og_image_preview_container">
                        <img src="<?= esc($invitation['og_image'] ?? '', 'attr') ?>" alt="OG Image" class="w-full h-32 object-cover rounded border border-gray-200" id="og_image_preview">
                    </div>
                </div>
                <div>
                    <label for="dress_code" class="block text-sm font-medium text-gray-700 mb-2">Dress Code</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="dress_code" name="dress_code" value="<?= esc($invitation['dress_code'] ?? '') ?>" placeholder="Any kind of pastel">
                </div>
                <div class="md:col-span-2">
                    <label for="gallery_images_files" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-images mr-2 text-blue-600"></i>Gallery Images
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors">
                    <input type="file" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="gallery_images_files" name="gallery_images_files[]" onchange="handleGalleryUpload(this)">
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format: JPG, PNG, GIF, WebP, SVG | Maksimal: 1MB per gambar | Pilih beberapa gambar sekaligus
                        </p>
                    </div>
                    <?php 
                    // Prepare gallery images untuk hidden input (join dengan newline)
                    $galleryImagesForInput = [];
                    if (!empty($invitation['gallery_images'])) {
                        if (is_array($invitation['gallery_images'])) {
                            $galleryImagesForInput = $invitation['gallery_images'];
                        } else {
                            $gallery = json_decode($invitation['gallery_images'], true);
                            if (is_array($gallery) && !empty($gallery)) {
                                $galleryImagesForInput = $gallery;
                            } elseif (is_string($invitation['gallery_images']) && !empty(trim($invitation['gallery_images']))) {
                                // Jika string biasa (bukan JSON), split dengan newline atau koma
                                $galleryImagesForInput = array_filter(array_map('trim', preg_split('/[\n\r,]+/', $invitation['gallery_images'])));
                            }
                        }
                    }
                    // Pastikan URL tidak ter-encode ganda
                    $galleryImagesValue = !empty($galleryImagesForInput) ? implode("\n", array_map(function($url) { 
                        // Decode jika ter-encode
                        $decoded = $url;
                        if (strpos($url, '%') !== false || strpos($url, '&amp;') !== false) {
                            $decoded = html_entity_decode(urldecode($url), ENT_QUOTES, 'UTF-8');
                        }
                        // Jika masih ter-encode dengan format http/x3A/x2F, decode lagi
                        if (strpos($decoded, 'http/x3A') !== false || strpos($decoded, 'x2F') !== false) {
                            $decoded = rawurldecode(str_replace(['x3A', 'x2F'], [':', '/'], $decoded));
                        }
                        return esc($decoded, 'attr');
                    }, $galleryImagesForInput)) : '';
                    ?>
                    <input type="hidden" id="gallery_images" name="gallery_images" value="<?= $galleryImagesValue ?>">
                    
                    <div id="gallery_preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-4">
                        <?php 
                        $galleryImages = [];
                        if (!empty($invitation['gallery_images'])) {
                            if (is_array($invitation['gallery_images'])) {
                                $galleryImages = $invitation['gallery_images'];
                            } else {
                                $gallery = json_decode($invitation['gallery_images'], true);
                                if (is_array($gallery) && !empty($gallery)) {
                                    $galleryImages = $gallery;
                                } elseif (is_string($invitation['gallery_images']) && !empty(trim($invitation['gallery_images']))) {
                                    $galleryImages = array_filter(array_map('trim', preg_split('/[\n\r,]+/', $invitation['gallery_images'])));
                                }
                            }
                        }
                        if (!empty($galleryImages)):
                        foreach ($galleryImages as $index => $img): 
                        ?>
                        <div class="relative group gallery-item" data-url="<?= esc($img, 'attr') ?>">
                            <div class="aspect-square rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-400 transition-all">
                                <img src="<?= esc($img, 'attr') ?>" alt="Gallery <?= $index + 1 ?>" class="w-full h-full object-cover">
                            </div>
                            <button type="button" onclick="removeGalleryImage(<?= $index ?>)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-xs hover:bg-red-600 shadow-lg transition-all opacity-0 group-hover:opacity-100">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-xs px-2 py-1 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                Gallery <?= $index + 1 ?>
                        </div>
                    </div>
                        <?php 
                        endforeach;
                        else:
                        ?>
                        <div class="col-span-full text-center py-8 text-gray-400">
                            <i class="fas fa-images text-4xl mb-2 block"></i>
                            <p class="text-sm">Belum ada gambar. Upload gambar untuk menambahkannya ke gallery.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Media</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="music_file" class="block text-sm font-medium text-gray-700 mb-2">Upload Music File</label>
                    <input type="file" accept="audio/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="music_file" name="music_file" onchange="handleMusicUpload(this)">
                    <input type="hidden" id="music_url" name="music_url" value="<?= esc($invitation['music_url'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: MP3, WAV, OGG | Maksimal: 5MB</p>
                    <div class="mt-2 <?= empty($invitation['music_url']) ? 'hidden' : '' ?>" id="music_preview_container">
                        <audio controls class="w-full" id="music_preview" src="<?= esc($invitation['music_url'] ?? '', 'attr') ?>">
                            Browser Anda tidak mendukung audio player.
                        </audio>
                    </div>
                </div>
                <div>
                    <label for="music_url" class="block text-sm font-medium text-gray-700 mb-2">Atau Music URL</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="music_url_input" name="music_url_input" value="<?= esc($invitation['music_url'] ?? '') ?>" placeholder="https://example.com/music.mp3" onchange="handleMusicUrlChange(this)">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika sudah upload file</p>
                </div>
                <div>
                    <label for="video_file" class="block text-sm font-medium text-gray-700 mb-2">Upload Video File</label>
                    <input type="file" accept="video/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2" id="video_file" name="video_file" onchange="handleVideoUpload(this)">
                    <input type="hidden" id="video_url" name="video_url" value="<?= esc($invitation['video_url'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mb-2">Format: MP4, WebM | Maksimal: 50MB</p>
                    <div class="mt-2 <?= empty($invitation['video_url']) ? 'hidden' : '' ?>" id="video_preview_container">
                        <video controls class="w-full h-48 object-cover rounded border border-gray-200" id="video_preview" src="<?= esc($invitation['video_url'] ?? '', 'attr') ?>">
                            Browser Anda tidak mendukung video player.
                        </video>
                    </div>
                </div>
                <div>
                    <label for="video_url_input" class="block text-sm font-medium text-gray-700 mb-2">Atau Video URL</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="video_url_input" name="video_url_input" value="<?= esc($invitation['video_url'] ?? '') ?>" placeholder="https://youtube.com/watch?v=..." onchange="handleVideoUrlChange(this)">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika sudah upload file</p>
                </div>
                <div>
                    <label for="video_id" class="block text-sm font-medium text-gray-700 mb-2">Video ID (YouTube)</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="video_id" name="video_id" value="<?= esc($invitation['video_id'] ?? '') ?>" placeholder="SzPrFMFqFwM">
                </div>
                <div>
                    <label for="livestream_url" class="block text-sm font-medium text-gray-700 mb-2">Livestream URL</label>
                    <input type="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="livestream_url" name="livestream_url" value="<?= esc($invitation['livestream_url'] ?? '') ?>" placeholder="https://youtube.com/watch/...">
                </div>
                <div>
                    <label for="livestream_id" class="block text-sm font-medium text-gray-700 mb-2">Livestream ID</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="livestream_id" name="livestream_id" value="<?= esc($invitation['livestream_id'] ?? '') ?>" placeholder="SzPrFMFqFwM">
                </div>
                <div>
                    <label for="livestream_schedule" class="block text-sm font-medium text-gray-700 mb-2">Jadwal Livestream</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="livestream_schedule" name="livestream_schedule" value="<?= esc($invitation['livestream_schedule'] ?? '') ?>" placeholder="10.00 - Acara Pernikahan">
                </div>
                <div class="md:col-span-2">
                    <label for="livestream_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Livestream</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="livestream_description" name="livestream_description" rows="2"><?= esc($invitation['livestream_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Konten & Pesan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-file-alt mr-2 text-blue-600"></i>Konten & Pesan
            </h3>
            <div class="space-y-6">
                <div>
                    <label for="story_text" class="block text-sm font-medium text-gray-700 mb-2">Cerita Pasangan</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="story_text" name="story_text" rows="6"><?= esc($invitation['story_text'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="cover_message" class="block text-sm font-medium text-gray-700 mb-2">Pesan Cover</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="cover_message" name="cover_message" value="<?= esc($invitation['cover_message'] ?? '') ?>" placeholder="We invite you to The Wedding of">
                </div>
                <div>
                    <label for="couple_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pasangan</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="couple_description" name="couple_description" rows="3"><?= esc($invitation['couple_description'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="venue_message" class="block text-sm font-medium text-gray-700 mb-2">Pesan Venue</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="venue_message" name="venue_message" rows="3"><?= esc($invitation['venue_message'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="apology_text" class="block text-sm font-medium text-gray-700 mb-2">Pesan Permohonan Maaf</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="apology_text" name="apology_text" rows="3"><?= esc($invitation['apology_text'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="thank_you_text" class="block text-sm font-medium text-gray-700 mb-2">Pesan Terima Kasih</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="thank_you_text" name="thank_you_text" rows="4"><?= esc($invitation['thank_you_text'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_name_1" class="block text-sm font-medium text-gray-700 mb-2">Nama Acara 1 <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="event_name_1" name="event_name_1" value="<?= esc($invitation['event_name_1'] ?? '') ?>" placeholder="Acara Pernikahan">
                    </div>
                    <div>
                        <label for="event_date_1" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Acara 1 <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="event_date_1" name="event_date_1" value="<?= !empty($invitation['event_date_1']) ? date('Y-m-d\TH:i', strtotime($invitation['event_date_1'])) : '' ?>">
                    </div>
                    <div>
                        <label for="event_time_1" class="block text-sm font-medium text-gray-700 mb-2">Waktu Acara 1 <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="event_time_1" name="event_time_1" value="<?= esc($invitation['event_time_1'] ?? '') ?>" placeholder="08:00 - 10:00">
                    </div>
                    <div>
                        <label for="event_name_2" class="block text-sm font-medium text-gray-700 mb-2">Nama Acara 2 <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="event_name_2" name="event_name_2" value="<?= esc($invitation['event_name_2'] ?? '') ?>" placeholder="Resepsi">
                    </div>
                    <div>
                        <label for="event_date_2" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Acara 2 <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="event_date_2" name="event_date_2" value="<?= !empty($invitation['event_date_2']) ? date('Y-m-d\TH:i', strtotime($invitation['event_date_2'])) : '' ?>">
                    </div>
                    <div>
                        <label for="event_time_2" class="block text-sm font-medium text-gray-700 mb-2">Waktu Acara 2 <span class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="event_time_2" name="event_time_2" value="<?= esc($invitation['event_time_2'] ?? '') ?>" placeholder="15:00 - 17:00">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaturan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-cog mr-2 text-blue-600"></i>Pengaturan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish</label>
                    <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="published_at" name="published_at" value="<?= !empty($invitation['published_at']) ? date('Y-m-d\TH:i', strtotime($invitation['published_at'])) : '' ?>">
                    <p class="mt-1 text-sm text-gray-500">Jadwalkan publish otomatis</p>
                </div>
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kadaluarsa</label>
                    <input type="datetime-local" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="expires_at" name="expires_at" value="<?= !empty($invitation['expires_at']) ? date('Y-m-d\TH:i', strtotime($invitation['expires_at'])) : '' ?>">
                    <p class="mt-1 text-sm text-gray-500">Undangan tidak akan bisa diakses setelah tanggal ini</p>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (Opsional)</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="password" name="password" value="<?= esc($invitation['password'] ?? '') ?>" placeholder="Kosongkan jika tidak perlu password">
                    <p class="mt-1 text-sm text-gray-500">Lindungi undangan dengan password</p>
                </div>
                <div>
                    <label for="max_views" class="block text-sm font-medium text-gray-700 mb-2">Max Views</label>
                    <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="max_views" name="max_views" value="<?= esc($invitation['max_views'] ?? 0) ?>" min="0">
                    <p class="mt-1 text-sm text-gray-500">0 = unlimited</p>
                </div>
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="language" name="language">
                        <option value="id" <?= ($invitation['language'] ?? 'id') === 'id' ? 'selected' : '' ?>>Indonesia</option>
                        <option value="en" <?= ($invitation['language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                    </select>
                </div>
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="timezone" name="timezone" value="<?= esc($invitation['timezone'] ?? 'Asia/Jakarta') ?>">
                </div>
                <div class="md:col-span-2">
                    <h6 class="text-lg font-semibold text-gray-800 mb-4">Fitur yang Diaktifkan</h6>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="rsvp_enabled" name="rsvp_enabled" value="1" <?= ($invitation['rsvp_enabled'] ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">RSVP Enabled</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="analytics_enabled" name="analytics_enabled" value="1" <?= ($invitation['analytics_enabled'] ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Analytics Enabled</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="social_sharing_enabled" name="social_sharing_enabled" value="1" <?= ($invitation['social_sharing_enabled'] ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Social Sharing</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="print_enabled" name="print_enabled" value="1" <?= ($invitation['print_enabled'] ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Print Enabled</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="qr_code_enabled" name="qr_code_enabled" value="1" <?= ($invitation['qr_code_enabled'] ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">QR Code</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="guestbook_enabled" name="guestbook_enabled" value="1" <?= ($invitation['guestbook_enabled'] ?? 0) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Guestbook</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sumbangan / Bank Account -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-university mr-2 text-blue-600"></i>Informasi Sumbangan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="flex items-center space-x-2 cursor-pointer mb-4">
                        <input type="checkbox" id="donation_enabled" name="donation_enabled" value="1" <?= ($invitation['donation_enabled'] ?? 0) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Aktifkan Sumbangan</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bank_name" name="bank_name" value="<?= esc($invitation['bank_name'] ?? '') ?>" placeholder="Contoh: BRI, BCA, Mandiri, dll">
                </div>
                <div>
                    <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bank_account_number" name="bank_account_number" value="<?= esc($invitation['bank_account_number'] ?? '') ?>" placeholder="Contoh: 1234567890">
                </div>
                <div>
                    <label for="bank_account_name" class="block text-sm font-medium text-gray-700 mb-2">Atas Nama</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bank_account_name" name="bank_account_name" value="<?= esc($invitation['bank_account_name'] ?? '') ?>" placeholder="Nama pemilik rekening">
                </div>
            </div>
        </div>

        <!-- SEO & Custom -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-search mr-2 text-blue-600"></i>SEO & Custom
            </h3>
            <div class="space-y-6">
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="meta_description" name="meta_description" rows="3"><?= esc($invitation['meta_description'] ?? '') ?></textarea>
                    <p class="mt-1 text-sm text-gray-500">Deskripsi untuk SEO (150-160 karakter)</p>
                </div>
                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="meta_keywords" name="meta_keywords" value="<?= esc($invitation['meta_keywords'] ?? '') ?>" placeholder="keyword1, keyword2, keyword3">
                    <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma</p>
                </div>
            </div>
        </div>

        <!-- Our Story Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-book-open mr-2 text-blue-600"></i>Our Story
            </h3>
            <p class="text-sm text-gray-600 mb-4">Tambahkan beberapa cerita dengan foto untuk timeline perjalanan cinta Anda</p>
            
            <div id="ourStoryContainer">
                <?php
                $ourStories = $ourStories ?? [];
                ?>
                <?php if (empty($ourStories)): ?>
                <div class="our-story-item border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-gray-700">Cerita #1</h4>
                        <button type="button" class="text-red-600 hover:text-red-800 remove-story-item" onclick="removeStoryItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="text" name="our_story[0][year]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="2018" value="">
                </div>
                <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                            <input type="text" name="our_story[0][title]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Perkenalan" value="">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                        <input type="file" name="our_story[0][story_image_file]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" accept="image/*" onchange="handleStoryImageUpload(this, 0)">
                        <input type="hidden" name="our_story[0][story_image]" id="story_image_0" value="">
                        <div id="story_image_0_preview_container" class="mt-2 hidden">
                            <img id="story_image_0_preview" src="" alt="Preview" class="w-full h-48 object-cover rounded border border-gray-200">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cerita</label>
                        <textarea name="our_story[0][story_text]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="4" placeholder="Ceritakan momen spesial Anda..."></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                        <input type="number" name="our_story[0][display_order]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="0" min="0">
                    </div>
                </div>
                <?php else: ?>
                <?php foreach ($ourStories as $index => $story): ?>
                <div class="our-story-item border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-gray-700">Cerita #<?= $index + 1 ?></h4>
                        <button type="button" class="text-red-600 hover:text-red-800 remove-story-item" onclick="removeStoryItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <input type="hidden" name="our_story[<?= $index ?>][id]" value="<?= $story['id'] ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="text" name="our_story[<?= $index ?>][year]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="2018" value="<?= esc($story['year'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                            <input type="text" name="our_story[<?= $index ?>][title]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Perkenalan" value="<?= esc($story['title'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                        <input type="file" name="our_story[<?= $index ?>][story_image_file]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" accept="image/*" onchange="handleStoryImageUpload(this, <?= $index ?>)">
                        <input type="hidden" name="our_story[<?= $index ?>][story_image]" id="story_image_<?= $index ?>" value="<?= esc($story['story_image'] ?? '') ?>">
                        <?php if (!empty($story['story_image'])): ?>
                        <div id="story_image_<?= $index ?>_preview_container" class="mt-2">
                            <img id="story_image_<?= $index ?>_preview" src="<?= esc($story['story_image']) ?>" alt="Preview" class="w-full h-48 object-cover rounded border border-gray-200">
                        </div>
                        <?php else: ?>
                        <div id="story_image_<?= $index ?>_preview_container" class="mt-2 hidden">
                            <img id="story_image_<?= $index ?>_preview" src="" alt="Preview" class="w-full h-48 object-cover rounded border border-gray-200">
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cerita</label>
                        <textarea name="our_story[<?= $index ?>][story_text]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="4" placeholder="Ceritakan momen spesial Anda..."><?= esc($story['story_text'] ?? '') ?></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                        <input type="number" name="our_story[<?= $index ?>][display_order]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?= esc($story['display_order'] ?? 0) ?>" min="0">
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" onclick="addStoryItem()" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Cerita
            </button>
        </div>

        <!-- Check-In Card Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-qrcode mr-2 text-blue-600"></i>Check-In Card
            </h3>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" id="check_in_card_enabled" name="check_in_card_enabled" value="1" <?= (!empty($invitation['check_in_card_enabled']) && $invitation['check_in_card_enabled'] == 1) ? 'checked' : '' ?> class="mr-2">
                    <label for="check_in_card_enabled" class="text-sm font-medium text-gray-700">Aktifkan Check-In Card</label>
                </div>
                <div id="checkInCardFields" class="<?= (!empty($invitation['check_in_card_enabled']) && $invitation['check_in_card_enabled'] == 1) ? '' : 'hidden' ?>">
                    <div>
                        <label for="check_in_card_instructions" class="block text-sm font-medium text-gray-700 mb-2">Instruksi Check-In</label>
                        <textarea id="check_in_card_instructions" name="check_in_card_instructions" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="3" placeholder="Silahkan tunjukkan QR Code ini kepada penerima tamu undangan di lokasi acara. Scan QR Code digunakan untuk mencatat kehadiran dan menukarkan souvenir"><?= esc($invitation['check_in_card_instructions'] ?? '') ?></textarea>
                    </div>
                    <?php if (!empty($invitation['id']) && !empty($invitation['check_in_qr_code_image'])): ?>
                    <div class="mt-4">
                        <h4 class="font-semibold text-gray-700 mb-2">QR Code Check-In</h4>
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
                                <div class="flex-shrink-0">
                                    <img src="<?= esc($invitation['check_in_qr_code_image']) ?>" alt="QR Code Check-In" class="w-48 h-48 border-2 border-gray-300 rounded-lg bg-white p-2">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 mb-2">
                                        <strong>QR Code ini akan ditampilkan di undangan.</strong> Tamu dapat scan QR code ini untuk melakukan check-in.
                                    </p>
                                    <p class="text-xs text-gray-500 mb-3">
                                        QR Code: <code class="bg-gray-200 px-2 py-1 rounded"><?= esc(substr($invitation['check_in_qr_code'] ?? '', 0, 30)) ?>...</code>
                                    </p>
                                    <div class="flex gap-2">
                                        <a href="<?= esc($invitation['check_in_qr_code_image']) ?>" target="_blank" download class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            <i class="fas fa-download mr-1"></i> Download QR Code
                                        </a>
                                        <a href="<?= base_url('checkin/' . $invitation['slug']) ?>" target="_blank" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                            <i class="fas fa-external-link-alt mr-1"></i> Preview Halaman Check-In
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>QR Code akan otomatis ter-generate saat Anda mengaktifkan Check-In Card dan menyimpan undangan.
                        </p>
                    </div>
                    <?php elseif (!empty($invitation['id'])): ?>
                    <div class="mt-4">
                        <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-1"></i>QR Code akan otomatis ter-generate setelah Anda menyimpan undangan dengan Check-In Card aktif.
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 sticky bottom-0">
            <div class="flex space-x-4">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan Undangan
                </button>
                <a href="<?= base_url('admin/invitation') ?>" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('title').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slug').value = slug;
    document.getElementById('slugPreview').textContent = slug || 'slug';
});

document.getElementById('slug').addEventListener('input', function() {
    const slug = this.value || 'slug';
    document.getElementById('slugPreview').textContent = slug;
});

// File Upload Handler - Semua Format Gambar, Max 1MB
function handleFileUpload(input, fieldName) {
    const file = input.files[0];
    if (!file) return;
    
    // Validasi format gambar (semua format)
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
    
    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        alert('File harus berupa gambar (JPG, PNG, GIF, WebP, SVG)');
        input.value = '';
        return;
    }
    
    // Validasi ukuran maksimal 1MB
    const maxSize = 1 * 1024 * 1024; // 1MB
    if (file.size > maxSize) {
        alert('Ukuran file maksimal 1MB. Ukuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
        input.value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('image', file);
    
    const previewContainer = document.getElementById(fieldName + '_preview_container');
    const previewImg = document.getElementById(fieldName + '_preview');
    
    // Tampilkan preview container dan loading state
    if (previewContainer) {
        previewContainer.classList.remove('hidden');
        previewContainer.innerHTML = '<div class="w-full h-32 flex items-center justify-center bg-gray-100 rounded border border-gray-200"><i class="fas fa-spinner fa-spin text-blue-500"></i> <span class="ml-2 text-gray-600">Mengupload...</span></div>';
    }
    
    fetch('<?= base_url('admin/template-image/upload') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            document.getElementById(fieldName).value = result.url;
            if (previewContainer) {
                previewContainer.innerHTML = `<img src="${result.url}" alt="${fieldName}" class="w-full h-32 object-cover rounded border border-gray-200" id="${fieldName}_preview">`;
            }
            if (typeof showToast !== 'undefined') {
                showToast('Gambar berhasil diupload', 'success');
            }
        } else {
            alert('Error: ' + (result.message || 'Upload failed'));
            input.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
        }
    })
    .catch(error => {
        alert('Error uploading image: ' + error.message);
        input.value = '';
        if (previewContainer) previewContainer.classList.add('hidden');
    });
}

// Gallery Upload Handler
let galleryUrls = [];
<?php 
$galleryImagesForJS = [];
if (!empty($invitation['gallery_images'])) {
    if (is_array($invitation['gallery_images'])) {
        $galleryImagesForJS = $invitation['gallery_images'];
    } else {
        $gallery = json_decode($invitation['gallery_images'], true);
        if (is_array($gallery)) {
            $galleryImagesForJS = $gallery;
        } elseif (is_string($invitation['gallery_images'])) {
            // Jika string biasa (bukan JSON), split dengan newline atau koma
            $galleryImagesForJS = array_filter(array_map('trim', preg_split('/[\n\r,]+/', $invitation['gallery_images'])));
        }
    }
}
if (!empty($galleryImagesForJS)): 
?>
galleryUrls = <?= json_encode(array_map(function($url) { 
    // Decode URL jika ter-encode
    $decoded = $url;
    if (strpos($url, '%') !== false || strpos($url, '&amp;') !== false) {
        $decoded = html_entity_decode(urldecode($url), ENT_QUOTES, 'UTF-8');
    }
    // Jika masih ter-encode dengan format http/x3A/x2F, decode lagi
    if (strpos($decoded, 'http/x3A') !== false || strpos($decoded, 'x2F') !== false) {
        $decoded = rawurldecode(str_replace(['x3A', 'x2F'], [':', '/'], $decoded));
    }
    return $decoded;
}, $galleryImagesForJS)) ?>;
<?php endif; ?>

// Inisialisasi gallery saat form load
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan galleryUrls terisi dari hidden input (prioritas utama)
    const hiddenInput = document.getElementById('gallery_images');
    if (hiddenInput && hiddenInput.value.trim()) {
        const urlsFromInput = hiddenInput.value.split('\n').filter(url => url.trim());
        // Merge dengan galleryUrls yang sudah ada (jika ada dari PHP), hindari duplikat
        urlsFromInput.forEach(url => {
            if (!galleryUrls.includes(url)) {
                galleryUrls.push(url);
            }
        });
    }
    // Jika galleryUrls masih kosong, coba ambil dari PHP variable
    if (galleryUrls.length === 0 && typeof galleryUrlsForJS !== 'undefined' && galleryUrlsForJS.length > 0) {
        galleryUrls = [...galleryUrlsForJS];
    }
    // Update preview dan pastikan hidden input terisi
    updateGalleryPreview();
});

function handleGalleryUpload(input) {
    const files = Array.from(input.files);
    if (files.length === 0) return;
    
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];
    
    files.forEach((file) => {
        // Validasi format gambar (semua format)
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            alert('File ' + file.name + ' harus berupa gambar (JPG, PNG, GIF, WebP, SVG)');
            return;
        }
        
        // Validasi ukuran maksimal 1MB
        const maxSize = 1 * 1024 * 1024; // 1MB
        if (file.size > maxSize) {
            alert('Ukuran file ' + file.name + ' maksimal 1MB. Ukuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
            return;
        }
        
        const formData = new FormData();
        formData.append('image', file);
        
        fetch('<?= base_url('admin/template-image/upload') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                const url = result.url || result.data?.url;
                if (url && !galleryUrls.includes(url)) {
                    galleryUrls.push(url);
                }
                updateGalleryPreview();
                if (typeof showToast !== 'undefined') {
                    showToast('Gambar ' + file.name + ' berhasil diupload', 'success');
                }
            } else {
                alert('Error uploading ' + file.name + ': ' + (result.message || 'Upload failed'));
            }
        })
        .catch(error => {
            alert('Error uploading ' + file.name + ': ' + error.message);
        });
    });
    
    // Reset input setelah semua file diproses
    input.value = '';
}

function updateGalleryPreview() {
    const previewContainer = document.getElementById('gallery_preview');
    const hiddenInput = document.getElementById('gallery_images');
    
    // Update hidden input dengan semua URL (existing + baru) - ini penting untuk preserve data saat submit
    if (hiddenInput) {
        hiddenInput.value = galleryUrls.join('\n');
    }
    
    // Update preview dengan design yang lebih bagus
    if (previewContainer) {
        if (galleryUrls.length === 0) {
            previewContainer.innerHTML = `
                <div class="col-span-full text-center py-8 text-gray-400">
                    <i class="fas fa-images text-4xl mb-2 block"></i>
                    <p class="text-sm">Belum ada gambar. Upload gambar untuk menambahkannya ke gallery.</p>
                </div>
            `;
        } else {
            previewContainer.innerHTML = galleryUrls.map((url, index) => `
                <div class="relative group gallery-item" data-url="${url}">
                    <div class="aspect-square rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-400 transition-all shadow-sm hover:shadow-md">
                        <img src="${url}" alt="Gallery ${index + 1}" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'200\' height=\'200\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EImage Error%3C/text%3E%3C/svg%3E'">
                    </div>
                    <button type="button" onclick="removeGalleryImage(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-xs hover:bg-red-600 shadow-lg transition-all opacity-0 group-hover:opacity-100 z-10">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-xs px-2 py-1 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity">
                        Gallery ${index + 1}
                    </div>
                </div>
            `).join('');
        }
    }
}

function removeGalleryImage(index) {
    if (index >= 0 && index < galleryUrls.length) {
        galleryUrls.splice(index, 1);
        updateGalleryPreview();
    }
}

document.getElementById('invitationForm').addEventListener('submit', function(e) {
    // Pastikan gallery URLs ter-update sebelum submit
    const hiddenInput = document.getElementById('gallery_images');
    if (hiddenInput) {
        // Update hidden input dengan semua URL dari galleryUrls array
        hiddenInput.value = galleryUrls.join('\n');
    }
    
    const checkboxes = ['rsvp_enabled', 'analytics_enabled', 'social_sharing_enabled', 'print_enabled', 'qr_code_enabled', 'guestbook_enabled'];
    checkboxes.forEach(name => {
        const checkbox = document.getElementById(name);
        if (!checkbox.checked) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = name;
            hidden.value = '0';
            this.appendChild(hidden);
        }
    });
});

// Music Upload Handler
function handleMusicUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validasi format audio
    if (!file.type.startsWith('audio/')) {
        alert('File harus berupa audio (MP3, WAV, OGG)');
        input.value = '';
        return;
    }
    
    // Validasi ukuran maksimal 5MB
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        alert('Ukuran file maksimal 5MB. Ukuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
        input.value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('music', file);
    
    const previewContainer = document.getElementById('music_preview_container');
    if (previewContainer) {
        previewContainer.classList.remove('hidden');
        previewContainer.innerHTML = '<div class="w-full flex items-center justify-center bg-gray-100 rounded border border-gray-200 p-4"><i class="fas fa-spinner fa-spin text-blue-500"></i> <span class="ml-2 text-gray-600">Mengupload...</span></div>';
    }
    
    fetch('<?= base_url('admin/template-image/upload') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            const url = result.url || result.data?.url;
            document.getElementById('music_url').value = url;
            document.getElementById('music_url_input').value = url;
            if (previewContainer) {
                previewContainer.innerHTML = `<audio controls class="w-full" id="music_preview" src="${url}">Browser Anda tidak mendukung audio player.</audio>`;
            }
            if (typeof showToast !== 'undefined') {
                showToast('Music berhasil diupload', 'success');
            }
        } else {
            alert('Error: ' + (result.message || 'Upload failed'));
            input.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
        }
    })
    .catch(error => {
        alert('Error uploading music: ' + error.message);
        input.value = '';
        if (previewContainer) previewContainer.classList.add('hidden');
    });
}

function handleMusicUrlChange(input) {
    const url = input.value.trim();
    document.getElementById('music_url').value = url;
    const previewContainer = document.getElementById('music_preview_container');
    const preview = document.getElementById('music_preview');
    if (url) {
        if (previewContainer) previewContainer.classList.remove('hidden');
        if (preview) preview.src = url;
    } else {
        if (previewContainer) previewContainer.classList.add('hidden');
    }
}

// Video Upload Handler
function handleVideoUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validasi format video
    if (!file.type.startsWith('video/')) {
        alert('File harus berupa video (MP4, WebM)');
        input.value = '';
        return;
    }
    
    // Validasi ukuran maksimal 50MB
    const maxSize = 50 * 1024 * 1024; // 50MB
    if (file.size > maxSize) {
        alert('Ukuran file maksimal 50MB. Ukuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
        input.value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('video', file);
    
    const previewContainer = document.getElementById('video_preview_container');
    if (previewContainer) {
        previewContainer.classList.remove('hidden');
        previewContainer.innerHTML = '<div class="w-full h-48 flex items-center justify-center bg-gray-100 rounded border border-gray-200"><i class="fas fa-spinner fa-spin text-blue-500"></i> <span class="ml-2 text-gray-600">Mengupload...</span></div>';
    }
    
    fetch('<?= base_url('admin/template-image/upload') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            const url = result.url || result.data?.url;
            document.getElementById('video_url').value = url;
            document.getElementById('video_url_input').value = url;
            if (previewContainer) {
                previewContainer.innerHTML = `<video controls class="w-full h-48 object-cover rounded border border-gray-200" id="video_preview" src="${url}">Browser Anda tidak mendukung video player.</video>`;
            }
            if (typeof showToast !== 'undefined') {
                showToast('Video berhasil diupload', 'success');
            }
        } else {
            alert('Error: ' + (result.message || 'Upload failed'));
            input.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
        }
    })
    .catch(error => {
        alert('Error uploading video: ' + error.message);
        input.value = '';
        if (previewContainer) previewContainer.classList.add('hidden');
    });
}

function handleVideoUrlChange(input) {
    const url = input.value.trim();
    document.getElementById('video_url').value = url;
    const previewContainer = document.getElementById('video_preview_container');
    const preview = document.getElementById('video_preview');
    if (url) {
        if (previewContainer) previewContainer.classList.remove('hidden');
        if (preview) preview.src = url;
    } else {
        if (previewContainer) previewContainer.classList.add('hidden');
    }
}

// Our Story Management - Global functions
let storyItemCount = <?= !empty($ourStories) ? count($ourStories) : 1 ?>;

function addStoryItem() {
        const container = document.getElementById('ourStoryContainer');
        const newItem = document.createElement('div');
        newItem.className = 'our-story-item border border-gray-200 rounded-lg p-4 mb-4';
        newItem.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-semibold text-gray-700">Cerita #${storyItemCount + 1}</h4>
                <button type="button" class="text-red-600 hover:text-red-800 remove-story-item" onclick="removeStoryItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="text" name="our_story[${storyItemCount}][year]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="2018" value="">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                    <input type="text" name="our_story[${storyItemCount}][title]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Perkenalan" value="">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                <input type="file" name="our_story[${storyItemCount}][story_image_file]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" accept="image/*" onchange="handleStoryImageUpload(this, ${storyItemCount})">
                <input type="hidden" name="our_story[${storyItemCount}][story_image]" id="story_image_${storyItemCount}" value="">
                <div id="story_image_${storyItemCount}_preview_container" class="mt-2 hidden">
                    <img id="story_image_${storyItemCount}_preview" src="" alt="Preview" class="w-full h-48 object-cover rounded border border-gray-200">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cerita</label>
                <textarea name="our_story[${storyItemCount}][story_text]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="4" placeholder="Ceritakan momen spesial Anda..."></textarea>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                <input type="number" name="our_story[${storyItemCount}][display_order]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="${storyItemCount}" min="0">
            </div>
        `;
        container.appendChild(newItem);
        storyItemCount++;
    }
    
function removeStoryItem(button) {
    if (confirm('Yakin ingin menghapus cerita ini?')) {
        button.closest('.our-story-item').remove();
    }
}

function handleStoryImageUpload(input, index) {
        const file = input.files[0];
        if (!file) return;
        
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar');
            input.value = '';
            return;
        }
        
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('Ukuran file maksimal 5MB');
            input.value = '';
            return;
        }
        
        const formData = new FormData();
        formData.append('image', file);
        
        const previewContainer = document.getElementById(`story_image_${index}_preview_container`);
        if (previewContainer) {
            previewContainer.classList.remove('hidden');
            previewContainer.innerHTML = '<div class="w-full h-48 flex items-center justify-center bg-gray-100 rounded border border-gray-200"><i class="fas fa-spinner fa-spin text-blue-500"></i> <span class="ml-2 text-gray-600">Mengupload...</span></div>';
        }
        
        fetch('<?= base_url('admin/template-image/upload') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                document.getElementById(`story_image_${index}`).value = result.url;
                if (previewContainer) {
                    previewContainer.innerHTML = `<img src="${result.url}" alt="Preview" class="w-full h-48 object-cover rounded border border-gray-200" id="story_image_${index}_preview">`;
                }
            } else {
                alert('Error: ' + (result.message || 'Upload failed'));
                input.value = '';
                if (previewContainer) previewContainer.classList.add('hidden');
            }
        })
        .catch(error => {
            alert('Error uploading image: ' + error.message);
            input.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
        });
}

// Validasi tanggal tidak boleh masa lalu
document.addEventListener('DOMContentLoaded', function() {
    const dateFields = ['wedding_date', 'reception_date', 'countdown_date', 'event_date_1', 'event_date_2', 'published_at'];
    
    dateFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            // Set min date ke hari ini
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const minDate = today.toISOString().slice(0, 16);
            field.setAttribute('min', minDate);
            
            // Validasi saat submit
            field.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                selectedDate.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    alert('Tanggal tidak boleh masa lalu');
                    this.value = '';
                    return false;
                }
            });
        }
    });
    
    // Check-In Card Management
    const checkInCardEnabled = document.getElementById('check_in_card_enabled');
    if (checkInCardEnabled) {
        checkInCardEnabled.addEventListener('change', function() {
            const fields = document.getElementById('checkInCardFields');
            if (fields) {
                if (this.checked) {
                    fields.classList.remove('hidden');
                } else {
                    fields.classList.add('hidden');
                }
            }
        });
    }
    
    // Validasi form submit
    document.getElementById('invitationForm').addEventListener('submit', function(e) {
        let isValid = true;
        const dateFields = ['wedding_date', 'reception_date', 'countdown_date', 'event_date_1', 'event_date_2', 'published_at'];
        
        dateFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && field.value) {
                const selectedDate = new Date(field.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                selectedDate.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    alert('Tanggal ' + fieldId + ' tidak boleh masa lalu');
                    field.focus();
                    isValid = false;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});

// Make function globally accessible - defined outside DOMContentLoaded
function showGenerateQrModal() {
    const invitationId = <?= !empty($invitation['id']) ? $invitation['id'] : 'null' ?>;
    if (!invitationId) {
        alert('Simpan undangan terlebih dahulu sebelum generate QR code');
        return;
    }
    
    const modal = document.getElementById('generateQrModal');
    const input = document.getElementById('guestNameInput');
    if (modal && input) {
        modal.classList.remove('hidden');
        input.value = '';
        input.focus();
    }
}

function closeGenerateQrModal() {
    const modal = document.getElementById('generateQrModal');
    const input = document.getElementById('guestNameInput');
    if (modal) {
        modal.classList.add('hidden');
    }
    if (input) {
        input.value = '';
    }
}

function generateCheckInCard() {
    const input = document.getElementById('guestNameInput');
    const guestName = input ? input.value.trim() : '';
    
    if (!guestName) {
        alert('Masukkan nama tamu terlebih dahulu');
        if (input) input.focus();
        return;
    }
    
    const invitationId = <?= !empty($invitation['id']) ? $invitation['id'] : 'null' ?>;
    if (!invitationId) {
        alert('Simpan undangan terlebih dahulu sebelum generate QR code');
        return;
    }
    
    // Disable button dan show loading
    const generateBtn = event.target;
    const originalText = generateBtn.innerHTML;
    generateBtn.disabled = true;
    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    
    // Generate QR code via AJAX
    fetch('<?= base_url('admin/invitation/generate-checkin-card') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            invitation_id: invitationId,
            guest_name: guestName
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        return response.json();
    })
    .then(result => {
        if (result.status === 'success') {
            closeGenerateQrModal();
            alert('QR Code berhasil dibuat untuk: ' + guestName + '\nHalaman akan di-refresh.');
            location.reload();
        } else {
            alert('Error: ' + (result.message || 'Gagal generate QR code'));
            console.error('QR Code generation error:', result);
            generateBtn.disabled = false;
            generateBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('QR Code generation error:', error);
        alert('Error: ' + error.message + '\nSilakan cek console untuk detail lebih lanjut.');
        generateBtn.disabled = false;
        generateBtn.innerHTML = originalText;
    });
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('generateQrModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeGenerateQrModal();
            }
        });
        
        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeGenerateQrModal();
            }
        });
        
        // Submit form with Enter key
        const input = document.getElementById('guestNameInput');
        if (input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    generateCheckInCard();
                }
            });
        }
    }
});
</script>
<?= $this->endSection() ?>
