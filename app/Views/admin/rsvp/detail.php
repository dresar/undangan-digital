<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Detail RSVP - <?= esc($invitation['title']) ?> - Admin Panel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-4 md:p-6 mb-4 md:mb-6 text-white">
        <div class="flex justify-between items-start">
            <div>
                <a href="<?= base_url('admin/rsvp') ?>" class="text-blue-100 hover:text-white mb-3 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                <h1 class="text-xl md:text-3xl font-bold mb-2"><?= esc($invitation['title']) ?></h1>
                <?php if (!empty($invitation['groom_name']) || !empty($invitation['bride_name'])): ?>
                <p class="text-blue-100 text-sm md:text-lg">
                    <?= esc(($invitation['groom_name'] ?? '') . ' & ' . ($invitation['bride_name'] ?? '')) ?>
                </p>
                <?php endif; ?>
                <p class="text-blue-100 mt-2 text-xs md:text-sm">
                    <i class="fas fa-link mr-1"></i>
                    <code class="bg-blue-700 px-2 py-1 rounded text-xs md:text-sm"><?= esc($invitation['slug']) ?></code>
                </p>
            </div>
            <div class="mt-3 md:mt-0">
                <a href="<?= base_url($invitation['slug']) ?>" target="_blank" class="bg-white text-blue-600 px-3 md:px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors font-semibold text-sm md:text-base">
                    <i class="fas fa-external-link-alt mr-2"></i><span class="hidden sm:inline">Lihat Undangan</span><span class="sm:hidden">Lihat</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total RSVP</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= number_format($stats['total']) ?></h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Akan Hadir</p>
                    <h3 class="text-3xl font-bold text-green-600"><?= number_format($stats['attending']) ?></h3>
                    <?php if ($stats['total'] > 0): ?>
                    <p class="text-xs text-gray-500 mt-1">
                        <?= number_format(($stats['attending'] / $stats['total']) * 100, 1) ?>% dari total
                    </p>
                    <?php endif; ?>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-user-check text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tidak Hadir</p>
                    <h3 class="text-3xl font-bold text-red-600"><?= number_format($stats['not_attending']) ?></h3>
                    <?php if ($stats['total'] > 0): ?>
                    <p class="text-xs text-gray-500 mt-1">
                        <?= number_format(($stats['not_attending'] / $stats['total']) * 100, 1) ?>% dari total
                    </p>
                    <?php endif; ?>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-user-times text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Tamu</p>
                    <h3 class="text-3xl font-bold text-purple-600"><?= number_format($stats['total_guests'] ?? 0) ?></h3>
                    <?php if ($stats['attending'] > 0): ?>
                    <p class="text-xs text-gray-500 mt-1">
                        Rata-rata <?= number_format(($stats['total_guests'] ?? 0) / max($stats['attending'], 1), 1) ?> tamu/RSVP
                    </p>
                    <?php endif; ?>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="get" action="<?= base_url('admin/rsvp/detail/' . $invitation['id']) ?>" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Kehadiran</label>
                <select name="attendance" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="1" <?= ($attendance ?? '') === '1' ? 'selected' : '' ?>>Akan Hadir</option>
                    <option value="0" <?= ($attendance ?? '') === '0' ? 'selected' : '' ?>>Tidak Hadir</option>
                </select>
            </div>
            <div class="md:col-span-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Cari nama, email, telepon, atau pesan..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="md:col-span-1 flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Combined List: RSVP & Ucapan -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-list-check mr-2"></i>RSVP & Ucapan (<?= $paginationInfo['total'] ?>)
            </h2>
        </div>
        
        <?php if (empty($combinedList)): ?>
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada RSVP atau ucapan untuk undangan ini</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-200">
            <?php foreach ($combinedList as $item): ?>
                <?php if ($item['type'] === 'rsvp'): ?>
                    <?php 
                    $rsvp = $item['data'];
                    $attendanceStatus = $rsvp['attendance'];
                    $isAttending = ($attendanceStatus === '1' || $attendanceStatus === 'yes');
                    $borderColor = $isAttending ? 'border-l-green-500' : 'border-l-red-500';
                    $badgeColor = $isAttending ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    $iconColor = $isAttending ? 'text-green-600' : 'text-red-600';
                    ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors border-l-4 <?= $borderColor ?>">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-3">
                                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                                        <i class="fas fa-check-circle <?= $iconColor ?>"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900"><?= esc($rsvp['name']) ?></h3>
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?= $badgeColor ?> mt-1">
                                            <?= $isAttending ? '✓ Akan Hadir' : '✗ Tidak Hadir' ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ml-12">
                                    <?php if (!empty($rsvp['email'])): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope w-5 mr-2 text-gray-400"></i>
                                        <span><?= esc($rsvp['email']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($rsvp['phone'])): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-phone w-5 mr-2 text-gray-400"></i>
                                        <span><?= esc($rsvp['phone']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-users w-5 mr-2 text-gray-400"></i>
                                        <span><strong><?= esc($rsvp['guest_count'] ?? 1) ?></strong> tamu</span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($rsvp['message'])): ?>
                                <div class="mt-4 ml-12 p-4 bg-gray-50 rounded-lg border-l-2 border-gray-300">
                                    <p class="text-gray-700 italic">
                                        <i class="fas fa-quote-left mr-1 text-gray-400"></i>
                                        <?= nl2br(esc($rsvp['message'])) ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex flex-col items-end space-y-2 ml-4">
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('d M Y, H:i', strtotime($rsvp['created_at'])) ?>
                                </span>
                                <a href="<?= base_url('admin/rsvp/delete/' . $rsvp['id']) ?>" 
                                    onclick="return confirm('Yakin ingin menghapus RSVP ini?')" 
                                    class="text-red-600 hover:text-red-800 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $guestbook = $item['data']; ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors border-l-4 border-l-purple-500">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-3">
                                    <div class="bg-purple-100 rounded-full p-2 mr-3">
                                        <i class="fas fa-comment-heart text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900"><?= esc($guestbook['name']) ?></h3>
                                        <?php if (!empty($guestbook['address'])): ?>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1 text-purple-400"></i><?= esc($guestbook['address']) ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mt-4 ml-12 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border-l-2 border-purple-300">
                                    <p class="text-gray-700 leading-relaxed">
                                        <i class="fas fa-quote-left mr-1 text-purple-400"></i>
                                        <?= nl2br(esc($guestbook['message'])) ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end space-y-2 ml-4">
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('d M Y, H:i', strtotime($guestbook['created_at'])) ?>
                                </span>
                                <a href="<?= base_url('admin/rsvp/delete-guestbook/' . $guestbook['id']) ?>" 
                                    onclick="return confirm('Yakin ingin menghapus ucapan ini?')" 
                                    class="text-red-600 hover:text-red-800 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (!empty($paginationInfo) && $paginationInfo['totalPages'] > 1): ?>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <?= (($paginationInfo['currentPage'] - 1) * $paginationInfo['perPage']) + 1 ?> - <?= min($paginationInfo['currentPage'] * $paginationInfo['perPage'], $paginationInfo['total']) ?> dari <?= $paginationInfo['total'] ?> entri
                </div>
                <div class="flex space-x-2">
                    <?php if ($paginationInfo['currentPage'] > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $paginationInfo['currentPage'] - 1])) ?>" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $paginationInfo['currentPage'] - 2); $i <= min($paginationInfo['totalPages'], $paginationInfo['currentPage'] + 2); $i++): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="px-3 py-2 border border-gray-300 rounded-lg <?= $i == $paginationInfo['currentPage'] ? 'bg-blue-600 text-white' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($paginationInfo['currentPage'] < $paginationInfo['totalPages']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $paginationInfo['currentPage'] + 1])) ?>" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
