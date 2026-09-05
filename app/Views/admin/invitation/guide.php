<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Panduan JSON - Admin Panel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Panduan JSON Schema</h2>
        <a href="<?= base_url('admin/invitation') ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">Struktur Content JSON</h4>
            <p class="text-gray-700 mb-4">Content JSON adalah array yang berisi objek-objek dengan property <code class="px-2 py-1 bg-gray-100 rounded text-sm">type</code> untuk menentukan jenis cell yang akan dirender.</p>
            
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <strong class="text-blue-800">Format Dasar:</strong>
                <pre class="mt-2 mb-0 bg-white p-4 rounded border border-gray-200 overflow-x-auto"><code>[
  {
    "type": "hero",
    "data": { ... }
  },
  {
    "type": "quote",
    "data": { ... }
  }
]</code></pre>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-lg">
                <h5 class="text-white font-semibold m-0">1. HeroCell - Tampilan Utama Mempelai</h5>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Menampilkan nama mempelai dan foto utama.</p>
                <div class="mb-4">
                    <button class="px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm clipboard-btn" data-clipboard-target="#heroSnippet">
                        <i class="fas fa-clipboard mr-2"></i>Copy Snippet
                    </button>
                </div>
                <pre id="heroSnippet" class="bg-gray-100 p-4 rounded-lg border border-gray-200 overflow-x-auto"><code>{
  "type": "hero",
  "data": {
    "groom_name": "John Doe",
    "bride_name": "Jane Smith",
    "photo": "https://example.com/photo.jpg",
    "subtitle": "Kami mengundang Anda untuk merayakan hari bahagia kami",
    "bg_color_start": "#667eea",
    "bg_color_end": "#764ba2"
  }
}</code></pre>
                <div class="mt-4">
                    <strong class="text-gray-800">Property yang tersedia:</strong>
                    <ul class="mt-2 space-y-1 text-gray-700">
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">groom_name</code> - Nama mempelai pria</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">bride_name</code> - Nama mempelai wanita</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">photo</code> - URL foto (opsional)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">subtitle</code> - Subtitle (opsional)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">bg_color_start</code> - Warna gradient awal (default: #667eea)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">bg_color_end</code> - Warna gradient akhir (default: #764ba2)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-lg">
                <h5 class="text-white font-semibold m-0">2. QuoteCell - Kata Mutiara</h5>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Menampilkan kutipan atau kata mutiara.</p>
                <div class="mb-4">
                    <button class="px-4 py-2 border border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors text-sm clipboard-btn" data-clipboard-target="#quoteSnippet">
                        <i class="fas fa-clipboard mr-2"></i>Copy Snippet
                    </button>
                </div>
                <pre id="quoteSnippet" class="bg-gray-100 p-4 rounded-lg border border-gray-200 overflow-x-auto"><code>{
  "type": "quote",
  "data": {
    "quote": "Cinta adalah ketika dua jiwa bertemu dan saling melengkapi",
    "author": "Unknown",
    "bg_color": "#f8f9fa",
    "text_color": "#333"
  }
}</code></pre>
                <div class="mt-4">
                    <strong class="text-gray-800">Property yang tersedia:</strong>
                    <ul class="mt-2 space-y-1 text-gray-700">
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">quote</code> - Teks kutipan</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">author</code> - Penulis (opsional)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">bg_color</code> - Warna background (default: #f8f9fa)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">text_color</code> - Warna teks (default: #333)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4 rounded-t-lg">
                <h5 class="text-white font-semibold m-0">3. GalleryCell - Galeri Foto</h5>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Menampilkan grid foto dalam galeri.</p>
                <div class="mb-4">
                    <button class="px-4 py-2 border border-yellow-500 text-yellow-600 rounded-lg hover:bg-yellow-50 transition-colors text-sm clipboard-btn" data-clipboard-target="#gallerySnippet">
                        <i class="fas fa-clipboard mr-2"></i>Copy Snippet
                    </button>
                </div>
                <pre id="gallerySnippet" class="bg-gray-100 p-4 rounded-lg border border-gray-200 overflow-x-auto"><code>{
  "type": "gallery",
  "data": {
    "title": "Galeri Foto",
    "images": [
      {
        "url": "https://example.com/photo1.jpg",
        "alt": "Foto 1"
      },
      {
        "url": "https://example.com/photo2.jpg",
        "alt": "Foto 2"
      }
    ],
    "columns": 3,
    "bg_color": "#ffffff",
    "title_color": "#333"
  }
}</code></pre>
                <div class="mt-4">
                    <strong class="text-gray-800">Property yang tersedia:</strong>
                    <ul class="mt-2 space-y-1 text-gray-700">
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">title</code> - Judul galeri (opsional)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">images</code> - Array objek dengan <code class="px-1 py-0.5 bg-gray-100 rounded text-xs">url</code> dan <code class="px-1 py-0.5 bg-gray-100 rounded text-xs">alt</code></li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">columns</code> - Jumlah kolom (default: 3)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">bg_color</code> - Warna background (default: #ffffff)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">title_color</code> - Warna judul (default: #333)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-lg">
                <h5 class="text-white font-semibold m-0">4. RsvpCell - Form Konfirmasi Kehadiran</h5>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Menampilkan form untuk konfirmasi kehadiran.</p>
                <div class="mb-4">
                    <button class="px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm clipboard-btn" data-clipboard-target="#rsvpSnippet">
                        <i class="fas fa-clipboard mr-2"></i>Copy Snippet
                    </button>
                </div>
                <pre id="rsvpSnippet" class="bg-gray-100 p-4 rounded-lg border border-gray-200 overflow-x-auto"><code>{
  "type": "rsvp",
  "data": {
    "title": "Konfirmasi Kehadiran",
    "bg_color": "#f8f9fa",
    "title_color": "#333"
  }
}</code></pre>
                <div class="mt-4">
                    <strong class="text-gray-800">Property yang tersedia:</strong>
                    <ul class="mt-2 space-y-1 text-gray-700">
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">title</code> - Judul form (opsional)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">bg_color</code> - Warna background (default: #f8f9fa)</li>
                        <li><code class="px-2 py-1 bg-gray-100 rounded text-sm">title_color</code> - Warna judul (default: #333)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4 rounded-t-lg">
                <h5 class="text-white font-semibold m-0">Contoh Lengkap</h5>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Berikut adalah contoh lengkap content JSON dengan semua cell:</p>
                <div class="mb-4">
                    <button class="px-4 py-2 border border-gray-500 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm clipboard-btn" data-clipboard-target="#fullSnippet">
                        <i class="fas fa-clipboard mr-2"></i>Copy Snippet Lengkap
                    </button>
                </div>
                <pre id="fullSnippet" class="bg-gray-100 p-4 rounded-lg border border-gray-200 overflow-x-auto max-h-[500px] overflow-y-auto"><code>[
  {
    "type": "hero",
    "data": {
      "groom_name": "John Doe",
      "bride_name": "Jane Smith",
      "photo": "https://example.com/photo.jpg",
      "subtitle": "Kami mengundang Anda untuk merayakan hari bahagia kami",
      "bg_color_start": "#667eea",
      "bg_color_end": "#764ba2"
    }
  },
  {
    "type": "quote",
    "data": {
      "quote": "Cinta adalah ketika dua jiwa bertemu dan saling melengkapi",
      "author": "Unknown",
      "bg_color": "#f8f9fa",
      "text_color": "#333"
    }
  },
  {
    "type": "gallery",
    "data": {
      "title": "Galeri Foto",
      "images": [
        {
          "url": "https://example.com/photo1.jpg",
          "alt": "Foto 1"
        },
        {
          "url": "https://example.com/photo2.jpg",
          "alt": "Foto 2"
        }
      ],
      "columns": 3,
      "bg_color": "#ffffff",
      "title_color": "#333"
    }
  },
  {
    "type": "rsvp",
    "data": {
      "title": "Konfirmasi Kehadiran",
      "bg_color": "#f8f9fa",
      "title_color": "#333"
    }
  }
]</code></pre>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
