@extends('layouts.app')

@section('title', 'Fitur - FixLA')

@section('content')
<!-- Header -->
<section class="bg-gradient-to-r from-blue-600 to-blue-400 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Fitur Unggulan</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto">Berbagai fitur canggih untuk memudahkan pelaporan dan monitoring jalan rusak di Lamongan</p>
    </div>
</section>

<!-- Fitur List - 6 Card -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Fitur 1 -->
            <div class="group bg-white rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-camera text-3xl text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Foto & GPS</h3>
                <p class="text-gray-500 text-sm">Akurasi tinggi</p>
            </div>
            
            <!-- Fitur 2 -->
            <div class="group bg-white rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-map-marked-alt text-3xl text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Peta Interaktif</h3>
                <p class="text-gray-500 text-sm">Real-time update</p>
            </div>
            
            <!-- Fitur 3 -->
            <div class="group bg-white rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-street-view text-3xl text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Live Street View</h3>
                <p class="text-gray-500 text-sm">Visual real-time</p>
            </div>
            
            <!-- Fitur 4 -->
            <div class="group bg-white rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-calculator text-3xl text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Estimasi Biaya</h3>
                <p class="text-gray-500 text-sm">Akurat & cepat</p>
            </div>
            
            <!-- Fitur 5 -->
            <div class="group bg-white rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-chart-line text-3xl text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Pantau Status</h3>
                <p class="text-gray-500 text-sm">Transparan 100%</p>
            </div>
            
            <!-- Fitur 6 -->
            <div class="group bg-white rounded-2xl p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-bell text-3xl text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Notifikasi</h3>
                <p class="text-gray-500 text-sm">Update instan</p>
            </div>
            
        </div>
    </div>
</section>

@endsection