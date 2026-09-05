<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - <?= esc($invitation['title'] ?? 'Undangan') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#8b5cf6',
                    }
                }
            }
        }
    </script>
    <style>
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .overlay {
                display: none;
            }
            .overlay.open {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation (Mobile) -->
    <aside id="sidebar" class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-lg z-50 lg:relative lg:translate-x-0 lg:shadow-none">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-900"><?= esc($invitation['title'] ?? 'Undangan') ?></h2>
                    <p class="text-xs text-gray-500">User Panel</p>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <nav class="p-4 space-y-2">
            <a href="#guests" onclick="showTab('guests'); toggleSidebar();" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-users w-5 mr-3"></i>
                <span>Daftar Tamu</span>
            </a>
            <a href="#scan" onclick="showTab('scan'); toggleSidebar();" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-qrcode w-5 mr-3"></i>
                <span>Scan QR Code</span>
            </a>
            <a href="#guestbook" onclick="showTab('guestbook'); toggleSidebar();" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-comments w-5 mr-3"></i>
                <span>Ucapan</span>
                <?php if (count($guestbooks) > 0): ?>
                <span class="ml-auto bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded-full"><?= count($guestbooks) ?></span>
                <?php endif; ?>
            </a>
            <a href="#settings" onclick="showTab('settings'); toggleSidebar();" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-cog w-5 mr-3"></i>
                <span>Pengaturan</span>
            </a>
            <a href="<?= base_url($invitation['slug']) ?>" target="_blank" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors mt-4 border-t border-gray-200 pt-4">
                <i class="fas fa-external-link-alt w-5 mr-3"></i>
                <span>Lihat Undangan</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Mobile Header -->
        <header class="bg-white shadow-sm sticky top-0 z-30 lg:hidden">
            <div class="px-4 py-3 flex justify-between items-center">
                <button onclick="toggleSidebar()" class="text-gray-700 hover:text-gray-900">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex-1 text-center">
                    <h1 class="text-lg font-bold text-gray-900"><?= esc($invitation['title'] ?? 'Undangan') ?></h1>
                </div>
                <a href="<?= base_url($invitation['slug']) ?>" target="_blank" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-4 lg:mb-6">
            <div class="bg-white rounded-lg shadow p-3 lg:p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs lg:text-sm text-gray-500">Total Tamu</p>
                        <p class="text-xl lg:text-2xl font-bold text-gray-900"><?= count($checkInCards) ?></p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2 lg:p-3">
                        <i class="fas fa-users text-blue-600 text-lg lg:text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-3 lg:p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs lg:text-sm text-gray-500">Sudah Check-In</p>
                        <p class="text-xl lg:text-2xl font-bold text-green-600"><?= count(array_filter($checkInCards, fn($card) => !empty($card['checked_in']))) ?></p>
                    </div>
                    <div class="bg-green-100 rounded-full p-2 lg:p-3">
                        <i class="fas fa-check-circle text-green-600 text-lg lg:text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-3 lg:p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs lg:text-sm text-gray-500">Belum Check-In</p>
                        <p class="text-xl lg:text-2xl font-bold text-orange-600"><?= count(array_filter($checkInCards, fn($card) => empty($card['checked_in']))) ?></p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-2 lg:p-3">
                        <i class="fas fa-clock text-orange-600 text-lg lg:text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-3 lg:p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs lg:text-sm text-gray-500">Ucapan</p>
                        <p class="text-xl lg:text-2xl font-bold text-purple-600"><?= count($guestbooks) ?></p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-2 lg:p-3">
                        <i class="fas fa-comments text-purple-600 text-lg lg:text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-lg shadow mb-6">

            <!-- Tab Content: Daftar Tamu -->
            <div id="content-guests" class="tab-content p-4 lg:p-6">
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Tamu Undangan</h2>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button onclick="filterGuests('all')" class="filter-btn active flex-1 sm:flex-none px-3 lg:px-4 py-2 text-xs lg:text-sm rounded-lg bg-blue-600 text-white">Semua</button>
                        <button onclick="filterGuests('checked')" class="filter-btn flex-1 sm:flex-none px-3 lg:px-4 py-2 text-xs lg:text-sm rounded-lg bg-gray-200 text-gray-700">Sudah</button>
                        <button onclick="filterGuests('unchecked')" class="filter-btn flex-1 sm:flex-none px-3 lg:px-4 py-2 text-xs lg:text-sm rounded-lg bg-gray-200 text-gray-700">Belum</button>
                    </div>
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tamu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Check-In</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="guests-table-body">
                            <?php if (empty($checkInCards)): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                    Belum ada tamu undangan. Generate QR code di admin panel.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($checkInCards as $card): ?>
                            <tr class="guest-row" data-status="<?= !empty($card['checked_in']) ? 'checked' : 'unchecked' ?>">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($card['guest_name'] ?? 'Tamu Undangan') ?></div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <code class="text-xs text-gray-500"><?= esc(substr($card['qr_code'] ?? '', 0, 20)) ?>...</code>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <?php if (!empty($card['checked_in'])): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Sudah Check-In
                                    </span>
                                    <?php else: ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        <i class="fas fa-clock mr-1"></i>Belum Check-In
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    <?= !empty($card['checked_in_at']) ? date('d/m/Y H:i', strtotime($card['checked_in_at'])) : '-' ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <?php if (!empty($card['qr_code_image'])): ?>
                                    <a href="<?= esc($card['qr_code_image']) ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-download"></i> Download QR
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-3" id="guests-card-body">
                    <?php if (empty($checkInCards)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 block"></i>
                        <p class="text-sm">Belum ada tamu undangan. Generate QR code di admin panel.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($checkInCards as $card): ?>
                    <div class="guest-card bg-gray-50 rounded-lg p-4 border border-gray-200" data-status="<?= !empty($card['checked_in']) ? 'checked' : 'unchecked' ?>">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm"><?= esc($card['guest_name'] ?? 'Tamu Undangan') ?></h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    <code><?= esc(substr($card['qr_code'] ?? '', 0, 25)) ?>...</code>
                                </p>
                            </div>
                            <div>
                                <?php if (!empty($card['checked_in'])): ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Check-In
                                </span>
                                <?php else: ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-clock mr-1"></i>Belum
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <?= !empty($card['checked_in_at']) ? date('d/m/Y H:i', strtotime($card['checked_in_at'])) : 'Belum check-in' ?>
                            </div>
                            <?php if (!empty($card['qr_code_image'])): ?>
                            <a href="<?= esc($card['qr_code_image']) ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fas fa-download mr-1"></i>Download QR
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab Content: Scan QR Code -->
            <div id="content-scan" class="tab-content hidden p-4 lg:p-6">
                <div class="max-w-md mx-auto">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Scan QR Code Tamu</h2>
                    <div class="bg-gray-100 rounded-lg p-4 mb-4">
                        <div id="scanner-container" class="relative">
                            <video id="scanner-video" class="w-full rounded-lg" style="display: none;"></video>
                            <canvas id="scanner-canvas" class="w-full rounded-lg" style="display: none;"></canvas>
                            <div id="scanner-placeholder" class="text-center py-12">
                                <i class="fas fa-qrcode text-6xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600">Klik tombol di bawah untuk mulai scan</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button onclick="startScanner()" id="btn-start-scan" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-camera mr-2"></i>Mulai Scan
                        </button>
                        <button onclick="stopScanner()" id="btn-stop-scan" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors hidden">
                            <i class="fas fa-stop mr-2"></i>Stop Scan
                        </button>
                    </div>
                    <div id="scan-result" class="mt-4"></div>
                </div>
            </div>

            <!-- Tab Content: Ucapan -->
            <div id="content-guestbook" class="tab-content hidden p-4 lg:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ucapan & Doa</h2>
                <div class="space-y-4">
                    <?php if (empty($guestbooks)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 block"></i>
                        Belum ada ucapan
                    </div>
                    <?php else: ?>
                    <?php foreach ($guestbooks as $guestbook): ?>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold text-gray-900"><?= esc($guestbook['name'] ?? 'Anonim') ?></p>
                                <p class="text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($guestbook['created_at'])) ?></p>
                            </div>
                        </div>
                        <p class="text-gray-700"><?= nl2br(esc($guestbook['message'] ?? '')) ?></p>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab Content: Pengaturan -->
            <div id="content-settings" class="tab-content hidden p-4 lg:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Undangan</h2>
                <form method="post" action="<?= base_url('user/' . $invitation['slug'] . '/update') ?>" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                            <input type="text" name="title" value="<?= esc($invitation['title'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                            <select name="template_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <?php foreach ($templates as $template): ?>
                                <option value="<?= $template['id'] ?>" <?= ($invitation['template_id'] ?? '') == $template['id'] ? 'selected' : '' ?>>
                                    <?= esc($template['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Mempelai Pria</label>
                            <input type="text" name="groom_name" value="<?= esc($invitation['groom_name'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Mempelai Wanita</label>
                            <input type="text" name="bride_name" value="<?= esc($invitation['bride_name'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pernikahan</label>
                            <input type="datetime-local" name="wedding_date" value="<?= !empty($invitation['wedding_date']) ? date('Y-m-d\TH:i', strtotime($invitation['wedding_date'])) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <input type="text" name="wedding_location" value="<?= esc($invitation['wedding_location'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="wedding_address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= esc($invitation['wedding_address'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                            <input type="text" name="contact_phone" value="<?= esc($invitation['contact_phone'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- QR Code Scanner Library -->
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrcodeScanner = null;
        let currentTab = 'guests';

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Update nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('bg-blue-50', 'text-blue-600');
                item.classList.add('text-gray-700');
            });

            // Show selected tab
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Highlight active nav item
            const activeNav = document.querySelector(`a[href="#${tabName}"]`);
            if (activeNav) {
                activeNav.classList.add('bg-blue-50', 'text-blue-600');
                activeNav.classList.remove('text-gray-700');
            }

            currentTab = tabName;

            // Stop scanner if switching away from scan tab
            if (tabName !== 'scan' && html5QrcodeScanner) {
                stopScanner();
            }
        }

        function filterGuests(status) {
            const rows = document.querySelectorAll('.guest-row');
            const cards = document.querySelectorAll('.guest-card');
            const buttons = document.querySelectorAll('.filter-btn');
            
            buttons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            event.target.classList.add('active', 'bg-blue-600', 'text-white');
            event.target.classList.remove('bg-gray-200', 'text-gray-700');

            // Filter table rows (desktop)
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.getAttribute('data-status');
                    row.style.display = rowStatus === status ? '' : 'none';
                }
            });

            // Filter cards (mobile)
            cards.forEach(card => {
                if (status === 'all') {
                    card.style.display = '';
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    card.style.display = cardStatus === status ? '' : 'none';
                }
            });
        }

        // Initialize - show guests tab by default
        document.addEventListener('DOMContentLoaded', function() {
            showTab('guests');
        });

        function startScanner() {
            const container = document.getElementById('scanner-container');
            const placeholder = document.getElementById('scanner-placeholder');
            const btnStart = document.getElementById('btn-start-scan');
            const btnStop = document.getElementById('btn-stop-scan');
            const resultDiv = document.getElementById('scan-result');

            html5QrcodeScanner = new Html5Qrcode("scanner-container");
            
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    // QR Code detected
                    handleQrCodeScanned(decodedText);
                },
                (errorMessage) => {
                    // Ignore errors
                }
            )
            .then(() => {
                placeholder.style.display = 'none';
                btnStart.classList.add('hidden');
                btnStop.classList.remove('hidden');
            })
            .catch((err) => {
                console.error("Unable to start scanning", err);
                resultDiv.innerHTML = '<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">Gagal memulai scanner. Pastikan kamera dapat diakses.</div>';
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    html5QrcodeScanner = null;
                    
                    const placeholder = document.getElementById('scanner-placeholder');
                    const btnStart = document.getElementById('btn-start-scan');
                    const btnStop = document.getElementById('btn-stop-scan');
                    
                    placeholder.style.display = 'block';
                    btnStart.classList.remove('hidden');
                    btnStop.classList.add('hidden');
                }).catch((err) => {
                    console.error("Stop failed", err);
                });
            }
        }

        function handleQrCodeScanned(qrCode) {
            const resultDiv = document.getElementById('scan-result');
            resultDiv.innerHTML = '<div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Memproses QR Code...</div>';

            fetch('<?= base_url('user/' . $invitation['slug'] . '/scan-qr') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'qr_code=' + encodeURIComponent(qrCode)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    resultDiv.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><i class="fas fa-check-circle mr-2"></i>Check-in berhasil! Tamu: ' + data.data.guest_name + '</div>';
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else if (data.status === 'warning') {
                    resultDiv.innerHTML = '<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-triangle mr-2"></i>' + data.message + ' - ' + data.data.guest_name + '</div>';
                } else {
                    resultDiv.innerHTML = '<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg"><i class="fas fa-times-circle mr-2"></i>' + data.message + '</div>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">Error: ' + error.message + '</div>';
            });
        }
    </script>
</body>
</html>

