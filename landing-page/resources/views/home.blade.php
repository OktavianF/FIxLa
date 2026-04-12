@extends('layouts.app')

@section('title', 'FixLA - Laporan Jalan Rusak')

@section('content')
<!-- Hero Section dengan Background Image & Mockup HP -->
<section class="relative text-white overflow-hidden">
    <!-- Background Image dengan Overlay Biru -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/la.png') }}" 
             alt="Lamongan" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/80 to-blue-400/80"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 md:py-4">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Left Content - Tulisan -->
            <div class="text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-bold mb-2 leading-tight">
                    Laporkan Jalan Rusak
                    <span class="block text-blue-200">di Lamongan</span>
                </h1>
                
                <p class="text-2xl text-blue-100 mb-3 font-semibold">
                    Cepat & Akurat!
                </p>
                
                <p class="text-base text-blue-50 mb-5 leading-relaxed">
                    Aplikasi mobile untuk masyarakat Lamongan melaporkan kondisi jalan rusak 
                    dengan foto, lokasi GPS, dan deskripsi kerusakan. Pantau status perbaikan 
                    secara transparan.
                </p>
            </div>

            <!-- Right Content - Mockup HP -->
            <div class="flex justify-center relative">
                <div class="relative">
                    <!-- Phone Frame -->
                    <div class="relative w-[280px] md:w-[320px] bg-gray-900 rounded-[3rem] p-3 shadow-2xl">
                        <!-- Dynamic Island -->
                        <div class="absolute top-2 left-1/2 transform -translate-x-1/2 w-32 h-6 bg-black rounded-full z-10"></div>
                        
                        <!-- Screen Content -->
                        <div class="bg-white rounded-2xl overflow-hidden">
                            <!-- App Header -->
                            <div class="bg-gradient-to-r from-purple-200 to-blue-600 p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-8 items-center gap-2">
                                        <img src="{{ asset('images/logo.png') }}" alt="FixLA Logo" class="h-15 w-auto" onerror="this.src='https://via.placeholder.com/20x20/3b82f6/ffffff?text=RC'">
                                    </div>
                                    <div class="flex gap-2">
                                        <i class="fas fa-bell text-white text-sm"></i>
                                        <i class="fas fa-user-circle text-white text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Main Content -->
                            <div class="p-3">
                                <!-- Welcome -->
                                <div class="mb-3">
                                    <p class="text-gray-600 text-xs">Selamat datang,</p>
                                    <p class="font-bold text-gray-800 text-sm">Warga Lamongan!</p>
                                </div>
                                
                                <!-- Report Button -->
                                <button class="w-full bg-gradient-to-r from-purple-300 to-blue-600 text-white py-2 rounded-lg text-sm font-semibold mb-3 flex items-center justify-center gap-2">
                                    <i class="fas fa-plus-circle"></i>
                                    Laporkan Jalan Rusak
                                </button>
                                
                                <!-- Active Report -->
                                <div class="bg-blue-50 rounded-lg p-2 mb-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-blue-600">Laporan Aktif</span>
                                        <span class="text-xs text-gray-500">2 laporan</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-road text-gray-500 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-gray-800">Jl. Raya Lamongan</p>
                                            <div class="flex items-center gap-1 mt-1">
                                                <div class="w-12 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="w-3/4 h-full bg-green-500 rounded-full"></div>
                                                </div>
                                                <span class="text-xs text-gray-500">75%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Map Preview -->
                                <div class="bg-gray-100 rounded-lg p-2 mb-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-700">Peta Jalan Rusak</span>
                                        <i class="fas fa-expand text-gray-400 text-xs"></i>
                                    </div>
                                    <div class="bg-gray-200 h-24 rounded-lg flex items-center justify-center relative">
                                        <i class="fas fa-map-marked-alt text-gray-400 text-2xl"></i>
                                        <div class="absolute top-2 left-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                        <div class="absolute bottom-2 right-2 w-2 h-2 bg-yellow-500 rounded-full"></div>
                                        <div class="absolute top-1/2 left-1/3 w-2 h-2 bg-blue-500 rounded-full"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">3 titik jalan rusak terdekat</p>
                                </div>
                                
                                <!-- Recent Reports -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-700">Laporan Terbaru</span>
                                        <span class="text-xs text-blue-600">Lihat semua</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-camera text-gray-500 text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs font-medium text-gray-800">Jl. Panglima Sudirman</p>
                                                <p class="text-xs text-gray-500">2 jam yang lalu</p>
                                            </div>
                                            <span class="text-xs text-green-600">Diproses</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-camera text-gray-500 text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs font-medium text-gray-800">Jl. Veteran</p>
                                                <p class="text-xs text-gray-500">5 jam yang lalu</p>
                                            </div>
                                            <span class="text-xs text-yellow-600">Review</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bottom Navigation -->
                            <div class="bg-white border-t border-gray-100 p-2 flex justify-around">
                                <i class="fas fa-home text-purple-400 text-sm"></i>
                                <i class="fas fa-map text-gray-400 text-sm"></i>
                                <i class="fas fa-bell text-gray-400 text-sm"></i>
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        
                        <!-- Home Indicator -->
                        <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gray-400 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Preview -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Kenapa Memilih FixLA?
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Kemudahan pelaporan dengan fitur-fitur canggih yang kami sediakan
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 - Foto & GPS -->
            <div class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-camera text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Foto & GPS</h3>
                <p class="text-gray-600">Laporan dengan foto dan lokasi akurat</p>
            </div>
            
            <!-- Feature 2 - Peta Interaktif -->
            <div class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-map-marked-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Peta Interaktif</h3>
                <p class="text-gray-600">Lihat titik jalan rusak di peta</p>
            </div>
            
            <!-- Feature 3 - Live Street View -->
            <div class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-street-view text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Live Street View</h3>
                <p class="text-gray-600">Lihat kondisi jalan real-time</p>
            </div>
            
            <!-- Feature 4 - Pantau Status -->
            <div class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pantau Status</h3>
                <p class="text-gray-600">Tracking perbaikan transparan</p>
            </div>
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ url('/fitur') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-400 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                Lihat Semua Fitur
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Mudah, Cepat, & Efektif
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Hanya 3 langkah sederhana untuk melaporkan jalan rusak
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="bg-blue-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-2xl font-bold text-white">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Ambil Foto</h3>
                <p class="text-gray-600">Foto kondisi jalan rusak beserta lokasi GPS</p>
            </div>
            
            <!-- Step 2 -->
            <div class="text-center">
                <div class="bg-blue-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-2xl font-bold text-white">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Laporkan</h3>
                <p class="text-gray-600">Kirim laporan dengan deskripsi kerusakan</p>
            </div>
            
            <!-- Step 3 -->
            <div class="text-center">
                <div class="bg-blue-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-2xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pantau Status</h3>
                <p class="text-gray-600">Lihat progres perbaikan secara real-time</p>
            </div>
        </div>
    </div>
</section>

@endsection