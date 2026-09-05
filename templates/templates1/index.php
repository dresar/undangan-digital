<!DOCTYPE html>
<html lang="{lang}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{meta_description}">
    <meta name="keywords" content="undangan digital, wedding invitation, {groom_name}, {bride_name}">
    <meta property="og:title" content="{title}">
    <meta property="og:description" content="{meta_description}">
    <meta property="og:image" content="{og_image}">
    <meta property="og:url" content="{invitation_url}">
    <meta name="twitter:card" content="summary_large_image">
    <title>{title}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&family=Dancing+Script:wght@400;500;600;700&family=Great+Vibes&family=Sacramento&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Swiper JS for Gallery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Lightbox2 for Image Gallery -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    
    <!-- Countdown Timer Library -->
    <script src="https://cdn.jsdelivr.net/npm/countdown@2.6.0/dist/countdown.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        
        .font-dancing {
            font-family: 'Dancing Script', cursive;
        }
        
        .font-great-vibes {
            font-family: 'Great Vibes', cursive;
        }
        
        .font-sacramento {
            font-family: 'Sacramento', cursive;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover;
            opacity: 0.3;
            z-index: -1;
        }
        
        .hero-content {
            text-align: center;
            color: white;
            z-index: 1;
            padding: 2rem;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInDown 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        
        .hero-date {
            font-size: 2rem;
            font-weight: 600;
            margin-top: 2rem;
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Fade In Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Section Styling */
        .section {
            padding: 5rem 1rem;
            position: relative;
        }
        
        .section-title {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 3rem;
            color: #333;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Card Styling */
        .card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        /* Countdown Timer */
        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 3rem 0;
            flex-wrap: wrap;
        }
        
        .countdown-item {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            min-width: 120px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .countdown-number {
            font-size: 3rem;
            font-weight: 700;
            color: #667eea;
            display: block;
        }
        
        .countdown-label {
            font-size: 1rem;
            color: #666;
            margin-top: 0.5rem;
        }
        
        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 2rem 0;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            aspect-ratio: 1;
            cursor: pointer;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(102, 126, 234, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            padding: 2rem 0;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 3rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 4px solid white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            left: 9px;
            top: 20px;
            width: 2px;
            height: calc(100% + 1rem);
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        
        .timeline-item:last-child::after {
            display: none;
        }
        
        /* RSVP Form */
        .rsvp-form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        /* Music Player */
        .music-player {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .music-player:hover {
            transform: scale(1.1);
        }
        
        .music-player.playing {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }
            50% {
                box-shadow: 0 4px 25px rgba(102, 126, 234, 0.8);
            }
        }
        
        /* Decorative SVG */
        .decorative-svg {
            position: absolute;
            opacity: 0.1;
            z-index: -1;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .countdown-container {
                gap: 1rem;
            }
            
            .countdown-item {
                min-width: 80px;
                padding: 1.5rem;
            }
            
            .countdown-number {
                font-size: 2rem;
            }
        }
        
        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 100px;
            right: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Music Player -->
    <div class="music-player" id="musicPlayer" onclick="toggleMusic()">
        <i class="fas fa-music" id="musicIcon"></i>
    </div>
    <audio id="backgroundMusic" loop>
        <source src="{music_url}" type="audio/mpeg">
    </audio>
    
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="hero-content">
            <div class="floating" data-aos="fade-down" data-aos-duration="1000">
                <svg class="decorative-svg" width="200" height="200" style="top: 10%; left: 10%;">
                    <path d="M100,50 Q150,100 100,150 Q50,100 100,50" fill="none" stroke="white" stroke-width="2" opacity="0.3"/>
                </svg>
                <h1 class="hero-title font-great-vibes">{title}</h1>
            </div>
            <p class="hero-subtitle font-dancing" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                Dengan memohon rahmat dan ridho Allah SWT
            </p>
            <div class="flex items-center justify-center gap-4 mt-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg mx-auto mb-4">
                        <img src="{groom_image}" alt="{groom_name}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-2xl font-bold text-white">{groom_name}</h3>
                    <p class="text-white opacity-90 mt-2">
                        <i class="fas fa-user-tie mr-2"></i>Putra dari
                    </p>
                    <p class="text-white opacity-90">{groom_father_name} & {groom_mother_name}</p>
                </div>
                <div class="text-4xl text-white font-bold mx-4">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg mx-auto mb-4">
                        <img src="{bride_image}" alt="{bride_name}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-2xl font-bold text-white">{bride_name}</h3>
                    <p class="text-white opacity-90 mt-2">
                        <i class="fas fa-user-tie mr-2"></i>Putri dari
                    </p>
                    <p class="text-white opacity-90">{bride_father_name} & {bride_mother_name}</p>
                </div>
            </div>
            <div class="hero-date font-playfair" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                <i class="fas fa-calendar-alt mr-2"></i>
                {wedding_day_name}, {wedding_day} {wedding_month_name} {wedding_year}
            </div>
            <div class="mt-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="800">
                <a href="#countdown" class="btn-gradient inline-block">
                    <i class="fas fa-arrow-down mr-2"></i>Lihat Detail
                </a>
            </div>
        </div>
        
        <!-- Decorative SVG Elements -->
        <svg class="decorative-svg" width="300" height="300" style="top: 5%; right: 5%;">
            <circle cx="150" cy="150" r="100" fill="none" stroke="white" stroke-width="2" opacity="0.2"/>
            <circle cx="150" cy="150" r="70" fill="none" stroke="white" stroke-width="1" opacity="0.2"/>
        </svg>
        <svg class="decorative-svg" width="200" height="200" style="bottom: 10%; left: 5%;">
            <polygon points="100,20 180,80 100,140 20,80" fill="none" stroke="white" stroke-width="2" opacity="0.2"/>
        </svg>
    </section>
    
    <!-- Countdown Section -->
    <section class="section bg-white" id="countdown">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-hourglass-half mr-3"></i>Hitungan Mundur
            </h2>
            <div class="countdown-container" id="countdownContainer" data-aos="fade-up" data-aos-delay="200">
                <div class="countdown-item">
                    <span class="countdown-number" id="days">00</span>
                    <span class="countdown-label">Hari</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="hours">00</span>
                    <span class="countdown-label">Jam</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="minutes">00</span>
                    <span class="countdown-label">Menit</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="seconds">00</span>
                    <span class="countdown-label">Detik</span>
                </div>
            </div>
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="400">
                <p class="text-xl text-gray-700 mb-4">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Kami menunggu kehadiran Anda pada:
                </p>
                <p class="text-2xl font-bold text-gray-800">
                    {wedding_date_short}
                </p>
            </div>
        </div>
    </section>
    
    <!-- Our Story Section -->
    <section class="section bg-gradient-to-br from-purple-50 to-pink-50" id="story">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-book-heart mr-3"></i>Kisah Kami
            </h2>
            <div class="timeline" data-aos="fade-up" data-aos-delay="200">
                {our_stories_html}
            </div>
        </div>
    </section>
    
    <!-- Event Details Section -->
    <section class="section bg-white" id="events">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-calendar-day mr-3"></i>Detail Acara
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                <!-- Event 1 -->
                <div class="card" data-aos="fade-right" data-aos-delay="200">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-mosque text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{event_name_1}</h3>
                        <div class="space-y-3 text-gray-600">
                            <p class="flex items-center justify-center">
                                <i class="fas fa-clock mr-2 text-purple-500"></i>
                                <span class="font-semibold">{event_time_1}</span>
                            </p>
                            <p class="flex items-center justify-center">
                                <i class="fas fa-calendar mr-2 text-purple-500"></i>
                                <span>{wedding_day_name}, {wedding_day} {wedding_month_name} {wedding_year}</span>
                            </p>
                            <p class="flex items-center justify-center">
                                <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                <span>{wedding_location}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Event 2 -->
                <div class="card" data-aos="fade-left" data-aos-delay="400">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                            <i class="fas fa-glass-cheers text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{event_name_2}</h3>
                        <div class="space-y-3 text-gray-600">
                            <p class="flex items-center justify-center">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                <span class="font-semibold">{event_time_2}</span>
                            </p>
                            <p class="flex items-center justify-center">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                <span>{reception_day_name}, {reception_day} {reception_month_name} {reception_year}</span>
                            </p>
                            <p class="flex items-center justify-center">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                <span>{wedding_location}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Location Map -->
            <div class="mt-12" data-aos="fade-up" data-aos-delay="600">
                {location_map_html}
                {location_map_search_html}
            </div>
        </div>
    </section>
    
    <!-- Gallery Section -->
    <section class="section bg-gradient-to-br from-blue-50 to-purple-50" id="gallery">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-images mr-3"></i>Galeri Foto
            </h2>
            <div class="gallery-grid" data-aos="fade-up" data-aos-delay="200">
                {gallery_html}
            </div>
        </div>
    </section>
    
    <!-- Video Section -->
    <section class="section bg-white" id="video">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-video mr-3"></i>Video Pre-Wedding
            </h2>
            <div class="flex justify-center mt-8" data-aos="fade-up" data-aos-delay="200">
                {video_html}
            </div>
        </div>
    </section>
    
    <!-- Livestream Section -->
    <section class="section bg-gradient-to-br from-pink-50 to-red-50" id="livestream">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-broadcast-tower mr-3"></i>Live Streaming
            </h2>
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="200">
                {livestream_html_1}
            </div>
        </div>
    </section>
    
    <!-- Dress Code Section -->
    <section class="section bg-white" id="dresscode">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-tshirt mr-3"></i>Dress Code
            </h2>
            <div class="max-w-2xl mx-auto mt-8" data-aos="fade-up" data-aos-delay="200">
                <div class="card text-center">
                    <img src="{dress_code_image}" alt="Dress Code" class="w-full rounded-lg mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Aturan Berpakaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div>
                            <h4 class="font-bold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-male mr-2 text-blue-500"></i>Pria
                            </h4>
                            <ul class="space-y-2 text-gray-600">
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Kemeja atau Batik</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Celana panjang</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Sepatu tertutup</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-female mr-2 text-pink-500"></i>Wanita
                            </h4>
                            <ul class="space-y-2 text-gray-600">
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Dress atau Kebaya</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Hijab untuk muslimah</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Sepatu tertutup</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                            Mohon menghindari warna putih, merah, dan hitam polos
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Bank Info Section -->
    <section class="section bg-gradient-to-br from-green-50 to-emerald-50" id="bank">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-university mr-3"></i>Informasi Rekening
            </h2>
            <div class="max-w-md mx-auto mt-8" data-aos="fade-up" data-aos-delay="200">
                {bank_info_html}
            </div>
        </div>
    </section>
    
    <!-- Check In QR Code Section -->
    <section class="section bg-white" id="checkin">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-qrcode mr-3"></i>Check In
            </h2>
            <div class="max-w-md mx-auto mt-8 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="card">
                    <img src="{check_in_qr_code_image}" alt="QR Code Check In" class="w-full max-w-xs mx-auto mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Scan untuk Check In</h3>
                    <p class="text-gray-600 mb-6">
                        Silakan scan QR code di atas untuk melakukan check in kehadiran Anda
                    </p>
                    <a href="{base_url_guestbook}" class="btn-gradient inline-block">
                        <i class="fas fa-sign-in-alt mr-2"></i>Check In Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Instagram Section -->
    <section class="section bg-gradient-to-br from-purple-50 to-pink-50" id="instagram">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fab fa-instagram mr-3"></i>Ikuti Kami
            </h2>
            <div class="flex justify-center gap-8 mt-8" data-aos="fade-up" data-aos-delay="200">
                {groom_instagram_html}
                {bride_instagram_html}
            </div>
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="400">
                {wedding_filter_instagram_html}
            </div>
        </div>
    </section>
    
    <!-- RSVP Section -->
    <section class="section bg-white" id="rsvp">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-envelope-open-text mr-3"></i>Konfirmasi Kehadiran
            </h2>
            <div class="rsvp-form mt-8" data-aos="fade-up" data-aos-delay="200">
                <form id="rsvpForm" action="{base_url_rsvp}" method="POST">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user mr-2"></i>Nama Lengkap
                        </label>
                        <input type="text" name="name" class="form-input" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone mr-2"></i>Nomor Telepon
                        </label>
                        <input type="tel" name="phone" class="form-input" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users mr-2"></i>Jumlah Tamu
                        </label>
                        <input type="number" name="guest_count" class="form-input" required min="1" placeholder="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-check-circle mr-2"></i>Konfirmasi Kehadiran
                        </label>
                        <select name="attendance" class="form-input" required>
                            <option value="">Pilih...</option>
                            <option value="yes">Ya, saya akan hadir</option>
                            <option value="no">Maaf, tidak bisa hadir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-comment mr-2"></i>Pesan / Ucapan
                        </label>
                        <textarea name="message" class="form-input" rows="4" placeholder="Tuliskan pesan atau ucapan Anda..."></textarea>
                    </div>
                    <button type="submit" class="btn-gradient w-full">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Calendar Section -->
    <section class="section bg-gradient-to-br from-blue-50 to-cyan-50" id="calendar">
        <div class="container mx-auto max-w-6xl">
            <h2 class="section-title font-playfair" data-aos="fade-up">
                <i class="fas fa-calendar-alt mr-3"></i>Tambahkan ke Kalender
            </h2>
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="200">
                {calendar_url_html}
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="text-center">
                <h3 class="text-3xl font-bold font-playfair mb-4">{groom_name} & {bride_name}</h3>
                <p class="text-gray-400 mb-6">
                    <i class="fas fa-heart text-red-500 mr-2"></i>
                    Terima kasih telah menjadi bagian dari hari bahagia kami
                    <i class="fas fa-heart text-red-500 ml-2"></i>
                </p>
                <div class="flex justify-center gap-6 mb-6">
                    <a href="{invitation_url}" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                    <a href="{invitation_url}" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="{invitation_url}" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                    <a href="{invitation_url}" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-whatsapp text-2xl"></i>
                    </a>
                </div>
                <p class="text-sm text-gray-500">
                    &copy; 2024 {groom_name} & {bride_name}. Made with <i class="fas fa-heart text-red-500"></i> for you
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <!-- Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Music Player
        const musicPlayer = document.getElementById('musicPlayer');
        const backgroundMusic = document.getElementById('backgroundMusic');
        const musicIcon = document.getElementById('musicIcon');
        let isPlaying = false;
        
        function toggleMusic() {
            if (isPlaying) {
                backgroundMusic.pause();
                musicIcon.classList.remove('fa-pause');
                musicIcon.classList.add('fa-music');
                musicPlayer.classList.remove('playing');
                isPlaying = false;
            } else {
                backgroundMusic.play().catch(e => {
                    console.log('Autoplay prevented:', e);
                });
                musicIcon.classList.remove('fa-music');
                musicIcon.classList.add('fa-pause');
                musicPlayer.classList.add('playing');
                isPlaying = true;
            }
        }
        
        // Countdown Timer
        function updateCountdown() {
            const targetDate = new Date('{countdown_date_js}').getTime();
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            if (distance < 0) {
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();
        
        // Back to Top Button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Form Submission
        document.getElementById('rsvpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading"></span> Mengirim...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual API call)
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('Terima kasih! Konfirmasi kehadiran Anda telah terkirim.');
                this.reset();
            })
            .catch(error => {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Initialize Lightbox
        if (typeof lightbox !== 'undefined') {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'fadeDuration': 300
            });
        }
        
        // Lazy Load Images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-section');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>

