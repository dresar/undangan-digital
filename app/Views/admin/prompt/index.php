<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Prompt Builder<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Prompt Builder</h2>
            <p class="text-gray-600 mt-1">Buat prompt lengkap untuk AI dengan semua variabel template</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-lg">
                    <h5 class="text-white font-semibold m-0">
                        <i class="fas fa-list-ul mr-2"></i>
                        Variabel yang Tersedia
                    </h5>
                </div>
                <div class="p-6 max-h-[600px] overflow-y-auto">
                    <div class="mb-6">
                        <h6 class="text-blue-600 font-semibold mb-3">Variable <code class="text-sm">$invitation</code></h6>
                        <div class="space-y-3">
                            <?php foreach ($variables as $key => $desc): ?>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-grow">
                                        <code class="text-blue-600 text-sm">$invitation['<?= $key ?>']</code>
                                        <p class="text-xs text-gray-600 mt-1 mb-0"><?= esc($desc) ?></p>
                                    </div>
                                    <button type="button" class="ml-2 px-2 py-1 border border-blue-500 text-blue-600 rounded hover:bg-blue-50 transition-colors text-sm" onclick="insertVariable('<?= $key ?>')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h6 class="text-green-600 font-semibold mb-3">Helper Functions</h6>
                        <div class="space-y-3">
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <code class="text-green-600 text-sm">esc($string)</code>
                                <p class="text-xs text-gray-600 mt-1 mb-2">Escape HTML untuk security</p>
                                <button type="button" class="w-full px-2 py-1 border border-green-500 text-green-600 rounded hover:bg-green-50 transition-colors text-sm" onclick="insertText('esc(')">
                                    <i class="fas fa-plus mr-1"></i>Insert
                                </button>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <code class="text-green-600 text-sm">base_url($path)</code>
                                <p class="text-xs text-gray-600 mt-1 mb-2">Generate base URL</p>
                                <button type="button" class="w-full px-2 py-1 border border-green-500 text-green-600 rounded hover:bg-green-50 transition-colors text-sm" onclick="insertText('base_url(')">
                                    <i class="fas fa-plus mr-1"></i>Insert
                                </button>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <code class="text-green-600 text-sm">date('format', strtotime($date))</code>
                                <p class="text-xs text-gray-600 mt-1 mb-2">Format tanggal</p>
                                <button type="button" class="w-full px-2 py-1 border border-green-500 text-green-600 rounded hover:bg-green-50 transition-colors text-sm" onclick="insertText(\"date('Y-m-d', strtotime(\")">
                                    <i class="fas fa-plus mr-1"></i>Insert
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h6 class="text-blue-600 font-semibold mb-3">Library yang Tersedia (Opsional)</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                <strong>Tailwind CSS</strong> <span class="text-red-600 ml-1">(WAJIB - CDN)</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-yellow-600 mr-2"></i>jQuery (opsional)
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-yellow-600 mr-2"></i>Moment.js (opsional)
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-yellow-600 mr-2"></i>FancyBox (opsional)
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-yellow-600 mr-2"></i>Clipboard.js (opsional)
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-yellow-600 mr-2"></i>Particles.js (opsional)
                            </li>
                        </ul>
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800 mb-0"><strong>Note:</strong> Gunakan Tailwind CSS CDN untuk styling utama. Library lain opsional.</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h6 class="text-red-600 font-semibold mb-3">Gambar CDN</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center">
                                <i class="fas fa-image text-blue-600 mr-2"></i>Unsplash: images.unsplash.com
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-image text-blue-600 mr-2"></i>Pixabay: pixabay.com
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-image text-blue-600 mr-2"></i>Pexels: images.pexels.com
                            </li>
                        </ul>
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-xs text-red-800 mb-0"><strong>WAJIB:</strong> Semua gambar (background, decoration) HARUS dari CDN!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-lg flex justify-between items-center">
                    <h5 class="text-white font-semibold m-0">
                        <i class="fas fa-comments mr-2"></i>
                        Editor Prompt
                    </h5>
                    <div class="flex space-x-2">
                        <button type="button" class="px-3 py-1 bg-white bg-opacity-20 text-white rounded hover:bg-opacity-30 transition-colors text-sm" onclick="resetPrompt()" title="Reset ke Default">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </button>
                        <button type="button" class="px-3 py-1 bg-white bg-opacity-20 text-white rounded hover:bg-opacity-30 transition-colors text-sm" onclick="formatPrompt()" title="Format Prompt">
                            <i class="fas fa-code mr-1"></i> Format
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="promptText" class="block text-sm font-medium text-gray-700 mb-2">Prompt untuk AI</label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" id="promptText" rows="20"><?= esc($defaultPrompt) ?></textarea>
                        <p class="mt-2 text-sm text-gray-500">Edit prompt sesuai kebutuhan. Gunakan tombol di samping untuk insert variabel.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="copyPrompt()">
                            <i class="fas fa-clipboard mr-2"></i>Copy Prompt Lengkap
                        </button>
                        <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" onclick="copyAsPHP()">
                            <i class="fas fa-file-code mr-2"></i>Copy Template PHP
                        </button>
                        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors" onclick="copyVariableList()">
                            <i class="fas fa-list-check mr-2"></i>Copy Daftar Variabel
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-lg">
                    <h5 class="text-white font-semibold m-0">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Tips & Contoh
                    </h5>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h6 class="text-blue-600 font-semibold mb-2">PENTING - Baca Sebelum Copy:</h6>
                        <ul class="space-y-1 text-sm text-gray-700">
                            <li>• Prompt ini sudah lengkap dengan semua requirement</li>
                            <li>• AI akan generate 1 file PHP dengan CSS dan JS inline</li>
                            <li>• Gunakan Tailwind CSS CDN (bukan Bootstrap)</li>
                            <li>• Semua gambar dari CDN (unsplash.com, pixabay.com)</li>
                            <li>• Variabel wajib digunakan, jika kosong pakai hardcode</li>
                            <li>• Copy hasil dari AI langsung ke editor index.php</li>
                        </ul>
                    </div>
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-1"></i>
                            <div>
                                <strong class="text-yellow-800">Perhatian:</strong>
                                <p class="text-sm text-yellow-700 mb-0">Pastikan AI mengikuti format 1 file PHP lengkap dengan CSS dan JS inline. Jangan pisahkan ke file terpisah!</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-2 mt-1"></i>
                            <div>
                                <strong class="text-blue-800">Cara Pakai:</strong>
                                <p class="text-sm text-blue-700 mb-0">1. Copy prompt lengkap → 2. Paste ke AI (ChatGPT/Gemini) → 3. Copy hasil PHP → 4. Paste ke editor index.php template</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function insertVariable(key) {
    const textarea = document.getElementById('promptText');
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, cursorPos);
    const textAfter = textarea.value.substring(cursorPos);
    const insertText = '{$invitation[\'' + key + '\']}';
    textarea.value = textBefore + insertText + textAfter;
    textarea.focus();
    textarea.setSelectionRange(cursorPos + insertText.length, cursorPos + insertText.length);
}

function insertText(text) {
    const textarea = document.getElementById('promptText');
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, cursorPos);
    const textAfter = textarea.value.substring(cursorPos);
    textarea.value = textBefore + text + textAfter;
    textarea.focus();
    textarea.setSelectionRange(cursorPos + text.length, cursorPos + text.length);
}

function copyPrompt() {
    const textarea = document.getElementById('promptText');
    textarea.select();
    document.execCommand('copy');
    showNotification('Prompt berhasil disalin ke clipboard!', 'success');
}

function copyAsPHP() {
    const template = '<?php\n' +
        '// Template untuk paste hasil dari AI\n' +
        '// Copy seluruh kode PHP yang dihasilkan AI dan paste di sini\n\n' +
        '// Contoh struktur yang diharapkan:\n' +
        '$groomName = !empty($invitation[\'groom_name\']) ? esc($invitation[\'groom_name\']) : \'John Doe\';\n' +
        '$brideName = !empty($invitation[\'bride_name\']) ? esc($invitation[\'bride_name\']) : \'Jane Smith\';\n' +
        '?>\n' +
        '<!-- Paste kode lengkap dari AI di sini (HTML + CSS + JS dalam 1 file) -->';
    navigator.clipboard.writeText(template).then(() => {
        showNotification('Template PHP berhasil disalin! Paste hasil dari AI di editor index.php', 'success');
    });
}

function copyVariableList() {
    const variables = 'VARIABLE YANG TERSEDIA:\n' +
        '- $invitation[\'title\'] - Judul undangan\n' +
        '- $invitation[\'groom_name\'] - Nama mempelai pria\n' +
        '- $invitation[\'bride_name\'] - Nama mempelai wanita\n' +
        '- $invitation[\'groom_parents\'] - Orang tua mempelai pria\n' +
        '- $invitation[\'bride_parents\'] - Orang tua mempelai wanita\n' +
        '- $invitation[\'wedding_date\'] - Tanggal acara (format: Y-m-d H:i:s)\n' +
        '- $invitation[\'wedding_location\'] - Lokasi acara\n' +
        '- $invitation[\'wedding_address\'] - Alamat lengkap\n' +
        '- $invitation[\'contact_phone\'] - No. telepon\n' +
        '- $invitation[\'contact_email\'] - Email\n' +
        '- $invitation[\'contact_whatsapp\'] - WhatsApp\n' +
        '- $invitation[\'music_url\'] - URL musik\n' +
        '- $invitation[\'video_url\'] - URL video\n' +
        '- $invitation[\'cover_image\'] - Cover image\n\n' +
        'RULES:\n' +
        '- SELALU gunakan esc() untuk output\n' +
        '- Jika variable kosong, gunakan data hardcode\n' +
        '- Semua gambar HARUS dari CDN (unsplash.com, pixabay.com)';
    navigator.clipboard.writeText(variables).then(() => {
        showNotification('Daftar variabel berhasil disalin!', 'success');
    });
}

function resetPrompt() {
    if (confirm('Reset prompt ke default? Perubahan akan hilang.')) {
        const defaultPrompt = <?= json_encode($defaultPrompt) ?>;
        document.getElementById('promptText').value = defaultPrompt;
        showNotification('Prompt direset ke default', 'info');
    }
}

function formatPrompt() {
    const textarea = document.getElementById('promptText');
    let text = textarea.value;
    text = text.replace(/\n{3,}/g, '\n\n');
    text = text.trim();
    textarea.value = text;
    showNotification('Prompt diformat', 'info');
}
</script>
<?= $this->endSection() ?>
