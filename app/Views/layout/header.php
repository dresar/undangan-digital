<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
    <div class="px-4 md:px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3 md:space-x-4">
            <button id="hamburgerBtn" class="md:hidden text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-2 transition-colors" aria-label="Toggle Menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-lg md:text-xl font-semibold text-gray-800 m-0">Admin Panel</h1>
        </div>
        <div class="flex items-center space-x-3 md:space-x-4">
            <button class="relative text-gray-600 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-100" onclick="document.getElementById('notificationCenter').style.display = document.getElementById('notificationCenter').style.display === 'none' ? 'block' : 'none'">
                <i class="fas fa-bell text-lg md:text-xl"></i>
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden" id="notificationBadge">0</span>
            </button>
        </div>
    </div>
</header>

