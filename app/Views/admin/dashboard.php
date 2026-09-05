<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard - Admin Panel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-100 rounded-lg p-4">
                        <i class="fas fa-envelope-open-text text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Total Undangan</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($total_invitations) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-green-100 rounded-lg p-4">
                        <i class="fas fa-eye text-green-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Total Views</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($total_views) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-100 rounded-lg p-4">
                        <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Published</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($published_count) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-yellow-100 rounded-lg p-4">
                        <i class="fas fa-file-alt text-yellow-600 text-2xl"></i>
                    </div>
                </div>
                <div class="flex-grow ml-4">
                    <p class="text-sm text-gray-600 mb-1">Draft</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?= number_format($draft_count) ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h5 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h5>
        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('admin/invitation/create') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus-circle mr-2"></i>Buat Undangan Baru
            </a>
            <a href="<?= base_url('admin/invitation') ?>" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                <i class="fas fa-list-ul mr-2"></i>Lihat Semua Undangan
            </a>
            <a href="<?= base_url('admin/invitation/guide') ?>" class="px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                <i class="fas fa-book mr-2"></i>Panduan JSON
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
