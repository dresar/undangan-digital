<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Daftar Undangan - Admin Panel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Undangan</h2>
        <a href="<?= base_url('admin/invitation/create') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus-circle mr-2"></i>Buat Baru
        </a>
    </div>

    <?php if (isset($error)): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <div>
                <strong>Error:</strong> <?= esc($error) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

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

    <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="get" action="<?= base_url('admin/invitation') ?>" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-4">
                <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari judul atau slug..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="md:col-span-3">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="published" <?= ($status ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="id" <?= ($sort ?? 'id') === 'id' ? 'selected' : '' ?>>Sortir: ID</option>
                    <option value="title" <?= ($sort ?? '') === 'title' ? 'selected' : '' ?>>Sortir: Judul</option>
                    <option value="views_count" <?= ($sort ?? '') === 'views_count' ? 'selected' : '' ?>>Sortir: Views</option>
                    <option value="created_at" <?= ($sort ?? '') === 'created_at' ? 'selected' : '' ?>>Sortir: Tanggal</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="w-full px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>
    
    <!-- Grid Layout -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
            <div>
                <p class="text-sm text-gray-600">Total: <span class="font-semibold text-gray-900"><?= $pager ? $pager->getTotal() : 0 ?></span> undangan</p>
            </div>
            <button type="button" class="px-4 py-2 border border-red-500 text-red-600 rounded-lg hover:bg-red-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm hidden" onclick="bulkDelete()" id="bulkDeleteBtn" disabled>
                <i class="fas fa-trash mr-2"></i>Hapus Terpilih
            </button>
        </div>

        <?php if (empty($invitations) || !is_array($invitations)): ?>
        <div class="text-center py-12">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">
                <?php if (isset($error)): ?>
                    <span class="text-red-600">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Error memuat data. Silakan refresh halaman.
                    </span>
                <?php else: ?>
                    Tidak ada data undangan
                <?php endif; ?>
            </p>
        </div>
        <?php else: ?>
        <!-- Grid: 1 kolom mobile, 3 kolom desktop -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
            <?php 
            $checkInCardModel = new \App\Models\CheckInCardModel();
            foreach ($invitations as $inv): 
                $checkInCards = $checkInCardModel->getByInvitationId($inv['id']);
                
                // Get preview image: cover_image first, then template preview_image, then default gradient
                $previewImage = '';
                if (!empty($inv['cover_image'])) {
                    // Decode URL jika ter-encode, tapi jangan double decode
                    $previewImage = $inv['cover_image'];
                    // Cek apakah URL sudah ter-encode (mengandung % atau &amp;)
                    if (strpos($previewImage, '%') !== false || strpos($previewImage, '&amp;') !== false) {
                        $previewImage = html_entity_decode(urldecode($previewImage), ENT_QUOTES, 'UTF-8');
                    }
                    // Jika masih ter-encode (http/x3A/x2F), decode lagi
                    if (strpos($previewImage, 'http/x3A') !== false || strpos($previewImage, 'x2F') !== false) {
                        $previewImage = rawurldecode(str_replace(['x3A', 'x2F'], [':', '/'], $previewImage));
                    }
                } elseif (!empty($inv['template_id']) && !empty($templates[$inv['template_id']]['preview_image'])) {
                    $previewImage = $templates[$inv['template_id']]['preview_image'];
                }
                
                // Ensure absolute URL - pastikan tidak double encode
                if (!empty($previewImage)) {
                    // Jika sudah absolute URL, gunakan langsung
                    if (preg_match('/^https?:\/\//', $previewImage)) {
                        // URL sudah absolute, tidak perlu diubah
                    } elseif (strpos($previewImage, base_url()) !== false) {
                        // Sudah mengandung base_url, tidak perlu diubah
                    } else {
                        // Relative path, tambahkan base_url
                        $previewImage = base_url(ltrim($previewImage, '/'));
                    }
                }
            ?>
            <div class="invitation-card bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
                <!-- Card Header -->
                <div class="relative h-32 p-4 <?= empty($previewImage) ? 'bg-gradient-to-br from-blue-500 to-purple-600' : '' ?>" <?= !empty($previewImage) ? 'style="background-image: url(\'' . esc($previewImage, 'attr') . '\'); background-size: cover; background-position: center;"' : '' ?>>
                    <!-- Overlay untuk readability -->
                    <?php if (!empty($previewImage)): ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                    <?php endif; ?>
                    <div class="absolute top-2 right-2 z-10">
                        <span class="status-badge-<?= $inv['id'] ?> px-2 py-1 text-xs font-semibold rounded-full <?= $inv['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                            <?= esc($inv['status']) ?>
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 z-10">
                        <h3 class="text-white font-bold text-lg line-clamp-2 drop-shadow-lg"><?= esc($inv['title']) ?></h3>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 space-y-3">
                    <!-- Info -->
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-link w-4 mr-2 text-gray-400"></i>
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded flex-1 truncate"><?= esc($inv['slug']) ?></code>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-eye w-4 mr-2 text-gray-400"></i>
                                <span><?= number_format($inv['views_count']) ?> views</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-qrcode w-4 mr-2 text-gray-400"></i>
                                <span><?= count($checkInCards) ?> QR</span>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar w-4 mr-2 text-gray-400"></i>
                            <span class="moment-date" data-date="<?= esc($inv['created_at'], 'attr') ?>"><?= date('d/m/Y H:i', strtotime($inv['created_at'])) ?></span>
                        </div>
                    </div>

                    <!-- QR Code Button -->
                    <?php if (!empty($checkInCards)): ?>
                    <button onclick="showQrCodes(<?= $inv['id'] ?>)" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <i class="fas fa-qrcode mr-2"></i>Lihat QR Code (<?= count($checkInCards) ?>)
                    </button>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-200">
                        <a href="<?= base_url('admin/invitation/edit/' . $inv['id']) ?>" class="px-3 py-2 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition-colors text-sm text-center">
                            <i class="fas fa-pencil mr-1"></i>Edit
                        </a>
                        <a href="<?= base_url($inv['slug']) ?>" target="_blank" class="px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm text-center">
                            <i class="fas fa-eye mr-1"></i>Preview
                        </a>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" class="px-2 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs" onclick="copyLink('<?= base_url($inv['slug']) ?>')" title="Copy Link">
                            <i class="fas fa-link"></i>
                        </button>
                        <button type="button" class="px-2 py-2 border <?= $inv['status'] === 'published' ? 'border-yellow-500 text-yellow-600 hover:bg-yellow-50' : 'border-green-500 text-green-600 hover:bg-green-50' ?> rounded-lg transition-colors text-xs" onclick="toggleStatus(<?= $inv['id'] ?>)" title="Toggle Status">
                            <i class="fas fa-toggle-<?= $inv['status'] === 'published' ? 'on' : 'off' ?>"></i>
                        </button>
                        <a href="<?= base_url('admin/invitation/delete/' . $inv['id']) ?>" class="px-2 py-2 border border-red-500 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-xs text-center" onclick="return confirm('Yakin ingin menghapus?')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($pager && ($pager->hasMore() || $pager->getCurrentPage() > 1)): ?>
        <div class="mt-6 flex justify-center">
            <div class="bg-white px-4 py-3 rounded-lg border border-gray-200">
                <?= $pager->links('default', 'pagination_tailwind') ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal QR Code -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" onclick="if(event.target === this) closeQrModal()">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">QR Code Check-In Cards</h3>
                <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="qrModalContent" class="p-6">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkButton();
}

function updateBulkButton() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    document.getElementById('bulkDeleteBtn').disabled = checked.length === 0;
}

function bulkDelete() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    if (checked.length === 0) {
        alert('Pilih minimal satu item');
        return;
    }
    
    if (!confirm(`Yakin ingin menghapus ${checked.length} undangan?`)) {
        return;
    }
    
    const form = document.getElementById('bulkForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    });
}

function toggleStatus(id) {
    fetch('<?= base_url('admin/invitation/toggle-status') ?>/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.querySelector('.status-badge-' + id);
            badge.textContent = data.status;
            badge.className = 'px-2 py-1 text-xs font-semibold rounded-full status-badge-' + id + ' ' + (data.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    });
}

function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        showToast('Link berhasil disalin!', 'success');
    }).catch(() => {
        // Fallback
        const textarea = document.createElement('textarea');
        textarea.value = url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Link berhasil disalin!', 'success');
    });
}

function showQrCodes(invitationId) {
    const modal = document.getElementById('qrModal');
    const content = document.getElementById('qrModalContent');
    
    // Show loading
    content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i><p class="mt-4 text-gray-600">Memuat QR Code...</p></div>';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Fetch QR codes
    fetch('<?= base_url('admin/invitation/get-qr-codes') ?>/' + invitationId, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.qr_codes && data.qr_codes.length > 0) {
            let html = '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">';
            data.qr_codes.forEach(card => {
                html += `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-gray-900">${card.guest_name || 'Tamu Undangan'}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    ${card.checked_in ? '<span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Sudah Check-In</span>' : '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">Belum Check-In</span>'}
                                </p>
                            </div>
                        </div>
                        ${card.qr_code_image ? `
                            <div class="text-center mb-3">
                                <img src="${card.qr_code_image}" alt="QR Code" class="mx-auto border border-gray-200 rounded-lg" style="max-width: 200px;">
                            </div>
                            <div class="text-center">
                                <a href="${card.qr_code_image}" download class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    <i class="fas fa-download mr-2"></i>Download QR
                                </a>
                            </div>
                        ` : '<p class="text-sm text-gray-500 text-center">QR Code belum tersedia</p>'}
                    </div>
                `;
            });
            html += '</div>';
            content.innerHTML = html;
        } else {
            content.innerHTML = '<div class="text-center py-8"><i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i><p class="text-gray-600">Belum ada QR Code untuk undangan ini</p></div>';
        }
    })
    .catch(error => {
        content.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Error memuat QR Code</p></div>';
    });
}

function closeQrModal() {
    const modal = document.getElementById('qrModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

$(document).ready(function() {
    $('.moment-date').each(function() {
        const date = $(this).data('date');
        if (date) {
            $(this).text(moment(date).format('DD MMMM YYYY, HH:mm'));
            $(this).attr('title', moment(date).fromNow());
        }
    });
});
</script>
<?= $this->endSection() ?>
