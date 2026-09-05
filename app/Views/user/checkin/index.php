<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 text-center">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    <i class="fas fa-check-circle mr-2"></i>Check-In
                </h1>
                <p class="text-blue-100"><?= esc($invitation['title'] ?? 'Undangan') ?></p>
            </div>
            
            <!-- Content -->
            <div class="p-6 md:p-8">
                <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($invitation['check_in_card_instructions'])): ?>
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <?= esc($invitation['check_in_card_instructions']) ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <form id="checkInForm" class="space-y-6">
                    <div>
                        <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Nama Tamu
                        </label>
                        <input 
                            type="text" 
                            id="guest_name" 
                            name="guest_name" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                            placeholder="Masukkan nama Anda"
                            autofocus
                        >
                    </div>
                    
                    <div id="message" class="hidden p-4 rounded-lg"></div>
                    
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105"
                    >
                        <i class="fas fa-check mr-2"></i>Check-In
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="<?= base_url($invitation['slug']) ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Undangan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.getElementById('checkInForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const messageDiv = document.getElementById('message');
        const guestName = document.getElementById('guest_name').value.trim();
        
        if (!guestName) {
            messageDiv.className = 'p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg';
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Nama tamu harus diisi';
            messageDiv.classList.remove('hidden');
            return;
        }
        
        // Disable button dan show loading
        submitBtn.disabled = true;
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        
        // Submit form
        fetch('<?= base_url('checkin/' . $invitation['slug']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                guest_name: guestName
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                messageDiv.className = 'p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg';
                messageDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + result.message;
                messageDiv.classList.remove('hidden');
                
                form.reset();
                
                // Redirect setelah 2 detik
                setTimeout(() => {
                    window.location.href = '<?= base_url($invitation['slug']) ?>';
                }, 2000);
            } else {
                messageDiv.className = 'p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>' + (result.message || 'Terjadi kesalahan');
                messageDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.className = 'p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg';
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Terjadi kesalahan. Silakan coba lagi.';
            messageDiv.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        });
    });
    </script>
</body>
</html>

