<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Prompt extends BaseController
{
    public function index()
    {
        $variables = [
            'title' => 'Judul undangan',
            'groom_name' => 'Nama mempelai pria',
            'bride_name' => 'Nama mempelai wanita',
            'groom_parents' => 'Orang tua mempelai pria',
            'bride_parents' => 'Orang tua mempelai wanita',
            'wedding_date' => 'Tanggal acara (format: Y-m-d H:i:s)',
            'wedding_location' => 'Lokasi acara',
            'wedding_address' => 'Alamat lengkap',
            'contact_phone' => 'No. telepon',
            'contact_email' => 'Email',
            'contact_whatsapp' => 'WhatsApp',
            'music_url' => 'URL musik',
            'video_url' => 'URL video',
            'cover_image' => 'Cover image',
            'category' => 'Kategori',
            'tags' => 'Tags',
        ];

        $defaultPrompt = $this->getDefaultPrompt();

        $data = [
            'variables' => $variables,
            'defaultPrompt' => $defaultPrompt,
        ];

        return view('admin/prompt/index', $data);
    }

    private function getDefaultPrompt()
    {
        return <<<'PROMPT'
Buatkan template undangan digital LENGKAP dan PROFESIONAL dalam 1 file PHP dengan minimal 800 baris kode. 

REQUIREMENTS WAJIB:
1. MINIMAL 800 BARIS KODE (HTML + CSS + JS dalam 1 file)
2. Gunakan PHP untuk logic dan variable
3. Gunakan Tailwind CSS CDN untuk styling utama
4. Semua gambar HARUS dari CDN (unsplash.com, pixabay.com, pexels.com) - JANGAN gunakan local file
5. Include SEMUA CSS dan JavaScript dalam 1 file (tag <style> dan <script>)
6. Variabel WAJIB digunakan, jika belum ada kasih data hardcode dulu
7. WAJIB menggunakan AOS (Animate On Scroll) library untuk animasi scroll
8. WAJIB ada animasi font/text yang smooth dan elegan
9. WAJIB responsive mobile-first design
10. WAJIB ada banyak animasi transisi yang smooth

VARIABLE YANG TERSEDIA (WAJIB DIGUNAKAN SEMUA):
- \$invitation['title'] - Judul undangan
- \$invitation['groom_name'] - Nama mempelai pria
- \$invitation['bride_name'] - Nama mempelai wanita  
- \$invitation['groom_parents'] - Orang tua mempelai pria
- \$invitation['bride_parents'] - Orang tua mempelai wanita
- \$invitation['wedding_date'] - Tanggal acara (format: Y-m-d H:i:s)
- \$invitation['wedding_location'] - Lokasi acara
- \$invitation['wedding_address'] - Alamat lengkap
- \$invitation['location_map_url'] - URL Google Maps atau embed map
- \$invitation['location_map_image'] - Gambar denah lokasi (CDN)
- \$invitation['contact_phone'] - No. telepon
- \$invitation['contact_email'] - Email
- \$invitation['contact_whatsapp'] - WhatsApp
- \$invitation['music_url'] - URL musik
- \$invitation['video_url'] - URL video
- \$invitation['cover_image'] - Cover image
- \$invitation['recipient_name'] - Nama penerima undangan (kirim ke siapa)
- \$invitation['recipient_title'] - Gelar penerima (Bapak/Ibu/Saudara/i) - default: "Bapak/Ibu/Saudara/i"
- \$invitation['recipient_address'] - Alamat penerima (Di Tempat)
- \$invitation['invitation_url'] - URL undangan untuk sharing
- \$invitation['gallery_images'] - Array URL gambar gallery (maksimal 10 gambar)

RULES PENTING:
1. Jika variable belum ada, gunakan data hardcode:
   - \$groomName = !empty(\$invitation['groom_name']) ? esc(\$invitation['groom_name']) : 'John Doe';
   - \$brideName = !empty(\$invitation['bride_name']) ? esc(\$invitation['bride_name']) : 'Jane Smith';
   - \$weddingDate = !empty(\$invitation['wedding_date']) ? \$invitation['wedding_date'] : '2024-12-25 10:00:00';
   - \$coverImage = !empty(\$invitation['cover_image']) ? esc(\$invitation['cover_image'], 'attr') : 'https://images.unsplash.com/photo-1519741497674-611481863552';

2. SELALU gunakan esc() untuk output: <?= esc(\$invitation['title']) ?>

3. CDN Libraries WAJIB:
   - Tailwind CSS: <script src="https://cdn.tailwindcss.com"></script>
   - AOS (Animate On Scroll): <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
   - AOS JS: <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
   - Swiper (untuk carousel/slider): <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
   - Swiper JS: <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
   - Google Fonts: <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

4. Semua gambar dari CDN:
   - Background hero: https://images.unsplash.com/photo-1519741497674-611481863552
   - Background section: https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6
   - Decoration flowers: https://images.unsplash.com/photo-1518621012428-6a5c0e8e5b9a
   - Gallery images: https://images.unsplash.com/photo-...

5. JANGAN pisahkan CSS dan JS ke file terpisah - SEMUA dalam 1 file PHP

FITUR YANG WAJIB ADA (DETAIL LENGKAP):

0. INTRO/SPLASH SCREEN (WAJIB - HARUS DIKLIK DULU):
   - Full screen overlay dengan background blur atau gradient
   - Tampilkan alamat lengkap penerima dengan format:
     "Kepada Yth.
      [recipient_title] [recipient_name]
      [recipient_address]"
   - Contoh: "Kepada Yth. Bapak/Ibu/Saudara/i John Doe Di Tempat"
   - Jika recipient_name kosong, gunakan: "Kepada Yth. Bapak/Ibu/Saudara/i Tamu Undangan Di Tempat"
   - Tombol besar "Buka Undangan" di tengah dengan animasi pulse
   - Setelah klik, intro fade out dan reveal konten utama
   - JANGAN auto-scroll atau auto-open, WAJIB user klik dulu
   - Di mobile: tombol harus besar dan mudah diklik (minimal 60px height)

1. COVER SECTION (setelah intro):
   - Full screen height dengan background image dari CDN
   - Tampilkan nama mempelai dengan besar dan jelas
   - Tampilkan alamat penerima di cover: "Kepada Yth. [recipient_title] [recipient_name] [recipient_address]" (jika ada)
   - Animasi fade-in dan slide-up untuk nama
   - Typography animasi (text muncul dengan efek typing atau fade)
   - Parallax effect pada background
   - Smooth scroll indicator dengan animasi bounce
   - Overlay gradient untuk readability
   - Di mobile: font size besar dan readable (minimal 24px untuk nama)

2. COUNTDOWN TIMER (dengan animasi):
   - Countdown ke tanggal pernikahan dengan format: Hari, Jam, Menit, Detik
   - Setiap angka dengan animasi flip/rotate saat berubah
   - Background card dengan glassmorphism effect
   - Responsive grid layout
   - Animasi pulse pada angka yang akan berubah

3. COUPLE SECTION (dengan animasi):
   - Foto mempelai (gunakan CDN placeholder jika tidak ada)
   - Nama dengan animasi font (fade-in, slide, atau typewriter effect)
   - Quote/kata mutiara dengan animasi
   - Decorative elements (bunga, hearts) dengan animasi float
   - Timeline love story dengan animasi scroll

4. EVENT DETAILS (dengan animasi):
   - Lokasi dengan Google Maps embed atau link
   - Alamat lengkap dengan icon animasi
   - Tanggal & waktu dengan calendar icon
   - Card design dengan hover effects
   - AOS animation: fade-up, fade-left, fade-right

5. DENAH LOKASI SECTION (WAJIB - PALING BAWAH):
   - Section khusus untuk denah lokasi acara
   - Tampilkan gambar denah lokasi (location_map_image dari CDN)
   - Jika location_map_url tersedia, tampilkan Google Maps embed atau link
   - Judul: "Denah Lokasi" atau "Peta Lokasi"
   - Layout: Gambar denah di atas, map di bawah (atau sebaliknya)
   - Di mobile: gambar denah full width, map responsive
   - AOS animation: fade-up dengan delay
   - Card dengan shadow dan border radius

6. PARENTS SECTION:
   - Orang tua mempelai pria dengan foto (CDN)
   - Orang tua mempelai wanita dengan foto (CDN)
   - Layout dengan grid responsive
   - Hover effects dengan scale transform

7. GALLERY FOTO KAMI SECTION (PENTING UNTUK MOBILE):
   - MAKSIMAL 10 foto (jika gallery_images tersedia, gunakan itu, jika tidak gunakan CDN unsplash)
   - Judul section: "Gallery Foto Kami"
   - Desktop: Grid layout 3-4 kolom dengan masonry effect
   - MOBILE WAJIB: 2 kolom grid (grid-cols-2) untuk gambar
   - MOBILE WAJIB: Gunakan Swiper carousel untuk slide gambar (swipe left/right) - maksimal 10 slide
   - Swiper dengan navigation dots di bawah dan arrow navigation
   - Lightbox dengan FancyBox atau custom modal saat klik gambar
   - Lazy loading images
   - Hover effects dengan overlay (subtle, tidak terlalu banyak gerakan)
   - Card animasi: fade-in saat scroll, TAPI tidak banyak gerakan (hanya opacity + slight scale)
   - Jika kurang dari 10 gambar, isi dengan placeholder dari CDN

8. RSVP SECTION:
   - Form RSVP dengan validasi
   - Input fields dengan floating labels
   - Submit button dengan loading animation
   - Success/error messages dengan slide animation

9. CONTACT SECTION:
   - Phone, Email, WhatsApp dengan icon
   - Tombol copy dengan Clipboard.js
   - Social media links (jika ada)
   - Hover effects dengan scale dan color change

10. MUSIC/VIDEO PLAYER:
   - Audio player untuk music_url
   - Video embed untuk video_url
   - Custom player design dengan controls
   - Play/pause animations

11. BOTTOM NAVIGATION BAR (WAJIB - seperti di mobile app):
    - Fixed di bottom dengan background putih/glassmorphism
    - Icon navigation dengan label: Home, Gallery, RSVP, Kontak, Share
    - Active state dengan highlight
    - Smooth transition saat switch menu
    - Di mobile: selalu visible, di desktop bisa hide saat scroll down
    - Icon: Home (house), Gallery (images), RSVP (calendar), Kontak (phone), Share (share)

12. FOOTER:
    - Thank you message
    - Copyright info
    - Scroll to top button dengan smooth animation
    - Share buttons untuk social media (jika invitation_url tersedia)

ANIMASI YANG WAJIB ADA:
1. AOS (Animate On Scroll) - WAJIB digunakan:
   - data-aos="fade-up" untuk sections
   - data-aos="fade-left" untuk konten kiri
   - data-aos="fade-right" untuk konten kanan
   - data-aos="zoom-in" untuk images
   - data-aos="flip-left" untuk cards
   - data-aos-delay untuk staggered animations

2. Font/Text Animations:
   - Typing effect untuk nama mempelai
   - Fade-in dengan delay untuk teks
   - Letter spacing animation
   - Text gradient animation
   - Text shadow glow effect

3. Scroll Animations:
   - Smooth scroll behavior
   - Parallax pada background
   - Sticky header saat scroll
   - Progress bar untuk scroll position

4. Hover Animations (SUBTLE - tidak banyak gerakan):
   - Scale transform pada cards (maksimal 1.05, tidak lebih)
   - Color transitions yang smooth
   - Shadow effects yang halus
   - Border animations minimal
   - Di mobile: disable hover effects, gunakan touch feedback

5. Loading Animations:
   - Page loader dengan spinner
   - Skeleton loading untuk images
   - Fade-in page content

6. Transitions:
   - Smooth transitions untuk semua elements
   - Ease-in-out timing functions
   - Transform dan opacity transitions

STYLING REQUIREMENTS:
1. Tailwind CSS untuk utility classes
2. Custom CSS di <style> tag untuk:
   - Custom animations (@keyframes)
   - Font styling (Playfair Display untuk heading, Poppins untuk body)
   - Custom gradients
   - Glassmorphism effects
   - Custom shadows

3. Color Scheme:
   - Primary: Gold/Rose Gold (#D4AF37 atau #E8B4B8)
   - Secondary: Soft Pink/White
   - Accent: Deep Rose (#C2185B)
   - Background: White dengan gradient overlays

4. Typography:
   - Heading: Playfair Display (elegant, serif)
   - Body: Poppins (clean, sans-serif)
   - Font sizes responsive dengan clamp()

5. Spacing:
   - Generous padding dan margin
   - Consistent spacing system
   - Mobile: smaller spacing, Desktop: larger spacing

RESPONSIVE DESIGN (PALING PENTING - MOBILE WAJIB SEMPURNA):
1. Mobile First Approach - SEMUA di-design untuk mobile dulu
2. Breakpoints:
   - Mobile: < 640px (PRIORITAS UTAMA)
   - Tablet: 640px - 1024px
   - Desktop: > 1024px
3. Grid systems yang responsive:
   - Mobile: 1-2 kolom untuk cards/gallery
   - Tablet: 2-3 kolom
   - Desktop: 3-4 kolom
4. Images yang responsive:
   - Mobile: gunakan object-fit: cover, width 100%
   - Lazy loading wajib
   - Optimize dengan srcset jika memungkinkan
5. Font sizes yang responsive:
   - Mobile: minimal 14px untuk body, 20px untuk heading
   - Gunakan clamp() untuk fluid typography
   - Line height yang readable (minimal 1.5)
6. Navigation yang mobile-friendly:
   - Bottom navigation bar selalu accessible
   - Touch target minimal 44x44px
   - Smooth scroll dengan offset untuk fixed header
7. Touch interactions:
   - Swipe untuk gallery carousel
   - Tap feedback yang jelas
   - No hover-only interactions di mobile
8. Performance mobile:
   - Minimize animations di mobile (reduce motion jika perlu)
   - Optimize images
   - Lazy load semua heavy content

JAVASCRIPT REQUIREMENTS:
1. Intro/Splash Screen Logic:
   - Check localStorage untuk skip intro jika sudah pernah buka
   - Show intro overlay dengan nama recipient
   - On click "Buka Undangan", hide intro dan show main content
   - Smooth fade transition
2. AOS initialization: AOS.init() dengan mobile optimization
3. Smooth scroll untuk anchor links dengan offset
4. Countdown timer dengan update real-time
5. Swiper initialization untuk gallery carousel (mobile)
6. Bottom navigation active state management
7. Form validation
8. Clipboard.js untuk copy functionality
9. Lazy loading untuk images
10. Intersection Observer untuk scroll animations (reduce di mobile)
11. Touch/swipe handlers untuk mobile
12. Share functionality jika invitation_url tersedia

OUTPUT FORMAT:
Berikan 1 file PHP LENGKAP minimal 800 baris dengan struktur:

<?php
// Set semua variable dengan fallback hardcode
\$groomName = !empty(\$invitation['groom_name']) ? esc(\$invitation['groom_name']) : 'John Doe';
\$brideName = !empty(\$invitation['bride_name']) ? esc(\$invitation['bride_name']) : 'Jane Smith';
\$title = !empty(\$invitation['title']) ? esc(\$invitation['title']) : 'Undangan Pernikahan';
\$recipientName = !empty(\$invitation['recipient_name']) ? esc(\$invitation['recipient_name']) : 'Tamu Undangan';
\$recipientTitle = !empty(\$invitation['recipient_title']) ? esc(\$invitation['recipient_title']) : 'Bapak/Ibu/Saudara/i';
\$recipientAddress = !empty(\$invitation['recipient_address']) ? esc(\$invitation['recipient_address']) : 'Di Tempat';
\$invitationUrl = !empty(\$invitation['invitation_url']) ? esc(\$invitation['invitation_url'], 'attr') : '';
\$weddingDate = !empty(\$invitation['wedding_date']) ? \$invitation['wedding_date'] : '2024-12-25 10:00:00';
\$weddingLocation = !empty(\$invitation['wedding_location']) ? esc(\$invitation['wedding_location']) : 'Grand Ballroom Hotel';
\$weddingAddress = !empty(\$invitation['wedding_address']) ? esc(\$invitation['wedding_address']) : 'Jl. Contoh No. 123, Jakarta';
\$locationMapUrl = !empty(\$invitation['location_map_url']) ? esc(\$invitation['location_map_url'], 'attr') : '';
\$locationMapImage = !empty(\$invitation['location_map_image']) ? esc(\$invitation['location_map_image'], 'attr') : 'https://images.unsplash.com/photo-1524661135-423995f22d0b';
\$groomParents = !empty(\$invitation['groom_parents']) ? esc(\$invitation['groom_parents']) : 'Bapak & Ibu John Doe';
\$brideParents = !empty(\$invitation['bride_parents']) ? esc(\$invitation['bride_parents']) : 'Bapak & Ibu Jane Smith';
\$contactPhone = !empty(\$invitation['contact_phone']) ? esc(\$invitation['contact_phone']) : '+62 812-3456-7890';
\$contactEmail = !empty(\$invitation['contact_email']) ? esc(\$invitation['contact_email']) : 'contact@example.com';
\$contactWhatsapp = !empty(\$invitation['contact_whatsapp']) ? esc(\$invitation['contact_whatsapp']) : '+62 812-3456-7890';
\$musicUrl = !empty(\$invitation['music_url']) ? esc(\$invitation['music_url'], 'attr') : '';
\$videoUrl = !empty(\$invitation['video_url']) ? esc(\$invitation['video_url'], 'attr') : '';
\$coverImage = !empty(\$invitation['cover_image']) ? esc(\$invitation['cover_image'], 'attr') : 'https://images.unsplash.com/photo-1519741497674-611481863552';
\$galleryImages = !empty(\$invitation['gallery_images']) ? json_decode(\$invitation['gallery_images'], true) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \$title ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Clipboard.js -->
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <style>
    /* MINIMAL 200 BARIS CSS dengan:
       - Custom animations (@keyframes)
       - Font styling
       - Gradients
       - Glassmorphism
       - Transitions
       - Responsive styles
    */
    </style>
</head>
<body>
    <!-- MINIMAL 400 BARIS HTML dengan:
         - Semua sections lengkap
         - AOS attributes
         - Tailwind classes
         - Responsive design
    -->
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    // MINIMAL 200 BARIS JavaScript dengan:
    // - AOS.init()
    // - Countdown timer
    // - Smooth scroll
    // - Form validation
    // - Clipboard.js
    // - Lazy loading
    // - Scroll animations
    </script>
</body>
</html>

PENTING: 
- Kode HARUS minimal 800 baris
- HARUS ada banyak animasi dan transisi (TAPI subtle, tidak terlalu banyak gerakan)
- HARUS menggunakan AOS untuk scroll animations
- HARUS ada font/text animations
- HARUS responsive dan profesional
- MOBILE ADALAH PRIORITAS UTAMA - semua harus perfect di mobile
- Intro/Splash screen WAJIB ada dan WAJIB diklik dulu
- Bottom navigation bar WAJIB ada
- Gallery di mobile WAJIB 2 kolom grid + Swiper carousel
- Card animasi subtle (fade + slight scale, tidak banyak gerakan)
- Komentar di kode minimalis (hanya yang penting)
- JANGAN buat kode singkat atau sederhana
PROMPT;
    }
}

