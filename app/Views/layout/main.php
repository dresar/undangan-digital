<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SaaS Undangan Digital') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/fancybox.css') ?>">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-gray-50">
    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
    
    <?= $this->include('layout/sidebar') ?>
    
    <div class="md:ml-64 min-h-screen transition-all duration-300" id="mainContent">
        <?= $this->include('layout/header') ?>
        
        <main class="p-4 md:p-6">
            <?= $this->renderSection('content') ?>
        </main>
        
        <?= $this->include('layout/footer') ?>
    </div>
    
    <!-- Toast Notification Container - Kecil di pojok kanan atas -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50"></div>
    
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/clipboard.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/moment.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/moment-id.js') ?>"></script>
    <script src="<?= base_url('assets/js/fancybox.umd.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    
    <!-- Hamburger Menu Script -->
    <script>
    // Pastikan script berjalan setelah semua elemen di-render
    (function() {
        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            
            if (!sidebar || !overlay || !hamburgerBtn) {
                // Retry jika elemen belum ada
                setTimeout(initSidebar, 100);
                return;
            }
            
            function toggleSidebar() {
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Buka sidebar
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    hamburgerBtn.innerHTML = '<i class="fas fa-times text-xl"></i>';
                } else {
                    // Tutup sidebar
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                    hamburgerBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                }
            }
            
            // Attach event listener ke hamburger button
            hamburgerBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });
            
            // Close sidebar saat klik di luar (overlay)
            overlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });
            
            // Close sidebar saat resize ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                    hamburgerBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                }
            });
            
            // Expose function globally
            window.toggleSidebar = toggleSidebar;
        }
        
        // Jalankan saat DOM ready atau langsung jika sudah ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebar);
        } else {
            initSidebar();
        }
    })();
    </script>
    <script>
        // Toast Notification Function - Kecil, di pojok kanan atas, auto close 1.5 detik
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            
            // Hapus toast yang ada sebelumnya (hanya 1 toast)
            container.innerHTML = '';
            
            // Tentukan warna berdasarkan type
            const colors = {
                'success': { bg: 'bg-green-500', icon: 'fa-check-circle' },
                'error': { bg: 'bg-red-500', icon: 'fa-exclamation-circle' },
                'warning': { bg: 'bg-yellow-500', icon: 'fa-exclamation-triangle' },
                'info': { bg: 'bg-blue-500', icon: 'fa-info-circle' }
            };
            
            const color = colors[type] || colors['info'];
            
            // Buat toast element - kecil dan compact
            const toast = document.createElement('div');
            toast.className = `${color.bg} text-white px-3 py-2 rounded-lg shadow-lg flex items-center space-x-2`;
            toast.style.animation = 'slideInRight 0.3s ease-out';
            toast.style.fontSize = '13px';
            toast.style.maxWidth = '300px';
            
            toast.innerHTML = `
                <i class="fas ${color.icon} text-xs"></i>
                <div class="flex-1 text-xs leading-tight">${message}</div>
            `;
            
            container.appendChild(toast);
            
            // Auto close setelah 1.5 detik (tidak perlu tombol close)
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 1500);
        }
        
        // Alias untuk kompatibilitas
        function showNotification(message, type) {
            showToast(message, type);
        }
        
        // Tampilkan flash messages
        <?php if (session()->getFlashdata('success')): ?>
        showToast('<?= esc(session()->getFlashdata('success'), 'js') ?>', 'success');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
        showToast('<?= esc(session()->getFlashdata('error'), 'js') ?>', 'error');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('warning')): ?>
        showToast('<?= esc(session()->getFlashdata('warning'), 'js') ?>', 'warning');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('info')): ?>
        showToast('<?= esc(session()->getFlashdata('info'), 'js') ?>', 'info');
        <?php endif; ?>
    </script>
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .animate-slide-in {
            animation: slideInRight 0.3s ease-out;
        }
    </style>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
