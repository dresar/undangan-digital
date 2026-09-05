<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Browse: <?= esc($slug) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= base_url('admin/template') ?>" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-folder text-yellow-500 mr-2"></i>
                    <?= esc($slug) ?>
                </h2>
            </div>
            <p class="text-gray-600 text-sm">
                <code class="bg-gray-100 px-2 py-1 rounded text-xs"><?= esc($folderPath) ?></code>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('admin/template/preview/' . urlencode($slug)) ?>" 
               target="_blank"
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-eye mr-2"></i>Preview
            </a>
            <a href="<?= base_url('admin/template') ?>" 
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- File List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2"></i>File & Folder (<?= count($files) ?>)
            </h3>
        </div>
        
        <?php if (empty($files)): ?>
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-folder-open text-5xl mb-3 text-gray-300"></i>
            <p>Folder kosong</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Path
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Size
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($files as $file): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <?php if ($file['is_dir']): ?>
                                <i class="fas fa-folder text-yellow-500"></i>
                            <?php else: ?>
                                <?php
                                $iconClass = 'fa-file text-gray-400';
                                switch ($file['extension']) {
                                    case 'php':
                                        $iconClass = 'fa-file-code text-purple-500';
                                        break;
                                    case 'css':
                                        $iconClass = 'fa-file-code text-blue-500';
                                        break;
                                    case 'js':
                                        $iconClass = 'fa-file-code text-yellow-500';
                                        break;
                                    case 'html':
                                        $iconClass = 'fa-file-code text-orange-500';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                    case 'gif':
                                    case 'webp':
                                    case 'svg':
                                        $iconClass = 'fa-file-image text-green-500';
                                        break;
                                    case 'mp3':
                                    case 'wav':
                                    case 'ogg':
                                        $iconClass = 'fa-file-audio text-pink-500';
                                        break;
                                    case 'mp4':
                                    case 'webm':
                                        $iconClass = 'fa-file-video text-red-500';
                                        break;
                                }
                                ?>
                                <i class="fas <?= $iconClass ?>"></i>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-800"><?= esc($file['name']) ?></span>
                            <?php if ($file['name'] === 'index.php'): ?>
                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Main</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 font-mono">
                            <?= esc($file['path']) ?>
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-600">
                            <?php if (!$file['is_dir']): ?>
                                <?= formatBytes($file['size']) ?>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-2">Edit Manual</p>
                <p class="mb-2">Edit file template secara manual melalui editor code Anda. Path folder:</p>
                <code class="block bg-white px-3 py-2 rounded border border-blue-200 text-xs mb-2 overflow-x-auto">
                    <?= esc($folderPath) ?>
                </code>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>File utama: <code class="bg-white px-2 py-0.5 rounded">index.php</code></li>
                    <li>Semua asset gunakan <code class="bg-white px-2 py-0.5 rounded">base_url('assets/...')</code></li>
                    <li>Variable invitation tersedia sebagai object <code class="bg-white px-2 py-0.5 rounded">$invitation</code></li>
                </ul>
            </div>
        </div>
    </div>

</div>

<?php
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?>

<?= $this->endSection() ?>

