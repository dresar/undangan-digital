<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>RSVP - Admin Panel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 md:mb-6">
        <div class="mb-3 md:mb-0">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">RSVP & Ucapan</h2>
            <p class="text-sm md:text-base text-gray-600 mt-1">Kelola konfirmasi kehadiran dan ucapan untuk semua undangan</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-100 rounded-lg p-4">
                        <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Total RSVP</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['total']) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-green-100 rounded-lg p-4">
                        <i class="fas fa-user-check text-green-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Akan Hadir</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['attending']) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-red-100 rounded-lg p-4">
                        <i class="fas fa-user-times text-red-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Tidak Hadir</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['not_attending']) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-purple-100 rounded-lg p-4">
                        <i class="fas fa-comment-heart text-purple-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Total Ucapan</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_guestbook'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6 mb-4 md:mb-6">
        <form method="get" action="<?= base_url('admin/rsvp') ?>" class="flex flex-col sm:flex-row gap-3 md:gap-4">
            <div class="flex-1">
                <input type="text" name="search" class="w-full px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Cari judul undangan, nama pasangan..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="flex gap-2 sm:gap-3">
                <button type="submit" class="flex-1 sm:flex-none px-4 md:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm md:text-base">
                    <i class="fas fa-search mr-2"></i><span class="hidden sm:inline">Cari</span>
                </button>
                <?php if ($search): ?>
                <a href="<?= base_url('admin/rsvp') ?>" class="px-4 md:px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm md:text-base">
                    <i class="fas fa-times"></i>
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Invitations List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Undangan</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RSVP</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akan Hadir</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tamu</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ucapan</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th class="px-4 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($invitations)): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            <p>Belum ada undangan</p>
                            <?php if (ENVIRONMENT === 'development'): ?>
                            <p class="text-xs mt-2 text-gray-400">
                                Debug: Total Undangan = <?= $paginationInfo['total'] ?? 0 ?>
                            </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($invitations as $inv): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="<?= base_url('admin/rsvp/detail/' . $inv['id']) ?>" class="text-blue-600 hover:text-blue-800">
                                    <?= esc($inv['title'] ?: (($inv['groom_name'] ?? '') . ' & ' . ($inv['bride_name'] ?? 'Undangan #' . $inv['id']))) ?>
                                </a>
                            </div>
                            <?php if (!empty($inv['groom_name']) || !empty($inv['bride_name'])): ?>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= esc(($inv['groom_name'] ?? '') . ' & ' . ($inv['bride_name'] ?? '')) ?>
                            </div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-400 mt-1">
                                <code><?= esc($inv['slug']) ?></code>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $inv['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                <?= esc($inv['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold"><?= number_format($inv['rsvp_total']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="text-green-600 font-semibold"><?= number_format($inv['rsvp_attending']) ?></span>
                            <?php if ($inv['rsvp_not_attending'] > 0): ?>
                            <span class="text-red-600">/ <?= number_format($inv['rsvp_not_attending']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold"><?= number_format($inv['rsvp_total_guests']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold"><?= number_format($inv['guestbook_count']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= number_format($inv['views_count']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('d M Y, H:i', strtotime($inv['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?= base_url('admin/rsvp/detail/' . $inv['id']) ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="Lihat Detail RSVP & Ucapan">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url($inv['slug']) ?>" target="_blank" class="text-green-600 hover:text-green-900" title="Lihat Undangan">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if (!empty($paginationInfo) && $paginationInfo['totalPages'] > 1): ?>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <?= (($paginationInfo['currentPage'] - 1) * $paginationInfo['perPage']) + 1 ?> - <?= min($paginationInfo['currentPage'] * $paginationInfo['perPage'], $paginationInfo['total']) ?> dari <?= $paginationInfo['total'] ?> undangan
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
    </div>
</div>
<?= $this->endSection() ?>
