<aside id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-2xl z-50 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="p-6 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-envelope-heart text-blue-600 text-xl"></i>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg m-0">Undangan Digital</h4>
                <p class="text-blue-200 text-xs m-0">SaaS Platform</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="<?= base_url('admin/invitation/dashboard') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?= uri_string() === 'admin' || uri_string() === 'admin/invitation/dashboard' ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' ?>">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li>
                <button onclick="toggleMenu('invitationMenu')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-blue-700/50 hover:text-white">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope w-5"></i>
                        <span>Undangan</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="invitationMenuIcon"></i>
                </button>
                <ul id="invitationMenu" class="ml-4 mt-1 space-y-1 <?= strpos(uri_string(), 'admin/invitation') !== false && uri_string() !== 'admin/invitation/dashboard' ? '' : 'hidden' ?>">
                    <li><a href="<?= base_url('admin/invitation') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 <?= uri_string() === 'admin/invitation' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700/50 hover:text-white' ?>">
                        <i class="fas fa-list w-4"></i>
                        <span>Semua Undangan</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/invitation/create') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 <?= uri_string() === 'admin/invitation/create' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700/50 hover:text-white' ?>">
                        <i class="fas fa-plus-circle w-4"></i>
                        <span>Buat Baru</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/invitation?status=draft') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 text-blue-200 hover:bg-blue-700/50 hover:text-white">
                        <i class="fas fa-file-alt w-4"></i>
                        <span>Draft</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/invitation?status=published') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 text-blue-200 hover:bg-blue-700/50 hover:text-white">
                        <i class="fas fa-check-circle w-4"></i>
                        <span>Published</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/invitation?is_featured=1') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 text-blue-200 hover:bg-blue-700/50 hover:text-white">
                        <i class="fas fa-star w-4"></i>
                        <span>Featured</span>
                    </a></li>
                </ul>
            </li>
            
            <li>
                <a href="<?= base_url('admin/rsvp') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/rsvp') !== false ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' ?>">
                    <i class="fas fa-check-circle w-5"></i>
                    <span>RSVP</span>
                </a>
            </li>
            
            <li>
                <button onclick="toggleMenu('analyticsMenu')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-blue-700/50 hover:text-white">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Analytics</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="analyticsMenuIcon"></i>
                </button>
                <ul id="analyticsMenu" class="ml-4 mt-1 space-y-1 hidden">
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-chart-pie w-4"></i><span>Overview</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-envelope w-4"></i><span>Per Undangan</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-mobile-alt w-4"></i><span>Device Stats</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-map-marker-alt w-4"></i><span>Location Stats</span></a></li>
                </ul>
            </li>
            
            <li>
                <a href="<?= base_url('admin/template') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/template') !== false ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' ?>">
                    <i class="fas fa-palette w-5"></i>
                    <span>Templates</span>
                </a>
            </li>
            
            <li>
                <button onclick="toggleMenu('assetMenu')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/asset') !== false ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' ?>">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-cloud-download-alt w-5"></i>
                        <span>CDN Assets</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="assetMenuIcon"></i>
                </button>
                <ul id="assetMenu" class="ml-4 mt-1 space-y-1 <?= strpos(uri_string(), 'admin/asset') !== false ? '' : 'hidden' ?>">
                    <li><a href="<?= base_url('admin/asset') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 <?= uri_string() === 'admin/asset' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700/50 hover:text-white' ?>">
                        <i class="fas fa-list w-4"></i>
                        <span>Semua Assets</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/asset/create') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 <?= uri_string() === 'admin/asset/create' ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700/50 hover:text-white' ?>">
                        <i class="fas fa-plus-circle w-4"></i>
                        <span>Tambah Asset</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/asset?type=css') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 text-blue-200 hover:bg-blue-700/50 hover:text-white">
                        <i class="fas fa-code w-4"></i>
                        <span>CSS Libraries</span>
                    </a></li>
                    <li><a href="<?= base_url('admin/asset?type=js') ?>" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm transition-all duration-200 text-blue-200 hover:bg-blue-700/50 hover:text-white">
                        <i class="fas fa-file-code w-4"></i>
                        <span>JS Libraries</span>
                    </a></li>
                </ul>
            </li>
            
            <li class="pt-4 border-t border-blue-700 mt-4">
                <a href="<?= base_url('admin/prompt') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/prompt') !== false ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' ?>">
                    <i class="fas fa-comment-dots w-5"></i>
                    <span>Prompt</span>
                </a>
            </li>
            
            <li>
                <a href="<?= base_url('admin/invitation/guide') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/invitation/guide' ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' ?>">
                    <i class="fas fa-book w-5"></i>
                    <span>Panduan JSON</span>
                </a>
            </li>
            
            <li>
                <button onclick="toggleMenu('settingsMenu')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-blue-700/50 hover:text-white">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-cog w-5"></i>
                        <span>Pengaturan</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="settingsMenuIcon"></i>
                </button>
                <ul id="settingsMenu" class="ml-4 mt-1 space-y-1 hidden">
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-sliders-h w-4"></i><span>Umum</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-paint-brush w-4"></i><span>Tampilan</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-envelope w-4"></i><span>Email</span></a></li>
                    <li><a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-700/50 hover:text-white"><i class="fas fa-plug w-4"></i><span>Integrasi</span></a></li>
                </ul>
            </li>
            
            <li>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-blue-700/50 hover:text-white">
                    <i class="fas fa-question-circle w-5"></i>
                    <span>Bantuan</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="p-4 border-t border-blue-700">
        <div class="flex items-center space-x-2 text-blue-200 text-sm">
            <i class="fas fa-code"></i>
            <span>Version 1.0.0</span>
        </div>
    </div>
</aside>

<script>
function toggleMenu(menuId) {
    const menu = document.getElementById(menuId);
    const icon = document.getElementById(menuId + 'Icon');
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        menu.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}
</script>
