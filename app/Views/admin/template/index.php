<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Templates Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Templates Management</h2>
            <p class="text-gray-600 text-sm mt-1">Kelola template di folder <code class="bg-gray-100 px-2 py-1 rounded">templates/</code></p>
        </div>
        <button onclick="showCreateModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Buat Folder Baru
        </button>
    </div>

    <?php if (session('success')): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        <i class="fas fa-check-circle mr-2"></i><?= session('success') ?>
    </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i><?= session('error') ?>
    </div>
    <?php endif; ?>

    <!-- Templates Grid -->
    <?php if (empty($templates)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-folder-open text-6xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada template</h3>
        <p class="text-gray-500 mb-6">Buat folder template pertama Anda untuk memulai.</p>
        <button onclick="showCreateModal()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Buat Folder Template
        </button>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($templates as $template): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            
            <!-- Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">
                            <i class="fas fa-folder text-yellow-500 mr-2"></i>
                            <?= esc($template['slug']) ?>
                        </h3>
                        <?php if ($template['db_info']): ?>
                        <span class="inline-block px-2 py-1 text-xs rounded <?= $template['db_info']['is_active'] == 1 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                            <?= $template['db_info']['is_active'] == 1 ? 'Active' : 'Inactive' ?>
                        </span>
                        <?php else: ?>
                        <span class="inline-block px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                            Not in DB
                        </span>
                        <?php endif; ?>
                    </div>
                    <button onclick="toggleActive('<?= esc($template['slug'], 'js') ?>')" 
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                            title="Toggle Active">
                        <i class="fas fa-toggle-<?= $template['db_info'] && $template['db_info']['is_active'] == 1 ? 'on' : 'off' ?> text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Info -->
            <div class="p-4">
                <div class="space-y-2 text-sm">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-file w-5 text-gray-400"></i>
                        <span><?= count($template['files']) ?> file(s)</span>
                    </div>
                    <div class="flex items-center <?= $template['has_index'] ? 'text-green-600' : 'text-red-600' ?>">
                        <i class="fas fa-<?= $template['has_index'] ? 'check-circle' : 'times-circle' ?> w-5"></i>
                        <span><?= $template['has_index'] ? 'index.php ada' : 'index.php tidak ada' ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex gap-2">
                <a href="<?= base_url('admin/template/browse/' . urlencode($template['slug'])) ?>" 
                   class="flex-1 px-3 py-2 border border-blue-500 text-blue-600 rounded hover:bg-blue-50 transition-colors text-center text-sm">
                    <i class="fas fa-folder-open mr-1"></i>Browse
                </a>
                <?php if ($template['has_index']): ?>
                <a href="<?= base_url('admin/template/preview/' . urlencode($template['slug'])) ?>" 
                   target="_blank"
                   class="flex-1 px-3 py-2 border border-green-500 text-green-600 rounded hover:bg-green-50 transition-colors text-center text-sm">
                    <i class="fas fa-eye mr-1"></i>Preview
                </a>
                <?php endif; ?>
                <button onclick="confirmDelete('<?= esc($template['slug'], 'js') ?>')" 
                        class="px-3 py-2 border border-red-500 text-red-600 rounded hover:bg-red-50 transition-colors text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<!-- Modal Create Folder -->
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Buat Folder Template Baru</h3>
        </div>
        <form method="post" action="<?= base_url('admin/template/create-folder') ?>">
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Folder (slug)
                </label>
                <input type="text" 
                       name="folder_name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="templates1, templates2, ..."
                       pattern="[a-zA-Z0-9_-]+"
                       required>
                <p class="text-xs text-gray-500 mt-2">
                    Hanya huruf, angka, dash (-), dan underscore (_). Contoh: templates1, my-template
                </p>
            </div>
            <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
                <button type="button" 
                        onclick="hideCreateModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Folder
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function hideCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function confirmDelete(slug) {
    if (confirm('Yakin ingin menghapus template "' + slug + '"?\n\nSemua file di folder akan dihapus!')) {
        window.location.href = '<?= base_url('admin/template/delete/') ?>' + slug;
    }
}

function toggleActive(slug) {
    fetch('<?= base_url('admin/template/toggle-active/') ?>' + slug, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal mengubah status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

// Close modal when clicking outside
document.getElementById('createModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideCreateModal();
    }
});
</script>

<?= $this->endSection() ?>

