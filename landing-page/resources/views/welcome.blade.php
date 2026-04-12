<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>FixLA - Lapor Jalan Rusak</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen selection:bg-primary selection:text-white">

    <!-- Header / Navbar -->
    <header class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="absolute inset-0 bg-white/70 backdrop-blur-md border-b border-gray-200/50 -z-10 shadow-sm"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center shadow-lg shadow-primary/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">FixLA</span>
                </div>
                
                <nav class="hidden md:flex space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-primary font-medium transition-colors">Home</a>
                    <a href="#fitur" class="text-gray-600 hover:text-primary font-medium transition-colors">Fitur</a>
                    <a href="#about" class="text-gray-600 hover:text-primary font-medium transition-colors">Tentang Kami</a>
                </nav>
                
                <div class="md:hidden">
                    <!-- Mobile menu button can be implemented later -->
                    <button class="p-2 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        
        <!-- Hero Section (Home) -->
        <section id="home" class="relative pt-20 pb-20 lg:pt-32 lg:pb-28 overflow-hidden bg-white">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
            <!-- Decorative blobs -->
            <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 blur-3xl opacity-30 rounded-full w-[600px] h-[600px] bg-gradient-to-br from-primary to-accent -z-10"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 blur-3xl opacity-20 rounded-full w-[400px] h-[400px] bg-gradient-to-tr from-accent to-primary -z-10"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">
                    <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left space-y-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary font-medium text-sm">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                            Kini Hadir di Lamongan
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                            Laporkan Jalan Rusak, <br class="hidden lg:block" />
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">Wujudkan Kota Nyaman</span>
                        </h1>
                        <p class="text-lg md:text-xl text-gray-600 max-w-2xl">
                            Aplikasi FixLA memudahkan warga Lamongan untuk melaporkan infrastruktur jalan yang rusak secara real-time langsung ke Dinas PU.
                        </p>
                        
                        <div class="pt-2">
                            <a href="#" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gray-900 border border-transparent rounded-2xl hover:bg-black hover:scale-105 hover:shadow-2xl hover:shadow-gray-900/40 transition-all duration-300">
                                <div class="w-8 h-8 flex-shrink-0">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" class="w-full h-full"><path fill="#fff" d="M17.151 12.27c0 .66-.219 1.15-.658 1.48L5.3 22.42c-.2.13-.42.2-.64.2-.23 0-.47-.07-.63-.22a1 1 0 01-.28-.7L4 2.3c0-.28.1-.53.28-.72.16-.16.4-.23.64-.23.23 0 .44.07.64.2l11.19 8.67c.45.34.66.82.66 1.48z" opacity=".2"></path><path fill="#fff" d="M3.75 1.55c-.17 0-.35.03-.5.1-.24.1-.42.3-.53.54L3.06 22S14.28 13.56 16.5 11.9L3.75 1.55z" opacity=".2"></path><path fill="#fff" fill-rule="evenodd" d="M4 2.32c-.17 0-.34.05-.48.16L12.55 10l-9.03-7.23a.8.8 0 01.48-.45H4zM4.02 21.68l8.53-6.83L3.52 22.1c.14.1.32.14.5.14-.04-.04-.04-.04 0 0z" clip-rule="evenodd" opacity=".15"></path><path fill="#fff" d="M4.03 2.5a.78.78 0 01-.01 1.09l7.02 7.03 5.48-5.46a.71.71 0 00-1-.07L4.32 3.52c-.15-.12-.3-.24-.48-.28.05-.2.1-.45.19-.74z" opacity=".2"></path><path fill="#fff" d="M4.03 21.5a.78.78 0 00-.01-1.09l7.02-7.03 5.37 5.34c.32.22.68.27 1 .15l-11.75 8.9c-.24.18-.46.33-.6.55-.07-.27-.1-.52-.03-.82z" opacity=".2"></path><path fill="#04b167" d="M3.3 21.49c-.26-.14-.55-.42-.55-.86V3.37c0-.44.29-.72.55-.86l10.87 10.86L3.3 21.49z"></path><path fill="#fccf54" d="M14.17 13.37l-1.63-1.63L11.53 10.7l1.01-1.01 1.63-1.63 2.92 1.66c1 .57 1 1.5 0 2.07l-2.92 1.66z"></path><path fill="#027bf3" d="M14.17 13.37l-2.64-2.63-8.22 8.22c.31.33.82.38 1.34.09l9.53-5.68z"></path><path fill="#e34133" d="M14.17 10.7l-9.53-5.68c-.52-.29-1.03-.24-1.34.09l8.22 8.22 2.64-2.63z"></path></svg>
                                </div>
                                <div class="flex flex-col items-start mx-1 mt-0">
                                    <span class="text-[10px] uppercase text-gray-300 font-semibold tracking-wider">Download App on</span>
                                    <span class="text-xl text-white font-bold leading-tight -mt-0.5">Google Play</span>
                                </div>
                                
                                <div class="absolute inset-0 border border-white/10 rounded-2xl group-hover:border-white/20 transition-colors"></div>
                            </a>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 relative flex justify-center mt-10 lg:mt-0">
                        <div class="relative w-[300px] md:w-[350px] lg:w-[400px]">
                            <!-- Glassmorphism decorations -->
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/40 backdrop-blur-md rounded-2xl border border-white/60 shadow-xl flex items-center justify-center animate-bounce z-20" style="animation-duration: 3s;">
                                <div class="text-4xl">🛠️</div>
                            </div>
                            <div class="absolute top-1/2 -left-12 w-28 h-28 bg-white/50 backdrop-blur-md rounded-full border border-white/60 shadow-xl flex items-center justify-center animate-pulse z-20">
                                <div class="text-4xl">📍</div>
                            </div>
                            <div class="absolute -bottom-8 right-12 px-6 py-4 bg-white/80 backdrop-blur-xl rounded-2xl border border-white shadow-2xl z-20 flex items-center gap-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                                <div class="font-bold text-gray-800">Status Updated!</div>
                            </div>
                            
                            <!-- Phone Mockup image generated from AI -->
                            <div class="relative rounded-[2.5rem] p-3 bg-gray-900 shadow-2xl shadow-gray-900/50">
                                <img src="{{ asset('images/mockup.png') }}" class="rounded-[2rem] w-full h-auto object-cover object-top border border-gray-800/50 shadow-inner" alt="FixLA App Mockup" />
                                <div class="absolute inset-0 rounded-[2.5rem] shadow-[inset_0_0_20px_rgba(255,255,255,0.1)] pointer-events-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan FixLA</h2>
                    <p class="text-lg text-gray-600">Berpartisipasi dalam perbaikan infrastruktur jalan kini semudah dalam genggaman. Nikmati fitur canggih yang transparan.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm shadow-gray-200/50 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-blue-50 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pelaporan Real-time</h3>
                        <p class="text-gray-600 leading-relaxed">Laporkan kerusakan jalan sekejap dengan menyertakan foto lokasi detail (GPS koordinat). Laporan langsung diteruskan secara otomatis.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm shadow-gray-200/50 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-cyan-50 text-accent rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-accent group-hover:text-white transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Jejak Status Transparan</h3>
                        <p class="text-gray-600 leading-relaxed">Pantau terus status perbaikan dari proses terkirim, dijadwalkan, hingga laporan tersebut dinyatakan sepenuhnya diperbaiki.</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm shadow-gray-200/50 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transparansi Perbaikan</h3>
                        <p class="text-gray-600 leading-relaxed">Nikmati integrasi sistem penduga biaya otomatis dari dinas terkait, sehingga setiap pelapor mengetahui transparansi penanganan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="w-full lg:w-1/2">
                        <div class="aspect-square md:aspect-video lg:aspect-square bg-gradient-to-tr from-gray-100 to-gray-50 rounded-[3rem] p-8 border border-gray-100 shadow-lg flex items-center justify-center relative">
                            <!-- Geometric overlay for modern vibe -->
                            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/graphy.png')] opacity-10 rounded-[3rem]"></div>
                            <div class="relative z-10 w-full h-full rounded-[2rem] bg-gray-900 overflow-hidden shadow-2xl flex flex-col justify-center items-center text-white text-center p-8 space-y-6">
                                <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center border border-white/20 shadow-xl shadow-primary/50">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold mb-2">Kerja Sama Terpadu</h3>
                                    <p class="text-gray-400">Pemerintah Kabupaten Lamongan & Dinas PUBM</p>
                                </div>
                            </div>
                            
                            <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-primary rounded-full blur-3xl opacity-20"></div>
                            <div class="absolute -top-6 -right-6 w-32 h-32 bg-accent rounded-full blur-3xl opacity-20"></div>
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-1/2 space-y-6">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Tentang FixLA</h2>
                        <div class="w-20 h-1.5 bg-gradient-to-r from-primary to-accent rounded-full mb-8"></div>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            FixLA adalah singkatan dari "Fix Lamongan", sebuah platform pelaporan infrastruktur digital berbasis mobile app yang menjembatani warga dengan Pemerintah Kabupaten Lamongan.
                        </p>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            Aplikasi ini dirancang sebagai wadah keterbukaan publik yang memungkinkan warga untuk bersuara ketika menjumpai kondisi jalan rusak di wilayah Lamongan. Melalui validasi dan sinkronisasi dengan database Dinas Pekerjaan Umum Bina Marga (DPUBM), FixLA mempercepat laju perbaikan infrastruktur jalan demi Lamongan yang lebih baik.
                        </p>
                        <ul class="space-y-4 pt-6">
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center mt-1 flex-shrink-0">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-gray-700 font-medium">Proses transparan & akuntabel.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center mt-1 flex-shrink-0">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-gray-700 font-medium">Responsif dan langsung ditangani sesuai prioritas.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center mt-1 flex-shrink-0">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-gray-700 font-medium">Dikembangkan dengan basis data geospatial modern.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/10 rounded flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-wider">FixLA</span>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} FixLA Lamongan. All rights reserved.</p>
                <p class="mt-2 md:mt-0">Built for a better infrastructure.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple script to adjust navbar background on scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>
