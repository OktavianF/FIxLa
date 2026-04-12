@extends('layouts.app')

@section('title', 'Tentang - FixLA')

@section('content')
<!-- Header -->
<section class="bg-gradient-to-r from-blue-600 to-blue-400 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang FixLA</h1>
        <p class="text-xl text-blue-100">Mewujudkan jalan aman dan nyaman untuk masyarakat Lamongan</p>
    </div>
</section>

<!-- Apa Itu FixLA -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Apa itu FixLA?</h2>
        <p class="text-gray-600 text-lg leading-relaxed mb-4">
            FixLA adalah aplikasi mobile untuk melaporkan jalan rusak di Lamongan dengan <strong class="text-blue-600">foto, GPS, dan deskripsi</strong>. Dengan Fitur unggulan yaitu <strong class="text-blue-600">peta interaktif, live street view, dan estimasi biaya perbaikan</strong>. Pantau status perbaikan secara transparan.
        </p>
        <div class="flex justify-center gap-4">
            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium">
                <i class="fas fa-check-circle mr-1"></i> Mudah
            </span>
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium">
                <i class="fas fa-check-circle mr-1"></i> Akurat
            </span>
            <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-medium">
                <i class="fas fa-check-circle mr-1"></i> Transparan
            </span>
        </div>
    </div>
</section>

<!-- Visi & Misi -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl p-8 text-center shadow-md hover:shadow-xl transition">
                <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-eye text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Visi</h3>
                <p class="text-gray-600">Menjadi aplikasi pelaporan infrastruktur terdepan di Lamongan untuk mewujudkan jalan yang aman dan nyaman.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition">
                <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bullseye text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Misi</h3>
                <div class="space-y-2 text-left">
                    <p class="text-gray-600"><i class="fas fa-check-circle text-blue-600 mr-2"></i> Mudahkan laporan jalan rusak</p>
                    <p class="text-gray-600"><i class="fas fa-check-circle text-blue-600 mr-2"></i> Tingkatkan transparansi perbaikan</p>
                    <p class="text-gray-600"><i class="fas fa-check-circle text-blue-600 mr-2"></i> Percepat respons pemerintah</p>
                    <p class="text-gray-600"><i class="fas fa-check-circle text-blue-600 mr-2"></i> Sediakan data akurat</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tujuan -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800">Tujuan Kami</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-blue-50 rounded-2xl hover:shadow-md transition">
                <i class="fas fa-comments text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">Efektifkan Komunikasi</h3>
                <p class="text-gray-600 text-sm">Masyarakat & pemerintah daerah</p>
            </div>
            <div class="text-center p-6 bg-blue-50 rounded-2xl hover:shadow-md transition">
                <i class="fas fa-tachometer-alt text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">Percepat Perbaikan</h3>
                <p class="text-gray-600 text-sm">Lebih cepat & tepat sasaran</p>
            </div>
            <div class="text-center p-6 bg-blue-50 rounded-2xl hover:shadow-md transition">
                <i class="fas fa-chart-line text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">Tingkatkan Transparansi</h3>
                <p class="text-gray-600 text-sm">Informasi status perbaikan</p>
            </div>
        </div>
    </div>
</section>
@endsection