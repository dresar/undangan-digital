<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= $asset ? 'Edit' : 'Tambah' ?> CDN Asset<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?= $asset ? 'Edit' : 'Tambah' ?> CDN Asset</h2>
            <p class="text-gray-600 mt-1"><?= $asset ? 'Perbarui' : 'Tambahkan' ?> library atau asset CDN baru</p>
        </div>
        <div>
            <a href="<?= base_url('admin/asset') ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="post" action="<?= base_url($asset ? 'admin/asset/update/' . $asset['id'] : 'admin/asset/store') ?>" enctype="multipart/form-data">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-lg">
                        <h5 class="text-white font-semibold m-0">Informasi Asset</h5>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Asset <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="name" name="name" value="<?= esc($asset['name'] ?? '') ?>" required>
                                <p class="mt-1 text-sm text-gray-500">Contoh: Moment.js, FancyBox, Three.js</p>
                            </div>
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="type" name="type" required onchange="toggleUploadField()">
                                    <option value="">Pilih Tipe</option>
                                    <option value="css" <?= ($asset['type'] ?? '') === 'css' ? 'selected' : '' ?>>CSS</option>
                                    <option value="js" <?= ($asset['type'] ?? '') === 'js' ? 'selected' : '' ?>>JavaScript</option>
                                    <option value="font" <?= ($asset['type'] ?? '') === 'font' ? 'selected' : '' ?>>Font</option>
                                    <option value="image" <?= ($asset['type'] ?? '') === 'image' ? 'selected' : '' ?>>Image</option>
                                </select>
                            </div>
                        </div>

                        <div id="uploadSection" class="hidden">
                            <label for="asset_file" class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                            <input type="file" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="asset_file" name="asset_file" accept="image/*,.css,.js,.woff,.woff2,.ttf,.otf">
                            <p class="mt-1 text-sm text-gray-500">Upload file gambar, CSS, JS, atau font. Maksimal 5MB</p>
                            <?php if ($asset && $asset['type'] === 'image' && !empty($asset['url']) && strpos($asset['url'], base_url()) !== false): ?>
                            <div class="mt-3">
                                <img src="<?= esc($asset['url'], 'attr') ?>" alt="Preview" class="border border-gray-300 rounded-lg max-h-48" id="previewImage">
                            </div>
                            <?php endif; ?>
                        </div>

                        <div id="urlSection">
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL CDN <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" id="url" name="url" rows="3"><?= esc($asset['url'] ?? '') ?></textarea>
                            <p class="mt-1 text-sm text-gray-500">Masukkan URL lengkap CDN atau kosongkan jika upload file (bisa multiple lines untuk multiple URLs)</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="version" class="block text-sm font-medium text-gray-700 mb-2">Version</label>
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="version" name="version" value="<?= esc($asset['version'] ?? '') ?>" placeholder="Contoh: 2.29.4">
                            </div>
                            <div>
                                <label for="load_order" class="block text-sm font-medium text-gray-700 mb-2">Load Order</label>
                                <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="load_order" name="load_order" value="<?= esc($asset['load_order'] ?? 0) ?>" min="0">
                                <p class="mt-1 text-sm text-gray-500">Urutan loading (0 = pertama)</p>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="description" name="description" rows="2"><?= esc($asset['description'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($asset['is_active'] ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Aktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex space-x-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('admin/asset') ?>" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">Batal</a>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-lg">
                    <h6 class="text-white font-semibold m-0">Contoh CDN URLs</h6>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h6 class="text-blue-600 font-semibold mb-2">CSS:</h6>
                        <pre class="bg-gray-100 p-3 rounded-lg text-xs overflow-x-auto"><code>https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css</code></pre>
                    </div>
                    
                    <div>
                        <h6 class="text-green-600 font-semibold mb-2">JavaScript:</h6>
                        <pre class="bg-gray-100 p-3 rounded-lg text-xs overflow-x-auto"><code>https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js
https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/id.js</code></pre>
                    </div>
                    
                    <div>
                        <h6 class="text-yellow-600 font-semibold mb-2">Font:</h6>
                        <pre class="bg-gray-100 p-3 rounded-lg text-xs overflow-x-auto"><code>https://fonts.googleapis.com/css2?family=Poppins</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleUploadField() {
    const type = document.getElementById('type').value;
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');
    const urlField = document.getElementById('url');
    const urlLabel = urlSection.querySelector('label');
    
    if (type === 'image' || type === 'font' || type === 'css' || type === 'js') {
        uploadSection.classList.remove('hidden');
        urlField.removeAttribute('required');
        urlLabel.innerHTML = 'URL CDN <span class="text-gray-500 text-xs">(Opsional jika upload file)</span>';
    } else {
        uploadSection.classList.add('hidden');
        urlField.setAttribute('required', 'required');
        urlLabel.innerHTML = 'URL CDN <span class="text-red-500">*</span>';
    }
}

document.getElementById('asset_file')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('previewImage');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'previewImage';
                preview.className = 'border border-gray-300 rounded-lg max-h-48 mt-3';
                document.getElementById('uploadSection').appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

if (document.getElementById('type').value) {
    toggleUploadField();
}
</script>
<?= $this->endSection() ?>
