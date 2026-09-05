<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>CDN Assets<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">CDN Assets</h2>
            <p class="text-gray-600 mt-1">Kelola library dan asset CDN untuk template</p>
        </div>
        <div>
            <a href="<?= base_url('admin/asset/create') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Asset
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-cloud-download-alt text-xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-0">Total Assets</p>
                    <h4 class="text-2xl font-bold text-gray-800 mb-0"><?= $stats['total'] ?></h4>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-file-code text-xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-0">CSS Libraries</p>
                    <h4 class="text-2xl font-bold text-gray-800 mb-0"><?= $stats['css'] ?></h4>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-green-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-file-code text-xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-0">JS Libraries</p>
                    <h4 class="text-2xl font-bold text-gray-800 mb-0"><?= $stats['js'] ?></h4>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-yellow-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-0">Aktif</p>
                    <h4 class="text-2xl font-bold text-gray-800 mb-0"><?= $stats['active'] ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center flex-wrap gap-4">
            <h5 class="text-lg font-semibold text-gray-800 mb-0">Daftar Assets</h5>
            <form method="get" class="flex gap-2 flex-wrap">
                <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="css" <?= $type === 'css' ? 'selected' : '' ?>>CSS</option>
                    <option value="js" <?= $type === 'js' ? 'selected' : '' ?>>JavaScript</option>
                    <option value="font" <?= $type === 'font' ? 'selected' : '' ?>>Font</option>
                    <option value="image" <?= $type === 'image' ? 'selected' : '' ?>>Image</option>
                </select>
                <input type="text" name="search" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-48" placeholder="Cari..." value="<?= esc($search ?? '') ?>">
                <button type="submit" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm">
                    <i class="fas fa-search"></i>
                </button>
                <a href="<?= base_url('admin/asset') ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    <i class="fas fa-redo"></i>
                </a>
            </form>
        </div>
        <div class="p-6">
            <?php if (empty($assets)): ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 mt-4">Belum ada CDN Asset. <a href="<?= base_url('admin/asset/create') ?>" class="text-blue-600 hover:underline">Tambah asset pertama</a></p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($assets as $asset): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded"><?= $asset['load_order'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= esc($asset['name']) ?></div>
                                <?php if (!empty($asset['description'])): ?>
                                <div class="text-sm text-gray-500"><?= esc($asset['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $badgeClass = [
                                    'css' => 'bg-blue-100 text-blue-800',
                                    'js' => 'bg-green-100 text-green-800',
                                    'font' => 'bg-yellow-100 text-yellow-800',
                                    'image' => 'bg-purple-100 text-purple-800',
                                ];
                                $badgeClass = $badgeClass[$asset['type']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded <?= $badgeClass ?>"><?= strtoupper($asset['type']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs text-gray-600 max-w-xs block truncate" title="<?= esc($asset['url']) ?>">
                                    <?= esc($asset['url']) ?>
                                </code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= $asset['version'] ? esc($asset['version']) : '-' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="px-3 py-1 text-sm rounded-lg transition-colors <?= $asset['is_active'] ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' ?>" onclick="toggleStatus(<?= $asset['id'] ?>, this)">
                                    <i class="fas fa-<?= $asset['is_active'] ? 'check-circle' : 'circle' ?> mr-1"></i>
                                    <?= $asset['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-1">
                                    <a href="<?= base_url('admin/asset/edit/' . $asset['id']) ?>" class="px-2 py-1 border border-blue-500 text-blue-600 rounded hover:bg-blue-50 transition-colors" title="Edit">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                    <button type="button" class="px-2 py-1 border border-blue-500 text-blue-600 rounded hover:bg-blue-50 transition-colors" onclick="copyUrl('<?= esc($asset['url'], 'js') ?>')" title="Copy URL">
                                        <i class="fas fa-clipboard"></i>
                                    </button>
                                    <a href="<?= base_url('admin/asset/delete/' . $asset['id']) ?>" class="px-2 py-1 border border-red-500 text-red-600 rounded hover:bg-red-50 transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus asset ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleStatus(id, btn) {
    fetch('<?= base_url('admin/asset/toggle-status/') ?>' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_active) {
                    btn.className = 'px-3 py-1 text-sm rounded-lg transition-colors bg-green-100 text-green-800 hover:bg-green-200';
                    btn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Aktif';
                } else {
                    btn.className = 'px-3 py-1 text-sm rounded-lg transition-colors bg-gray-100 text-gray-800 hover:bg-gray-200';
                    btn.innerHTML = '<i class="fas fa-circle mr-1"></i> Nonaktif';
                }
                showNotification(data.message, 'success');
            }
        });
}

function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        showNotification('URL berhasil disalin ke clipboard', 'success');
    });
}
</script>
<?= $this->endSection() ?>
